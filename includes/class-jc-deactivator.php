<?php
/**
 * Fired during plugin deactivation.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Deactivator class.
 */
class JC_Deactivator {

	/**
	 * Deactivate the plugin.
	 */
	public static function deactivate() {
		flush_rewrite_rules();
		wp_clear_scheduled_hook( 'job_connect_check_expired_jobs' );
	}
}
