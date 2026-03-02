<?php
/**
 * Public REST API for job listings.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_REST_Jobs class.
 */
class JC_REST_Jobs {

	const NAMESPACE = 'jc/v1';

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	/**
	 * Register REST routes.
	 */
	public function register_routes() {
		register_rest_route(
			self::NAMESPACE,
			'/jobs',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_jobs' ),
				'permission_callback' => array( $this, 'can_browse_jobs' ),
				'args'                => array(
					'per_page' => array( 'default' => 10, 'sanitize_callback' => 'absint' ),
					'page'     => array( 'default' => 1, 'sanitize_callback' => 'absint' ),
					'search'   => array( 'sanitize_callback' => 'sanitize_text_field' ),
					'location' => array( 'sanitize_callback' => 'sanitize_text_field' ),
				),
			)
		);
		register_rest_route(
			self::NAMESPACE,
			'/jobs/(?P<id>\d+)',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_job' ),
				'permission_callback' => array( $this, 'can_view_job' ),
				'args'                => array( 'id' => array( 'validate_callback' => function( $v ) { return is_numeric( $v ); } ) ),
			)
		);
	}

	/**
	 * Check if user can browse jobs.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return bool
	 */
	public function can_browse_jobs( $request ) {
		$caps = JC_Settings::get( 'jc_browse_job_listings_capability' );
		if ( empty( $caps ) || ! is_array( $caps ) ) {
			return true;
		}
		$user = wp_get_current_user();
		foreach ( $caps as $cap ) {
			if ( $user->has_cap( $cap ) ) {
				return true;
			}
		}
		return current_user_can( 'manage_options' );
	}

	/**
	 * Check if user can view a single job.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return bool
	 */
	public function can_view_job( $request ) {
		$caps = JC_Settings::get( 'jc_view_job_listing_capability' );
		if ( empty( $caps ) || ! is_array( $caps ) ) {
			return true;
		}
		$user = wp_get_current_user();
		foreach ( $caps as $cap ) {
			if ( $user->has_cap( $cap ) ) {
				return true;
			}
		}
		return current_user_can( 'manage_options' );
	}

	/**
	 * GET jobs list.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response
	 */
	public function get_jobs( $request ) {
		$args = array(
			'post_type'      => JC_Post_Types::PT_LISTING,
			'post_status'    => 'publish',
			'posts_per_page' => $request->get_param( 'per_page' ),
			'paged'          => $request->get_param( 'page' ),
		);
		if ( $request->get_param( 'search' ) ) {
			$args['s'] = $request->get_param( 'search' );
		}
		if ( $request->get_param( 'location' ) ) {
			$args['meta_query'] = array( array( 'key' => '_job_location', 'value' => $request->get_param( 'location' ), 'compare' => 'LIKE' ) );
		}
		$query = new WP_Query( $args );
		$jobs  = array();
		foreach ( $query->posts as $post ) {
			$jobs[] = $this->prepare_job_for_response( $post );
		}
		return new WP_REST_Response( array( 'jobs' => $jobs, 'total' => (int) $query->found_posts ) );
	}

	/**
	 * GET single job.
	 *
	 * @param WP_REST_Request $request Request.
	 * @return WP_REST_Response|WP_Error
	 */
	public function get_job( $request ) {
		$id = (int) $request->get_param( 'id' );
		$post = get_post( $id );
		if ( ! $post || $post->post_type !== JC_Post_Types::PT_LISTING ) {
			return new WP_Error( 'not_found', __( 'Job not found.', 'job-connect' ), array( 'status' => 404 ) );
		}
		if ( $post->post_status !== 'publish' ) {
			return new WP_Error( 'not_found', __( 'Job not found.', 'job-connect' ), array( 'status' => 404 ) );
		}
		return new WP_REST_Response( $this->prepare_job_for_response( $post ) );
	}

	/**
	 * Prepare job post for REST response.
	 *
	 * @param WP_Post $post Post.
	 * @return array
	 */
	private function prepare_job_for_response( $post ) {
		return array(
			'id'          => $post->ID,
			'title'       => $post->post_title,
			'description' => apply_filters( 'the_content', $post->post_content ),
			'company'     => get_post_meta( $post->ID, '_company_name', true ),
			'location'    => get_post_meta( $post->ID, '_job_location', true ),
			'application' => get_post_meta( $post->ID, '_application', true ),
			'expires'     => get_post_meta( $post->ID, '_job_expires', true ),
			'link'        => get_permalink( $post->ID ),
			'date'        => get_the_date( 'c', $post ),
		);
	}
}
