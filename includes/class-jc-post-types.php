<?php
/**
 * Job listing post type registration.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Post_Types class.
 */
class JC_Post_Types {

	const PT_LISTING = 'job_listing';

	const CAP_MANAGE_LISTINGS         = 'manage_job_listings';
	const CAP_EDIT_LISTING            = 'edit_job_listing';
	const CAP_READ_LISTING            = 'read_job_listing';
	const CAP_DELETE_LISTING          = 'delete_job_listing';
	const CAP_EDIT_LISTINGS           = 'edit_job_listings';
	const CAP_EDIT_OTHERS_LISTINGS    = 'edit_others_job_listings';
	const CAP_PUBLISH_LISTINGS        = 'publish_job_listings';
	const CAP_READ_PRIVATE_LISTINGS   = 'read_private_job_listings';
	const CAP_DELETE_LISTINGS         = 'delete_job_listings';
	const CAP_DELETE_PRIVATE_LISTINGS = 'delete_private_job_listings';
	const CAP_DELETE_PUBLISHED_LISTINGS = 'delete_published_job_listings';
	const CAP_DELETE_OTHERS_LISTINGS  = 'delete_others_job_listings';
	const CAP_EDIT_PRIVATE_LISTINGS   = 'edit_private_job_listings';
	const CAP_EDIT_PUBLISHED_LISTINGS = 'edit_published_job_listings';
	const CAP_MANAGE_LISTING_TERMS     = 'manage_job_listing_terms';
	const CAP_EDIT_LISTING_TERMS      = 'edit_job_listing_terms';
	const CAP_DELETE_LISTING_TERMS    = 'delete_job_listing_terms';
	const CAP_ASSIGN_LISTING_TERMS    = 'assign_job_listing_terms';

	const PERMALINK_OPTION = 'jc_permalinks';

	/**
	 * Single instance.
	 *
	 * @var JC_Post_Types
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return JC_Post_Types
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
		add_action( 'init', array( $this, 'register_post_type' ), 0 );
		add_action( 'init', array( $this, 'add_feed' ), 10 );
		add_action( 'job_connect_check_expired_jobs', array( $this, 'check_for_expired_jobs' ) );
		add_filter( 'template_include', array( $this, 'job_template_include' ), 99 );
		add_filter( 'the_content', array( $this, 'single_job_content' ), 5 );
		add_filter( 'render_block', array( $this, 'suppress_blog_blocks_on_single_job' ), 10, 2 );
		add_filter( 'render_block', array( $this, 'inject_jobs_archive_into_query_block' ), 10, 2 );
		add_filter( 'body_class', array( $this, 'single_job_body_class' ), 10, 2 );
	}

	/**
	 * Use plugin template only for job_listing archive. Single job uses theme template so header/nav render correctly.
	 *
	 * @param string $template Current template path.
	 * @return string
	 */
	public function job_template_include( $template ) {
		if ( ! is_post_type_archive( self::PT_LISTING ) ) {
			return $template;
		}
		// Block themes: use the theme's template so header/footer/layout match; we inject content via render_block.
		if ( ! JC_Template::theme_has_template( 'header.php' ) ) {
			return $template;
		}
		$archive_template = JC_Template::locate( 'archive-job_listing.php' );
		if ( file_exists( $archive_template ) ) {
			return $archive_template;
		}
		return $template;
	}

	/**
	 * Replace post content with single job layout when viewing a job_listing.
	 * Theme template (and thus header/nav) is used; only the_content is our job markup.
	 * Removes this filter while rendering so the template's apply_filters('the_content') does not recurse.
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	public function single_job_content( $content ) {
		if ( ! is_singular( self::PT_LISTING ) ) {
			return $content;
		}
		$job_id = get_the_ID();
		if ( ! $job_id ) {
			return $content;
		}
		remove_filter( 'the_content', array( $this, 'single_job_content' ), 5 );
		ob_start();
		JC_Template::load( 'content-single-job_listing.php', array( 'job_id' => $job_id ) );
		$output = ob_get_clean();
		add_filter( 'the_content', array( $this, 'single_job_content' ), 5 );
		return $output;
	}

	/**
	 * On job_listing archive with block theme: replace the main Query block content with our job list.
	 * This makes the jobs page use the theme's template (same header/footer/layout as other pages).
	 *
	 * @param string   $block_content Block HTML.
	 * @param WP_Block $block        Block instance.
	 * @return string
	 */
	public function inject_jobs_archive_into_query_block( $block_content, $parsed_block ) {
		if ( ! is_post_type_archive( self::PT_LISTING ) ) {
			return $block_content;
		}
		$name = isset( $parsed_block['blockName'] ) ? $parsed_block['blockName'] : '';
		if ( $name !== 'core/query' ) {
			return $block_content;
		}
		$atts = self::get_jobs_archive_atts();
		return JC_Template::get_contents( 'job-listings.php', array( 'atts' => $atts ) );
	}

	/**
	 * Default atts for the jobs archive (and shortcode from Jobs page when set).
	 * Used by archive template and by inject_jobs_archive_into_query_block.
	 *
	 * @return array
	 */
	public static function get_jobs_archive_atts() {
		$atts = array(
			'per_page'        => JC_Settings::get( 'jc_per_page' ),
			'orderby'         => 'date',
			'order'           => 'desc',
			'show_filters'    => true,
			'show_pagination' => true,
			'show_job_type'   => 'true',
			'show_category'   => 'true',
			'filters_layout'  => 'default',
			'keywords'        => '',
			'location'        => '',
			'job_types'       => '',
			'categories'      => '',
			'post_status'     => 'publish',
		);
		$jobs_page_id = (int) JC_Settings::get( 'jc_jobs_page_id' );
		if ( $jobs_page_id ) {
			$jobs_page = get_post( $jobs_page_id );
			if ( $jobs_page && $jobs_page->post_content && has_shortcode( $jobs_page->post_content, 'jobs' ) ) {
				$pattern = get_shortcode_regex( array( 'jobs' ) );
				if ( preg_match( '/' . $pattern . '/s', $jobs_page->post_content, $matches ) && ! empty( $matches[3] ) ) {
					$parsed = shortcode_parse_atts( $matches[3] );
					if ( is_array( $parsed ) ) {
						$atts = array_merge( $atts, $parsed );
					}
				}
			}
		}
		return JC_Shortcodes::normalize_jobs_atts( $atts );
	}

	/**
	 * Suppress blog-style blocks on single job listing so theme layout (header/nav) is kept but post title, author, nav, "More posts" are hidden.
	 *
	 * @param string $block_content Block HTML.
	 * @param array  $block        Block data.
	 * @return string
	 */
	public function suppress_blog_blocks_on_single_job( $block_content, $block ) {
		if ( ! is_singular( self::PT_LISTING ) ) {
			return $block_content;
		}
		$name = isset( $block['blockName'] ) ? $block['blockName'] : '';
		$suppress = array(
			'core/post-title',
			'core/post-author',
			'core/post-author-name',
			'core/post-author-biography',
			'core/post-author-avatar',
			'core/post-date',
			'core/post-navigation-link',
			'core/query',
		);
		if ( in_array( $name, $suppress, true ) ) {
			return '';
		}
		// Hide paragraph blocks that are part of the "Written by X in" author line.
		if ( $name === 'core/paragraph' && $block_content !== '' ) {
			$text = trim( wp_strip_all_tags( $block_content ) );
			$author_phrases = array( 'Written by', 'in', 'Written by ', ' in' );
			foreach ( $author_phrases as $phrase ) {
				if ( $text === $phrase ) {
					return '';
				}
			}
		}
		return $block_content;
	}

	/**
	 * Add body class on single job listing for optional CSS targeting.
	 *
	 * @param string[] $classes Body classes.
	 * @param string[] $additional Additional classes.
	 * @return string[]
	 */
	public function single_job_body_class( $classes, $additional ) {
		if ( is_singular( self::PT_LISTING ) ) {
			$classes[] = 'single-job-connect-listing';
		}
		return $classes;
	}

	/**
	 * Register jobs RSS feed.
	 */
	public function add_feed() {
		add_feed( 'job_feed', array( $this, 'output_job_feed' ) );
	}

	/**
	 * Output RSS feed for jobs.
	 */
	public function output_job_feed() {
		header( 'Content-Type: ' . feed_content_type( 'rss2' ) . '; charset=' . get_option( 'blog_charset' ), true );
		$args = array(
			'post_type'      => self::PT_LISTING,
			'post_status'    => 'publish',
			'posts_per_page' => 25,
		);
		if ( JC_Settings::get( 'jc_hide_filled_positions' ) === '1' ) {
			$args['meta_query'] = array( array( 'key' => '_filled', 'value' => '1', 'compare' => '!=' ) );
		}
		$query = new WP_Query( $args );
		echo '<?xml version="1.0" encoding="' . esc_attr( get_option( 'blog_charset' ) ) . '"?>';
		?>
		<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">
			<channel>
				<title><?php bloginfo_rss( 'name' ); ?> - <?php esc_html_e( 'Jobs', 'job-connect' ); ?></title>
				<link><?php echo esc_url( home_url( '/' ) ); ?></link>
				<description><?php bloginfo_rss( 'description' ); ?></description>
				<lastBuildDate><?php echo esc_html( date( 'r' ) ); ?></lastBuildDate>
				<atom:link href="<?php echo esc_url( get_feed_link( 'job_feed' ) ); ?>" rel="self" type="application/rss+xml"/>
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();
					$job_id = get_the_ID();
					$app    = get_post_meta( $job_id, '_application', true );
					?>
					<item>
						<title><?php the_title_rss(); ?></title>
						<link><?php the_permalink_rss(); ?></link>
						<pubDate><?php echo esc_html( get_the_date( 'r' ) ); ?></pubDate>
						<description><![CDATA[<?php the_excerpt_rss(); ?>]]></description>
						<?php if ( $app ) : ?>
							<guid isPermaLink="false">job-<?php echo (int) $job_id; ?></guid>
						<?php endif; ?>
					</item>
				<?php }
				wp_reset_postdata();
				?>
			</channel>
		</rss>
		<?php
		exit;
	}

	/**
	 * Get permalink structure.
	 *
	 * @return array
	 */
	public static function get_permalink_structure() {
		$raw = get_option( self::PERMALINK_OPTION, '[]' );
		if ( is_string( $raw ) ) {
			$raw = json_decode( $raw, true );
		}
		$raw = is_array( $raw ) ? $raw : array();
		$permalinks = wp_parse_args( $raw, array(
			'job_base'      => '',
			'category_base' => '',
			'type_base'     => '',
			'jobs_archive'  => '',
		) );
		$permalinks['job_rewrite_slug']          = untrailingslashit( ! empty( $permalinks['job_base'] ) ? $permalinks['job_base'] : _x( 'job', 'Job permalink', 'job-connect' ) );
		$permalinks['category_rewrite_slug']     = untrailingslashit( ! empty( $permalinks['category_base'] ) ? $permalinks['category_base'] : _x( 'job-category', 'Job category slug', 'job-connect' ) );
		$permalinks['type_rewrite_slug']         = untrailingslashit( ! empty( $permalinks['type_base'] ) ? $permalinks['type_base'] : _x( 'job-type', 'Job type slug', 'job-connect' ) );
		$permalinks['jobs_archive_rewrite_slug'] = untrailingslashit( ! empty( $permalinks['jobs_archive'] ) ? $permalinks['jobs_archive'] : 'jobs' );
		return $permalinks;
	}

	/**
	 * Register the job_listing post type.
	 */
	public function register_post_type() {
		if ( post_type_exists( self::PT_LISTING ) ) {
			return;
		}

		$permalink = self::get_permalink_structure();
		$singular  = __( 'Job', 'job-connect' );
		$plural    = __( 'Jobs', 'job-connect' );

		$has_archive = apply_filters( 'job_connect_enable_job_archive_page', true ) ? $permalink['jobs_archive_rewrite_slug'] : false;

		$rewrite = array(
			'slug'       => $permalink['job_rewrite_slug'],
			'with_front' => false,
			'feeds'      => true,
			'pages'      => false,
		);

		register_post_type(
			self::PT_LISTING,
			apply_filters(
				'job_connect_register_post_type_job_listing',
				array(
					'labels'              => array(
						'name'                  => $plural,
						'singular_name'         => $singular,
						'menu_name'             => __( 'Job Connect', 'job-connect' ),
						'all_items'             => sprintf( __( 'All %s', 'job-connect' ), $plural ),
						'add_new'               => __( 'Add New', 'job-connect' ),
						'add_new_item'          => sprintf( __( 'Add %s', 'job-connect' ), $singular ),
						'edit_item'             => sprintf( __( 'Edit %s', 'job-connect' ), $singular ),
						'new_item'              => sprintf( __( 'New %s', 'job-connect' ), $singular ),
						'view_item'             => sprintf( __( 'View %s', 'job-connect' ), $singular ),
						'search_items'          => sprintf( __( 'Search %s', 'job-connect' ), $plural ),
						'not_found'             => sprintf( __( 'No %s found', 'job-connect' ), $plural ),
						'not_found_in_trash'    => sprintf( __( 'No %s found in trash', 'job-connect' ), $plural ),
						'featured_image'        => __( 'Company Logo', 'job-connect' ),
						'set_featured_image'    => __( 'Set company logo', 'job-connect' ),
						'remove_featured_image' => __( 'Remove company logo', 'job-connect' ),
						'use_featured_image'    => __( 'Use as company logo', 'job-connect' ),
					),
					'description'         => sprintf( __( 'Job listings for your site.', 'job-connect' ), $plural ),
					'public'                => true,
					'show_ui'               => true,
					'capability_type'       => self::PT_LISTING,
					'map_meta_cap'          => true,
					'publicly_queryable'    => true,
					'exclude_from_search'   => false,
					'hierarchical'          => false,
					'rewrite'               => $rewrite,
					'query_var'             => true,
					'supports'              => array( 'title', 'editor', 'custom-fields', 'thumbnail', 'author' ),
					'has_archive'           => $has_archive,
					'show_in_nav_menus'     => false,
					'delete_with_user'      => true,
					'show_in_rest'          => true,
					'rest_base'             => 'job-listings',
					'menu_position'         => 30,
					'show_in_menu'          => 'job-connect',
				)
			)
		);

		register_post_status(
			'expired',
			array(
				'label'                     => _x( 'Expired', 'post status', 'job-connect' ),
				'public'                    => true,
				'protected'                 => true,
				'exclude_from_search'       => true,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				'label_count'               => _n_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', 'job-connect' ),
			)
		);
	}

	/**
	 * Check for expired jobs (cron callback).
	 */
	public function check_for_expired_jobs() {
		JC_Cron::check_expired_jobs();
	}
}
