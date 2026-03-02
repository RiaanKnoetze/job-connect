<?php
/**
 * Cron jobs for Job Connect (expiry, etc.).
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Cron class.
 */
class JC_Cron {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'job_connect_check_expired_jobs', array( __CLASS__, 'check_expired_jobs' ) );
		add_action( 'init', array( $this, 'schedule_events' ) );
	}

	/**
	 * Schedule cron events.
	 */
	public function schedule_events() {
		if ( ! wp_next_scheduled( 'job_connect_check_expired_jobs' ) ) {
			wp_schedule_event( time(), 'daily', 'job_connect_check_expired_jobs' );
		}
	}

	/**
	 * Check for expired jobs and update status / send emails (Phase 5).
	 */
	public static function check_expired_jobs() {
		global $wpdb;
		$today = date( 'Y-m-d', current_time( 'timestamp' ) );
		$ids   = $wpdb->get_col( $wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_job_expires' AND meta_value != '' AND meta_value < %s",
			$today
		) );
		foreach ( (array) $ids as $post_id ) {
			$post = get_post( $post_id );
			if ( $post && $post->post_type === JC_Post_Types::PT_LISTING && $post->post_status === 'publish' ) {
				wp_update_post( array( 'ID' => $post_id, 'post_status' => 'expired' ) );
			}
		}
	}
}
