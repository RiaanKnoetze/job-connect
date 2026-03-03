<?php
/**
 * Job dashboard (employer's jobs).
 *
 * @package Job_Connect
 * @var array $atts Shortcode attributes.
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) {
	echo '<p>' . esc_html__( 'You must be logged in to view your job dashboard.', 'job-connect' ) . '</p>';
	return;
}

$per_page = isset( $atts['posts_per_page'] ) ? absint( $atts['posts_per_page'] ) : 25;
$paged    = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$args     = array(
	'post_type'      => JC_Post_Types::PT_LISTING,
	'author'          => get_current_user_id(),
	'posts_per_page'  => $per_page,
	'paged'           => $paged,
	'post_status'     => array( 'publish', 'pending', 'draft', 'expired' ),
);
$query = new WP_Query( $args );
$show_success_notice  = isset( $_GET['job_submitted'] ) && $_GET['job_submitted'] === '1';
$show_updated_notice  = isset( $_GET['job_updated'] ) && $_GET['job_updated'] === '1';
?>
<div class="job-connect-dashboard jc-content-wrap w-full my-6 px-4">
	<?php if ( $show_success_notice ) : ?>
		<div class="job-connect-notice job-connect-notice--success mb-4 flex flex-row items-center gap-4 rounded-lg border-l-4 border-l-emerald-500 bg-emerald-50 py-3 pl-4 pr-4" role="status">
			<span class="shrink-0 text-emerald-600" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
			</span>
			<p class="!mb-0 m-0 min-w-0 flex-1 text-sm font-medium text-emerald-900"><?php esc_html_e( 'Your job was submitted successfully.', 'job-connect' ); ?></p>
		</div>
	<?php endif; ?>
	<?php if ( $show_updated_notice ) : ?>
		<div class="job-connect-notice job-connect-notice--success mb-4 flex flex-row items-center gap-4 rounded-lg border-l-4 border-l-emerald-500 bg-emerald-50 py-3 pl-4 pr-4" role="status">
			<span class="shrink-0 text-emerald-600" aria-hidden="true">
				<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" /></svg>
			</span>
			<p class="!mb-0 m-0 min-w-0 flex-1 text-sm font-medium text-emerald-900"><?php esc_html_e( 'Your job was updated successfully.', 'job-connect' ); ?></p>
		</div>
	<?php endif; ?>
	<p class="mb-4">
		<a href="<?php echo esc_url( get_permalink( (int) JC_Settings::get( 'jc_submit_job_form_page_id' ) ) ); ?>" class="inline-flex items-center justify-center rounded-md bg-zinc-900 px-4 py-2 text-sm font-medium text-white shadow-sm transition-colors hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"><?php esc_html_e( 'Submit a Job', 'job-connect' ); ?></a>
	</p>
	<?php if ( $query->have_posts() ) : ?>
		<div class="job-dashboard-list overflow-x-auto text-left text-sm text-zinc-950">
			<div class="grid min-w-[max-content] grid-cols-[2fr_1fr_auto]" role="table" aria-label="<?php esc_attr_e( 'Your job listings', 'job-connect' ); ?>">
				<div class="contents text-zinc-500" role="row">
					<div class="jc-col-job min-w-0 border-b border-zinc-950/10 px-4 py-2 font-medium" role="columnheader"><?php esc_html_e( 'Job', 'job-connect' ); ?></div>
					<div class="jc-col-status min-w-0 border-b border-zinc-950/10 px-4 py-2 font-medium" role="columnheader"><?php esc_html_e( 'Status', 'job-connect' ); ?></div>
					<div class="jc-col-actions shrink-0 border-b border-zinc-950/10 px-4 py-2 font-medium" role="columnheader"><span class="sr-only"><?php esc_html_e( 'Actions', 'job-connect' ); ?></span></div>
				</div>
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					$id       = get_the_ID();
					$status   = get_post_status( $id );
					$company  = get_post_meta( $id, '_company_name', true );
					$posted   = get_the_date( '', $id );
					$edit_url = add_query_arg( 'job_id', $id, get_permalink( (int) JC_Settings::get( 'jc_submit_job_form_page_id' ) ) );
					$can_edit = JC_Form_Submit_Job::instance()->can_edit_job( $id );
					$status_badge = array(
						'publish' => 'bg-lime-400/20 text-lime-700',
						'pending' => 'bg-amber-400/20 text-amber-700',
						'draft'   => 'bg-zinc-500/10 text-zinc-700',
						'expired' => 'bg-red-400/20 text-red-700',
					);
					$badge_class = isset( $status_badge[ $status ] ) ? $status_badge[ $status ] : 'bg-zinc-500/10 text-zinc-700';
					?>
					<div class="contents" role="row">
						<div class="jc-col-job relative min-w-0 border-b border-zinc-950/5 px-4 py-4" role="cell">
							<div class="flex items-center gap-4">
								<div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-zinc-100 text-zinc-500" aria-hidden="true">
									<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
								</div>
								<div class="min-w-0 flex-1">
									<div class="font-medium text-zinc-900">
										<a href="<?php echo esc_url( get_permalink( $id ) ); ?>" class="hover:text-zinc-700 hover:underline"><?php the_title(); ?></a>
									</div>
									<div class="mt-0.5 text-zinc-500">
										<?php if ( $company ) : ?>
											<span><?php echo esc_html( $company ); ?></span>
											<?php if ( $posted ) : ?><span class="mx-1.5" aria-hidden="true">·</span><?php endif; ?>
										<?php endif; ?>
										<?php if ( $posted ) : ?>
											<time datetime="<?php echo esc_attr( get_the_date( 'c', $id ) ); ?>"><?php echo esc_html( $posted ); ?></time>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>
						<div class="jc-col-status relative min-w-0 border-b border-zinc-950/5 px-4 py-4 text-zinc-500" role="cell">
							<span class="inline-flex items-center gap-x-1.5 rounded-md px-1.5 py-0.5 text-sm font-medium <?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( ucfirst( $status ) ); ?></span>
						</div>
						<div class="jc-col-actions relative shrink-0 border-b border-zinc-950/5 px-4 py-4" role="cell">
							<div class="-mx-2 flex items-center gap-1">
								<a href="<?php echo esc_url( get_permalink( $id ) ); ?>" class="rounded px-2 py-1 text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900"><?php esc_html_e( 'View', 'job-connect' ); ?></a>
								<?php if ( $can_edit ) : ?>
									<a href="<?php echo esc_url( $edit_url ); ?>" class="rounded px-2 py-1 text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900"><?php esc_html_e( 'Edit', 'job-connect' ); ?></a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php wp_reset_postdata(); ?>
		<?php if ( $query->max_num_pages > 1 ) : ?>
			<nav class="job-connect-pagination mt-6 text-center"><?php echo wp_kses_post( paginate_links( array( 'total' => $query->max_num_pages ) ) ); ?></nav>
		<?php endif; ?>
	<?php else : ?>
		<p class="text-sm text-zinc-600"><?php esc_html_e( 'You have not submitted any jobs yet.', 'job-connect' ); ?></p>
	<?php endif; ?>
</div>
