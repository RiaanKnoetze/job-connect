<?php
/**
 * REST API for Job Connect settings.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_REST_Settings class.
 */
class JC_REST_Settings {

	const NAMESPACE = 'jc/v1';

	/**
	 * Single instance.
	 *
	 * @var JC_REST_Settings
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return JC_REST_Settings
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Register REST routes.
	 */
	public function register_routes() {
		register_rest_route(
			self::NAMESPACE,
			'/settings',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_settings' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'update_settings' ),
					'permission_callback' => array( $this, 'check_permission' ),
					'args'                => array(
						'settings' => array(
							'type'     => 'object',
							'required' => false,
							'description' => __( 'Key-value map of option names to values.', 'job-connect' ),
						),
					),
				),
			)
		);

		register_rest_route(
			self::NAMESPACE,
			'/setup-wizard',
			array(
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'run_setup_wizard' ),
					'permission_callback' => array( $this, 'check_permission' ),
				),
			)
		);
	}

	/**
	 * Run setup wizard: create default pages and assign to settings.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response|WP_Error
	 */
	public function run_setup_wizard( $request ) {
		$jobs_page = get_page_by_path( 'jobs', OBJECT, 'page' );
		if ( ! $jobs_page ) {
			$jobs_page_id = wp_insert_post( array(
				'post_title'   => _x( 'Jobs', 'Default page title', 'job-connect' ),
				'post_name'    => 'jobs',
				'post_content' => '[jobs]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => get_current_user_id(),
			) );
		} else {
			$jobs_page_id = $jobs_page->ID;
		}

		$submit_page = get_page_by_path( 'submit-job', OBJECT, 'page' );
		if ( ! $submit_page ) {
			$submit_page_id = wp_insert_post( array(
				'post_title'   => _x( 'Submit a Job', 'Default page title', 'job-connect' ),
				'post_name'    => 'submit-job',
				'post_content' => '[submit_job_form]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => get_current_user_id(),
			) );
		} else {
			$submit_page_id = $submit_page->ID;
		}

		$dashboard_page = get_page_by_path( 'job-dashboard', OBJECT, 'page' );
		if ( ! $dashboard_page ) {
			$dashboard_page_id = wp_insert_post( array(
				'post_title'   => _x( 'Job Dashboard', 'Default page title', 'job-connect' ),
				'post_name'    => 'job-dashboard',
				'post_content' => '[job_dashboard]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => get_current_user_id(),
			) );
		} else {
			$dashboard_page_id = $dashboard_page->ID;
		}

		$login_page = get_page_by_path( 'login', OBJECT, 'page' );
		if ( ! $login_page ) {
			$login_page_id = wp_insert_post( array(
				'post_title'   => _x( 'Log in', 'Default page title', 'job-connect' ),
				'post_name'    => 'login',
				'post_content' => '[job_connect_login]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => get_current_user_id(),
			) );
		} else {
			$login_page_id = $login_page->ID;
		}

		$register_page = get_page_by_path( 'register', OBJECT, 'page' );
		if ( ! $register_page ) {
			$register_page_id = wp_insert_post( array(
				'post_title'   => _x( 'Create an account', 'Default page title', 'job-connect' ),
				'post_name'    => 'register',
				'post_content' => '[job_connect_register]',
				'post_status'  => 'publish',
				'post_type'    => 'page',
				'post_author'  => get_current_user_id(),
			) );
		} else {
			$register_page_id = $register_page->ID;
		}

		if ( ! empty( $jobs_page_id ) && ! is_wp_error( $jobs_page_id ) ) {
			update_option( 'jc_jobs_page_id', $jobs_page_id );
		}
		if ( ! empty( $submit_page_id ) && ! is_wp_error( $submit_page_id ) ) {
			update_option( 'jc_submit_job_form_page_id', $submit_page_id );
		}
		if ( ! empty( $dashboard_page_id ) && ! is_wp_error( $dashboard_page_id ) ) {
			update_option( 'jc_job_dashboard_page_id', $dashboard_page_id );
		}
		if ( ! empty( $login_page_id ) && ! is_wp_error( $login_page_id ) ) {
			update_option( 'jc_login_page_id', $login_page_id );
		}
		if ( ! empty( $register_page_id ) && ! is_wp_error( $register_page_id ) ) {
			update_option( 'jc_register_page_id', $register_page_id );
		}

		update_option( 'jc_setup_wizard_done', '1' );

		// Flush rewrite rules so the Jobs page owns /jobs/ (archive is disabled when jc_jobs_page_id is set).
		flush_rewrite_rules();

		return new WP_REST_Response( JC_Settings::get_all(), 200 );
	}

	/**
	 * Check permission (manage_options).
	 *
	 * @param WP_REST_Request $request Request.
	 * @return bool
	 */
	public function check_permission( $request ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * GET settings.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	public function get_settings( $request ) {
		return new WP_REST_Response( JC_Settings::get_all(), 200 );
	}

	/**
	 * POST settings (update).
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response|WP_Error
	 */
	public function update_settings( $request ) {
		$body = $request->get_json_params();
		if ( ! is_array( $body ) ) {
			$body = array();
		}
		$settings = isset( $body['settings'] ) ? $body['settings'] : $body;
		$defaults = JC_Settings::get_defaults();
		$allowed  = array_keys( $defaults );

		foreach ( $settings as $key => $value ) {
			if ( ! in_array( $key, $allowed, true ) ) {
				continue;
			}
			if ( is_array( $defaults[ $key ] ) ) {
				$value = is_array( $value ) ? array_map( 'sanitize_text_field', $value ) : array();
			} elseif ( in_array( $key, array( 'jc_submit_job_form_page_id', 'jc_job_dashboard_page_id', 'jc_jobs_page_id', 'jc_terms_and_conditions_page_id', 'jc_login_page_id', 'jc_register_page_id' ), true ) ) {
				$value = absint( $value );
			} elseif ( in_array( $key, array( 'jc_per_page', 'jc_submission_duration', 'jc_submission_limit', 'jc_admin_expiring_job_days', 'jc_employer_expiring_job_days' ), true ) ) {
				$value = $value === '' ? '' : absint( $value );
				if ( $key === 'jc_submission_limit' && $value === 0 ) {
					$value = '';
				}
			} else {
				if ( is_bool( $value ) ) {
					$value = $value ? '1' : '0';
				} elseif ( $value === 'true' || $value === true ) {
					$value = '1';
				} elseif ( $value === 'false' || $value === false ) {
					$value = '0';
				} else {
					$value = sanitize_text_field( (string) $value );
				}
			}
			update_option( $key, $value );
		}
		if ( isset( $settings['jc_jobs_page_id'] ) ) {
			flush_rewrite_rules();
		}

		return new WP_REST_Response( JC_Settings::get_all(), 200 );
	}
}
