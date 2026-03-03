<?php
/**
 * Extends job listings keyword search to include company name (post meta).
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Job_Listings_Search class.
 */
class JC_Job_Listings_Search {

	/**
	 * Add JOIN so we can search by company name meta.
	 *
	 * @param string   $join  The JOIN clause.
	 * @param WP_Query $query The query.
	 * @return string
	 */
	public static function join_company( $join, $query ) {
		if ( $query->get( 'post_type' ) !== JC_Post_Types::PT_LISTING ) {
			return $join;
		}
		if ( $query->get( 'jc_search_keywords' ) === null || $query->get( 'jc_search_keywords' ) === '' ) {
			return $join;
		}
		global $wpdb;
		$join .= " LEFT JOIN {$wpdb->postmeta} AS jc_company_meta ON jc_company_meta.post_id = {$wpdb->posts}.ID AND jc_company_meta.meta_key = '_company_name' ";
		return $join;
	}

	/**
	 * Extend search WHERE to include company name (meta value).
	 *
	 * @param string   $search The search SQL.
	 * @param WP_Query $query  The query.
	 * @return string
	 */
	public static function search_company( $search, $query ) {
		if ( $query->get( 'post_type' ) !== JC_Post_Types::PT_LISTING ) {
			return $search;
		}
		$keywords = $query->get( 'jc_search_keywords' );
		if ( $keywords === null || $keywords === '' ) {
			return $search;
		}
		global $wpdb;
		$like = '%' . $wpdb->esc_like( $keywords ) . '%';
		$search .= $wpdb->prepare( ' OR (jc_company_meta.meta_value LIKE %s)', $like );
		return $search;
	}
}
