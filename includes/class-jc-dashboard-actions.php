<?php
/**
 * Job dashboard actions: mark filled, edit redirect, duplicate, delete.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Dashboard_Actions class.
 */
class JC_Dashboard_Actions {

	/**
	 * Single instance.
	 *
	 * @var JC_Dashboard_Actions
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return JC_Dashboard_Actions
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'template_redirect', array( $this, 'handle_actions' ), 5 );
	}

	/**
	 * Check if current user owns the job.
	 *
	 * @param int $job_id Post ID.
	 * @return bool
	 */
	private function user_owns_job( $job_id ) {
		if ( ! $job_id || ! is_user_logged_in() ) {
			return false;
		}
		$post = get_post( $job_id );
		if ( ! $post || $post->post_type !== JC_Post_Types::PT_LISTING ) {
			return false;
		}
		return (int) $post->post_author === (int) get_current_user_id();
	}

	/**
	 * Get dashboard redirect URL with optional query args.
	 *
	 * @param array $args Optional query args to add.
	 * @return string
	 */
	public static function get_dashboard_url( $args = array() ) {
		$page_id = (int) JC_Settings::get( 'jc_job_dashboard_page_id' );
		if ( ! $page_id ) {
			return home_url( '/' );
		}
		$url = get_permalink( $page_id );
		if ( ! empty( $args ) ) {
			$url = add_query_arg( $args, $url );
		}
		return $url;
	}

	/**
	 * Handle dashboard GET actions and redirect.
	 */
	public function handle_actions() {
		$action = isset( $_GET['action'] ) ? sanitize_key( $_GET['action'] ) : '';
		$job_id = isset( $_GET['job_id'] ) ? absint( $_GET['job_id'] ) : 0;
		$nonce  = isset( $_GET['_wpnonce'] ) ? sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ) : '';

		if ( ! $action || ! $job_id ) {
			return;
		}

		$dashboard_page_id = (int) JC_Settings::get( 'jc_job_dashboard_page_id' );
		if ( ! $dashboard_page_id || get_queried_object_id() !== $dashboard_page_id ) {
			return;
		}

		if ( ! wp_verify_nonce( $nonce, 'jc_dashboard_action' ) ) {
			wp_safe_redirect( self::get_dashboard_url( array( 'job_error' => 'nonce' ) ) );
			exit;
		}

		if ( ! $this->user_owns_job( $job_id ) ) {
			wp_safe_redirect( self::get_dashboard_url( array( 'job_error' => 'permission' ) ) );
			exit;
		}

		switch ( $action ) {
			case 'edit':
				$edit_page_id = (int) JC_Settings::get( 'jc_submit_job_form_page_id' );
				if ( $edit_page_id ) {
					wp_safe_redirect( add_query_arg( 'job_id', $job_id, get_permalink( $edit_page_id ) ) );
					exit;
				}
				break;

			case 'mark_filled':
				update_post_meta( $job_id, '_filled', '1' );
				wp_safe_redirect( self::get_dashboard_url( array( 'job_marked_filled' => '1' ) ) );
				exit;

			case 'duplicate':
				$new_id = $this->duplicate_job( $job_id );
				if ( $new_id ) {
					wp_safe_redirect( self::get_dashboard_url( array( 'job_duplicated' => '1' ) ) );
					exit;
				}
				wp_safe_redirect( self::get_dashboard_url( array( 'job_error' => 'duplicate' ) ) );
				exit;

			case 'delete':
				if ( wp_trash_post( $job_id ) ) {
					wp_safe_redirect( self::get_dashboard_url( array( 'job_deleted' => '1' ) ) );
					exit;
				}
				wp_safe_redirect( self::get_dashboard_url( array( 'job_error' => 'delete' ) ) );
				exit;
		}
	}

	/**
	 * Duplicate a job listing (post + meta + terms).
	 *
	 * @param int $job_id Source post ID.
	 * @return int|false New post ID or false on failure.
	 */
	private function duplicate_job( $job_id ) {
		$post = get_post( $job_id );
		if ( ! $post || $post->post_type !== JC_Post_Types::PT_LISTING ) {
			return false;
		}

		$new_post = array(
			'post_type'    => JC_Post_Types::PT_LISTING,
			'post_title'   => $post->post_title,
			'post_content' => $post->post_content,
			'post_status'  => 'draft',
			'post_author'  => get_current_user_id(),
		);

		$new_id = wp_insert_post( $new_post );
		if ( is_wp_error( $new_id ) || ! $new_id ) {
			return false;
		}

		$meta_keys = array(
			'_company_name', '_company_website', '_company_tagline', '_job_location',
			'_application', '_job_expires', '_job_salary', '_remote_position',
			'_featured', '_filled', '_job_salary_currency', '_job_salary_unit',
		);
		$duration = JC_Settings::get( 'jc_submission_duration' );
		foreach ( $meta_keys as $key ) {
			$value = get_post_meta( $job_id, $key, true );
			if ( $key === '_job_expires' && ! empty( $duration ) && is_numeric( $duration ) ) {
				$value = date( 'Y-m-d', strtotime( '+' . (int) $duration . ' days', current_time( 'timestamp' ) ) );
			}
			if ( $key === '_filled' || $key === '_featured' ) {
				$value = '0';
			}
			if ( $value !== '' ) {
				update_post_meta( $new_id, $key, $value );
			}
		}

		foreach ( array( 'job_listing_type', 'job_listing_category' ) as $tax ) {
			if ( ! taxonomy_exists( $tax ) ) {
				continue;
			}
			$terms = get_the_terms( $job_id, $tax );
			if ( $terms && ! is_wp_error( $terms ) ) {
				$term_ids = wp_list_pluck( $terms, 'term_id' );
				wp_set_object_terms( $new_id, $term_ids, $tax );
			}
		}

		$thumbnail_id = get_post_thumbnail_id( $job_id );
		if ( $thumbnail_id ) {
			set_post_thumbnail( $new_id, $thumbnail_id );
		}

		do_action( 'job_connect_job_duplicated', $new_id, $job_id );
		return $new_id;
	}

	/**
	 * Get nonce for dashboard action links.
	 *
	 * @return string
	 */
	public static function get_nonce() {
		return wp_create_nonce( 'jc_dashboard_action' );
	}
}
