/**
 * Job visibility (capabilities) section.
 *
 * @package Job_Connect
 */

import { __ } from '@wordpress/i18n';
import { Card, CardBody, CheckboxControl } from '@wordpress/components';

const allRoles = window.jobConnectAdmin?.capabilityRoles || {};

function CapabilityCheckboxes( { value, onChange } ) {
	const selected = Array.isArray( value ) ? value : [];

	const toggle = ( key, checked ) => {
		if ( checked ) {
			onChange( [ ...selected, key ] );
		} else {
			onChange( selected.filter( ( v ) => v !== key ) );
		}
	};

	return (
		<div className="jc-capability-checkboxes">
			{ Object.entries( allRoles ).map( ( [ key, label ] ) => (
				<CheckboxControl
					key={ key }
					label={ label }
					checked={ selected.includes( key ) }
					onChange={ ( checked ) => toggle( key, checked ) }
				/>
			) ) }
		</div>
	);
}

export default function JobVisibilitySection( { settings, updateSetting } ) {
	return (
		<div className="jc-settings-section">
			<div className="jc-settings-section__description">
				<h2>{ __( 'Job Visibility', 'job-connect' ) }</h2>
				<p>{ __( 'Control who can browse and view job listings on the frontend.', 'job-connect' ) }</p>
			</div>
			<Card className="jc-settings-section__card">
				<CardBody>
					<div className="jc-capability-field">
						<label className="jc-capability-field__label">{ __( 'Browse Job Capability', 'job-connect' ) }</label>
						<CapabilityCheckboxes
							value={ settings.jc_browse_job_listings_capability }
							onChange={ ( v ) => updateSetting( 'jc_browse_job_listings_capability', v ) }
						/>
						<p className="description">{ __( 'Enter which roles or capabilities allow visitors to browse job listings. If no value is selected, everyone (including logged out guests) will be able to browse job listings.', 'job-connect' ) }</p>
					</div>
					<div className="jc-capability-field">
						<label className="jc-capability-field__label">{ __( 'View Job Capability', 'job-connect' ) }</label>
						<CapabilityCheckboxes
							value={ settings.jc_view_job_listing_capability }
							onChange={ ( v ) => updateSetting( 'jc_view_job_listing_capability', v ) }
						/>
						<p className="description">{ __( 'Enter which roles or capabilities allow visitors to view a single job listing. If no value is selected, everyone (including logged out guests) will be able to view job listings.', 'job-connect' ) }</p>
					</div>
				</CardBody>
			</Card>
		</div>
	);
}
