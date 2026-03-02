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
			'keywords'         => '',
			'location'         => '',
			'job_types'        => '',
			'categories'       => '',
			'post_status'      => 'publish',
		), $atts, 'jobs' );

		ob_start();
		JC_Template::load( 'job-listings.php', array( 'atts' => $atts ) );
		return ob_get_clean();
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
