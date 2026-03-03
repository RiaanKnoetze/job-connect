<?php
/**
 * Single job listing card (in loop).
 *
 * @package Job_Connect
 * @var WP_Post $post Job listing post.
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $post ) || ! $post instanceof WP_Post ) {
	return;
}
$job_id    = $post->ID;
$location  = get_post_meta( $job_id, '_job_location', true );
$company   = get_post_meta( $job_id, '_company_name', true );
$types     = get_the_terms( $job_id, 'job_listing_type' );
$cats      = get_the_terms( $job_id, 'job_listing_category' );
$posted    = get_the_date( '', $job_id );
?>
<article class="job-listing rounded-lg border border-zinc-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md" data-job-id="<?php echo esc_attr( (string) $job_id ); ?>">
	<h3 class="m-0 mb-2 text-base font-semibold text-zinc-900">
		<a href="<?php echo esc_url( get_permalink( $job_id ) ); ?>" class="no-underline text-inherit hover:underline"><?php echo esc_html( get_the_title( $job_id ) ); ?></a>
	</h3>
	<div class="job-listing-meta mt-2 text-sm text-zinc-500 space-x-4 [&>span+span]:before:content-['•'] [&>span+span]:before:mr-2 [&>span+span]:before:text-zinc-400">
		<?php if ( $company ) : ?>
			<span class="company inline-block"><?php echo esc_html( $company ); ?></span>
		<?php endif; ?>
		<?php if ( $location ) : ?>
			<span class="location inline-block"><?php echo esc_html( $location ); ?></span>
		<?php endif; ?>
		<span class="posted inline-block"><?php echo esc_html( $posted ); ?></span>
	</div>
	<?php if ( $types && ! is_wp_error( $types ) ) : ?>
		<div class="job-types mt-2 flex flex-wrap gap-1.5">
			<?php foreach ( $types as $term ) : ?>
				<span class="job-type inline-block rounded-md bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-700"><?php echo esc_html( $term->name ); ?></span>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
		<div class="job-categories mt-2 flex flex-wrap gap-1.5">
			<?php foreach ( $cats as $term ) : ?>
				<span class="job-category inline-block rounded-md bg-zinc-100 px-2 py-0.5 text-xs font-medium text-zinc-700"><?php echo esc_html( $term->name ); ?></span>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</article>
