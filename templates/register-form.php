<?php
/**
 * Registration form template (shortcode [job_connect_register]).
 * Submits to current page; errors shown inline.
 *
 * @package Job_Connect
 * @var bool $show_heading Whether to output the form heading (default true).
 */

defined( 'ABSPATH' ) || exit;

$show_heading = isset( $show_heading ) ? $show_heading : true;

if ( is_user_logged_in() ) {
	$dashboard_url = JC_Dashboard_Actions::get_dashboard_url();
	?>
	<div class="job-connect-register-form-wrap jc-content-wrap w-full my-6">
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

if ( ! JC_Auth_Helpers::plugin_registration_enabled() ) {
	echo '<p>' . esc_html__( 'Registration is currently closed.', 'job-connect' ) . '</p>';
	return;
}

$register_errors = JC_Auth_Handler::get_register_errors();
$form_action     = get_permalink() ? get_permalink() : '';
$show_username   = JC_Settings::get( 'jc_generate_username_from_email' ) !== '1';
$registered      = isset( $_GET['registered'] ) && $_GET['registered'] === '1';
?>
<div class="job-connect-register-form-wrap jc-content-wrap w-full my-6" id="job-connect-register-section">
	<?php if ( $show_heading ) : ?>
		<h2 class="jc-register-form-title text-lg font-semibold text-zinc-900 mb-4" id="job-connect-register-heading"><?php esc_html_e( 'Create an account', 'job-connect' ); ?></h2>
	<?php endif; ?>
	<?php if ( $registered ) : ?>
		<div class="job-connect-notice job-connect-notice--success mb-4 rounded-lg border-l-4 border-l-emerald-500 bg-emerald-50 py-3 pl-4 pr-4" role="status">
			<p class="!mb-0 m-0 text-sm font-medium text-emerald-900"><?php esc_html_e( 'Your account was created successfully.', 'job-connect' ); ?></p>
		</div>
	<?php endif; ?>
	<?php if ( ! empty( $register_errors ) ) : ?>
		<div class="job-connect-errors-wrapper mb-4" role="alert">
			<ul class="job-connect-errors list-none m-0 mb-4 p-4 rounded-lg border border-jc-error-border bg-jc-error-bg text-jc-error-text">
				<?php foreach ( $register_errors as $err ) : ?>
					<li><?php echo esc_html( $err ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
	<form method="post" action="<?php echo esc_url( $form_action ); ?>" id="job-connect-register-form" class="space-y-4">
		<?php wp_nonce_field( 'job_connect_register', 'job_connect_register_nonce' ); ?>
		<?php if ( $show_username ) : ?>
			<p class="mb-4">
				<label for="reg-user_login"><?php esc_html_e( 'Username', 'job-connect' ); ?></label>
				<input type="text" name="user_login" id="reg-user_login" class="input" value="<?php echo esc_attr( isset( $_POST['user_login'] ) ? sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) : '' ); ?>" autocomplete="username" />
			</p>
		<?php endif; ?>
		<p class="mb-4">
			<label for="reg-user_email"><?php esc_html_e( 'Email address', 'job-connect' ); ?></label>
			<input type="email" name="user_email" id="reg-user_email" class="input" value="<?php echo esc_attr( isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '' ); ?>" autocomplete="email" required />
		</p>
		<p class="mb-4">
			<label for="reg-user_pass"><?php esc_html_e( 'Password', 'job-connect' ); ?></label>
			<input type="password" name="user_pass" id="reg-user_pass" class="input" autocomplete="new-password" required />
		</p>
		<?php do_action( 'job_connect_register_form_before_submit' ); ?>
		<p class="register-submit">
			<button type="submit" name="job_connect_register_submit" id="job-connect-register-submit" class="jc-btn-primary"><?php esc_html_e( 'Create account', 'job-connect' ); ?></button>
		</p>
	</form>
	<?php
	$login_page_id = (int) JC_Settings::get( 'jc_login_page_id' );
	$login_url     = $login_page_id ? get_permalink( $login_page_id ) : wp_login_url();
	?>
	<p class="jc-register-form-login mt-4 text-sm text-zinc-600">
		<?php esc_html_e( 'Already have an account?', 'job-connect' ); ?>
		<a href="<?php echo esc_url( $login_url ); ?>" class="font-medium text-zinc-900 underline hover:no-underline"><?php esc_html_e( 'Log in', 'job-connect' ); ?></a>
	</p>
</div>
