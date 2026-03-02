<?php
/**
 * Job listings archive template (used for /jobs/ URL).
 * Shows search form and job list using plugin settings.
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
	'keywords'        => '',
	'location'        => '',
	'job_types'       => '',
	'categories'      => '',
	'post_status'     => 'publish',
);

get_header();
?>
<div class="job-connect-archive-wrap">
	<?php JC_Template::load( 'job-listings.php', array( 'atts' => $atts ) ); ?>
</div>
<?php
get_footer();
