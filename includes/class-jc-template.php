<?php
/**
 * Template locating and loading for Job Connect.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Template class.
 */
class JC_Template {

	/**
	 * Whether the active theme (or parent) provides a classic template file.
	 * Returns false for block themes that rely on theme-compat, to avoid deprecation notices.
	 *
	 * @param string $filename Template filename (e.g. 'header.php', 'footer.php').
	 * @return bool True if the theme has the file, false otherwise.
	 */
	public static function theme_has_template( $filename ) {
		global $wp_stylesheet_path, $wp_template_path;
		if ( ! isset( $wp_stylesheet_path ) || ! isset( $wp_template_path ) ) {
			wp_set_template_globals();
		}
		if ( $wp_stylesheet_path && file_exists( $wp_stylesheet_path . '/' . $filename ) ) {
			return true;
		}
		if ( is_child_theme() && $wp_template_path && file_exists( $wp_template_path . '/' . $filename ) ) {
			return true;
		}
		return false;
	}

	/**
	 * Locate a template file (theme override or plugin).
	 *
	 * @param string $template_name Template name relative to templates/ (e.g. 'job-listings.php').
	 * @return string Full path to template file.
	 */
	public static function locate( $template_name ) {
		$theme_path = 'job-connect/' . $template_name;
		$path       = locate_template( array( $theme_path ) );
		if ( ! $path ) {
			$path = JC_PATH . 'templates/' . $template_name;
		}
		return apply_filters( 'job_connect_locate_template', $path, $template_name );
	}

	/**
	 * Load a template and optionally pass data.
	 *
	 * @param string $template_name Template name.
	 * @param array  $args          Variables to expose as local scope.
	 */
	public static function load( $template_name, $args = array() ) {
		$path = self::locate( $template_name );
		if ( ! file_exists( $path ) ) {
			return;
		}
		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args, EXTR_SKIP );
		}
		include $path;
	}

	/**
	 * Get template contents as string.
	 *
	 * @param string $template_name Template name.
	 * @param array  $args          Variables.
	 * @return string
	 */
	public static function get_contents( $template_name, $args = array() ) {
		ob_start();
		self::load( $template_name, $args );
		return ob_get_clean();
	}
}
