<?php
/**
 * Admin UI for Job Connect (list table, writepanels).
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Admin class.
 */
class JC_Admin {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_notices', array( $this, 'setup_wizard_notice' ) );
	}

	/**
	 * Show setup wizard notice when wizard not yet completed.
	 */
	public function setup_wizard_notice() {
		$screen = get_current_screen();
		if ( ! $screen || strpos( $screen->id, 'job-connect' ) === false ) {
			return;
		}
		if ( get_option( 'jc_setup_wizard_done', '0' ) === '1' ) {
			return;
		}
		$url = admin_url( 'admin.php?page=job-connect-settings' );
		?>
		<div class="notice notice-info">
			<p>
				<?php
				printf(
					/* translators: %s: link to settings page */
					wp_kses_post( __( 'Job Connect is almost ready. <a href="%s">Go to Settings</a> and use “Create default pages” in the Pages tab to create Jobs, Submit a Job, Job Dashboard, Log in, and Register pages.', 'job-connect' ) ),
					esc_url( $url )
				);
				?>
			</p>
		</div>
		<?php
	}

	/**
	 * Add admin menu (Job Connect top level; submenus added by other classes).
	 */
	public function admin_menu() {
		add_menu_page(
			__( 'Job Connect', 'job-connect' ),
			__( 'Job Connect', 'job-connect' ),
			'manage_options',
			'job-connect',
			'__return_false',
			'dashicons-portfolio',
			30
		);
	}
}
