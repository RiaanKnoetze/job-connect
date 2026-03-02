<?php
/**
 * Admin list table: Preview and Actions columns for All jobs.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Admin_Jobs_List class.
 */
class JC_Admin_Jobs_List {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_filter( 'manage_edit-job_listing_columns', array( $this, 'add_columns' ), 20 );
		add_action( 'manage_job_listing_posts_custom_column', array( $this, 'column_content' ), 10, 2 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_jc_job_preview', array( $this, 'ajax_job_preview' ) );
		add_action( 'wp_ajax_jc_job_set_status', array( $this, 'ajax_job_set_status' ) );
	}

	/**
	 * Add Preview (after Title/Position) and Actions columns.
	 *
	 * @param array $columns Existing columns.
	 * @return array
	 */
	public function add_columns( $columns ) {
		if ( ! is_array( $columns ) ) {
			$columns = array();
		}
		$new = array();
		foreach ( $columns as $key => $label ) {
			$new[ $key ] = $label;
			// Insert preview column after title or job_position (WPJM renames title to job_position). No header label.
			if ( $key === 'title' || $key === 'job_position' ) {
				$new['jc_preview'] = '';
			}
		}
		$new['jc_actions'] = __( 'Actions', 'job-connect' );
		return $new;
	}

	/**
	 * Output column content: preview icon and action buttons.
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 */
	public function column_content( $column, $post_id ) {
		if ( $column === 'jc_preview' ) {
			$post = get_post( $post_id );
			if ( ! $post || $post->post_type !== JC_Post_Types::PT_LISTING ) {
				return;
			}
			if ( ! current_user_can( 'read_post', $post_id ) ) {
				return;
			}
			printf(
				'<button type="button" class="button button-link jc-job-preview-btn" data-job-id="%d" title="%s" aria-label="%s"><i class="fa-regular fa-eye"></i></button>',
				(int) $post_id,
				esc_attr__( 'Preview', 'job-connect' ),
				esc_attr__( 'Preview job', 'job-connect' )
			);
			return;
		}
		if ( $column === 'jc_actions' ) {
			$post = get_post( $post_id );
			if ( ! $post || $post->post_type !== JC_Post_Types::PT_LISTING ) {
				return;
			}
			$this->render_action_buttons( $post );
		}
	}

	/**
	 * Render action buttons: Publish, Expired, Trash.
	 *
	 * @param WP_Post $post Job post.
	 */
	protected function render_action_buttons( $post ) {
		$post_id = (int) $post->ID;
		$status  = $post->post_status;
		$nonce   = wp_create_nonce( 'jc_job_set_status_' . $post_id );
		$actions = array();

		if ( current_user_can( 'publish_post', $post_id ) && $status !== 'publish' ) {
			$actions[] = sprintf(
				'<button type="button" class="button button-secondary jc-job-action" data-action="publish" data-post-id="%d" data-nonce="%s" title="%s" aria-label="%s"><i class="fa-solid fa-check"></i></button>',
				$post_id,
				esc_attr( $nonce ),
				esc_attr__( 'Publish', 'job-connect' ),
				esc_attr__( 'Publish', 'job-connect' )
			);
		}
		if ( current_user_can( 'edit_post', $post_id ) && $status !== 'expired' ) {
			$actions[] = sprintf(
				'<button type="button" class="button button-secondary jc-job-action" data-action="expired" data-post-id="%d" data-nonce="%s" title="%s" aria-label="%s"><i class="fa-solid fa-clock"></i></button>',
				$post_id,
				esc_attr( $nonce ),
				esc_attr__( 'Expired', 'job-connect' ),
				esc_attr__( 'Mark as expired', 'job-connect' )
			);
		}
		if ( current_user_can( 'delete_post', $post_id ) && $status !== 'trash' ) {
			$actions[] = sprintf(
				'<button type="button" class="button button-secondary jc-job-action jc-job-action-trash" data-action="trash" data-post-id="%d" data-nonce="%s" title="%s" aria-label="%s"><i class="fa-solid fa-trash"></i></button>',
				$post_id,
				esc_attr( $nonce ),
				esc_attr__( 'Trash', 'job-connect' ),
				esc_attr__( 'Move to Trash', 'job-connect' )
			);
		}

		if ( ! empty( $actions ) ) {
			echo '<div class="jc-job-actions-row">' . implode( ' ', $actions ) . '</div>';
		}
	}

	/**
	 * Enqueue scripts and styles on the job listing list screen.
	 *
	 * @param string $hook_suffix Current admin page.
	 */
	public function enqueue_scripts( $hook_suffix ) {
		$screen = get_current_screen();
		if ( ! $screen || $screen->id !== 'edit-job_listing' ) {
			return;
		}

		wp_enqueue_style(
			'jc-admin-jobs-list',
			JC_URL . 'assets/css/admin-jobs-list.css',
			array(),
			JC_VERSION
		);

		// Font Awesome for icons (use Kit or CDN; minimal set via CDN for compatibility).
		wp_enqueue_style(
			'jc-fontawesome',
			'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
			array(),
			'6.5.1'
		);

		wp_enqueue_script(
			'jc-admin-jobs-list',
			JC_URL . 'assets/js/admin-jobs-list.js',
			array(),
			JC_VERSION,
			true
		);

		wp_localize_script(
			'jc-admin-jobs-list',
			'jcAdminJobsList',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'jc_job_preview' ),
				'i18n'    => array(
					'preview' => __( 'Preview', 'job-connect' ),
					'close'   => __( 'Close', 'job-connect' ),
					'loading' => __( 'Loading…', 'job-connect' ),
					'error'   => __( 'Could not load preview.', 'job-connect' ),
					'done'    => __( 'Status updated.', 'job-connect' ),
					'featured'  => __( 'Featured', 'job-connect' ),
					'filled'    => __( 'Filled', 'job-connect' ),
					'copyLink'  => __( 'Copy link', 'job-connect' ),
					'copied'    => __( 'Copied!', 'job-connect' ),
				),
			)
		);
	}

	/**
	 * AJAX: Return job preview HTML.
	 */
	public function ajax_job_preview() {
		check_ajax_referer( 'jc_job_preview', 'nonce' );
		$job_id = isset( $_GET['job_id'] ) ? absint( $_GET['job_id'] ) : 0;
		if ( ! $job_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid job ID.', 'job-connect' ) ) );
		}
		$post = get_post( $job_id );
		if ( ! $post || $post->post_type !== JC_Post_Types::PT_LISTING ) {
			wp_send_json_error( array( 'message' => __( 'Job not found.', 'job-connect' ) ) );
		}
		if ( ! current_user_can( 'read_post', $job_id ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to view this job.', 'job-connect' ) ) );
		}
		$html = JC_Template::get_contents( 'content-single-job_listing.php', array(
			'job_id'        => $job_id,
			'admin_preview' => true,
		) );
		wp_send_json_success( array(
			'html'     => $html,
			'title'    => get_the_title( $job_id ),
			'featured' => get_post_meta( $job_id, '_featured', true ) === '1',
			'filled'   => get_post_meta( $job_id, '_filled', true ) === '1',
			'url'      => get_permalink( $job_id ),
		) );
	}

	/**
	 * AJAX: Set job status (publish, expired, trash).
	 */
	public function ajax_job_set_status() {
		$post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;
		if ( ! $post_id ) {
			wp_send_json_error( array( 'message' => __( 'Invalid job ID.', 'job-connect' ) ) );
		}
		check_ajax_referer( 'jc_job_set_status_' . $post_id, 'nonce' );
		$post = get_post( $post_id );
		if ( ! $post || $post->post_type !== JC_Post_Types::PT_LISTING ) {
			wp_send_json_error( array( 'message' => __( 'Job not found.', 'job-connect' ) ) );
		}
		$status = isset( $_POST['status'] ) ? sanitize_key( $_POST['status'] ) : '';
		$allowed = array( 'publish', 'expired', 'trash' );
		if ( ! in_array( $status, $allowed, true ) ) {
			wp_send_json_error( array( 'message' => __( 'Invalid status.', 'job-connect' ) ) );
		}
		if ( $status === 'publish' && ! current_user_can( 'publish_post', $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to publish this job.', 'job-connect' ) ) );
		}
		if ( $status === 'expired' && ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to edit this job.', 'job-connect' ) ) );
		}
		if ( $status === 'trash' && ! current_user_can( 'delete_post', $post_id ) ) {
			wp_send_json_error( array( 'message' => __( 'You do not have permission to trash this job.', 'job-connect' ) ) );
		}
		$updated = wp_update_post(
			array(
				'ID'          => $post_id,
				'post_status' => $status,
			),
			true
		);
		if ( is_wp_error( $updated ) ) {
			wp_send_json_error( array( 'message' => $updated->get_error_message() ) );
		}
		wp_send_json_success( array( 'message' => __( 'Status updated.', 'job-connect' ) ) );
	}
}
