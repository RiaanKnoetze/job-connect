<?php
/**
 * Uninstall Job Connect.
 *
 * @package Job_Connect
 */

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

$delete_data  = get_option( 'jc_delete_data_on_uninstall', '0' );
$bypass_trash = get_option( 'jc_bypass_trash_on_uninstall', '0' );

if ( '1' !== $delete_data ) {
	return;
}

global $wpdb;

// Delete options.
$options = $wpdb->get_col( "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE 'jc_%'" );
foreach ( $options as $option ) {
	delete_option( $option );
}

// Delete job listing posts (bypass trash if option set).
$post_ids = $wpdb->get_col( "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'job_listing'" );
foreach ( $post_ids as $post_id ) {
	if ( '1' === $bypass_trash ) {
		wp_delete_post( (int) $post_id, true );
	} else {
		wp_trash_post( (int) $post_id );
	}
}

// Delete terms and taxonomies.
$taxonomies = array( 'job_listing_type', 'job_listing_category' );
foreach ( $taxonomies as $taxonomy ) {
	$terms = get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => false, 'fields' => 'ids' ) );
	if ( ! is_wp_error( $terms ) ) {
		foreach ( $terms as $term_id ) {
			wp_delete_term( $term_id, $taxonomy );
		}
	}
}

// Clear scheduled hooks.
wp_clear_scheduled_hook( 'job_connect_check_expired_jobs' );
