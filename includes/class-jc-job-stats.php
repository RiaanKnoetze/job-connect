<?php
/**
 * Job view and impression tracking.
 *
 * Views = someone loaded the single job page (full listing).
 * Impressions = the job was shown in a list (e.g. jobs archive, search results).
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Job_Stats class.
 */
class JC_Job_Stats {

	const META_VIEW       = '_view_count';
	const META_IMPRESSION = '_impression_count';

	/**
	 * Job IDs that already had an impression recorded this request (avoid double count).
	 *
	 * @var array
	 */
	private static $impressions_recorded = array();

	/**
	 * Constructor.
	 */
	public static function init() {
		add_action( 'template_redirect', array( __CLASS__, 'maybe_record_view' ), 20 );
	}

	/**
	 * Record one view for a job (single job page load).
	 * Skips if the viewer is the job author (employer viewing own listing).
	 *
	 * @param int $job_id Job post ID.
	 */
	public static function record_view( $job_id ) {
		$job_id = absint( $job_id );
		if ( ! $job_id ) {
			return;
		}
		$post = get_post( $job_id );
		if ( ! $post || $post->post_type !== JC_Post_Types::PT_LISTING ) {
			return;
		}
		if ( $post->post_status !== 'publish' ) {
			return;
		}
		// Don't count when the job owner views their own listing.
		if ( is_user_logged_in() && (int) $post->post_author === (int) get_current_user_id() ) {
			return;
		}
		$count = absint( get_post_meta( $job_id, self::META_VIEW, true ) );
		update_post_meta( $job_id, self::META_VIEW, $count + 1 );
	}

	/**
	 * Record one impression for a job (job was shown in a list).
	 * Only counts once per job per request.
	 *
	 * @param int $job_id Job post ID.
	 */
	public static function record_impression( $job_id ) {
		$job_id = absint( $job_id );
		if ( ! $job_id ) {
			return;
		}
		if ( isset( self::$impressions_recorded[ $job_id ] ) ) {
			return;
		}
		$post = get_post( $job_id );
		if ( ! $post || $post->post_type !== JC_Post_Types::PT_LISTING ) {
			return;
		}
		// Only count published jobs (or pending if you show those in lists).
		if ( ! in_array( $post->post_status, array( 'publish', 'pending' ), true ) ) {
			return;
		}
		self::$impressions_recorded[ $job_id ] = true;
		$count = absint( get_post_meta( $job_id, self::META_IMPRESSION, true ) );
		update_post_meta( $job_id, self::META_IMPRESSION, $count + 1 );
	}

	/**
	 * On single job listing page, record a view.
	 */
	public static function maybe_record_view() {
		if ( ! is_singular( JC_Post_Types::PT_LISTING ) ) {
			return;
		}
		$job_id = get_queried_object_id();
		if ( ! $job_id ) {
			return;
		}
		self::record_view( $job_id );
	}
}
