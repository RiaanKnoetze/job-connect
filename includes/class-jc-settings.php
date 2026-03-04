<?php
/**
 * Plugin settings registry and options.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Settings class.
 */
class JC_Settings {

	const OPTION_GROUP = 'job_connect';

	/**
	 * Single instance.
	 *
	 * @var JC_Settings
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return JC_Settings
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
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Get all default option values (flat key => value).
	 * Used by activator and REST.
	 *
	 * @return array
	 */
	public static function get_defaults() {
		$roles = function_exists( 'get_editable_roles' ) ? get_editable_roles() : array();
		$account_roles = array();
		foreach ( $roles as $key => $role ) {
			if ( 'administrator' !== $key ) {
				$account_roles[ $key ] = $role['name'];
			}
		}
		$default_role = array_key_exists( 'employer', $account_roles ) ? 'employer' : ( array_key_exists( 'subscriber', $account_roles ) ? 'subscriber' : '' );

		return array(
			// General.
			'jc_date_format'                 => 'relative',
			'jc_google_maps_api_key'        => '',
			'jc_delete_data_on_uninstall'  => '0',
			'jc_bypass_trash_on_uninstall' => '0',
			// Job listings.
			'jc_per_page'                          => '10',
			'jc_pagination_type'                   => 'load_more',
			'jc_hide_filled_positions'             => '0',
			'jc_hide_expired'                      => '1',
			'jc_hide_expired_content'              => '1',
			'jc_enable_categories'                 => '0',
			'jc_enable_default_category_multiselect' => '0',
			'jc_category_filter_type'             => 'any',
			'jc_enable_types'                      => '1',
			'jc_multi_job_type'                    => '0',
			'jc_enable_remote_position'            => '1',
			'jc_enable_salary'                     => '0',
			'jc_enable_salary_currency'            => '0',
			'jc_default_salary_currency'           => 'USD',
			'jc_enable_salary_unit'                => '0',
			'jc_default_salary_unit'               => 'YEAR',
			'jc_display_location_address'           => '0',
			'jc_strip_job_description_shortcodes'  => '0',
			// Job submission.
			'jc_user_requires_account'              => '1',
			'jc_enable_registration'                => '0',
			'jc_enable_scheduled_listings'          => '0',
			'jc_generate_username_from_email'       => '1',
			'jc_use_standard_password_setup_email' => '1',
			'jc_registration_role'                  => $default_role,
			'jc_submission_requires_approval'       => '1',
			'jc_user_can_edit_pending_submissions'  => '0',
			'jc_user_edit_published_submissions'    => 'yes',
			'jc_submission_duration'                => '30',
			'jc_submission_limit'                   => '',
			'jc_allowed_application_method'         => '',
			'jc_show_agreement_job_submission'      => '0',
			// ReCAPTCHA.
			'jc_recaptcha_label'                => __( 'Are you human?', 'job-connect' ),
			'jc_recaptcha_site_key'             => '',
			'jc_recaptcha_secret_key'           => '',
			'jc_enable_recaptcha_job_submission' => '0',
			// Pages.
			'jc_submit_job_form_page_id'    => '',
			'jc_job_dashboard_page_id'      => '',
			'jc_jobs_page_id'               => '',
			'jc_terms_and_conditions_page_id' => '',
			'jc_login_page_id'              => '',
			'jc_register_page_id'           => '',
			'jc_setup_wizard_done'          => '0',
			// Job visibility.
			'jc_browse_job_listings_capability' => array(),
			'jc_view_job_listing_capability'    => array(),
			// Email notifications.
			'jc_email_admin_new_job'           => '1',
			'jc_email_admin_updated_job'        => '1',
			'jc_email_admin_expiring_job'       => '1',
			'jc_email_employer_expiring_job'     => '1',
			'jc_admin_expiring_job_days'         => '7',
			'jc_employer_expiring_job_days'      => '7',
		);
	}

	/**
	 * Get current settings as flat array (option_name => value).
	 *
	 * @return array
	 */
	public static function get_all() {
		$defaults = self::get_defaults();
		$out      = array();
		foreach ( array_keys( $defaults ) as $key ) {
			$out[ $key ] = get_option( $key, $defaults[ $key ] );
		}
		return $out;
	}

	/**
	 * Get a single setting.
	 *
	 * @param string $key Option name.
	 * @return mixed
	 */
	public static function get( $key ) {
		$defaults = self::get_defaults();
		$default  = isset( $defaults[ $key ] ) ? $defaults[ $key ] : null;
		return get_option( $key, $default );
	}

	/**
	 * Update a single setting.
	 *
	 * @param string $key   Option name.
	 * @param mixed  $value Value.
	 * @return bool
	 */
	public static function set( $key, $value ) {
		return update_option( $key, $value );
	}

	/**
	 * Register settings with WordPress.
	 */
	public function register_settings() {
		$defaults = self::get_defaults();
		foreach ( array_keys( $defaults ) as $option_name ) {
			if ( false === get_option( $option_name, false ) ) {
				add_option( $option_name, $defaults[ $option_name ] );
			}
			register_setting( self::OPTION_GROUP, $option_name, array(
				'type'              => is_array( $defaults[ $option_name ] ) ? 'array' : 'string',
				'sanitize_callback' => array( $this, 'sanitize_option' ),
			) );
		}
	}

	/**
	 * Sanitize a single option by key.
	 *
	 * @param mixed  $value Value.
	 * @param string $key   Optional. Option name (passed by filter).
	 * @return mixed
	 */
	public function sanitize_option( $value, $key = '' ) {
		if ( is_array( $value ) ) {
			return array_map( 'sanitize_text_field', $value );
		}
		return sanitize_text_field( $value );
	}

	/**
	 * Get settings schema for REST/React (grouped by section).
	 *
	 * @return array
	 */
	public static function get_schema() {
		return array(
			'general'        => array( 'jc_date_format', 'jc_google_maps_api_key', 'jc_delete_data_on_uninstall', 'jc_bypass_trash_on_uninstall' ),
			'job_listings'   => array( 'jc_per_page', 'jc_pagination_type', 'jc_hide_filled_positions', 'jc_hide_expired', 'jc_hide_expired_content', 'jc_enable_categories', 'jc_enable_default_category_multiselect', 'jc_category_filter_type', 'jc_enable_types', 'jc_multi_job_type', 'jc_enable_remote_position', 'jc_enable_salary', 'jc_enable_salary_currency', 'jc_default_salary_currency', 'jc_enable_salary_unit', 'jc_default_salary_unit', 'jc_display_location_address', 'jc_strip_job_description_shortcodes' ),
			'job_submission'  => array( 'jc_user_requires_account', 'jc_enable_registration', 'jc_enable_scheduled_listings', 'jc_generate_username_from_email', 'jc_use_standard_password_setup_email', 'jc_registration_role', 'jc_submission_requires_approval', 'jc_user_can_edit_pending_submissions', 'jc_user_edit_published_submissions', 'jc_submission_duration', 'jc_submission_limit', 'jc_allowed_application_method', 'jc_show_agreement_job_submission' ),
			'recaptcha'      => array( 'jc_recaptcha_label', 'jc_recaptcha_site_key', 'jc_recaptcha_secret_key', 'jc_enable_recaptcha_job_submission' ),
			'pages'          => array( 'jc_submit_job_form_page_id', 'jc_job_dashboard_page_id', 'jc_jobs_page_id', 'jc_terms_and_conditions_page_id', 'jc_login_page_id', 'jc_register_page_id' ),
			'job_visibility' => array( 'jc_browse_job_listings_capability', 'jc_view_job_listing_capability' ),
			'email_notifications' => array( 'jc_email_admin_new_job', 'jc_email_admin_updated_job', 'jc_email_admin_expiring_job', 'jc_email_employer_expiring_job', 'jc_admin_expiring_job_days', 'jc_employer_expiring_job_days' ),
		);
	}
}
