/**
 * Pages settings section.
 *
 * @package Job_Connect
 */

import { __ } from '@wordpress/i18n';
import { Card, CardBody, SelectControl, Button, Spinner } from '@wordpress/components';

export default function PagesSection( { settings, updateSetting, pageOptions: pageOptionsProp, onRunSetupWizard, runningWizard } ) {
	const rawPages = pageOptionsProp || window.jobConnectAdmin?.pages || [];
	const pageOptions = rawPages.length ? rawPages : [ { value: '', label: __( '— Select —', 'job-connect' ) } ];
	return (
		<div className="jc-settings-section">
			<div className="jc-settings-section__description">
				<h2>{ __( 'Pages', 'job-connect' ) }</h2>
				<p>{ __( 'Assign WordPress pages to Job Connect features and shortcodes.', 'job-connect' ) }</p>
			</div>
			<Card className="jc-settings-section__card">
				<CardBody>
					{ onRunSetupWizard && (
						<p style={ { marginBottom: '1em' } }>
							<Button
								isSecondary
								onClick={ onRunSetupWizard }
								disabled={ runningWizard }
							>
								{ runningWizard ? <Spinner /> : __( 'Create default pages (setup wizard)', 'job-connect' ) }
							</Button>
							{ ' ' }
							<span className="description">
								{ __( 'Creates Jobs, Submit a Job, Job Dashboard, Log in, and Create an account pages with shortcodes and assigns them here.', 'job-connect' ) }
							</span>
						</p>
					) }
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
		</div>
	);
}
