<?php
/**
 * Job summary (short version).
 *
 * @package Job_Connect
 * @var int $job_id Job post ID.
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $job_id ) ) {
	return;
}
$post = get_post( $job_id );
if ( ! $post || $post->post_type !== JC_Post_Types::PT_LISTING ) {
	return;
}
?>
<div class="job-summary">
	<strong><a href="<?php echo esc_url( get_permalink( $job_id ) ); ?>"><?php echo esc_html( get_the_title( $job_id ) ); ?></a></strong>
	<?php
	$company = get_post_meta( $job_id, '_company_name', true );
	if ( $company ) {
		echo ' — ' . esc_html( $company );
	}
	?>
</div>
