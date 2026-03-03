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

$atts = JC_Post_Types::get_jobs_archive_atts();

if ( JC_Template::theme_has_template( 'header.php' ) ) {
	get_header();
} else {
	JC_Template::load( 'block-theme-document-start.php' );
}

$is_block_theme = ! JC_Template::theme_has_template( 'header.php' );
?>
<?php if ( $is_block_theme ) : ?><main class="wp-block-group"><?php endif; ?>
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
<?php if ( $is_block_theme ) : ?></main><?php endif; ?>
<?php
if ( JC_Template::theme_has_template( 'footer.php' ) ) {
	get_footer();
} else {
	JC_Template::load( 'block-theme-document-end.php' );
}
