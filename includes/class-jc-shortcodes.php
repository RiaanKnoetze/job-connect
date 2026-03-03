<?php
/**
 * Shortcodes for Job Connect.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Shortcodes class.
 */
class JC_Shortcodes {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_shortcode( 'jobs', array( $this, 'output_jobs' ) );
		add_shortcode( 'job', array( $this, 'output_job' ) );
		add_shortcode( 'job_summary', array( $this, 'output_job_summary' ) );
		add_shortcode( 'submit_job_form', array( $this, 'submit_job_form' ) );
		add_shortcode( 'job_dashboard', array( $this, 'job_dashboard' ) );
	}

	/**
	 * [jobs] shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function output_jobs( $atts ) {
		$atts = shortcode_atts( array(
			'per_page'         => JC_Settings::get( 'jc_per_page' ),
			'orderby'          => 'date',
			'order'            => 'desc',
			'show_filters'     => true,
			'show_pagination'  => true,
			'show_job_type'    => 'true',
			'show_category'    => 'true',
			'filters_layout'   => 'default',
			'keywords'         => '',
			'location'         => '',
			'job_types'        => '',
			'categories'       => '',
			'post_status'      => 'publish',
		), $atts, 'jobs' );

		$atts = self::normalize_jobs_atts( $atts );

		ob_start();
		JC_Template::load( 'job-listings.php', array( 'atts' => $atts ) );
		return ob_get_clean();
	}

	/**
	 * Normalize [jobs] shortcode atts (show_job_type, show_category, filters_layout).
	 * Used by the shortcode and by the archive when it reads atts from the Jobs page.
	 *
	 * @param array $atts Attributes (e.g. from shortcode_atts or merged defaults).
	 * @return array Normalized atts with show_job_type/show_category as bool, filters_layout as 'inline'|'default'.
	 */
	public static function normalize_jobs_atts( $atts ) {
		$atts['show_job_type']  = self::parse_bool( isset( $atts['show_job_type'] ) ? $atts['show_job_type'] : 'true', true );
		$atts['show_category'] = self::parse_bool( isset( $atts['show_category'] ) ? $atts['show_category'] : 'true', true );
		$layout                 = isset( $atts['filters_layout'] ) ? sanitize_key( $atts['filters_layout'] ) : 'default';
		$atts['filters_layout'] = ( $layout === 'inline' ) ? 'inline' : 'default';
		return $atts;
	}

	/**
	 * Parse a shortcode attribute as boolean (accepts true, false, 1, 0, yes, no).
	 *
	 * @param mixed $value   Attribute value (usually string).
	 * @param bool  $default Default when value is empty or not recognized.
	 * @return bool
	 */
	private static function parse_bool( $value, $default = true ) {
		if ( $value === true || $value === false ) {
			return $value;
		}
		if ( $value === '' || $value === null ) {
			return $default;
		}
		$v = is_string( $value ) ? strtolower( trim( $value ) ) : $value;
		if ( in_array( $v, array( 'true', '1', 'yes' ), true ) ) {
			return true;
		}
		if ( in_array( $v, array( 'false', '0', 'no' ), true ) ) {
			return false;
		}
		return $default;
	}

	/**
	 * [job] shortcode – single job by id.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function output_job( $atts ) {
		$atts = shortcode_atts( array( 'id' => 0 ), $atts, 'job' );
		$id   = absint( $atts['id'] );
		if ( ! $id ) {
			return '';
		}
		ob_start();
		JC_Template::load( 'content-single-job_listing.php', array( 'job_id' => $id ) );
		return ob_get_clean();
	}

	/**
	 * [job_summary] shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function output_job_summary( $atts ) {
		$atts = shortcode_atts( array( 'id' => 0 ), $atts, 'job_summary' );
		$id   = absint( $atts['id'] );
		if ( ! $id ) {
			return '';
		}
		ob_start();
		JC_Template::load( 'content-summary-job_listing.php', array( 'job_id' => $id ) );
		return ob_get_clean();
	}

	/**
	 * [submit_job_form] shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function submit_job_form( $atts ) {
		ob_start();
		JC_Form_Submit_Job::instance()->output();
		return ob_get_clean();
	}

	/**
	 * [job_dashboard] shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function job_dashboard( $atts ) {
		$atts = shortcode_atts( array( 'posts_per_page' => 25 ), $atts, 'job_dashboard' );
		ob_start();
		JC_Template::load( 'job-dashboard.php', array( 'atts' => $atts ) );
		return ob_get_clean();
	}
}
