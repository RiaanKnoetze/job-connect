<?php
/**
 * Job listings archive template (used for /jobs/ URL).
 * Uses theme-friendly structure (content-area, site-container) so layout and width
 * match pages like Job dashboard and Submit a Job (e.g. Kadence).
 * When a Jobs page is set in settings, its [jobs] shortcode attributes are read
 * and applied here so /jobs/ behaves like the shortcode (filters_layout, show_job_type, show_category).
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

$atts = array(
	'per_page'        => JC_Settings::get( 'jc_per_page' ),
	'orderby'         => 'date',
	'order'           => 'desc',
	'show_filters'    => true,
	'show_pagination' => true,
	'show_job_type'   => 'true',
	'show_category'   => 'true',
	'filters_layout'  => 'default',
	'keywords'        => '',
	'location'        => '',
	'job_types'       => '',
	'categories'      => '',
	'post_status'     => 'publish',
);

$jobs_page_id = (int) JC_Settings::get( 'jc_jobs_page_id' );
if ( $jobs_page_id ) {
	$jobs_page = get_post( $jobs_page_id );
	if ( $jobs_page && $jobs_page->post_content && has_shortcode( $jobs_page->post_content, 'jobs' ) ) {
		$pattern = get_shortcode_regex( array( 'jobs' ) );
		if ( preg_match( '/' . $pattern . '/s', $jobs_page->post_content, $matches ) && ! empty( $matches[3] ) ) {
			$parsed = shortcode_parse_atts( $matches[3] );
			if ( is_array( $parsed ) ) {
				$atts = array_merge( $atts, $parsed );
			}
		}
	}
}

$atts = JC_Shortcodes::normalize_jobs_atts( $atts );

get_header();
?>
<div id="primary" class="content-area">
	<div class="content-container site-container">
		<div id="main" class="site-main">
			<article class="post type-job_listing job-connect-archive">
				<div class="entry-content">
					<?php JC_Template::load( 'job-listings.php', array( 'atts' => $atts ) ); ?>
				</div>
			</article>
		</div>
	</div>
</div>
<?php
get_footer();
