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
		<div class="job-connect-notice job-connect-notice--success mb-4 flex flex-row items-center gap-4 rounded-lg border-l-4 border-l-emerald-500 bg-emerald-50 py-3 pl-4 pr-4" role="status">
			<p class="!mb-0 m-0 min-w-0 flex-1 text-sm font-medium text-emerald-900">
				<?php esc_html_e( 'You are already logged in.', 'job-connect' ); ?>
				<a href="<?php echo esc_url( $dashboard_url ); ?>" class="underline font-semibold"><?php esc_html_e( 'View your job dashboard', 'job-connect' ); ?></a>.
			</p>
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
		<div class="job-connect-errors-wrapper mb-4" role="alert">
			<ul class="job-connect-errors list-none m-0 mb-4 p-4 rounded-lg border border-jc-error-border bg-jc-error-bg text-jc-error-text">
				<?php foreach ( $login_errors as $err ) : ?>
					<li><?php echo esc_html( $err ); ?></li>
				<?php endforeach; ?>
			</ul>
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
