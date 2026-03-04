/**
 * Pages settings section.
 *
 * @package Job_Connect
 */

import { __ } from '@wordpress/i18n';
import { Card, CardBody, CardHeader, SelectControl } from '@wordpress/components';

const rawPages = window.jobConnectAdmin?.pages || [];
const pageOptions = rawPages.length ? rawPages : [ { value: '', label: __( '— Select —', 'job-connect' ) } ];

export default function PagesSection( { settings, updateSetting } ) {
	return (
		<Card>
			<CardHeader>
				<h2 className="job-connect-section-title">{ __( 'Pages', 'job-connect' ) }</h2>
			</CardHeader>
			<CardBody>
				<SelectControl
					label={ __( 'Submit job form page', 'job-connect' ) }
					help={ __( 'Page containing [submit_job_form] shortcode.', 'job-connect' ) }
					value={ String( settings.jc_submit_job_form_page_id || '' ) }
					options={ pageOptions }
					onChange={ ( v ) => updateSetting( 'jc_submit_job_form_page_id', v ? String( v ) : '' ) }
				/>
				<SelectControl
					label={ __( 'Job dashboard page', 'job-connect' ) }
					help={ __( 'Page containing [job_dashboard] shortcode.', 'job-connect' ) }
					value={ String( settings.jc_job_dashboard_page_id || '' ) }
					options={ pageOptions }
					onChange={ ( v ) => updateSetting( 'jc_job_dashboard_page_id', v ? String( v ) : '' ) }
				/>
				<SelectControl
					label={ __( 'Login page', 'job-connect' ) }
					help={ __( 'Page containing [job_connect_login] shortcode.', 'job-connect' ) }
					value={ String( settings.jc_login_page_id || '' ) }
					options={ pageOptions }
					onChange={ ( v ) => updateSetting( 'jc_login_page_id', v ? String( v ) : '' ) }
				/>
				<SelectControl
					label={ __( 'Register page', 'job-connect' ) }
					help={ __( 'Page containing [job_connect_register] shortcode. Required for "Create an account" link when registration is enabled.', 'job-connect' ) }
					value={ String( settings.jc_register_page_id || '' ) }
					options={ pageOptions }
					onChange={ ( v ) => updateSetting( 'jc_register_page_id', v ? String( v ) : '' ) }
				/>
				<SelectControl
					label={ __( 'Job listings page', 'job-connect' ) }
					help={ __( 'Page containing [jobs] shortcode.', 'job-connect' ) }
					value={ String( settings.jc_jobs_page_id || '' ) }
					options={ pageOptions }
					onChange={ ( v ) => updateSetting( 'jc_jobs_page_id', v ? String( v ) : '' ) }
				/>
				<SelectControl
					label={ __( 'Terms and conditions page', 'job-connect' ) }
					help={ __( 'Linked when T&C checkbox is enabled.', 'job-connect' ) }
					value={ String( settings.jc_terms_and_conditions_page_id || '' ) }
					options={ pageOptions }
					onChange={ ( v ) => updateSetting( 'jc_terms_and_conditions_page_id', v ? String( v ) : '' ) }
				/>
			</CardBody>
		</Card>
	);
}
