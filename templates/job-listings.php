<?php
/**
 * Job listings template (loop + filters).
 *
 * @package Job_Connect
 * @var array $atts Shortcode attributes.
 */

defined( 'ABSPATH' ) || exit;

// Read search/filter params from URL (overrides shortcode atts for current request).
$search_keywords = isset( $_GET['search_keywords'] ) ? sanitize_text_field( wp_unslash( $_GET['search_keywords'] ) ) : ( isset( $atts['keywords'] ) ? $atts['keywords'] : '' );
$search_location = isset( $_GET['search_location'] ) ? sanitize_text_field( wp_unslash( $_GET['search_location'] ) ) : ( isset( $atts['location'] ) ? $atts['location'] : '' );
$filter_job_type = isset( $_GET['filter_job_type'] ) ? sanitize_text_field( wp_unslash( $_GET['filter_job_type'] ) ) : '';
$filter_category = isset( $_GET['filter_category'] ) ? sanitize_text_field( wp_unslash( $_GET['filter_category'] ) ) : '';

$per_page = isset( $atts['per_page'] ) ? absint( $atts['per_page'] ) : 10;
$paged    = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

$args = array(
	'post_type'      => JC_Post_Types::PT_LISTING,
	'post_status'    => isset( $atts['post_status'] ) ? $atts['post_status'] : 'publish',
	'posts_per_page' => $per_page,
	'paged'          => $paged,
	'orderby'        => isset( $atts['orderby'] ) ? $atts['orderby'] : 'date',
	'order'          => isset( $atts['order'] ) ? strtoupper( $atts['order'] ) : 'DESC',
);

if ( ! empty( $search_keywords ) ) {
	$args['s']                 = $search_keywords;
	$args['jc_search_keywords'] = $search_keywords;
	add_filter( 'posts_join', array( 'JC_Job_Listings_Search', 'join_company' ), 10, 2 );
	add_filter( 'posts_search', array( 'JC_Job_Listings_Search', 'search_company' ), 10, 2 );
}

$meta_query = array();
if ( ! empty( $search_location ) ) {
	$meta_query[] = array( 'key' => '_job_location', 'value' => $search_location, 'compare' => 'LIKE' );
}
if ( JC_Settings::get( 'jc_hide_filled_positions' ) === '1' ) {
	$meta_query[] = array( 'key' => '_filled', 'value' => '1', 'compare' => '!=' );
}
if ( JC_Settings::get( 'jc_hide_expired' ) === '1' ) {
	$meta_query[] = array( 'key' => '_job_expires', 'value' => date( 'Y-m-d', current_time( 'timestamp' ) ), 'compare' => '>=', 'type' => 'DATE' );
}
if ( ! empty( $meta_query ) ) {
	$args['meta_query'] = array_merge( array( 'relation' => 'AND' ), $meta_query );
}

$tax_query = array();
if ( ! empty( $filter_job_type ) && taxonomy_exists( 'job_listing_type' ) ) {
	$tax_query[] = array( 'taxonomy' => 'job_listing_type', 'field' => 'slug', 'terms' => $filter_job_type );
}
if ( ! empty( $filter_category ) && taxonomy_exists( 'job_listing_category' ) ) {
	$tax_query[] = array( 'taxonomy' => 'job_listing_category', 'field' => 'slug', 'terms' => $filter_category );
}
if ( ! empty( $tax_query ) ) {
	$args['tax_query'] = array_merge( array( 'relation' => 'AND' ), $tax_query );
}

$job_query     = new WP_Query( $args );
$enable_salary = JC_Settings::get( 'jc_enable_salary' ) === '1';

if ( ! empty( $search_keywords ) ) {
	remove_filter( 'posts_join', array( 'JC_Job_Listings_Search', 'join_company' ), 10 );
	remove_filter( 'posts_search', array( 'JC_Job_Listings_Search', 'search_company' ), 10 );
}

$filter_atts = array_merge( $atts, array(
	'search_keywords'  => $search_keywords,
	'search_location'  => $search_location,
	'filter_job_type'  => $filter_job_type,
	'filter_category'  => $filter_category,
) );
?>
<div class="job-connect-listings jc-content-wrap">
	<?php if ( ! empty( $atts['show_filters'] ) ) : ?>
		<?php JC_Template::load( 'job-filters.php', array( 'atts' => $filter_atts ) ); ?>
	<?php endif; ?>
	<?php if ( $job_query->have_posts() ) : ?>
		<div class="job-listings job-listings-grid overflow-x-auto text-left text-sm text-zinc-950">
			<div class="jc-listings-table grid min-w-0 sm:min-w-[max-content] grid-cols-1 <?php echo $enable_salary ? 'sm:grid-cols-[2fr_auto_auto_auto_auto]' : 'sm:grid-cols-[2fr_auto_auto_auto]'; ?>" role="table" aria-label="<?php esc_attr_e( 'Job listings', 'job-connect' ); ?>">
				<div class="contents jc-listings-header text-zinc-500" role="row">
					<div class="jc-col-job min-w-0 border-b border-zinc-950/10 px-4 py-2 font-medium" role="columnheader"><?php esc_html_e( 'Job', 'job-connect' ); ?></div>
					<div class="jc-col-location border-b border-zinc-950/10 px-4 py-2 font-medium" role="columnheader"><?php esc_html_e( 'Location', 'job-connect' ); ?></div>
					<div class="jc-col-type border-b border-zinc-950/10 px-4 py-2 font-medium" role="columnheader"><?php esc_html_e( 'Type', 'job-connect' ); ?></div>
				<?php if ( $enable_salary ) : ?>
					<div class="jc-col-salary border-b border-zinc-950/10 px-4 py-2 font-medium" role="columnheader"><?php esc_html_e( 'Salary', 'job-connect' ); ?></div>
				<?php endif; ?>
					<div class="jc-col-actions shrink-0 border-b border-zinc-950/10 px-4 py-2 font-medium" role="columnheader"><span class="sr-only"><?php esc_html_e( 'Actions', 'job-connect' ); ?></span></div>
				</div>
				<?php
				while ( $job_query->have_posts() ) {
					$job_query->the_post();
					JC_Template::load( 'content-job_listing_row.php', array( 'post' => get_post() ) );
				}
				wp_reset_postdata();
				?>
			</div>
		</div>
	<?php else : ?>
		<?php JC_Template::load( 'content-no-jobs-found.php' ); ?>
	<?php endif; ?>
	<?php
	$total_pages = (int) $job_query->max_num_pages;
	$show_pagination = ! empty( $atts['show_pagination'] ) || $total_pages > 1;
	if ( $show_pagination && $total_pages > 1 ) :
		$base = get_permalink();
		$base = add_query_arg( 'paged', '%#%', $base );
		if ( $search_keywords ) {
			$base = add_query_arg( 'search_keywords', $search_keywords, $base );
		}
		if ( $search_location ) {
			$base = add_query_arg( 'search_location', $search_location, $base );
		}
		if ( $filter_job_type ) {
			$base = add_query_arg( 'filter_job_type', $filter_job_type, $base );
		}
		if ( $filter_category ) {
			$base = add_query_arg( 'filter_category', $filter_category, $base );
		}
		?>
		<nav class="job-connect-pagination mt-6 text-center" aria-label="<?php esc_attr_e( 'Job listings pagination', 'job-connect' ); ?>">
			<?php
			echo wp_kses_post( paginate_links( array(
				'total'   => $total_pages,
				'current' => $paged,
				'base'    => $base,
				'format'  => '?paged=%#%',
			) ) );
			?>
		</nav>
	<?php endif; ?>
</div>
