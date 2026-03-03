<?php
/**
 * Single job listing template (full page with header/footer).
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

if ( JC_Template::theme_has_template( 'header.php' ) ) {
	get_header();
} else {
	JC_Template::load( 'block-theme-document-start.php' );
}

$job_id = get_the_ID();
if ( $job_id ) {
	JC_Template::load( 'content-single-job_listing.php', array( 'job_id' => $job_id ) );
}

if ( JC_Template::theme_has_template( 'footer.php' ) ) {
	get_footer();
} else {
	JC_Template::load( 'block-theme-document-end.php' );
}
