<?php
/**
 * Email notifications for Job Connect.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Email_Notifications class.
 */
class JC_Email_Notifications {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'job_connect_job_submitted', array( $this, 'maybe_send_admin_new_job' ), 10, 2 );
	}

	/**
	 * Send admin new job email if enabled.
	 *
	 * @param int $job_id Job ID.
	 * @param bool $updated Whether it was an update.
	 */
	public function maybe_send_admin_new_job( $job_id, $updated = false ) {
		if ( $updated && JC_Settings::get( 'jc_email_admin_updated_job' ) === '1' ) {
			// Phase 5: send admin updated email.
		} elseif ( ! $updated && JC_Settings::get( 'jc_email_admin_new_job' ) === '1' ) {
			// Phase 5: send admin new job email.
		}
	}
}
