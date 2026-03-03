<?php
/**
 * Submit job form handler.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Form_Submit_Job class.
 */
class JC_Form_Submit_Job extends Abstract_JC_Form {

	public $form_name = 'submit-job';

	/**
	 * Single instance.
	 *
	 * @var JC_Form_Submit_Job
	 */
	private static $instance = null;

	/**
	 * Validation errors.
	 *
	 * @var array
	 */
	private $errors = array();

	/**
	 * Get instance.
	 *
	 * @return JC_Form_Submit_Job
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
		// Run on init (priority 5) so we redirect before any output; otherwise redirect can fail (headers already sent).
		add_action( 'init', array( $this, 'process' ), 5 );
		add_action( 'template_redirect', array( $this, 'no_cache_submit_page' ), 5 );
		// Prevent WordPress from redirecting POST to canonical URL (e.g. trailing slash), which drops the POST body.
		add_filter( 'redirect_canonical', array( $this, 'prevent_canonical_redirect_on_post' ), 10, 2 );
		// Polyfill crypto.randomUUID for older browsers / non-secure contexts (e.g. Grammarly extension).
		add_action( 'wp_head', array( $this, 'crypto_random_uuid_polyfill' ), 1 );
	}

	/**
	 * Output a crypto.randomUUID polyfill in the head so it runs before extensions (e.g. Grammarly).
	 * Prevents "crypto.randomUUID is not a function" when tabbing between fields.
	 */
	public function crypto_random_uuid_polyfill() {
		$page_id = (int) JC_Settings::get( 'jc_submit_job_form_page_id' );
		if ( ! $page_id || get_queried_object_id() !== $page_id ) {
			return;
		}
		?>
		<script>
		(function() {
			if (typeof crypto !== 'undefined' && typeof crypto.randomUUID !== 'function' && typeof crypto.getRandomValues === 'function') {
				crypto.randomUUID = function randomUUID() {
					return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, function(c) {
						return (c ^ (crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4)).toString(16);
					});
				};
			}
		})();
		</script>
		<?php
	}

	/**
	 * Prevent redirect_canonical from redirecting a POST request (would lose POST body and break submission).
	 *
	 * @param string $redirect_url  Redirect URL.
	 * @param string $requested_url Requested URL.
	 * @return string|false URL to redirect to, or false to prevent redirect.
	 */
	public function prevent_canonical_redirect_on_post( $redirect_url, $requested_url ) {
		if ( ! isset( $_SERVER['REQUEST_METHOD'] ) || $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
			return $redirect_url;
		}
		if ( isset( $_POST['job_connect_submit'] ) ) {
			$this->debug_log( 'redirect_canonical: preventing redirect to preserve POST' );
			return false;
		}
		return $redirect_url;
	}

	/**
	 * Send no-cache headers on the submit job form page so nonces are not cached.
	 */
	public function no_cache_submit_page() {
		$page_id = (int) JC_Settings::get( 'jc_submit_job_form_page_id' );
		if ( ! $page_id || get_queried_object_id() !== $page_id ) {
			return;
		}
		if ( headers_sent() ) {
			return;
		}
		header( 'Cache-Control: no-store, no-cache, must-revalidate, max-age=0' );
		header( 'Pragma: no-cache' );
	}

	/**
	 * Get errors.
	 *
	 * @return array
	 */
	public function get_errors() {
		return $this->errors;
	}

	/**
	 * Get current step (0 for submit).
	 *
	 * @return int
	 */
	public function get_step() {
		return 0;
	}

	/**
	 * Get the job ID when editing (from GET job_id). Returns 0 if not editing or user cannot edit.
	 *
	 * @return int
	 */
	public function get_edit_job_id() {
		$job_id = isset( $_GET['job_id'] ) ? absint( $_GET['job_id'] ) : 0;
		if ( ! $job_id || ! $this->can_edit_job( $job_id ) ) {
			return 0;
		}
		return $job_id;
	}

	/**
	 * Check whether the current user can edit the given job (author, job_listing, and status allowed by settings).
	 *
	 * Respects: "Allow editing of pending listings" and "Allow editing of published listings".
	 *
	 * @param int $job_id Post ID.
	 * @return bool
	 */
	public function can_edit_job( $job_id ) {
		if ( ! $job_id || ! is_user_logged_in() ) {
			return false;
		}
		$post = get_post( $job_id );
		if ( ! $post || $post->post_type !== JC_Post_Types::PT_LISTING ) {
			return false;
		}
		if ( (int) $post->post_author !== (int) get_current_user_id() ) {
			return false;
		}
		$status = $post->post_status;
		if ( $status === 'draft' ) {
			return true;
		}
		if ( $status === 'pending' ) {
			return JC_Settings::get( 'jc_user_can_edit_pending_submissions' ) === '1';
		}
		if ( $status === 'publish' || $status === 'expired' ) {
			$published_setting = JC_Settings::get( 'jc_user_edit_published_submissions' );
			return in_array( $published_setting, array( 'yes', 'yes_moderated' ), true );
		}
		return false;
	}

	/**
	 * Get job data for pre-filling the edit form.
	 *
	 * @param int $job_id Job post ID.
	 * @return array Associative array of form field values (e.g. job_title, job_description, company_name).
	 */
	public function get_edit_job_data( $job_id ) {
		if ( ! $job_id || ! $this->can_edit_job( $job_id ) ) {
			return array();
		}
		$post = get_post( $job_id );
		if ( ! $post ) {
			return array();
		}
		$types    = get_the_terms( $job_id, 'job_listing_type' );
		$type_ids = ( $types && ! is_wp_error( $types ) ) ? wp_list_pluck( $types, 'term_id' ) : array();
		$categories = get_the_terms( $job_id, 'job_listing_category' );
		$cat_ids    = ( $categories && ! is_wp_error( $categories ) ) ? wp_list_pluck( $categories, 'term_id' ) : array();
		return array(
			'job_title'      => $post->post_title,
			'job_description' => $post->post_content,
			'company_name'    => get_post_meta( $job_id, '_company_name', true ),
			'company_website' => get_post_meta( $job_id, '_company_website', true ),
			'company_tagline' => get_post_meta( $job_id, '_company_tagline', true ),
			'job_location'    => get_post_meta( $job_id, '_job_location', true ),
			'application'     => get_post_meta( $job_id, '_application', true ),
			'job_salary'      => get_post_meta( $job_id, '_job_salary', true ),
			'remote_position' => get_post_meta( $job_id, '_remote_position', true ) === '1',
			'job_type'        => $type_ids,
			'job_category'    => $cat_ids,
		);
	}

	/**
	 * Log debug message (when WP_DEBUG_LOG or JOB_CONNECT_SUBMIT_DEBUG is on).
	 * With JOB_CONNECT_SUBMIT_DEBUG, writes to wp-content/job-connect-submit-debug.log.
	 *
	 * @param string $message Message to log.
	 */
	private function debug_log( $message ) {
		$use_error_log = defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG;
		$use_file      = defined( 'JOB_CONNECT_SUBMIT_DEBUG' ) && JOB_CONNECT_SUBMIT_DEBUG;
		if ( ! $use_error_log && ! $use_file ) {
			return;
		}
		$line = '[' . gmdate( 'Y-m-d H:i:s' ) . '] [Job Connect Submit] ' . $message . "\n";
		if ( $use_error_log ) {
			error_log( '[Job Connect Submit] ' . $message );
		}
		if ( $use_file && defined( 'WP_CONTENT_DIR' ) ) {
			$log_file = WP_CONTENT_DIR . '/job-connect-submit-debug.log';
			file_put_contents( $log_file, $line, FILE_APPEND | LOCK_EX );
		}
	}

	/**
	 * Process form submission.
	 */
	public function process() {
		$request_method = isset( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : 'unknown';
		$has_submit    = isset( $_POST['job_connect_submit'] );
		$has_nonce     = isset( $_POST['job_connect_submit_nonce'] );
		$this->debug_log( sprintf( 'process() called | REQUEST_METHOD=%s | has job_connect_submit=%s | has nonce=%s', $request_method, $has_submit ? 'yes' : 'no', $has_nonce ? 'yes' : 'no' ) );

		if ( ! isset( $_POST['job_connect_submit'] ) || ! isset( $_POST['job_connect_submit_nonce'] ) ) {
			$this->debug_log( 'Early exit: missing job_connect_submit or nonce in POST' );
			return;
		}
		// Only handle POST on front; avoid running in admin or on GET.
		if ( is_admin() || ! isset( $_SERVER['REQUEST_METHOD'] ) || $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
			$this->debug_log( 'Early exit: is_admin=' . ( is_admin() ? 'yes' : 'no' ) . ' or not POST' );
			return;
		}
		$nonce = isset( $_POST['job_connect_submit_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['job_connect_submit_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'job_connect_submit_job' ) ) {
			$this->debug_log( 'Early exit: nonce verification failed' );
			$this->errors[] = __( 'Your session may have expired or the form was cached. Please try again.', 'job-connect' );
			return;
		}
		if ( ! is_user_logged_in() && JC_Settings::get( 'jc_user_requires_account' ) === '1' ) {
			$this->debug_log( 'Early exit: login required' );
			$this->errors[] = __( 'You must be logged in to submit a job.', 'job-connect' );
			return;
		}

		$title          = isset( $_POST['job_title'] ) ? sanitize_text_field( wp_unslash( $_POST['job_title'] ) ) : '';
		$description    = isset( $_POST['job_description'] ) ? wp_kses_post( wp_unslash( $_POST['job_description'] ) ) : '';
		$company        = isset( $_POST['company_name'] ) ? sanitize_text_field( wp_unslash( $_POST['company_name'] ) ) : '';
		$company_website = isset( $_POST['company_website'] ) ? esc_url_raw( wp_unslash( $_POST['company_website'] ) ) : '';
		$company_tagline = isset( $_POST['company_tagline'] ) ? sanitize_text_field( wp_unslash( $_POST['company_tagline'] ) ) : '';
		$location      = isset( $_POST['job_location'] ) ? sanitize_text_field( wp_unslash( $_POST['job_location'] ) ) : '';
		$application   = isset( $_POST['application'] ) ? sanitize_text_field( wp_unslash( $_POST['application'] ) ) : '';

		$this->errors = array();
		if ( empty( $title ) ) {
			$this->errors[] = __( 'Job title is required.', 'job-connect' );
		}
		if ( '' === trim( wp_strip_all_tags( $description ) ) ) {
			$this->errors[] = __( 'Description is required.', 'job-connect' );
		}
		if ( empty( $application ) ) {
			$this->errors[] = __( 'Application email or URL is required.', 'job-connect' );
		}

		$edit_job_id = isset( $_POST['job_id'] ) ? absint( $_POST['job_id'] ) : 0;
		$is_edit     = $edit_job_id && $this->can_edit_job( $edit_job_id );

		if ( ! $is_edit ) {
			$limit = JC_Settings::get( 'jc_submission_limit' );
			if ( ! empty( $limit ) && is_numeric( $limit ) ) {
				$count = count_user_posts( get_current_user_id(), JC_Post_Types::PT_LISTING, true );
				if ( $count >= (int) $limit ) {
					$this->errors[] = __( 'You have reached your listing limit.', 'job-connect' );
				}
			}
		}

		if ( ! empty( $this->errors ) ) {
			$this->debug_log( 'Validation failed: ' . implode( '; ', $this->errors ) );
			return;
		}

		$salary = isset( $_POST['job_salary'] ) ? sanitize_text_field( wp_unslash( $_POST['job_salary'] ) ) : '';
		$remote = ! empty( $_POST['remote_position'] ) ? '1' : '0';

		if ( $is_edit ) {
			$existing   = get_post( $edit_job_id );
			$new_status = $existing->post_status;
			if ( $existing->post_status === 'publish' && JC_Settings::get( 'jc_user_edit_published_submissions' ) === 'yes_moderated' ) {
				$new_status = 'pending';
			}
			$post_data = array(
				'ID'           => $edit_job_id,
				'post_title'   => $title,
				'post_content' => $description,
				'post_status'  => $new_status,
			);
			$job_id = wp_update_post( $post_data );
			if ( is_wp_error( $job_id ) ) {
				$this->debug_log( 'wp_update_post failed: ' . $job_id->get_error_message() );
				$this->errors[] = __( 'Could not update job. Please try again.', 'job-connect' );
				return;
			}
			$expires = get_post_meta( $edit_job_id, '_job_expires', true );
		} else {
			$status   = JC_Settings::get( 'jc_submission_requires_approval' ) === '1' ? 'pending' : 'publish';
			$duration = JC_Settings::get( 'jc_submission_duration' );
			$expires  = '';
			if ( ! empty( $duration ) && is_numeric( $duration ) ) {
				$expires = date( 'Y-m-d', strtotime( '+' . (int) $duration . ' days', current_time( 'timestamp' ) ) );
			}
			$author_id = get_current_user_id();
			if ( ! $author_id ) {
				$admins    = get_users( array( 'role' => 'administrator', 'number' => 1, 'orderby' => 'ID' ) );
				$author_id = ! empty( $admins ) ? (int) $admins[0]->ID : 1;
			}
			$post_data = array(
				'post_type'    => JC_Post_Types::PT_LISTING,
				'post_title'   => $title,
				'post_content' => $description,
				'post_status'  => $status,
				'post_author'  => $author_id,
			);
			$job_id = wp_insert_post( $post_data );
			if ( is_wp_error( $job_id ) ) {
				$this->debug_log( 'wp_insert_post failed: ' . $job_id->get_error_message() );
				$this->errors[] = __( 'Could not create job. Please try again.', 'job-connect' );
				return;
			}
			update_post_meta( $job_id, '_featured', '0' );
			update_post_meta( $job_id, '_filled', '0' );
		}

		update_post_meta( $job_id, '_company_name', $company );
		update_post_meta( $job_id, '_company_website', $company_website );
		update_post_meta( $job_id, '_company_tagline', $company_tagline );
		update_post_meta( $job_id, '_job_location', $location );
		update_post_meta( $job_id, '_application', $application );
		update_post_meta( $job_id, '_job_expires', $expires );
		update_post_meta( $job_id, '_job_salary', $salary );
		update_post_meta( $job_id, '_remote_position', $remote );

		if ( taxonomy_exists( 'job_listing_type' ) && isset( $_POST['job_type'] ) && is_array( $_POST['job_type'] ) ) {
			$type_ids = array_filter( array_map( 'absint', wp_unslash( $_POST['job_type'] ) ) );
			wp_set_object_terms( $job_id, $type_ids, 'job_listing_type' );
		}
		if ( taxonomy_exists( 'job_listing_category' ) && isset( $_POST['job_category'] ) && is_array( $_POST['job_category'] ) ) {
			$cat_ids = array_filter( array_map( 'absint', wp_unslash( $_POST['job_category'] ) ) );
			wp_set_object_terms( $job_id, $cat_ids, 'job_listing_category' );
		}

		do_action( 'job_connect_job_submitted', $job_id, $is_edit );

		$dashboard_page_id = (int) JC_Settings::get( 'jc_job_dashboard_page_id' );
		$redirect = $dashboard_page_id ? get_permalink( $dashboard_page_id ) : get_permalink( $job_id );
		$redirect = add_query_arg( $is_edit ? 'job_updated' : 'job_submitted', '1', $redirect );
		$this->debug_log( 'Success: job_id=' . $job_id . ' (' . ( $is_edit ? 'updated' : 'created' ) . '), redirecting to ' . $redirect );
		$file = '';
		$line = 0;
		if ( headers_sent( $file, $line ) ) {
			$this->debug_log( 'Redirect failed: headers already sent in ' . $file . ' on line ' . $line );
			$this->errors[] = $is_edit
				? __( 'Job was updated but redirect failed. Please go to your dashboard.', 'job-connect' )
				: __( 'Job was created but redirect failed. Please go to your dashboard.', 'job-connect' );
			return;
		}
		wp_safe_redirect( $redirect );
		exit;
	}

	/**
	 * Output the form.
	 */
	public function output() {
		JC_Template::load( 'job-submit.php' );
	}
}
