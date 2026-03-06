<?php
/**
 * Admin settings page (React root and enqueue).
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Admin_Settings class.
 */
class JC_Admin_Settings {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_settings_page' ), 20 );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
	}

	/**
	 * Add Settings submenu under Job Connect.
	 */
	public function add_settings_page() {
		add_submenu_page(
			'job-connect',
			__( 'Settings', 'job-connect' ),
			__( 'Settings', 'job-connect' ),
			'manage_options',
			'job-connect-settings',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Render the settings page (React root only).
	 */
	public function render_settings_page() {
		echo '<div class="wrap job-connect-settings-wrap">';
		echo '<div id="job-connect-settings-root"></div>';
		echo '</div>';
	}

	/**
	 * Enqueue admin scripts only on settings page.
	 *
	 * @param string $hook_suffix Current admin page hook.
	 */
	public function enqueue_scripts( $hook_suffix ) {
		if ( 'job-connect_page_job-connect-settings' !== $hook_suffix ) {
			return;
		}

		$asset_file = JC_PATH . 'build/admin.asset.php';
		$asset      = file_exists( $asset_file )
			? require $asset_file
			: array(
				'dependencies' => array( 'wp-api-fetch', 'wp-components', 'wp-element', 'wp-i18n', 'wp-notices', 'wp-data' ),
				'version'      => JC_VERSION,
			);

		$deps = isset( $asset['dependencies'] ) ? $asset['dependencies'] : array();
		if ( in_array( 'react-jsx-runtime', $deps, true ) && ! wp_script_is( 'react-jsx-runtime', 'registered' ) ) {
			$deps = array_diff( $deps, array( 'react-jsx-runtime' ) );
			$deps = array_merge( array( 'wp-element' ), $deps );
		}

		wp_register_script(
			'job-connect-admin',
			JC_URL . 'build/admin.js',
			$deps,
			$asset['version'],
			true
		);

		wp_set_script_translations( 'job-connect-admin', 'job-connect', JC_PATH . 'languages' );
		wp_enqueue_script( 'job-connect-admin' );

		$pages = get_pages( array( 'number' => 500 ) );
		$page_options = array( array( 'value' => '', 'label' => __( '— Select —', 'job-connect' ) ) );
		foreach ( $pages as $page ) {
			$page_options[] = array( 'value' => (string) $page->ID, 'label' => $page->post_title );
		}

		$roles = array();
		$all_roles = array();
		if ( function_exists( 'get_editable_roles' ) ) {
			foreach ( get_editable_roles() as $key => $role ) {
				$all_roles[ $key ] = $role['name'];
				if ( 'administrator' !== $key ) {
					$roles[ $key ] = $role['name'];
				}
			}
		}

		wp_localize_script(
			'job-connect-admin',
			'jobConnectAdmin',
			array(
				'apiUrl'          => rest_url( 'jc/v1/' ),
				'nonce'           => wp_create_nonce( 'wp_rest' ),
				'restUrl'         => rest_url(),
				'settings'        => JC_Settings::get_all(),
				'pages'           => $page_options,
				'roles'           => $roles,
				'capabilityRoles' => $all_roles,
				'adminUrl'        => admin_url(),
			)
		);
	}

	/**
	 * Enqueue admin styles only on settings page.
	 *
	 * @param string $hook_suffix Current admin page hook.
	 */
	public function enqueue_styles( $hook_suffix ) {
		if ( 'job-connect_page_job-connect-settings' !== $hook_suffix ) {
			return;
		}

		$asset_file = JC_PATH . 'build/admin.asset.php';
		$version    = JC_VERSION;
		if ( file_exists( $asset_file ) ) {
			$asset   = require $asset_file;
			$version = isset( $asset['version'] ) ? $asset['version'] : $version;
		}

		if ( file_exists( JC_PATH . 'build/admin.css' ) ) {
			wp_enqueue_style(
				'job-connect-admin',
				JC_URL . 'build/admin.css',
				array( 'wp-components' ),
				$version
			);
		}
	}
}
