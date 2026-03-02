<?php
/**
 * Job dashboard (employer's jobs).
 *
 * @package Job_Connect
 * @var array $atts Shortcode attributes.
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) {
	echo '<p>' . esc_html__( 'You must be logged in to view your job dashboard.', 'job-connect' ) . '</p>';
	return;
}

$per_page = isset( $atts['posts_per_page'] ) ? absint( $atts['posts_per_page'] ) : 25;
$paged    = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
$args     = array(
	'post_type'      => JC_Post_Types::PT_LISTING,
	'author'          => get_current_user_id(),
	'posts_per_page'  => $per_page,
	'paged'           => $paged,
	'post_status'     => array( 'publish', 'pending', 'draft', 'expired' ),
);
$query = new WP_Query( $args );
$show_success_notice  = isset( $_GET['job_submitted'] ) && $_GET['job_submitted'] === '1';
$show_updated_notice  = isset( $_GET['job_updated'] ) && $_GET['job_updated'] === '1';
?>
<div class="job-connect-dashboard">
	<?php if ( $show_success_notice ) : ?>
		<div class="job-connect-notice job-connect-notice--success" role="status">
			<p><?php esc_html_e( 'Your job was submitted successfully.', 'job-connect' ); ?></p>
		</div>
	<?php endif; ?>
	<?php if ( $show_updated_notice ) : ?>
		<div class="job-connect-notice job-connect-notice--success" role="status">
			<p><?php esc_html_e( 'Your job was updated successfully.', 'job-connect' ); ?></p>
		</div>
	<?php endif; ?>
	<p><a href="<?php echo esc_url( get_permalink( (int) JC_Settings::get( 'jc_submit_job_form_page_id' ) ) ); ?>" class="button"><?php esc_html_e( 'Submit a Job', 'job-connect' ); ?></a></p>
	<?php if ( $query->have_posts() ) : ?>
		<table class="job-dashboard-table">
			<thead>
				<tr>
					<th><?php esc_html_e( 'Job', 'job-connect' ); ?></th>
					<th><?php esc_html_e( 'Status', 'job-connect' ); ?></th>
					<th><?php esc_html_e( 'Actions', 'job-connect' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					$id = get_the_ID();
					?>
					<tr>
						<td><a href="<?php echo esc_url( get_permalink( $id ) ); ?>"><?php the_title(); ?></a></td>
						<td><?php echo esc_html( get_post_status( $id ) ); ?></td>
						<td>
							<?php if ( JC_Form_Submit_Job::instance()->can_edit_job( $id ) ) : ?>
								<a href="<?php echo esc_url( add_query_arg( 'job_id', $id, get_permalink( (int) JC_Settings::get( 'jc_submit_job_form_page_id' ) ) ) ); ?>"><?php esc_html_e( 'Edit', 'job-connect' ); ?></a>
							<?php endif; ?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
		<?php wp_reset_postdata(); ?>
		<?php if ( $query->max_num_pages > 1 ) : ?>
			<nav class="job-connect-pagination"><?php echo wp_kses_post( paginate_links( array( 'total' => $query->max_num_pages ) ) ); ?></nav>
		<?php endif; ?>
	<?php else : ?>
		<p><?php esc_html_e( 'You have not submitted any jobs yet.', 'job-connect' ); ?></p>
	<?php endif; ?>
</div>
