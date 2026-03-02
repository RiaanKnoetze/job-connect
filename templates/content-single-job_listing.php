<?php
/**
 * Single job listing full content.
 *
 * @package Job_Connect
 * @var int  $job_id        Job post ID.
 * @var bool $admin_preview When true, output for admin preview modal (no notice, apply, posted; optional structured meta).
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $job_id ) ) {
	return;
}
$admin_preview = ! empty( $admin_preview );
$post = get_post( $job_id );
if ( ! $post || $post->post_type !== JC_Post_Types::PT_LISTING ) {
	return;
}

$company_name    = get_post_meta( $job_id, '_company_name', true );
$company_website = get_post_meta( $job_id, '_company_website', true );
$company_tagline = get_post_meta( $job_id, '_company_tagline', true );
$location        = get_post_meta( $job_id, '_job_location', true );
$application     = get_post_meta( $job_id, '_application', true );
$salary          = get_post_meta( $job_id, '_job_salary', true );
$remote          = get_post_meta( $job_id, '_remote_position', true );
$featured        = get_post_meta( $job_id, '_featured', true );
$expires         = get_post_meta( $job_id, '_job_expires', true );
$types           = get_the_terms( $job_id, 'job_listing_type' );
$categories      = get_the_terms( $job_id, 'job_listing_category' );

$is_email = $application && is_email( $application );
$apply_url = $application;
if ( $is_email ) {
	$subject = rawurlencode( sprintf( __( 'Application for: %s', 'job-connect' ), get_the_title( $job_id ) ) );
	$apply_url = 'mailto:' . $application . '?subject=' . $subject;
}
$is_pending_for_owner = ! $admin_preview && $post->post_status === 'pending' && is_user_logged_in() && (int) $post->post_author === (int) get_current_user_id();
?>
<article class="job-listing-single<?php echo $admin_preview ? ' job-listing-single--admin-preview' : ''; ?>" data-job-id="<?php echo esc_attr( (string) $job_id ); ?>">
	<?php if ( $is_pending_for_owner ) : ?>
		<div class="job-connect-notice job-connect-notice--warning" role="status"><?php esc_html_e( 'This job is pending approval. It will be visible to the public once an administrator approves it.', 'job-connect' ); ?></div>
	<?php endif; ?>
	<div class="job-listing-single-header">
		<?php if ( ! $admin_preview ) : ?>
		<h1>
			<?php echo esc_html( get_the_title( $job_id ) ); ?>
			<?php if ( $featured === '1' ) : ?>
				<span class="job-featured-badge"><?php esc_html_e( 'Featured', 'job-connect' ); ?></span>
			<?php endif; ?>
		</h1>
		<?php endif; ?>
		<?php if ( $admin_preview ) : ?>
		<div class="job-meta job-meta--admin">
			<?php if ( $company_name ) : ?>
				<span class="job-meta__item job-meta__item--company" title="<?php esc_attr_e( 'Company', 'job-connect' ); ?>"><i class="fa-solid fa-building" aria-hidden="true"></i><span class="job-meta__value"><?php if ( $company_website ) : ?><a href="<?php echo esc_url( $company_website ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $company_name ); ?></a><?php else : ?><?php echo esc_html( $company_name ); ?><?php endif; ?></span></span>
			<?php endif; ?>
			<?php if ( $location ) : ?>
				<span class="job-meta__item job-meta__item--location" title="<?php esc_attr_e( 'Location', 'job-connect' ); ?>"><i class="fa-solid fa-location-dot" aria-hidden="true"></i><span class="job-meta__value"><?php echo esc_html( $location ); ?></span></span>
			<?php endif; ?>
			<?php if ( $remote === '1' ) : ?>
				<span class="job-meta__item job-meta__item--remote" title="<?php esc_attr_e( 'Remote', 'job-connect' ); ?>"><i class="fa-solid fa-house" aria-hidden="true"></i><span class="job-meta__value"><?php esc_html_e( 'Remote', 'job-connect' ); ?></span></span>
			<?php endif; ?>
			<span class="job-meta__item job-meta__item--posted" title="<?php esc_attr_e( 'Posted', 'job-connect' ); ?>"><i class="fa-solid fa-calendar-days" aria-hidden="true"></i><span class="job-meta__value"><?php echo esc_html( get_the_date( '', $job_id ) ); ?></span></span>
			<?php if ( $types && ! is_wp_error( $types ) ) : ?>
				<?php foreach ( $types as $term ) : ?>
					<span class="job-meta__item job-meta__item--type" title="<?php esc_attr_e( 'Job type', 'job-connect' ); ?>"><i class="fa-solid fa-briefcase" aria-hidden="true"></i><span class="job-meta__value"><?php echo esc_html( $term->name ); ?></span></span>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if ( $salary && JC_Settings::get( 'jc_enable_salary' ) === '1' ) : ?>
				<span class="job-meta__item job-meta__item--salary" title="<?php esc_attr_e( 'Salary', 'job-connect' ); ?>"><i class="fa-solid fa-coins" aria-hidden="true"></i><span class="job-meta__value"><?php echo esc_html( $salary ); ?></span></span>
			<?php endif; ?>
			<?php if ( $expires ) : ?>
				<span class="job-meta__item job-meta__item--expires" title="<?php esc_attr_e( 'Expires', 'job-connect' ); ?>"><i class="fa-solid fa-calendar-xmark" aria-hidden="true"></i><span class="job-meta__value"><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $expires ) ) ); ?></span></span>
			<?php endif; ?>
		</div>
		<?php else : ?>
		<div class="job-meta">
			<?php if ( $company_name ) : ?>
				<span class="company">
					<?php if ( $company_website ) : ?>
						<a href="<?php echo esc_url( $company_website ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $company_name ); ?></a>
					<?php else : ?>
						<?php echo esc_html( $company_name ); ?>
					<?php endif; ?>
				</span>
			<?php endif; ?>
			<?php if ( $location ) : ?>
				<span class="location"><?php echo esc_html( $location ); ?></span>
			<?php endif; ?>
			<?php if ( $remote === '1' ) : ?>
				<span class="remote"><?php esc_html_e( 'Remote', 'job-connect' ); ?></span>
			<?php endif; ?>
			<span class="posted"><?php echo esc_html( get_the_date( '', $job_id ) ); ?></span>
		</div>
		<?php endif; ?>
		<?php if ( $company_tagline ) : ?>
			<p class="job-company-tagline"><?php echo esc_html( $company_tagline ); ?></p>
		<?php endif; ?>
		<?php if ( ! $admin_preview && $types && ! is_wp_error( $types ) ) : ?>
			<div class="job-types-categories">
				<?php foreach ( $types as $term ) : ?>
					<span class="job-type-tag"><i class="fa-solid fa-briefcase" aria-hidden="true"></i><?php echo esc_html( $term->name ); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ( ! $admin_preview && $salary && JC_Settings::get( 'jc_enable_salary' ) === '1' ) : ?>
			<p class="job-salary"><strong><?php esc_html_e( 'Salary', 'job-connect' ); ?>:</strong> <?php echo esc_html( $salary ); ?></p>
		<?php endif; ?>
		<?php if ( ! $admin_preview && $expires ) : ?>
			<p class="job-expires"><strong><?php esc_html_e( 'Expires', 'job-connect' ); ?>:</strong> <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $expires ) ) ); ?></p>
		<?php endif; ?>
	</div>

	<div class="job-description"><?php echo wp_kses_post( apply_filters( 'the_content', $post->post_content ) ); ?></div>

	<?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
		<div class="job-categories-hashtags">
			<?php foreach ( $categories as $term ) : ?>
				<span class="job-category-tag job-category-tag--hashtag">#<?php echo esc_html( $term->name ); ?></span>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! $admin_preview && $application ) : ?>
		<div class="job-apply-section">
			<h3><?php esc_html_e( 'Apply for this job', 'job-connect' ); ?></h3>
			<p>
				<a href="<?php echo esc_url( $apply_url ); ?>" class="job-connect-apply-button" <?php echo $is_email ? '' : 'target="_blank" rel="noopener noreferrer"'; ?>><?php echo $is_email ? esc_html__( 'Apply by email', 'job-connect' ) : esc_html__( 'Apply for this job', 'job-connect' ); ?></a>
			</p>
		</div>
	<?php endif; ?>

	<?php if ( ! $admin_preview ) : ?>
		<p class="job-posted-date"><?php printf( esc_html__( 'Posted on %s', 'job-connect' ), esc_html( get_the_date( '', $job_id ) ) ); ?></p>
	<?php endif; ?>
</article>
