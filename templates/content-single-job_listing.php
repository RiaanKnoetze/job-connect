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
$archive_url = $admin_preview ? '' : JC_Post_Types::get_jobs_list_url();
?>
<article class="job-listing-single jc-content-wrap jc-single-job<?php echo $admin_preview ? ' job-listing-single--admin-preview' : ''; ?>" data-job-id="<?php echo esc_attr( (string) $job_id ); ?>">
	<?php if ( $is_pending_for_owner ) : ?>
		<div class="job-connect-notice job-connect-notice--warning mb-4 flex flex-row items-center gap-4 rounded-lg border-l-4 border-l-amber-500 bg-amber-50 py-3 pl-4 pr-4" role="status">
			<span class="shrink-0 text-amber-600" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
			</span>
			<p class="!mb-0 m-0 min-w-0 flex-1 text-sm font-medium text-amber-900"><?php esc_html_e( 'This job is pending approval. It will be visible to the public once an administrator approves it.', 'job-connect' ); ?></p>
		</div>
	<?php endif; ?>

	<?php if ( ! $admin_preview && $archive_url ) : ?>
		<div class="jc-single-job__back mb-6">
			<a href="<?php echo esc_url( $archive_url ); ?>" class="jc-single-job__back-link inline-flex items-center gap-2 text-sm font-medium text-zinc-500 transition-colors hover:text-zinc-900">
				<svg class="jc-single-job__back-icon h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
				<?php esc_html_e( 'Back to jobs', 'job-connect' ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="jc-single-job__layout">
		<div class="jc-single-job__content">
			<div class="jc-single-job__hero">
				<?php if ( ! $admin_preview ) : ?>
				<h1 class="jc-single-job__title">
					<?php echo esc_html( get_the_title( $job_id ) ); ?>
					<?php if ( $featured === '1' ) : ?>
						<span class="jc-single-job__featured"><?php esc_html_e( 'Featured', 'job-connect' ); ?></span>
					<?php endif; ?>
				</h1>
				<?php endif; ?>
				<?php if ( $company_name ) : ?>
					<p class="jc-single-job__company !mb-0">
						<?php if ( $company_website ) : ?>
							<a href="<?php echo esc_url( $company_website ); ?>" target="_blank" rel="noopener noreferrer" class="jc-single-job__company-link"><?php echo esc_html( $company_name ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $company_name ); ?>
						<?php endif; ?>
					</p>
				<?php endif; ?>
				<?php if ( $company_tagline ) : ?>
					<p class="jc-single-job__tagline !mb-0"><?php echo esc_html( $company_tagline ); ?></p>
				<?php endif; ?>
				<?php /* At-a-glance only: location, job type(s), posted. Full details live in sidebar. */ ?>
				<div class="jc-single-job__hero-meta">
					<?php if ( $location ) : ?>
						<span class="jc-single-job__hero-pill"><?php echo esc_html( $location ); ?></span>
					<?php endif; ?>
					<?php if ( $types && ! is_wp_error( $types ) ) : ?>
						<?php foreach ( $types as $term ) : ?>
							<span class="jc-single-job__hero-pill jc-single-job__hero-pill--type"><?php echo esc_html( $term->name ); ?></span>
						<?php endforeach; ?>
					<?php endif; ?>
					<span class="jc-single-job__hero-pill jc-single-job__hero-pill--date"><?php echo esc_html( get_the_date( '', $job_id ) ); ?></span>
				</div>
			</div>

			<div class="jc-single-job__main">
			<div class="jc-single-job__description">
				<?php echo wp_kses_post( apply_filters( 'the_content', $post->post_content ) ); ?>
			</div>
			<?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
				<div class="jc-single-job__categories">
					<span class="jc-single-job__categories-label"><?php esc_html_e( 'Categories', 'job-connect' ); ?></span>
					<div class="jc-single-job__category-tags">
						<?php foreach ( $categories as $term ) : ?>
							<span class="jc-single-job__category-tag">#<?php echo esc_html( $term->name ); ?></span>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
			</div>
		</div>

		<aside class="jc-single-job__sidebar">
			<div class="jc-single-job__details-card">
				<h3 class="jc-single-job__details-title"><?php esc_html_e( 'Job details', 'job-connect' ); ?></h3>
				<dl class="jc-single-job__details-list">
					<?php if ( $company_name ) : ?>
						<div class="jc-single-job__details-row">
							<dt><?php esc_html_e( 'Company', 'job-connect' ); ?></dt>
							<dd>
								<?php if ( $company_website ) : ?>
									<a href="<?php echo esc_url( $company_website ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $company_name ); ?></a>
								<?php else : ?>
									<?php echo esc_html( $company_name ); ?>
								<?php endif; ?>
							</dd>
						</div>
					<?php endif; ?>
					<?php if ( $location ) : ?>
						<div class="jc-single-job__details-row">
							<dt><?php esc_html_e( 'Location', 'job-connect' ); ?></dt>
							<dd><?php echo esc_html( $location ); ?></dd>
						</div>
					<?php endif; ?>
					<?php if ( $remote === '1' ) : ?>
						<div class="jc-single-job__details-row">
							<dt><?php esc_html_e( 'Work style', 'job-connect' ); ?></dt>
							<dd><?php esc_html_e( 'Remote', 'job-connect' ); ?></dd>
						</div>
					<?php endif; ?>
					<?php if ( $types && ! is_wp_error( $types ) ) : ?>
						<div class="jc-single-job__details-row">
							<dt><?php esc_html_e( 'Job type', 'job-connect' ); ?></dt>
							<dd>
								<?php echo esc_html( implode( ', ', wp_list_pluck( $types, 'name' ) ) ); ?>
							</dd>
						</div>
					<?php endif; ?>
					<?php if ( $salary && ! $admin_preview && JC_Settings::get( 'jc_enable_salary' ) === '1' ) : ?>
						<div class="jc-single-job__details-row">
							<dt><?php esc_html_e( 'Salary', 'job-connect' ); ?></dt>
							<dd><?php echo esc_html( $salary ); ?></dd>
						</div>
					<?php endif; ?>
					<?php if ( $expires ) : ?>
						<div class="jc-single-job__details-row">
							<dt><?php esc_html_e( 'Application deadline', 'job-connect' ); ?></dt>
							<dd><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $expires ) ) ); ?></dd>
						</div>
					<?php endif; ?>
				</dl>
			</div>
			<?php if ( ! $admin_preview && $application ) : ?>
				<div class="jc-single-job__apply-card">
					<a href="<?php echo esc_url( $apply_url ); ?>" class="jc-single-job__apply-btn" <?php echo $is_email ? '' : 'target="_blank" rel="noopener noreferrer"'; ?>>
						<?php echo $is_email ? esc_html__( 'Apply by email', 'job-connect' ) : esc_html__( 'Apply for this job', 'job-connect' ); ?>
						<svg class="jc-single-job__apply-icon" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd"/></svg>
					</a>
				</div>
			<?php endif; ?>
		</aside>
	</div>
</article>
