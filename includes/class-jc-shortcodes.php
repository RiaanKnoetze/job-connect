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
		add_shortcode( 'job_connect_login', array( $this, 'login_form' ) );
		add_shortcode( 'job_connect_register', array( $this, 'register_form' ) );
	}

	/**
	 * [jobs] shortcode.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function output_jobs( $atts ) {
		if ( ! jc_user_can_browse_job_listings() ) {
			return '<p class="jc-access-denied">' . esc_html__( 'You do not have permission to browse job listings.', 'job-connect' ) . '</p>';
		}

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
		if ( ! jc_user_can_view_job_listing( $id ) ) {
			return '<p class="jc-access-denied">' . esc_html__( 'You do not have permission to view this job listing.', 'job-connect' ) . '</p>';
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
		// Enqueue editor scripts so TinyMCE (Description field) works on the frontend.
		wp_enqueue_editor();
		wp_enqueue_media();
		wp_tinymce_inline_scripts();
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

	/**
	 * [job_connect_login] shortcode – login form with optional redirect.
	 *
	 * @param array $atts Shortcode attributes (optional redirect URL).
	 * @return string
	 */
	public function login_form( $atts ) {
		$atts    = shortcode_atts( array( 'redirect' => '' ), $atts, 'job_connect_login' );
		$redirect = $atts['redirect'];
		if ( $redirect === '' && isset( $_GET['redirect_to'] ) ) {
			$redirect = esc_url_raw( wp_unslash( $_GET['redirect_to'] ) );
		}
		if ( $redirect === '' ) {
			$redirect = JC_Auth_Helpers::get_login_redirect_url();
		}
		ob_start();
		JC_Template::load( 'login-form.php', array( 'redirect' => $redirect ) );
		return ob_get_clean();
	}

	/**
	 * [job_connect_register] shortcode – registration form (errors shown inline).
	 *
	 * @param array $atts Shortcode attributes. show_heading (bool) to show the form heading (default true).
	 * @return string
	 */
	public function register_form( $atts ) {
		$atts = shortcode_atts( array( 'show_heading' => true ), $atts, 'job_connect_register' );
		$show_heading = filter_var( $atts['show_heading'], FILTER_VALIDATE_BOOLEAN );
		ob_start();
		JC_Template::load( 'register-form.php', array( 'show_heading' => $show_heading ) );
		return ob_get_clean();
	}
}
