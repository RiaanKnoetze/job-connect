<?php
/**
 * Plugin Name: Job Connect
 * Plugin URI: https://github.com/jobconnect/job-connect
 * Description: A modern job board plugin for WordPress. Manage job listings, allow employers to submit jobs, and provide a full-featured job board with a React-based settings UI.
 * Version: 1.0.0
 * Author: Job Connect
 * Author URI: https://github.com/jobconnect
 * Requires at least: 6.4
 * Tested up to: 6.6
 * Requires PHP: 7.4
 * Text Domain: job-connect
 * Domain Path: /languages/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

// Define constants.
define( 'JC_VERSION', '1.0.0' );
define( 'JC_FILE', __FILE__ );
define( 'JC_PATH', plugin_dir_path( __FILE__ ) );
define( 'JC_URL', plugin_dir_url( __FILE__ ) );
define( 'JC_BASENAME', plugin_basename( __FILE__ ) );

// Include required files.
require_once JC_PATH . 'includes/class-jc-activator.php';
require_once JC_PATH . 'includes/class-jc-deactivator.php';
require_once JC_PATH . 'includes/class-jc-post-types.php';
require_once JC_PATH . 'includes/class-jc-taxonomies.php';
require_once JC_PATH . 'includes/class-jc-settings.php';
require_once JC_PATH . 'includes/class-jc-rest-settings.php';
require_once JC_PATH . 'includes/class-jc-rest-jobs.php';
require_once JC_PATH . 'includes/class-jc-template.php';
require_once JC_PATH . 'includes/class-jc-job-listings-search.php';
require_once JC_PATH . 'includes/class-jc-shortcodes.php';
require_once JC_PATH . 'includes/class-jc-forms.php';
require_once JC_PATH . 'includes/abstracts/abstract-jc-form.php';
require_once JC_PATH . 'includes/class-jc-form-submit-job.php';
require_once JC_PATH . 'includes/class-jc-form-edit-job.php';
require_once JC_PATH . 'includes/class-jc-email-notifications.php';
require_once JC_PATH . 'includes/class-jc-geocode.php';
require_once JC_PATH . 'includes/class-jc-cron.php';
require_once JC_PATH . 'includes/class-jc-dashboard-actions.php';
require_once JC_PATH . 'includes/class-jc-auth-helpers.php';
require_once JC_PATH . 'includes/class-jc-auth-handler.php';
require_once JC_PATH . 'includes/class-jc-job-stats.php';

if ( is_admin() ) {
	require_once JC_PATH . 'includes/admin/class-jc-admin.php';
	require_once JC_PATH . 'includes/admin/class-jc-admin-settings.php';
	require_once JC_PATH . 'includes/admin/class-jc-admin-writepanels.php';
	require_once JC_PATH . 'includes/admin/class-jc-admin-jobs-list.php';
}

/**
 * Returns the main Job Connect instance.
 *
 * @return Job_Connect
 */
function job_connect() {
	return Job_Connect::instance();
}

/**
 * Main Job Connect class.
 */
final class Job_Connect {

	/**
	 * Single instance.
	 *
	 * @var Job_Connect
	 */
	private static $instance = null;

	/**
	 * Post types handler.
	 *
	 * @var JC_Post_Types
	 */
	public $post_types;

	/**
	 * Settings handler.
	 *
	 * @var JC_Settings
	 */
	public $settings;

	/**
	 * Forms handler.
	 *
	 * @var JC_Forms
	 */
	public $forms;

	/**
	 * Get instance.
	 *
	 * @return Job_Connect
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
		$this->post_types = JC_Post_Types::instance();
		$this->settings   = JC_Settings::instance();
		$this->forms      = JC_Forms::instance();

		add_action( 'init', array( $this, 'init' ), 0 );
		add_action( 'rest_api_init', array( $this, 'rest_init' ) );
		add_action( 'after_setup_theme', array( $this, 'load_plugin_textdomain' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );

		new JC_Cron();
		new JC_Email_Notifications();

		// Instantiate form handlers early so their init hooks run before init fires (required for submit-job form processing).
		JC_Form_Submit_Job::instance();
		JC_Dashboard_Actions::instance();
		new JC_Auth_Handler();
		JC_Job_Stats::init();

		if ( is_admin() ) {
			new JC_Admin();
			new JC_Admin_Settings();
			new JC_Admin_Writepanels();
			new JC_Admin_Jobs_List();
		}
	}

	/**
	 * Init.
	 */
	public function init() {
		JC_Taxonomies::instance()->register();
		do_action( 'job_connect_init' );
	}

	/**
	 * Register REST routes.
	 */
	public function rest_init() {
		JC_REST_Settings::instance()->register_routes();
		new JC_REST_Jobs();
	}

	/**
	 * Load plugin textdomain.
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'job-connect', false, dirname( JC_BASENAME ) . '/languages' );
	}

	/**
	 * Enqueue frontend CSS (job list, single job, filters, form, dashboard).
	 */
	public function frontend_scripts() {
		if ( is_admin() ) {
			return;
		}
		wp_enqueue_style(
			'job-connect-frontend',
			JC_URL . 'assets/css/frontend.css',
			array(),
			JC_VERSION
		);
	}
}

// Activation.
register_activation_hook( __FILE__, array( 'JC_Activator', 'activate' ) );

// Deactivation.
register_deactivation_hook( __FILE__, array( 'JC_Deactivator', 'deactivate' ) );

// Bootstrap.
add_action( 'plugins_loaded', 'job_connect' );
