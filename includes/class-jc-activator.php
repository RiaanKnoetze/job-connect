<?php
/**
 * Fired during plugin activation.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Activator class.
 */
class JC_Activator {

	/**
	 * Activate the plugin.
	 */
	public static function activate() {
		self::init_user_roles();
		self::default_options();
		JC_Post_Types::instance()->register_post_type();
		JC_Taxonomies::instance()->register();
		self::default_terms();
		flush_rewrite_rules();
		update_option( 'job_connect_version', JC_VERSION );
	}

	/**
	 * Initialize user roles (employer).
	 */
	private static function init_user_roles() {
		$roles = wp_roles();
		if ( ! is_object( $roles ) ) {
			return;
		}

		if ( ! get_role( 'employer' ) ) {
			add_role(
				'employer',
				__( 'Employer', 'job-connect' ),
				array(
					'read'         => true,
					'edit_posts'   => false,
					'delete_posts' => false,
				)
			);
		}

		$capabilities = self::get_core_capabilities();
		foreach ( $capabilities as $cap_group ) {
			foreach ( $cap_group as $cap ) {
				$roles->add_cap( 'administrator', $cap );
			}
		}
	}

	/**
	 * Get core capabilities for job listings.
	 *
	 * @return array
	 */
	private static function get_core_capabilities() {
		return array(
			'core' => array(
				JC_Post_Types::CAP_MANAGE_LISTINGS,
			),
			JC_Post_Types::PT_LISTING => array(
				JC_Post_Types::CAP_EDIT_LISTING,
				JC_Post_Types::CAP_READ_LISTING,
				JC_Post_Types::CAP_DELETE_LISTING,
				JC_Post_Types::CAP_EDIT_LISTINGS,
				JC_Post_Types::CAP_EDIT_OTHERS_LISTINGS,
				JC_Post_Types::CAP_PUBLISH_LISTINGS,
				JC_Post_Types::CAP_READ_PRIVATE_LISTINGS,
				JC_Post_Types::CAP_DELETE_LISTINGS,
				JC_Post_Types::CAP_DELETE_PRIVATE_LISTINGS,
				JC_Post_Types::CAP_DELETE_PUBLISHED_LISTINGS,
				JC_Post_Types::CAP_DELETE_OTHERS_LISTINGS,
				JC_Post_Types::CAP_EDIT_PRIVATE_LISTINGS,
				JC_Post_Types::CAP_EDIT_PUBLISHED_LISTINGS,
				JC_Post_Types::CAP_MANAGE_LISTING_TERMS,
				JC_Post_Types::CAP_EDIT_LISTING_TERMS,
				JC_Post_Types::CAP_DELETE_LISTING_TERMS,
				JC_Post_Types::CAP_ASSIGN_LISTING_TERMS,
			),
		);
	}

	/**
	 * Set default options.
	 */
	private static function default_options() {
		$defaults = JC_Settings::get_defaults();
		foreach ( $defaults as $key => $value ) {
			if ( false === get_option( $key, false ) ) {
				add_option( $key, $value );
			}
		}
	}

	/**
	 * Create default taxonomy terms (job types).
	 */
	private static function default_terms() {
		if ( 1 === (int) get_option( 'jc_installed_terms', 0 ) ) {
			return;
		}

		$taxonomies = array(
			'job_listing_type' => array(
				'Full Time'  => array( 'employment_type' => 'FULL_TIME' ),
				'Part Time'  => array( 'employment_type' => 'PART_TIME' ),
				'Temporary'  => array( 'employment_type' => 'TEMPORARY' ),
				'Freelance'  => array( 'employment_type' => 'CONTRACTOR' ),
				'Internship' => array( 'employment_type' => 'INTERN' ),
			),
		);

		foreach ( $taxonomies as $taxonomy => $terms ) {
			foreach ( $terms as $term_name => $meta ) {
				if ( ! get_term_by( 'slug', sanitize_title( $term_name ), $taxonomy ) ) {
					$result = wp_insert_term( $term_name, $taxonomy );
					if ( is_array( $result ) && isset( $result['term_id'] ) && ! empty( $meta ) ) {
						foreach ( $meta as $meta_key => $meta_value ) {
							add_term_meta( $result['term_id'], $meta_key, $meta_value );
						}
					}
				}
			}
		}

		update_option( 'jc_installed_terms', 1 );
	}
}
