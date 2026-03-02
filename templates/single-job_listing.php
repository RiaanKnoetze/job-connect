<?php
/**
 * Single job listing template (full page with header/footer).
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

get_header();

$job_id = get_the_ID();
if ( $job_id ) {
	JC_Template::load( 'content-single-job_listing.php', array( 'job_id' => $job_id ) );
}

get_footer();
