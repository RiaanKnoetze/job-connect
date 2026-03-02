<?php
/**
 * Form field definitions and form handler registration.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Forms class.
 */
class JC_Forms {

	/**
	 * Single instance.
	 *
	 * @var JC_Forms
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return JC_Forms
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
		add_action( 'init', array( $this, 'register_shortcodes' ) );
	}

	/**
	 * Register shortcode handler (JC_Shortcodes).
	 */
	public function register_shortcodes() {
		new JC_Shortcodes();
	}

	/**
	 * Get default fields for submit job form.
	 *
	 * @return array
	 */
	public static function get_submit_job_fields() {
		return apply_filters( 'job_connect_submit_job_form_fields', array() );
	}
}
