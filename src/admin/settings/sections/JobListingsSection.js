/**
 * Job listings settings section.
 *
 * @package Job_Connect
 */

import { __ } from '@wordpress/i18n';
import { Card, CardBody, CardHeader, SelectControl, TextControl, CheckboxControl } from '@wordpress/components';

export default function JobListingsSection( { settings, updateSetting } ) {
	return (
		<Card>
			<CardHeader>
				<h2 className="job-connect-section-title">{ __( 'Job Listings', 'job-connect' ) }</h2>
			</CardHeader>
			<CardBody>
				<TextControl
					label={ __( 'Listings per page', 'job-connect' ) }
					type="number"
					value={ settings.jc_per_page || '10' }
					onChange={ ( v ) => updateSetting( 'jc_per_page', v || '10' ) }
				/>
				<SelectControl
					label={ __( 'Pagination type', 'job-connect' ) }
					value={ settings.jc_pagination_type || 'load_more' }
					options={ [
						{ label: __( 'Load more button', 'job-connect' ), value: 'load_more' },
						{ label: __( 'Page numbers', 'job-connect' ), value: 'pagination' },
					] }
					onChange={ ( v ) => updateSetting( 'jc_pagination_type', v ) }
				/>
				<CheckboxControl
					label={ __( 'Hide filled listings', 'job-connect' ) }
					checked={ settings.jc_hide_filled_positions === '1' }
					onChange={ ( v ) => updateSetting( 'jc_hide_filled_positions', v ? '1' : '0' ) }
				/>
				<CheckboxControl
					label={ __( 'Hide expired listings', 'job-connect' ) }
					checked={ settings.jc_hide_expired === '1' }
					onChange={ ( v ) => updateSetting( 'jc_hide_expired', v ? '1' : '0' ) }
				/>
				<CheckboxControl
					label={ __( 'Hide content in expired single listings', 'job-connect' ) }
					checked={ settings.jc_hide_expired_content === '1' }
					onChange={ ( v ) => updateSetting( 'jc_hide_expired_content', v ? '1' : '0' ) }
				/>
				<CheckboxControl
					label={ __( 'Enable categories', 'job-connect' ) }
					checked={ settings.jc_enable_categories === '1' }
					onChange={ ( v ) => updateSetting( 'jc_enable_categories', v ? '1' : '0' ) }
				/>
				<CheckboxControl
					label={ __( 'Enable job types', 'job-connect' ) }
					checked={ settings.jc_enable_types !== '0' }
					onChange={ ( v ) => updateSetting( 'jc_enable_types', v ? '1' : '0' ) }
				/>
				<CheckboxControl
					label={ __( 'Enable remote position field', 'job-connect' ) }
					checked={ settings.jc_enable_remote_position === '1' }
					onChange={ ( v ) => updateSetting( 'jc_enable_remote_position', v ? '1' : '0' ) }
				/>
				<CheckboxControl
					label={ __( 'Enable salary field', 'job-connect' ) }
					checked={ settings.jc_enable_salary === '1' }
					onChange={ ( v ) => updateSetting( 'jc_enable_salary', v ? '1' : '0' ) }
				/>
				<CheckboxControl
					label={ __( 'Display full location address when geocoded', 'job-connect' ) }
					checked={ settings.jc_display_location_address === '1' }
					onChange={ ( v ) => updateSetting( 'jc_display_location_address', v ? '1' : '0' ) }
				/>
			</CardBody>
		</Card>
	);
}
