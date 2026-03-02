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
