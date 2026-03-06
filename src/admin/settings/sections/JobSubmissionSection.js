/**
 * Job submission settings section.
 *
 * @package Job_Connect
 */

import { __ } from '@wordpress/i18n';
import { Card, CardBody, SelectControl, TextControl, CheckboxControl } from '@wordpress/components';

const pages = window.jobConnectAdmin?.pages || [];
const roles = window.jobConnectAdmin?.roles || {};
const roleOptions = Object.entries( roles ).map( ( [ value, label ] ) => ( { value, label } ) );

export default function JobSubmissionSection( { settings, updateSetting } ) {
	return (
		<div className="jc-settings-section">
			<div className="jc-settings-section__description">
				<h2>{ __( 'Job Submission', 'job-connect' ) }</h2>
				<p>{ __( 'Settings for how employers can submit and manage job listings.', 'job-connect' ) }</p>
			</div>
			<Card className="jc-settings-section__card">
				<CardBody>
					<CheckboxControl
						label={ __( 'Require an account to submit listings', 'job-connect' ) }
						checked={ settings.jc_user_requires_account === '1' }
						onChange={ ( v ) => updateSetting( 'jc_user_requires_account', v ? '1' : '0' ) }
					/>
					<CheckboxControl
						label={ __( 'Enable account creation during submission', 'job-connect' ) }
						checked={ settings.jc_enable_registration === '1' }
						onChange={ ( v ) => updateSetting( 'jc_enable_registration', v ? '1' : '0' ) }
					/>
					<SelectControl
						label={ __( 'Account role for new users', 'job-connect' ) }
						value={ settings.jc_registration_role || 'employer' }
						options={ roleOptions.length ? roleOptions : [ { value: 'employer', label: 'Employer' } ] }
						onChange={ ( v ) => updateSetting( 'jc_registration_role', v ) }
					/>
					<CheckboxControl
						label={ __( 'Generate username from email', 'job-connect' ) }
						checked={ settings.jc_generate_username_from_email === '1' }
						onChange={ ( v ) => updateSetting( 'jc_generate_username_from_email', v ? '1' : '0' ) }
					/>
					<CheckboxControl
						label={ __( 'Email new users a link to set password', 'job-connect' ) }
						checked={ settings.jc_use_standard_password_setup_email === '1' }
						onChange={ ( v ) => updateSetting( 'jc_use_standard_password_setup_email', v ? '1' : '0' ) }
					/>
					<CheckboxControl
						label={ __( 'Require admin approval for new listings', 'job-connect' ) }
						checked={ settings.jc_submission_requires_approval === '1' }
						onChange={ ( v ) => updateSetting( 'jc_submission_requires_approval', v ? '1' : '0' ) }
					/>
					<CheckboxControl
						label={ __( 'Allow editing of pending listings', 'job-connect' ) }
						checked={ settings.jc_user_can_edit_pending_submissions === '1' }
						onChange={ ( v ) => updateSetting( 'jc_user_can_edit_pending_submissions', v ? '1' : '0' ) }
					/>
					<SelectControl
						label={ __( 'Allow editing of published listings', 'job-connect' ) }
						value={ settings.jc_user_edit_published_submissions || 'yes' }
						options={ [
							{ label: __( 'Users cannot edit', 'job-connect' ), value: 'no' },
							{ label: __( 'Users can edit without approval', 'job-connect' ), value: 'yes' },
							{ label: __( 'Users can edit, edits require approval', 'job-connect' ), value: 'yes_moderated' },
						] }
						onChange={ ( v ) => updateSetting( 'jc_user_edit_published_submissions', v ) }
					/>
					<TextControl
						label={ __( 'Listing duration (days)', 'job-connect' ) }
						type="number"
						help={ __( 'Leave blank for no expiry.', 'job-connect' ) }
						value={ settings.jc_submission_duration || '30' }
						onChange={ ( v ) => updateSetting( 'jc_submission_duration', v || '' ) }
					/>
					<TextControl
						label={ __( 'Listing limit per user', 'job-connect' ) }
						type="number"
						help={ __( 'Leave blank for unlimited.', 'job-connect' ) }
						value={ settings.jc_submission_limit || '' }
						onChange={ ( v ) => updateSetting( 'jc_submission_limit', v || '' ) }
					/>
					<SelectControl
						label={ __( 'Application method', 'job-connect' ) }
						value={ settings.jc_allowed_application_method || '' }
						options={ [
							{ label: __( 'Email or URL', 'job-connect' ), value: '' },
							{ label: __( 'Email only', 'job-connect' ), value: 'email' },
							{ label: __( 'URL only', 'job-connect' ), value: 'url' },
						] }
						onChange={ ( v ) => updateSetting( 'jc_allowed_application_method', v || '' ) }
					/>
					<CheckboxControl
						label={ __( 'Require Terms and Conditions checkbox', 'job-connect' ) }
						checked={ settings.jc_show_agreement_job_submission === '1' }
						onChange={ ( v ) => updateSetting( 'jc_show_agreement_job_submission', v ? '1' : '0' ) }
					/>
				</CardBody>
			</Card>
		</div>
	);
}
