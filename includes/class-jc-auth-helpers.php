<?php
/**
 * Auth helpers: login redirect URL, login/register link URLs.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Auth_Helpers class.
 */
class JC_Auth_Helpers {

	/**
	 * Get the URL to redirect to after login (dashboard, submit page, or current page).
	 *
	 * @return string
	 */
	public static function get_login_redirect_url() {
		$dashboard_page_id = (int) JC_Settings::get( 'jc_job_dashboard_page_id' );
		$submit_page_id    = (int) JC_Settings::get( 'jc_submit_job_form_page_id' );
		$current_id        = get_queried_object_id();

		if ( $dashboard_page_id && $current_id === $dashboard_page_id ) {
			return JC_Dashboard_Actions::get_dashboard_url();
		}
		if ( $submit_page_id && $current_id === $submit_page_id ) {
			return $submit_page_id ? get_permalink( $submit_page_id ) : home_url( '/' );
		}

		// Default: current URL or home.
		$url = get_permalink();
		return $url ? $url : home_url( '/' );
	}

	/**
	 * Get the URL to use for the "Log in" link (custom login page with redirect_to or wp-login.php).
	 *
	 * @param string $redirect Optional. Redirect URL after login. Default from get_login_redirect_url().
	 * @return string
	 */
	public static function get_login_url( $redirect = '' ) {
		if ( $redirect === '' ) {
			$redirect = self::get_login_redirect_url();
		}
		$login_page_id = (int) JC_Settings::get( 'jc_login_page_id' );
		if ( $login_page_id ) {
			return add_query_arg( 'redirect_to', urlencode( $redirect ), get_permalink( $login_page_id ) );
		}
		return wp_login_url( $redirect );
	}

	/**
	 * Whether the plugin has registration enabled (Job Connect setting only).
	 * Use this to decide whether to show the registration form section on dashboard/submit pages.
	 *
	 * @return bool
	 */
	public static function plugin_registration_enabled() {
		$v = JC_Settings::get( 'jc_enable_registration' );
		return $v === '1' || $v === true || $v === 'yes';
	}

	/**
	 * Whether to show the "Register" link (WordPress allows registration and plugin has it enabled).
	 *
	 * @return bool
	 */
	public static function show_register_link() {
		return (bool) get_option( 'users_can_register' ) && self::plugin_registration_enabled();
	}

	/**
	 * Get the URL to redirect to after registration (dashboard).
	 *
	 * @return string
	 */
	public static function get_register_redirect_url() {
		$dashboard_page_id = (int) JC_Settings::get( 'jc_job_dashboard_page_id' );
		if ( $dashboard_page_id ) {
			return JC_Dashboard_Actions::get_dashboard_url();
		}
		return home_url( '/' );
	}

	/**
	 * Get the URL for the register form page (when jc_register_page_id is set).
	 *
	 * @return string Empty if no register page set.
	 */
	public static function get_register_url() {
		$page_id = (int) JC_Settings::get( 'jc_register_page_id' );
		if ( $page_id ) {
			return get_permalink( $page_id );
		}
		return '';
	}
}
