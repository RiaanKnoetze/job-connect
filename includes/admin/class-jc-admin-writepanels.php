<?php
/**
 * Admin meta boxes for job listing data.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Admin_Writepanels class.
 */
class JC_Admin_Writepanels {

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post_' . JC_Post_Types::PT_LISTING, array( $this, 'save_job_listing' ), 10, 3 );
		add_filter( 'manage_' . JC_Post_Types::PT_LISTING . '_posts_columns', array( $this, 'columns' ) );
		add_action( 'manage_' . JC_Post_Types::PT_LISTING . '_posts_custom_column', array( $this, 'column_content' ), 10, 2 );
	}

	/**
	 * Add meta boxes.
	 */
	public function add_meta_boxes() {
		add_meta_box(
			'job_connect_listing_data',
			__( 'Job data', 'job-connect' ),
			array( $this, 'listing_data_meta_box' ),
			JC_Post_Types::PT_LISTING,
			'normal',
			'high'
		);
	}

	/**
	 * Output listing data meta box.
	 *
	 * @param WP_Post $post Post object.
	 */
	public function listing_data_meta_box( $post ) {
		wp_nonce_field( 'job_connect_save_listing_data', 'job_connect_listing_data_nonce' );
		$company     = get_post_meta( $post->ID, '_company_name', true );
		$location    = get_post_meta( $post->ID, '_job_location', true );
		$application = get_post_meta( $post->ID, '_application', true );
		$job_expires = get_post_meta( $post->ID, '_job_expires', true );
		$job_salary  = get_post_meta( $post->ID, '_job_salary', true );
		$featured    = get_post_meta( $post->ID, '_featured', true );
		$filled      = get_post_meta( $post->ID, '_filled', true );
		$enable_salary = JC_Settings::get( 'jc_enable_salary' ) === '1';
		?>
		<p>
			<label for="jc_company_name"><?php esc_html_e( 'Company name', 'job-connect' ); ?></label><br />
			<input type="text" id="jc_company_name" name="jc_company_name" value="<?php echo esc_attr( $company ); ?>" class="widefat" />
		</p>
		<p>
			<label for="jc_job_location"><?php esc_html_e( 'Location', 'job-connect' ); ?></label><br />
			<input type="text" id="jc_job_location" name="jc_job_location" value="<?php echo esc_attr( $location ); ?>" class="widefat" />
		</p>
		<?php if ( $enable_salary ) : ?>
		<p>
			<label for="jc_job_salary"><?php esc_html_e( 'Salary', 'job-connect' ); ?></label><br />
			<input type="text" id="jc_job_salary" name="jc_job_salary" value="<?php echo esc_attr( $job_salary ); ?>" class="widefat" placeholder="<?php esc_attr_e( 'e.g. 50,000 - 60,000', 'job-connect' ); ?>" />
		</p>
		<?php endif; ?>
		<p>
			<label for="jc_application"><?php esc_html_e( 'Application email or URL', 'job-connect' ); ?></label><br />
			<input type="text" id="jc_application" name="jc_application" value="<?php echo esc_attr( $application ); ?>" class="widefat" />
		</p>
		<p>
			<label for="jc_job_expires"><?php esc_html_e( 'Expiry date', 'job-connect' ); ?></label><br />
			<input type="date" id="jc_job_expires" name="jc_job_expires" value="<?php echo esc_attr( $job_expires ); ?>" class="widefat" />
		</p>
		<p>
			<label><input type="checkbox" name="jc_featured" value="1" <?php checked( $featured, '1' ); ?> /> <?php esc_html_e( 'Featured', 'job-connect' ); ?></label>
		</p>
		<p>
			<label><input type="checkbox" name="jc_filled" value="1" <?php checked( $filled, '1' ); ?> /> <?php esc_html_e( 'Position filled', 'job-connect' ); ?></label>
		</p>
		<?php
	}

	/**
	 * Save job listing meta.
	 *
	 * @param int      $post_id Post ID.
	 * @param WP_Post  $post    Post object.
	 * @param bool     $update  Whether this is an update.
	 */
	public function save_job_listing( $post_id, $post, $update ) {
		if ( ! isset( $_POST['job_connect_listing_data_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['job_connect_listing_data_nonce'] ) ), 'job_connect_save_listing_data' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$fields = array(
			'jc_company_name'  => '_company_name',
			'jc_job_location'  => '_job_location',
			'jc_application'   => '_application',
			'jc_job_expires'   => '_job_expires',
		);
		if ( JC_Settings::get( 'jc_enable_salary' ) === '1' ) {
			$fields['jc_job_salary'] = '_job_salary';
		}
		foreach ( $fields as $post_key => $meta_key ) {
			if ( isset( $_POST[ $post_key ] ) ) {
				update_post_meta( $post_id, $meta_key, sanitize_text_field( wp_unslash( $_POST[ $post_key ] ) ) );
			}
		}
		if ( isset( $_POST['jc_featured'] ) && $_POST['jc_featured'] === '1' ) {
			update_post_meta( $post_id, '_featured', '1' );
		} else {
			update_post_meta( $post_id, '_featured', '0' );
		}
		if ( isset( $_POST['jc_filled'] ) && $_POST['jc_filled'] === '1' ) {
			update_post_meta( $post_id, '_filled', '1' );
		} else {
			update_post_meta( $post_id, '_filled', '0' );
		}
	}

	/**
	 * Add list table columns.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public function columns( $columns ) {
		$new = array();
		foreach ( $columns as $key => $label ) {
			$new[ $key ] = $label;
			if ( $key === 'title' ) {
				$new['job_location']   = __( 'Location', 'job-connect' );
				$new['job_expires']    = __( 'Expires', 'job-connect' );
				$new['job_featured']   = __( 'Featured', 'job-connect' );
				$new['job_filled']     = __( 'Filled', 'job-connect' );
			}
		}
		return $new;
	}

	/**
	 * Output column content.
	 *
	 * @param string $column  Column name.
	 * @param int    $post_id Post ID.
	 */
	public function column_content( $column, $post_id ) {
		switch ( $column ) {
			case 'job_location':
				echo esc_html( get_post_meta( $post_id, '_job_location', true ) );
				break;
			case 'job_expires':
				echo esc_html( get_post_meta( $post_id, '_job_expires', true ) );
				break;
			case 'job_featured':
				echo get_post_meta( $post_id, '_featured', true ) === '1' ? '&#9733;' : '—';
				break;
			case 'job_filled':
				echo get_post_meta( $post_id, '_filled', true ) === '1' ? __( 'Yes', 'job-connect' ) : '—';
				break;
		}
	}
}
