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
<article class="job-listing" data-job-id="<?php echo esc_attr( (string) $job_id ); ?>">
	<h3><a href="<?php echo esc_url( get_permalink( $job_id ) ); ?>"><?php echo esc_html( get_the_title( $job_id ) ); ?></a></h3>
	<div class="job-listing-meta">
		<?php if ( $company ) : ?>
			<span class="company"><?php echo esc_html( $company ); ?></span>
		<?php endif; ?>
		<?php if ( $location ) : ?>
			<span class="location"><?php echo esc_html( $location ); ?></span>
		<?php endif; ?>
		<span class="posted"><?php echo esc_html( $posted ); ?></span>
	</div>
	<?php if ( $types && ! is_wp_error( $types ) ) : ?>
		<div class="job-types">
			<?php foreach ( $types as $term ) : ?>
				<span class="job-type"><?php echo esc_html( $term->name ); ?></span>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
		<div class="job-categories">
			<?php foreach ( $cats as $term ) : ?>
				<span class="job-category"><?php echo esc_html( $term->name ); ?></span>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</article>
