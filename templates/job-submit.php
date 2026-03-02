<?php
/**
 * Submit job form.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() && JC_Settings::get( 'jc_user_requires_account' ) === '1' ) {
	echo '<p>' . esc_html__( 'You must be logged in to submit a job.', 'job-connect' ) . '</p>';
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
<div class="job-connect-submit-form">
	<?php if ( ! empty( $errors ) ) : ?>
		<div class="job-connect-errors-wrapper" role="alert">
			<p class="job-connect-errors-title"><?php esc_html_e( 'Please fix the following:', 'job-connect' ); ?></p>
			<ul class="job-connect-errors">
				<?php foreach ( $errors as $err ) : ?>
					<li><?php echo esc_html( $err ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>

	<form method="post" action="" class="job-connect-submit-form-fields" id="job-connect-submit-job-form">
		<?php wp_nonce_field( 'job_connect_submit_job', 'job_connect_submit_nonce' ); ?>
		<input type="hidden" name="job_connect_submit" value="1" />
		<?php if ( $edit_job_id ) : ?>
			<input type="hidden" name="job_id" value="<?php echo esc_attr( (string) $edit_job_id ); ?>" />
		<?php endif; ?>
		<p>
			<label for="job_title"><?php esc_html_e( 'Job title', 'job-connect' ); ?> *</label>
			<input type="text" id="job_title" name="job_title" value="<?php echo esc_attr( is_array( $val( 'job_title' ) ) ? '' : (string) $val( 'job_title' ) ); ?>" required />
		</p>
		<p>
			<label for="job_description"><?php esc_html_e( 'Description', 'job-connect' ); ?> *</label>
			<textarea id="job_description" name="job_description" rows="8" required><?php echo esc_textarea( $val_textarea( 'job_description' ) ); ?></textarea>
		</p>
		<p>
			<label for="company_name"><?php esc_html_e( 'Company name', 'job-connect' ); ?></label>
			<input type="text" id="company_name" name="company_name" value="<?php echo esc_attr( is_array( $val( 'company_name' ) ) ? '' : (string) $val( 'company_name' ) ); ?>" />
		</p>
		<p>
			<label for="company_website"><?php esc_html_e( 'Company website', 'job-connect' ); ?></label>
			<input type="url" id="company_website" name="company_website" value="<?php echo esc_attr( is_array( $val( 'company_website' ) ) ? '' : (string) $val( 'company_website' ) ); ?>" placeholder="https://" />
		</p>
		<p>
			<label for="company_tagline"><?php esc_html_e( 'Company tagline', 'job-connect' ); ?></label>
			<input type="text" id="company_tagline" name="company_tagline" value="<?php echo esc_attr( is_array( $val( 'company_tagline' ) ) ? '' : (string) $val( 'company_tagline' ) ); ?>" />
		</p>
		<p>
			<label for="job_location"><?php esc_html_e( 'Location', 'job-connect' ); ?></label>
			<input type="text" id="job_location" name="job_location" value="<?php echo esc_attr( is_array( $val( 'job_location' ) ) ? '' : (string) $val( 'job_location' ) ); ?>" />
		</p>
		<?php if ( JC_Settings::get( 'jc_enable_remote_position' ) === '1' ) : ?>
			<p>
				<label for="remote_position">
					<input type="checkbox" id="remote_position" name="remote_position" value="1" <?php checked( ! empty( $_POST['remote_position'] ) || ( $edit_job_id && ! empty( $edit_data['remote_position'] ) ) ); ?> />
					<?php esc_html_e( 'Remote position', 'job-connect' ); ?>
				</label>
			</p>
		<?php endif; ?>
		<?php if ( JC_Settings::get( 'jc_enable_salary' ) === '1' ) : ?>
			<p>
				<label for="job_salary"><?php esc_html_e( 'Salary', 'job-connect' ); ?></label>
				<input type="text" id="job_salary" name="job_salary" value="<?php echo esc_attr( is_array( $val( 'job_salary' ) ) ? '' : (string) $val( 'job_salary' ) ); ?>" placeholder="<?php esc_attr_e( 'e.g. 50,000 - 60,000', 'job-connect' ); ?>" />
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
				<p>
					<label for="job_type"><?php esc_html_e( 'Job type', 'job-connect' ); ?></label>
					<?php if ( $multi ) : ?>
						<select id="job_type" name="job_type[]" multiple="multiple" style="height: auto; min-height: 2.5em;">
							<?php foreach ( $job_types as $term ) : ?>
								<option value="<?php echo esc_attr( (string) $term->term_id ); ?>" <?php echo in_array( $term->term_id, $selected_type, true ) ? 'selected' : ''; ?>><?php echo esc_html( $term->name ); ?></option>
							<?php endforeach; ?>
						</select>
					<?php else : ?>
						<select id="job_type" name="job_type[]">
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
				<p>
					<label for="job_category"><?php esc_html_e( 'Category', 'job-connect' ); ?></label>
					<select id="job_category" name="job_category[]" <?php echo JC_Settings::get( 'jc_enable_default_category_multiselect' ) === '1' ? 'multiple="multiple" style="height: auto; min-height: 2.5em;"' : ''; ?>>
						<option value=""><?php esc_html_e( '— Select —', 'job-connect' ); ?></option>
						<?php foreach ( $job_cats as $term ) : ?>
							<option value="<?php echo esc_attr( (string) $term->term_id ); ?>" <?php echo in_array( $term->term_id, $selected_cat, true ) ? 'selected' : ''; ?>><?php echo esc_html( $term->name ); ?></option>
						<?php endforeach; ?>
					</select>
				</p>
			<?php endif; ?>
		<?php endif; ?>
		<p>
			<label for="application"><?php esc_html_e( 'Application email or URL', 'job-connect' ); ?> *</label>
			<input type="text" id="application" name="application" value="<?php echo esc_attr( is_array( $val( 'application' ) ) ? '' : (string) $val( 'application' ) ); ?>" required />
		</p>
		<p>
			<button type="submit" class="button" id="job-connect-submit-button"><?php echo $edit_job_id ? esc_html__( 'Update job', 'job-connect' ) : esc_html__( 'Submit job', 'job-connect' ); ?></button>
		</p>
	</form>
	<?php
	$jc_submit_debug = array(
		'requestMethod' => isset( $_SERVER['REQUEST_METHOD'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_METHOD'] ) ) : 'unknown',
		'errors'        => JC_Form_Submit_Job::instance()->get_errors(),
	);
	?>
	<script>
	(function() {
		var jcSubmitDebug = <?php echo wp_json_encode( $jc_submit_debug ); ?>;
		console.log('[Job Connect] Page loaded. Request method:', jcSubmitDebug.requestMethod, jcSubmitDebug.errors.length ? '| Server errors: ' + jcSubmitDebug.errors.join('; ') : '');
		if (jcSubmitDebug.requestMethod === 'POST' && jcSubmitDebug.errors.length === 0) {
			console.warn('[Job Connect] POST with no errors — redirect may have failed (e.g. headers already sent). Enable JOB_CONNECT_SUBMIT_DEBUG in wp-config.php and check wp-content/job-connect-submit-debug.log');
		}
		document.addEventListener('DOMContentLoaded', function() {
			var form = document.getElementById('job-connect-submit-job-form');
			if (form) {
				form.addEventListener('submit', function() {
					console.log('[Job Connect] Form submitting (POST) to', form.action || window.location.href);
				});
			}
		});
	})();
	</script>
</div>
