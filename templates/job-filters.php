<?php
/**
 * Job search/filter form.
 *
 * @package Job_Connect
 * @var array $atts Shortcode attributes.
 */

defined( 'ABSPATH' ) || exit;

$action_url = get_permalink();
$keywords   = isset( $atts['search_keywords'] ) ? $atts['search_keywords'] : '';
$location   = isset( $atts['search_location'] ) ? $atts['search_location'] : '';
$job_type   = isset( $atts['filter_job_type'] ) ? $atts['filter_job_type'] : '';
$category   = isset( $atts['filter_category'] ) ? $atts['filter_category'] : '';

$job_types   = array();
$categories  = array();
if ( taxonomy_exists( 'job_listing_type' ) ) {
	$job_types = get_terms( array( 'taxonomy' => 'job_listing_type', 'hide_empty' => true ) );
}
if ( taxonomy_exists( 'job_listing_category' ) ) {
	$categories = get_terms( array( 'taxonomy' => 'job_listing_category', 'hide_empty' => true ) );
}
?>
<form class="job-connect-filters" method="get" action="<?php echo esc_url( $action_url ); ?>">
	<p class="job-connect-filter-keywords">
		<label for="search_keywords"><?php esc_html_e( 'Keywords', 'job-connect' ); ?></label>
		<input type="text" id="search_keywords" name="search_keywords" value="<?php echo esc_attr( $keywords ); ?>" placeholder="<?php esc_attr_e( 'Job title, company, or keyword', 'job-connect' ); ?>" />
	</p>
	<p class="job-connect-filter-location">
		<label for="search_location"><?php esc_html_e( 'Location', 'job-connect' ); ?></label>
		<input type="text" id="search_location" name="search_location" value="<?php echo esc_attr( $location ); ?>" placeholder="<?php esc_attr_e( 'City, region, or remote', 'job-connect' ); ?>" />
	</p>
	<?php if ( ! empty( $job_types ) && ! is_wp_error( $job_types ) ) : ?>
		<p class="job-connect-filter-type">
			<label for="filter_job_type"><?php esc_html_e( 'Job type', 'job-connect' ); ?></label>
			<select id="filter_job_type" name="filter_job_type">
				<option value=""><?php esc_html_e( 'All types', 'job-connect' ); ?></option>
				<?php foreach ( $job_types as $term ) : ?>
					<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $job_type, $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	<?php endif; ?>
	<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
		<p class="job-connect-filter-category">
			<label for="filter_category"><?php esc_html_e( 'Category', 'job-connect' ); ?></label>
			<select id="filter_category" name="filter_category">
				<option value=""><?php esc_html_e( 'All categories', 'job-connect' ); ?></option>
				<?php foreach ( $categories as $term ) : ?>
					<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $category, $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	<?php endif; ?>
	<p class="job-connect-filter-submit">
		<button type="submit" class="button"><?php esc_html_e( 'Search Jobs', 'job-connect' ); ?></button>
	</p>
</form>
