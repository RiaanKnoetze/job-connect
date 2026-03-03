<?php
/**
 * Job search/filter form.
 *
 * @package Job_Connect
 * @var array $atts Shortcode attributes.
 */

defined( 'ABSPATH' ) || exit;

$jobs_page_id = (int) JC_Settings::get( 'jc_jobs_page_id' );
$action_url   = $jobs_page_id ? get_permalink( $jobs_page_id ) : get_permalink();
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

// Normalize to boolean so "false" (string) is not treated as truthy. Unset = show by default.
$show_job_type  = isset( $atts['show_job_type'] ) ? filter_var( $atts['show_job_type'], FILTER_VALIDATE_BOOLEAN ) : true;
$show_category  = isset( $atts['show_category'] ) ? filter_var( $atts['show_category'], FILTER_VALIDATE_BOOLEAN ) : true;
$layout_inline  = isset( $atts['filters_layout'] ) && $atts['filters_layout'] === 'inline';

$form_classes = 'job-connect-filters bg-white border border-zinc-200 rounded-lg p-5 mb-6 shadow-sm';
if ( $layout_inline ) {
	$form_classes .= ' job-connect-filters--inline flex flex-wrap items-end gap-4 w-full';
} else {
	$form_classes .= ' job-connect-filters--default grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_minmax(0,1fr)_minmax(0,1fr)_auto] gap-4 lg:gap-5 items-end';
}
$field_inline_classes = $layout_inline ? ' flex-1 min-w-0' : '';
?>
<form class="<?php echo esc_attr( $form_classes ); ?>" method="get" action="<?php echo esc_url( $action_url ); ?>">
	<p class="job-connect-filter-keywords<?php echo esc_attr( $field_inline_classes ); ?>">
		<label for="search_keywords" class="block text-sm font-medium text-zinc-700 mb-1.5"><?php esc_html_e( 'Keywords', 'job-connect' ); ?></label>
		<input type="text" id="search_keywords" name="search_keywords" value="<?php echo esc_attr( $keywords ); ?>" placeholder="<?php esc_attr_e( 'Job title, company, or keyword', 'job-connect' ); ?>" class="block w-full max-w-full min-w-0 rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm<?php echo $layout_inline ? ' ' . esc_attr( 'box-border' ) : ''; ?>" />
	</p>
	<p class="job-connect-filter-location<?php echo esc_attr( $field_inline_classes ); ?>">
		<label for="search_location" class="block text-sm font-medium text-zinc-700 mb-1.5"><?php esc_html_e( 'Location', 'job-connect' ); ?></label>
		<input type="text" id="search_location" name="search_location" value="<?php echo esc_attr( $location ); ?>" placeholder="<?php esc_attr_e( 'City, region, or remote', 'job-connect' ); ?>" class="block w-full max-w-full min-w-0 rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 shadow-sm placeholder:text-zinc-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm<?php echo $layout_inline ? ' ' . esc_attr( 'box-border' ) : ''; ?>" />
	</p>
	<?php if ( $show_job_type && ! empty( $job_types ) && ! is_wp_error( $job_types ) ) : ?>
		<p class="job-connect-filter-type<?php echo esc_attr( $field_inline_classes ); ?>">
			<label for="filter_job_type" class="block text-sm font-medium text-zinc-700 mb-1.5"><?php esc_html_e( 'Job type', 'job-connect' ); ?></label>
			<select id="filter_job_type" name="filter_job_type" class="block w-full max-w-full min-w-0 rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm<?php echo $layout_inline ? ' box-border' : ''; ?>">
				<option value=""><?php esc_html_e( 'All types', 'job-connect' ); ?></option>
				<?php foreach ( $job_types as $term ) : ?>
					<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $job_type, $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	<?php endif; ?>
	<?php if ( $show_category && ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
		<p class="job-connect-filter-category<?php echo esc_attr( $field_inline_classes ); ?>">
			<label for="filter_category" class="block text-sm font-medium text-zinc-700 mb-1.5"><?php esc_html_e( 'Category', 'job-connect' ); ?></label>
			<select id="filter_category" name="filter_category" class="block w-full max-w-full min-w-0 rounded-md border border-zinc-300 px-3 py-2 text-zinc-900 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm<?php echo $layout_inline ? ' box-border' : ''; ?>">
				<option value=""><?php esc_html_e( 'All categories', 'job-connect' ); ?></option>
				<?php foreach ( $categories as $term ) : ?>
					<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $category, $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
				<?php endforeach; ?>
			</select>
		</p>
	<?php endif; ?>
	<p class="job-connect-filter-submit <?php echo esc_attr( $layout_inline ? 'shrink-0' : 'sm:col-span-2 lg:col-span-1 lg:flex lg:justify-end' ); ?>">
		<button type="submit" class="inline-flex items-center justify-center rounded-md bg-zinc-900 px-5 py-2.5 text-sm font-medium text-white shadow-sm transition-colors hover:bg-zinc-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 min-h-[42px]"><?php esc_html_e( 'Search Jobs', 'job-connect' ); ?></button>
	</p>
</form>
