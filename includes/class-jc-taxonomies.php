<?php
/**
 * Job listing taxonomies (type, category).
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Taxonomies class.
 */
class JC_Taxonomies {

	const TAX_LISTING_TYPE     = 'job_listing_type';
	const TAX_LISTING_CATEGORY = 'job_listing_category';

	/**
	 * Single instance.
	 *
	 * @var JC_Taxonomies
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return JC_Taxonomies
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'register' ), 1 );
	}

	/**
	 * Register taxonomies.
	 */
	public function register() {
		$permalink = JC_Post_Types::get_permalink_structure();
		$admin_cap = JC_Post_Types::CAP_MANAGE_LISTINGS;

		if ( get_option( 'jc_enable_categories', '0' ) === '1' ) {
			$singular = __( 'Job category', 'job-connect' );
			$plural   = __( 'Job categories', 'job-connect' );
			register_taxonomy(
				self::TAX_LISTING_CATEGORY,
				JC_Post_Types::PT_LISTING,
				apply_filters( 'job_connect_register_taxonomy_job_listing_category', array(
					'hierarchical'          => true,
					'update_count_callback'  => '_update_post_term_count',
					'label'                  => $plural,
					'labels'                 => array(
						'name'              => $plural,
						'singular_name'     => $singular,
						'menu_name'         => __( 'Categories', 'job-connect' ),
						'search_items'      => sprintf( __( 'Search %s', 'job-connect' ), $plural ),
						'all_items'         => sprintf( __( 'All %s', 'job-connect' ), $plural ),
						'parent_item'        => sprintf( __( 'Parent %s', 'job-connect' ), $singular ),
						'parent_item_colon' => sprintf( __( 'Parent %s:', 'job-connect' ), $singular ),
						'edit_item'          => sprintf( __( 'Edit %s', 'job-connect' ), $singular ),
						'update_item'        => sprintf( __( 'Update %s', 'job-connect' ), $singular ),
						'add_new_item'       => sprintf( __( 'Add New %s', 'job-connect' ), $singular ),
						'new_item_name'      => sprintf( __( 'New %s Name', 'job-connect' ), $singular ),
					),
					'show_ui'               => true,
					'show_tagcloud'         => false,
					'public'                => true,
					'capabilities'          => array(
						'manage_terms' => $admin_cap,
						'edit_terms'   => $admin_cap,
						'delete_terms' => $admin_cap,
						'assign_terms' => $admin_cap,
					),
					'rewrite'               => array(
						'slug'         => $permalink['category_rewrite_slug'],
						'with_front'   => false,
						'hierarchical' => false,
					),
					'show_in_rest'          => true,
					'rest_base'             => 'job-categories',
				) )
			);
		}

		if ( get_option( 'jc_enable_types', '1' ) !== '0' ) {
			$singular = __( 'Job type', 'job-connect' );
			$plural   = __( 'Job types', 'job-connect' );
			register_taxonomy(
				self::TAX_LISTING_TYPE,
				JC_Post_Types::PT_LISTING,
				apply_filters( 'job_connect_register_taxonomy_job_listing_type', array(
					'hierarchical'         => true,
					'label'                 => $plural,
					'labels'                => array(
						'name'              => $plural,
						'singular_name'     => $singular,
						'menu_name'         => __( 'Types', 'job-connect' ),
						'search_items'      => sprintf( __( 'Search %s', 'job-connect' ), $plural ),
						'all_items'         => sprintf( __( 'All %s', 'job-connect' ), $plural ),
						'parent_item'        => sprintf( __( 'Parent %s', 'job-connect' ), $singular ),
						'parent_item_colon' => sprintf( __( 'Parent %s:', 'job-connect' ), $singular ),
						'edit_item'          => sprintf( __( 'Edit %s', 'job-connect' ), $singular ),
						'update_item'        => sprintf( __( 'Update %s', 'job-connect' ), $singular ),
						'add_new_item'       => sprintf( __( 'Add New %s', 'job-connect' ), $singular ),
						'new_item_name'      => sprintf( __( 'New %s Name', 'job-connect' ), $singular ),
					),
					'show_ui'              => true,
					'show_tagcloud'        => false,
					'public'               => true,
					'capabilities'         => array(
						'manage_terms' => $admin_cap,
						'edit_terms'   => $admin_cap,
						'delete_terms' => $admin_cap,
						'assign_terms' => $admin_cap,
					),
					'rewrite'              => array(
						'slug'         => $permalink['type_rewrite_slug'],
						'with_front'   => false,
						'hierarchical' => false,
					),
					'show_in_rest'         => true,
					'rest_base'            => 'job-types',
				) )
			);

			register_meta(
				'term',
				'employment_type',
				array(
					'object_subtype'    => self::TAX_LISTING_TYPE,
					'show_in_rest'      => true,
					'type'              => 'string',
					'single'            => true,
					'description'       => __( 'Employment Type', 'job-connect' ),
					'sanitize_callback' => 'sanitize_text_field',
				)
			);
		}
	}
}
