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
<article class="job-listing-single jc-content-wrap mx-auto mb-8 w-full max-w-5xl px-4<?php echo $admin_preview ? ' job-listing-single--admin-preview' : ''; ?>" data-job-id="<?php echo esc_attr( (string) $job_id ); ?>">
	<?php if ( $is_pending_for_owner ) : ?>
		<div class="job-connect-notice job-connect-notice--warning mb-4 flex flex-row items-center gap-4 rounded-lg border-l-4 border-l-amber-500 bg-amber-50 py-3 pl-4 pr-4" role="status">
			<span class="shrink-0 text-amber-600" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
			</span>
			<p class="!mb-0 m-0 min-w-0 flex-1 text-sm font-medium text-amber-900"><?php esc_html_e( 'This job is pending approval. It will be visible to the public once an administrator approves it.', 'job-connect' ); ?></p>
		</div>
	<?php endif; ?>
	<div class="job-listing-single-header mb-6 pb-4 border-b border-zinc-200">
		<?php if ( ! $admin_preview ) : ?>
		<h1 class="m-0 mb-1.5 text-2xl font-bold tracking-tight text-zinc-900 md:text-3xl">
			<?php echo esc_html( get_the_title( $job_id ) ); ?>
			<?php if ( $featured === '1' ) : ?>
				<span class="job-featured-badge ml-2 inline-block rounded-md bg-amber-100 px-2 py-0.5 text-xs font-medium text-amber-800"><?php esc_html_e( 'Featured', 'job-connect' ); ?></span>
			<?php endif; ?>
		</h1>
		<?php endif; ?>
		<?php if ( $admin_preview ) : ?>
		<div class="job-meta job-meta--admin mt-2 flex flex-wrap gap-x-4 gap-y-1 text-[0.95em] text-zinc-500 [&_a]:text-inherit [&_a]:no-underline [&_a:hover]:underline">
			<?php if ( $company_name ) : ?>
				<span class="job-meta__item job-meta__item--company flex items-center gap-1.5" title="<?php esc_attr_e( 'Company', 'job-connect' ); ?>"><i class="fa-solid fa-building opacity-90 text-[0.95em]" aria-hidden="true"></i><span class="job-meta__value"><?php if ( $company_website ) : ?><a href="<?php echo esc_url( $company_website ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $company_name ); ?></a><?php else : ?><?php echo esc_html( $company_name ); ?><?php endif; ?></span></span>
			<?php endif; ?>
			<?php if ( $location ) : ?>
				<span class="job-meta__item job-meta__item--location flex items-center gap-1.5" title="<?php esc_attr_e( 'Location', 'job-connect' ); ?>"><i class="fa-solid fa-location-dot opacity-90" aria-hidden="true"></i><span class="job-meta__value"><?php echo esc_html( $location ); ?></span></span>
			<?php endif; ?>
			<?php if ( $remote === '1' ) : ?>
				<span class="job-meta__item job-meta__item--remote flex items-center gap-1.5" title="<?php esc_attr_e( 'Remote', 'job-connect' ); ?>"><i class="fa-solid fa-house opacity-90" aria-hidden="true"></i><span class="job-meta__value"><?php esc_html_e( 'Remote', 'job-connect' ); ?></span></span>
			<?php endif; ?>
			<span class="job-meta__item job-meta__item--posted flex items-center gap-1.5" title="<?php esc_attr_e( 'Posted', 'job-connect' ); ?>"><i class="fa-solid fa-calendar-days opacity-90" aria-hidden="true"></i><span class="job-meta__value"><?php echo esc_html( get_the_date( '', $job_id ) ); ?></span></span>
			<?php if ( $types && ! is_wp_error( $types ) ) : ?>
				<?php foreach ( $types as $term ) : ?>
					<span class="job-meta__item job-meta__item--type flex items-center gap-1.5" title="<?php esc_attr_e( 'Job type', 'job-connect' ); ?>"><i class="fa-solid fa-briefcase opacity-90" aria-hidden="true"></i><span class="job-meta__value"><?php echo esc_html( $term->name ); ?></span></span>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if ( $salary && JC_Settings::get( 'jc_enable_salary' ) === '1' ) : ?>
				<span class="job-meta__item job-meta__item--salary flex items-center gap-1.5" title="<?php esc_attr_e( 'Salary', 'job-connect' ); ?>"><i class="fa-solid fa-coins opacity-90" aria-hidden="true"></i><span class="job-meta__value"><?php echo esc_html( $salary ); ?></span></span>
			<?php endif; ?>
			<?php if ( $expires ) : ?>
				<span class="job-meta__item job-meta__item--expires flex items-center gap-1.5" title="<?php esc_attr_e( 'Expires', 'job-connect' ); ?>"><i class="fa-solid fa-calendar-xmark opacity-90" aria-hidden="true"></i><span class="job-meta__value"><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $expires ) ) ); ?></span></span>
			<?php endif; ?>
		</div>
		<?php else : ?>
		<div class="job-meta mt-2 text-[0.95em] text-zinc-500 [&>span]:mr-4 [&>span]:inline-block [&_a]:text-inherit [&_a]:no-underline [&_a:hover]:underline">
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
			<p class="job-company-tagline mt-2 text-zinc-600"><?php echo esc_html( $company_tagline ); ?></p>
		<?php endif; ?>
		<?php if ( ! $admin_preview && $types && ! is_wp_error( $types ) ) : ?>
			<div class="job-types-categories mt-2 flex flex-wrap gap-1.5">
				<?php foreach ( $types as $term ) : ?>
					<span class="job-type-tag mr-1.5 mb-1.5 inline-flex items-center gap-1.5 rounded-md bg-zinc-100 px-2.5 py-1 text-sm font-medium text-zinc-700"><i class="fa-solid fa-briefcase text-[0.95em] opacity-90" aria-hidden="true"></i><?php echo esc_html( $term->name ); ?></span>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
		<?php if ( ! $admin_preview && $salary && JC_Settings::get( 'jc_enable_salary' ) === '1' ) : ?>
			<p class="job-salary mt-2"><strong><?php esc_html_e( 'Salary', 'job-connect' ); ?>:</strong> <?php echo esc_html( $salary ); ?></p>
		<?php endif; ?>
		<?php if ( ! $admin_preview && $expires ) : ?>
			<p class="job-expires mt-2"><strong><?php esc_html_e( 'Expires', 'job-connect' ); ?>:</strong> <?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $expires ) ) ); ?></p>
		<?php endif; ?>
	</div>

	<div class="job-description my-6 max-w-none leading-relaxed text-zinc-800"><?php echo wp_kses_post( apply_filters( 'the_content', $post->post_content ) ); ?></div>

	<?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
		<div class="job-categories-hashtags mt-4 flex flex-wrap gap-x-3 gap-y-2 border-t border-zinc-200 pt-4 text-[0.95em]">
			<?php foreach ( $categories as $term ) : ?>
				<span class="job-category-tag job-category-tag--hashtag bg-transparent p-0 font-medium text-zinc-500 hover:text-zinc-900">#<?php echo esc_html( $term->name ); ?></span>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<?php if ( ! $admin_preview && $application ) : ?>
		<div class="job-apply-section mt-8 rounded-lg border border-zinc-200 bg-zinc-50 p-6">
			<h3 class="m-0 mb-3 text-base font-semibold text-zinc-900"><?php esc_html_e( 'Apply for this job', 'job-connect' ); ?></h3>
			<p class="m-0">
				<a href="<?php echo esc_url( $apply_url ); ?>" class="job-connect-apply-button inline-flex items-center justify-center rounded-md bg-zinc-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" <?php echo $is_email ? '' : 'target="_blank" rel="noopener noreferrer"'; ?>><?php echo $is_email ? esc_html__( 'Apply by email', 'job-connect' ) : esc_html__( 'Apply for this job', 'job-connect' ); ?></a>
			</p>
		</div>
	<?php endif; ?>

	<?php if ( ! $admin_preview ) : ?>
		<p class="job-posted-date mt-4 text-sm text-zinc-500"><?php printf( esc_html__( 'Posted on %s', 'job-connect' ), esc_html( get_the_date( '', $job_id ) ) ); ?></p>
	<?php endif; ?>
</article>
