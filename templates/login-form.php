<?php
/**
 * Login form template (shortcode [job_connect_login]).
 * Submits to current page; errors shown inline.
 *
 * @package Job_Connect
 * @var string $redirect URL to redirect to after login.
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $redirect ) || $redirect === '' ) {
	$redirect = JC_Auth_Helpers::get_login_redirect_url();
}

if ( is_user_logged_in() ) {
	$dashboard_url = JC_Dashboard_Actions::get_dashboard_url();
	?>
	<div class="job-connect-login-form-wrap job-connect-require-login jc-content-wrap w-full my-6">
		<div class="job-connect-notice job-connect-notice--success mb-4" role="status">
			<span class="job-connect-notice__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg></span>
			<div class="job-connect-notice__content">
				<?php esc_html_e( 'You are already logged in.', 'job-connect' ); ?>
				<a href="<?php echo esc_url( $dashboard_url ); ?>" class="underline font-medium"><?php esc_html_e( 'View your job dashboard', 'job-connect' ); ?></a>.
			</div>
		</div>
	</div>
	<?php
	return;
}

$login_errors = JC_Auth_Handler::get_login_errors();
$form_action  = get_permalink() ? get_permalink() : '';
?>
<div class="job-connect-login-form-wrap jc-content-wrap w-full my-6" id="job-connect-login-section">
	<h2 class="jc-login-form-title text-lg font-semibold text-zinc-900 mb-4" id="job-connect-login-heading"><?php esc_html_e( 'Log in', 'job-connect' ); ?></h2>
	<?php if ( ! empty( $login_errors ) ) : ?>
		<div class="job-connect-notice job-connect-notice--error mb-4" role="alert">
			<span class="job-connect-notice__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg></span>
			<div class="job-connect-notice__content">
				<ul class="job-connect-notice__list">
					<?php foreach ( $login_errors as $err ) : ?>
						<li><?php echo esc_html( $err ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	<?php endif; ?>
	<form method="post" action="<?php echo esc_url( $form_action ); ?>" id="job-connect-login-form" class="space-y-4">
		<?php wp_nonce_field( 'job_connect_login', 'job_connect_login_nonce' ); ?>
		<input type="hidden" name="redirect_to" value="<?php echo esc_attr( $redirect ); ?>" />
		<p class="mb-4">
			<label for="user_login"><?php esc_html_e( 'Username or email', 'job-connect' ); ?></label>
			<input type="text" name="user_login" id="user_login" class="input" value="<?php echo esc_attr( isset( $_POST['user_login'] ) ? sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) : '' ); ?>" autocomplete="username" />
		</p>
		<p class="mb-4">
			<label for="user_pass"><?php esc_html_e( 'Password', 'job-connect' ); ?></label>
			<input type="password" name="user_pass" id="user_pass" class="input" autocomplete="current-password" />
		</p>
		<p class="login-remember mb-4">
			<label for="rememberme">
				<input name="rememberme" type="checkbox" id="rememberme" value="forever" />
				<?php esc_html_e( 'Remember me', 'job-connect' ); ?>
			</label>
		</p>
		<?php do_action( 'job_connect_login_form_before_submit' ); ?>
		<p class="login-submit">
			<button type="submit" name="wp-submit" id="wp-submit" class="jc-btn-primary"><?php esc_html_e( 'Log in', 'job-connect' ); ?></button>
		</p>
	</form>
	<?php if ( JC_Auth_Helpers::show_register_link() ) : ?>
		<?php $register_url = JC_Auth_Helpers::get_register_url(); ?>
		<p class="jc-login-form-register mt-4 text-sm text-zinc-600">
			<?php esc_html_e( "Don't have an account?", 'job-connect' ); ?>
			<a href="<?php echo esc_url( $register_url ? $register_url : wp_registration_url() ); ?>" class="font-medium text-zinc-900 underline hover:no-underline"><?php esc_html_e( 'Create an account', 'job-connect' ); ?></a>
		</p>
	<?php endif; ?>
</div>
