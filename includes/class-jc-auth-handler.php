<?php
/**
 * Handles login and registration form POST on the same page (no redirect to wp-login.php).
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Auth_Handler class.
 */
class JC_Auth_Handler {

	/**
	 * Login form errors (static so shortcode template can read them).
	 *
	 * @var array
	 */
	public static $login_errors = array();

	/**
	 * Register form errors.
	 *
	 * @var array
	 */
	public static $register_errors = array();

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'handle_post' ), 5 );
	}

	/**
	 * Process login or register POST; redirect on success, set errors on failure.
	 */
	public function handle_post() {
		if ( empty( $_POST ) || ! isset( $_SERVER['REQUEST_METHOD'] ) || $_SERVER['REQUEST_METHOD'] !== 'POST' ) {
			return;
		}

		if ( isset( $_POST['job_connect_login_nonce'] ) ) {
			$this->handle_login();
			return;
		}
		if ( isset( $_POST['job_connect_register_nonce'] ) ) {
			$this->handle_register();
		}
	}

	/**
	 * Process login form submission.
	 */
	private function handle_login() {
		self::$login_errors = array();
		$nonce = isset( $_POST['job_connect_login_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['job_connect_login_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'job_connect_login' ) ) {
			self::$login_errors[] = __( 'Security check failed. Please try again.', 'job-connect' );
			return;
		}
		$user_login = isset( $_POST['user_login'] ) ? sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) : '';
		$user_pass  = isset( $_POST['user_pass'] ) ? $_POST['user_pass'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$remember   = ! empty( $_POST['rememberme'] );
		$redirect_to = isset( $_POST['redirect_to'] ) ? esc_url_raw( wp_unslash( $_POST['redirect_to'] ) ) : JC_Auth_Helpers::get_login_redirect_url();

		if ( $user_login === '' ) {
			self::$login_errors[] = __( 'Please enter your username or email.', 'job-connect' );
		}
		if ( $user_pass === '' ) {
			self::$login_errors[] = __( 'Please enter your password.', 'job-connect' );
		}
		if ( ! empty( self::$login_errors ) ) {
			return;
		}

		$result = wp_signon(
			array(
				'user_login'    => $user_login,
				'user_password' => $user_pass,
				'remember'      => $remember,
			),
			is_ssl()
		);

		if ( is_wp_error( $result ) ) {
			self::$login_errors[] = $result->get_error_message();
			return;
		}
		wp_safe_redirect( $redirect_to ? $redirect_to : JC_Auth_Helpers::get_login_redirect_url() );
		exit;
	}

	/**
	 * Process registration form submission.
	 */
	private function handle_register() {
		self::$register_errors = array();
		$nonce = isset( $_POST['job_connect_register_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['job_connect_register_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'job_connect_register' ) ) {
			self::$register_errors[] = __( 'Security check failed. Please try again.', 'job-connect' );
			return;
		}
		if ( ! JC_Auth_Helpers::plugin_registration_enabled() ) {
			self::$register_errors[] = __( 'Registration is not allowed.', 'job-connect' );
			return;
		}

		$email    = isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '';
		$password = isset( $_POST['user_pass'] ) ? $_POST['user_pass'] : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$username = isset( $_POST['user_login'] ) ? sanitize_user( wp_unslash( $_POST['user_login'] ), true ) : '';

		if ( $email === '' || ! is_email( $email ) ) {
			self::$register_errors[] = __( 'Please enter a valid email address.', 'job-connect' );
		}
		if ( JC_Settings::get( 'jc_generate_username_from_email' ) !== '1' ) {
			if ( $username === '' ) {
				self::$register_errors[] = __( 'Please enter a username.', 'job-connect' );
			} elseif ( username_exists( $username ) ) {
				self::$register_errors[] = __( 'This username is already in use.', 'job-connect' );
			}
		}
		if ( email_exists( $email ) ) {
			self::$register_errors[] = __( 'An account with this email already exists.', 'job-connect' );
		}
		if ( strlen( (string) $password ) < 6 ) {
			self::$register_errors[] = __( 'Password must be at least 6 characters.', 'job-connect' );
		}
		if ( ! empty( self::$register_errors ) ) {
			return;
		}

		$generate_username = JC_Settings::get( 'jc_generate_username_from_email' ) === '1';
		if ( $generate_username ) {
			$username = sanitize_user( current( explode( '@', $email ) ), true );
			$base    = $username;
			$i       = 1;
			while ( username_exists( $username ) ) {
				$username = $base . $i;
				$i++;
			}
		}

		$role = JC_Settings::get( 'jc_registration_role' );
		if ( ! $role || ! get_role( $role ) ) {
			$role = 'subscriber';
		}
		$post_data = array(
			'user_login' => $username,
			'user_email' => $email,
			'user_pass'  => $password,
		);
		/** This filter is used by addons (e.g. candidate registration) to assign a different role. */
		$role = apply_filters( 'job_connect_registration_role', $role, $post_data );

		if ( ! $role || ! get_role( $role ) ) {
			$role = 'subscriber';
		}

		$user_data = array(
			'user_login' => $username,
			'user_email' => $email,
			'user_pass'  => $password,
			'role'       => $role,
		);
		/** Filter user data before insert; addons can add or modify fields. */
		$user_data = apply_filters( 'job_connect_register_user_data', $user_data, $post_data );

		$user_id = wp_insert_user( $user_data );

		if ( is_wp_error( $user_id ) ) {
			self::$register_errors[] = $user_id->get_error_message();
			return;
		}

		/** Fired after user is created; addons can add_user_meta( $user_id, ... ) and read custom fields from $_POST. */
		do_action( 'job_connect_after_register', $user_id, $post_data, $_POST );

		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id, true );
		$redirect = JC_Auth_Helpers::get_register_redirect_url();
		wp_safe_redirect( add_query_arg( 'registered', '1', $redirect ) );
		exit;
	}

	/**
	 * Get login errors for display in template.
	 *
	 * @return array
	 */
	public static function get_login_errors() {
		return self::$login_errors;
	}

	/**
	 * Get register errors for display in template.
	 *
	 * @return array
	 */
	public static function get_register_errors() {
		return self::$register_errors;
	}
}
