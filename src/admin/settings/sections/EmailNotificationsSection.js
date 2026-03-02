/**
 * Email notifications section.
 *
 * @package Job_Connect
 */

import { __ } from '@wordpress/i18n';
import { Card, CardBody, CardHeader, TextControl, CheckboxControl } from '@wordpress/components';

export default function EmailNotificationsSection( { settings, updateSetting } ) {
	return (
		<Card>
			<CardHeader>
				<h2 className="job-connect-section-title">{ __( 'Email Notifications', 'job-connect' ) }</h2>
			</CardHeader>
			<CardBody>
				<CheckboxControl
					label={ __( 'Email admin when a new job is submitted', 'job-connect' ) }
					checked={ settings.jc_email_admin_new_job === '1' }
					onChange={ ( v ) => updateSetting( 'jc_email_admin_new_job', v ? '1' : '0' ) }
				/>
				<CheckboxControl
					label={ __( 'Email admin when a job is updated', 'job-connect' ) }
					checked={ settings.jc_email_admin_updated_job === '1' }
					onChange={ ( v ) => updateSetting( 'jc_email_admin_updated_job', v ? '1' : '0' ) }
				/>
				<CheckboxControl
					label={ __( 'Email admin about expiring jobs', 'job-connect' ) }
					checked={ settings.jc_email_admin_expiring_job === '1' }
					onChange={ ( v ) => updateSetting( 'jc_email_admin_expiring_job', v ? '1' : '0' ) }
				/>
				<TextControl
					label={ __( 'Days before expiry to email admin', 'job-connect' ) }
					type="number"
					value={ settings.jc_admin_expiring_job_days || '7' }
					onChange={ ( v ) => updateSetting( 'jc_admin_expiring_job_days', v || '7' ) }
				/>
				<CheckboxControl
					label={ __( 'Email employer when their job is expiring', 'job-connect' ) }
					checked={ settings.jc_email_employer_expiring_job === '1' }
					onChange={ ( v ) => updateSetting( 'jc_email_employer_expiring_job', v ? '1' : '0' ) }
				/>
				<TextControl
					label={ __( 'Days before expiry to email employer', 'job-connect' ) }
					type="number"
					value={ settings.jc_employer_expiring_job_days || '7' }
					onChange={ ( v ) => updateSetting( 'jc_employer_expiring_job_days', v || '7' ) }
				/>
			</CardBody>
		</Card>
	);
}
