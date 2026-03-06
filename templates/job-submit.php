<?php
/**
 * Submit job form.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() && JC_Settings::get( 'jc_user_requires_account' ) === '1' ) {
	?>
	<div class="job-connect-require-login jc-content-wrap w-full my-6">
		<div class="job-connect-notice job-connect-notice--warning mb-4" role="alert">
			<span class="job-connect-notice__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg></span>
			<div class="job-connect-notice__content">
				<?php esc_html_e( 'You must be logged in to submit a job.', 'job-connect' ); ?>
			</div>
		</div>
		<div class="jc-auth-forms-row">
			<div class="jc-auth-form-block jc-auth-form-login">
				<?php echo do_shortcode( '[job_connect_login]' ); ?>
			</div>
			<?php if ( JC_Auth_Helpers::plugin_registration_enabled() ) : ?>
				<div class="jc-auth-form-block jc-auth-form-register">
					<?php echo do_shortcode( '[job_connect_register show_heading="1"]' ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return;
}

$form        = JC_Form_Submit_Job::instance();
$errors      = $form->get_errors();
$step        = $form->get_step();
$edit_job_id = $form->get_edit_job_id();
$edit_data   = $edit_job_id ? $form->get_edit_job_data( $edit_job_id ) : array();

$val = function( $key, $default = '' ) use ( $edit_data ) {
	if ( isset( $_POST[ $key ] ) ) {
		return is_array( $_POST[ $key ] ) ? array_map( 'absint', (array) wp_unslash( $_POST[ $key ] ) ) : sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
	}
	return isset( $edit_data[ $key ] ) ? $edit_data[ $key ] : $default;
};
$val_textarea = function( $key ) use ( $edit_data ) {
	if ( isset( $_POST[ $key ] ) ) {
		return wp_unslash( $_POST[ $key ] );
	}
	return isset( $edit_data[ $key ] ) ? $edit_data[ $key ] : '';
};
?>
<div class="job-connect-submit-form jc-content-wrap w-full my-4 px-4">
	<?php if ( ! empty( $errors ) ) : ?>
		<div class="job-connect-notice job-connect-notice--error mb-4" role="alert">
			<span class="job-connect-notice__icon" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg></span>
			<div class="job-connect-notice__content">
				<p class="job-connect-notice__title"><?php esc_html_e( 'Please fix the following:', 'job-connect' ); ?></p>
				<ul class="job-connect-notice__list">
					<?php foreach ( $errors as $err ) : ?>
						<li><?php echo esc_html( $err ); ?></li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	<?php endif; ?>

	<form method="post" action="" class="job-connect-submit-form-fields space-y-4" id="job-connect-submit-job-form">
		<?php wp_nonce_field( 'job_connect_submit_job', 'job_connect_submit_nonce' ); ?>
		<input type="hidden" name="job_connect_submit" value="1" />
		<?php if ( $edit_job_id ) : ?>
			<input type="hidden" name="job_id" value="<?php echo esc_attr( (string) $edit_job_id ); ?>" />
		<?php endif; ?>
		<p class="m-0">
			<label for="job_title" class="block text-sm font-medium text-zinc-700 mb-1.5"><?php esc_html_e( 'Job title', 'job-connect' ); ?> *</label>
			<input type="text" id="job_title" name="job_title" value="<?php echo esc_attr( is_array( $val( 'job_title' ) ) ? '' : (string) $val( 'job_title' ) ); ?>" required class="block w-full max-w-full rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
		</p>
		<p class="m-0">
			<label for="job_description" class="block text-sm font-medium text-zinc-700 mb-1.5"><?php esc_html_e( 'Description', 'job-connect' ); ?> *</label>
			<?php
			$description_content = $val_textarea( 'job_description' );
			$editor_settings    = array(
				'textarea_name' => 'job_description',
				'textarea_rows' => 12,
				'teeny'         => true,
				'quicktags'     => false,
				'media_buttons' => false,
				'wpautop'       => true,
				'tinymce'       => array(
					'toolbar1' => 'bold,italic,underline,strikethrough,|,bullist,numlist,|,link,unlink,|,blockquote,|,removeformat',
					'toolbar2' => '',
					'resize'   => true,
					'wp_autoresize_on' => true,
				),
				'editor_class'     => 'job-connect-description-editor',
				'drag_drop_upload' => false,
			);
			wp_editor( $description_content, 'job_description', $editor_settings );
			?>
		</p>
		<p class="m-0">
			<label for="company_name" class="block text-sm font-medium text-zinc-700 mb-1.5"><?php esc_html_e( 'Company name', 'job-connect' ); ?></label>
			<input type="text" id="company_name" name="company_name" value="<?php echo esc_attr( is_array( $val( 'company_name' ) ) ? '' : (string) $val( 'company_name' ) ); ?>" class="block w-full max-w-full rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
		</p>
		<p class="m-0">
			<label for="company_website" class="block text-sm font-medium text-zinc-700 mb-1.5"><?php esc_html_e( 'Company website', 'job-connect' ); ?></label>
			<input type="url" id="company_website" name="company_website" value="<?php echo esc_attr( is_array( $val( 'company_website' ) ) ? '' : (string) $val( 'company_website' ) ); ?>" placeholder="https://" class="block w-full max-w-full rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
		</p>
		<p class="m-0">
			<label for="company_tagline" class="block text-sm font-medium text-zinc-700 mb-1.5"><?php esc_html_e( 'Company tagline', 'job-connect' ); ?></label>
			<input type="text" id="company_tagline" name="company_tagline" value="<?php echo esc_attr( is_array( $val( 'company_tagline' ) ) ? '' : (string) $val( 'company_tagline' ) ); ?>" class="block w-full max-w-full rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
		</p>
		<p class="m-0">
			<label for="job_location" class="block text-sm font-medium text-zinc-700 mb-1.5"><?php esc_html_e( 'Location', 'job-connect' ); ?></label>
			<input type="text" id="job_location" name="job_location" value="<?php echo esc_attr( is_array( $val( 'job_location' ) ) ? '' : (string) $val( 'job_location' ) ); ?>" class="block w-full max-w-full rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
		</p>
		<?php if ( JC_Settings::get( 'jc_enable_remote_position' ) === '1' ) : ?>
			<p class="m-0 flex items-center gap-2">
				<label for="remote_position" class="inline-flex cursor-pointer items-center gap-2 text-sm font-medium text-zinc-700">
					<input type="checkbox" id="remote_position" name="remote_position" value="1" class="h-4 w-4 rounded border-zinc-300 text-zinc-900 focus:ring-blue-500" <?php checked( ! empty( $_POST['remote_position'] ) || ( $edit_job_id && ! empty( $edit_data['remote_position'] ) ) ); ?> />
					<?php esc_html_e( 'Remote position', 'job-connect' ); ?>
				</label>
			</p>
		<?php endif; ?>
		<?php if ( JC_Settings::get( 'jc_enable_salary' ) === '1' ) : ?>
			<p class="m-0">
				<label for="job_salary" class="block text-sm font-medium text-zinc-700 mb-1.5"><?php esc_html_e( 'Salary', 'job-connect' ); ?></label>
				<input type="text" id="job_salary" name="job_salary" value="<?php echo esc_attr( is_array( $val( 'job_salary' ) ) ? '' : (string) $val( 'job_salary' ) ); ?>" placeholder="<?php esc_attr_e( 'e.g. 50,000 - 60,000', 'job-connect' ); ?>" class="block w-full max-w-full rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
			</p>
		<?php endif; ?>
		<?php if ( taxonomy_exists( 'job_listing_type' ) && JC_Settings::get( 'jc_enable_types' ) !== '0' ) : ?>
			<?php
			$job_types    = get_terms( array( 'taxonomy' => 'job_listing_type', 'hide_empty' => false ) );
			$selected_type = $val( 'job_type', array() );
			if ( ! is_array( $selected_type ) ) {
				$selected_type = array();
			}
			if ( ! empty( $job_types ) && ! is_wp_error( $job_types ) ) :
				$multi = JC_Settings::get( 'jc_multi_job_type' ) === '1';
				?>
				<p class="m-0">
					<label for="job_type" class="block text-sm font-medium text-zinc-700 mb-1.5"><?php esc_html_e( 'Job type', 'job-connect' ); ?></label>
					<?php if ( $multi ) : ?>
						<select id="job_type" name="job_type[]" multiple="multiple" class="block w-full max-w-full rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-h-[2.5rem] sm:text-sm">
							<?php foreach ( $job_types as $term ) : ?>
								<option value="<?php echo esc_attr( (string) $term->term_id ); ?>" <?php echo in_array( $term->term_id, $selected_type, true ) ? 'selected' : ''; ?>><?php echo esc_html( $term->name ); ?></option>
							<?php endforeach; ?>
						</select>
					<?php else : ?>
						<select id="job_type" name="job_type[]" class="block w-full max-w-full rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
							<option value=""><?php esc_html_e( '— Select —', 'job-connect' ); ?></option>
							<?php foreach ( $job_types as $term ) : ?>
								<option value="<?php echo esc_attr( (string) $term->term_id ); ?>" <?php selected( ! empty( $selected_type[0] ) ? $selected_type[0] : 0, $term->term_id ); ?>><?php echo esc_html( $term->name ); ?></option>
							<?php endforeach; ?>
						</select>
					<?php endif; ?>
				</p>
			<?php endif; ?>
		<?php endif; ?>
		<?php if ( taxonomy_exists( 'job_listing_category' ) && JC_Settings::get( 'jc_enable_categories' ) === '1' ) : ?>
			<?php
			$job_cats     = get_terms( array( 'taxonomy' => 'job_listing_category', 'hide_empty' => false ) );
			$selected_cat = $val( 'job_category', array() );
			if ( ! is_array( $selected_cat ) ) {
				$selected_cat = array();
			}
			if ( ! empty( $job_cats ) && ! is_wp_error( $job_cats ) ) :
				?>
				<p class="m-0">
					<label for="job_category" class="block text-sm font-medium text-zinc-700 mb-1.5"><?php esc_html_e( 'Category', 'job-connect' ); ?></label>
					<select id="job_category" name="job_category[]" <?php echo JC_Settings::get( 'jc_enable_default_category_multiselect' ) === '1' ? 'multiple="multiple" class="block w-full max-w-full rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 min-h-[2.5rem] sm:text-sm"' : 'class="block w-full max-w-full rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"'; ?>>
						<option value=""><?php esc_html_e( '— Select —', 'job-connect' ); ?></option>
						<?php foreach ( $job_cats as $term ) : ?>
							<option value="<?php echo esc_attr( (string) $term->term_id ); ?>" <?php echo in_array( $term->term_id, $selected_cat, true ) ? 'selected' : ''; ?>><?php echo esc_html( $term->name ); ?></option>
						<?php endforeach; ?>
					</select>
				</p>
			<?php endif; ?>
		<?php endif; ?>
		<p class="m-0">
			<label for="application" class="block text-sm font-medium text-zinc-700 mb-1.5"><?php esc_html_e( 'Application email or URL', 'job-connect' ); ?> *</label>
			<input type="text" id="application" name="application" value="<?php echo esc_attr( is_array( $val( 'application' ) ) ? '' : (string) $val( 'application' ) ); ?>" required class="block w-full max-w-full rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
		</p>
		<p class="job-connect-submit-button-wrap m-0 pt-2">
			<button type="submit" class="inline-flex items-center justify-center rounded-md bg-zinc-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" id="job-connect-submit-button"><?php echo $edit_job_id ? esc_html__( 'Update job', 'job-connect' ) : esc_html__( 'Submit job', 'job-connect' ); ?></button>
		</p>
	</form>
</div>
