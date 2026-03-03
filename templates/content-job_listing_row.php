<?php
/**
 * Single job listing row (flex layout for jobs list).
 *
 * @package Job_Connect
 * @var WP_Post $post Job listing post.
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $post ) || ! $post instanceof WP_Post ) {
	return;
}
$job_id   = $post->ID;
$location = get_post_meta( $job_id, '_job_location', true );
$company  = get_post_meta( $job_id, '_company_name', true );
$types    = get_the_terms( $job_id, 'job_listing_type' );
$posted   = get_the_date( '', $job_id );
?>
<div class="contents" role="row">
	<div class="jc-col-job relative min-w-0 border-b border-zinc-950/5 px-4 py-4" role="cell">
		<div class="flex items-center gap-4">
			<div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-zinc-100 text-zinc-500" aria-hidden="true">
				<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
			</div>
			<div class="min-w-0 flex-1">
				<div class="font-medium text-zinc-900">
					<a href="<?php echo esc_url( get_permalink( $job_id ) ); ?>" class="hover:text-zinc-700 hover:underline"><?php echo esc_html( get_the_title( $job_id ) ); ?></a>
				</div>
				<div class="mt-0.5 text-zinc-500">
					<?php if ( $company ) : ?>
						<span><?php echo esc_html( $company ); ?></span>
						<?php if ( $posted ) : ?><span class="mx-1.5" aria-hidden="true">·</span><?php endif; ?>
					<?php endif; ?>
					<?php if ( $posted ) : ?>
						<time datetime="<?php echo esc_attr( get_the_date( 'c', $job_id ) ); ?>"><?php echo esc_html( $posted ); ?></time>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<div class="jc-col-location relative min-w-0 border-b border-zinc-950/5 px-4 py-4 text-zinc-500" role="cell">
		<?php echo $location ? esc_html( $location ) : '—'; ?>
	</div>
	<div class="jc-col-type relative min-w-0 border-b border-zinc-950/5 px-4 py-4 text-zinc-500" role="cell">
		<?php if ( $types && ! is_wp_error( $types ) ) : ?>
			<div class="flex flex-wrap gap-1.5">
				<?php foreach ( $types as $term ) : ?>
					<span class="inline-flex items-center gap-x-1.5 rounded-md px-1.5 py-0.5 text-sm font-medium bg-zinc-500/10 text-zinc-700"><?php echo esc_html( $term->name ); ?></span>
				<?php endforeach; ?>
			</div>
		<?php else : ?>
			—
		<?php endif; ?>
	</div>
	<div class="jc-col-actions relative shrink-0 border-b border-zinc-950/5 px-4 py-4" role="cell">
		<a href="<?php echo esc_url( get_permalink( $job_id ) ); ?>" class="rounded px-2 py-1 text-sm font-medium text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900"><?php esc_html_e( 'View', 'job-connect' ); ?></a>
	</div>
</div>
