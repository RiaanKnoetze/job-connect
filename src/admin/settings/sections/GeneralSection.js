/**
 * General settings section.
 *
 * @package Job_Connect
 */

import { __ } from '@wordpress/i18n';
import { Card, CardBody, SelectControl, TextControl, CheckboxControl } from '@wordpress/components';

export default function GeneralSection( { settings, updateSetting } ) {
	return (
		<div className="jc-settings-section">
			<div className="jc-settings-section__description">
				<h2>{ __( 'General', 'job-connect' ) }</h2>
				<p>{ __( 'Date format, Google Maps API key, and data cleanup options.', 'job-connect' ) }</p>
			</div>
			<Card className="jc-settings-section__card">
				<CardBody>
					<SelectControl
						label={ __( 'Date format', 'job-connect' ) }
						value={ settings.jc_date_format || 'relative' }
						options={ [
							{ label: __( 'Relative (e.g. 1 day ago)', 'job-connect' ), value: 'relative' },
							{ label: __( 'Default date format', 'job-connect' ), value: 'default' },
						] }
						onChange={ ( v ) => updateSetting( 'jc_date_format', v ) }
					/>
					<TextControl
						label={ __( 'Google Maps API key', 'job-connect' ) }
						help={ __( 'Required for location geocoding. Get a key from Google Maps API.', 'job-connect' ) }
						value={ settings.jc_google_maps_api_key || '' }
						onChange={ ( v ) => updateSetting( 'jc_google_maps_api_key', v || '' ) }
					/>
					<CheckboxControl
						label={ __( 'Delete data when plugin is uninstalled', 'job-connect' ) }
						checked={ settings.jc_delete_data_on_uninstall === '1' }
						onChange={ ( v ) => updateSetting( 'jc_delete_data_on_uninstall', v ? '1' : '0' ) }
					/>
					<CheckboxControl
						label={ __( 'Bypass trash for job listings on uninstall', 'job-connect' ) }
						checked={ settings.jc_bypass_trash_on_uninstall === '1' }
						onChange={ ( v ) => updateSetting( 'jc_bypass_trash_on_uninstall', v ? '1' : '0' ) }
					/>
				</CardBody>
			</Card>
		</div>
	);
}
