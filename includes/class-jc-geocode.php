<?php
/**
 * Geocoding for job locations (Google Maps API).
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Geocode class.
 */
class JC_Geocode {

	/**
	 * Get location data for an address (Phase 7).
	 *
	 * @param string $address Address string.
	 * @return array|null Lat/lng and formatted address or null.
	 */
	public static function get_location_data( $address ) {
		$key = JC_Settings::get( 'jc_google_maps_api_key' );
		if ( empty( $key ) || empty( $address ) ) {
			return null;
		}
		// Phase 7: implement API call.
		return null;
	}
}
