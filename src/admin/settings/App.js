/**
 * Job Connect Settings – main app with tabs.
 *
 * @package Job_Connect
 */

import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import {
	Card,
	CardBody,
	CardHeader,
	Button,
	Spinner,
	Notice,
	TabPanel,
} from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import GeneralSection from './sections/GeneralSection';
import JobListingsSection from './sections/JobListingsSection';
import JobSubmissionSection from './sections/JobSubmissionSection';
import RecaptchaSection from './sections/RecaptchaSection';
import PagesSection from './sections/PagesSection';
import JobVisibilitySection from './sections/JobVisibilitySection';
import EmailNotificationsSection from './sections/EmailNotificationsSection';

const TABS = [
	{ name: 'general', title: __( 'General', 'job-connect' ) },
	{ name: 'job_listings', title: __( 'Job Listings', 'job-connect' ) },
	{ name: 'job_submission', title: __( 'Job Submission', 'job-connect' ) },
	{ name: 'recaptcha', title: __( 'ReCAPTCHA', 'job-connect' ) },
	{ name: 'pages', title: __( 'Pages', 'job-connect' ) },
	{ name: 'job_visibility', title: __( 'Job Visibility', 'job-connect' ) },
	{ name: 'email_notifications', title: __( 'Email Notifications', 'job-connect' ) },
];

export default function App() {
	const [ settings, setSettings ] = useState( window.jobConnectAdmin?.settings || {} );
	const [ saving, setSaving ] = useState( false );
	const [ notice, setNotice ] = useState( null );

	const updateSetting = ( key, value ) => {
		setSettings( ( prev ) => ( { ...prev, [ key ]: value } ) );
	};

	const saveSettings = async () => {
		setSaving( true );
		setNotice( null );
		try {
			const response = await apiFetch( {
				path: '/jc/v1/settings',
				method: 'POST',
				data: { settings },
				headers: {
					'X-WP-Nonce': window.jobConnectAdmin?.nonce || '',
					'Content-Type': 'application/json',
				},
			} );
			setSettings( response );
			setNotice( { type: 'success', message: __( 'Settings saved.', 'job-connect' ) } );
		} catch ( err ) {
			setNotice( {
				type: 'error',
				message: err.message || __( 'Failed to save settings.', 'job-connect' ),
			} );
		} finally {
			setSaving( false );
		}
	};

	const renderTab = ( tabName ) => {
		const common = { settings, updateSetting };
		switch ( tabName ) {
			case 'general':
				return <GeneralSection { ...common } />;
			case 'job_listings':
				return <JobListingsSection { ...common } />;
			case 'job_submission':
				return <JobSubmissionSection { ...common } />;
			case 'recaptcha':
				return <RecaptchaSection { ...common } />;
			case 'pages':
				return <PagesSection { ...common } />;
			case 'job_visibility':
				return <JobVisibilitySection { ...common } />;
			case 'email_notifications':
				return <EmailNotificationsSection { ...common } />;
			default:
				return null;
		}
	};

	const setupWizardDone = settings.jc_setup_wizard_done === '1';
	const [ runningWizard, setRunningWizard ] = useState( false );

	const runSetupWizard = async () => {
		setRunningWizard( true );
		setNotice( null );
		try {
			const response = await apiFetch( {
				path: '/jc/v1/setup-wizard',
				method: 'POST',
				headers: {
					'X-WP-Nonce': window.jobConnectAdmin?.nonce || '',
					'Content-Type': 'application/json',
				},
			} );
			setSettings( response );
			setNotice( { type: 'success', message: __( 'Setup complete. Default pages have been created and assigned.', 'job-connect' ) } );
		} catch ( err ) {
			setNotice( {
				type: 'error',
				message: err.message || __( 'Setup wizard failed.', 'job-connect' ),
			} );
		} finally {
			setRunningWizard( false );
		}
	};

	return (
		<div className="job-connect-settings-app">
			<h1 className="job-connect-settings-title">{ __( 'Job Connect Settings', 'job-connect' ) }</h1>
			{ ! setupWizardDone && (
				<Card className="job-connect-setup-wizard-card" style={ { marginBottom: '1.5em' } }>
					<CardHeader>
						<strong>{ __( 'First time setup', 'job-connect' ) }</strong>
					</CardHeader>
					<CardBody>
						<p>{ __( 'Create default pages (Jobs, Submit a Job, Job Dashboard) and assign them in Pages settings.', 'job-connect' ) }</p>
						<Button
							isPrimary
							onClick={ runSetupWizard }
							disabled={ runningWizard }
						>
							{ runningWizard ? <Spinner /> : __( 'Create default pages', 'job-connect' ) }
						</Button>
					</CardBody>
				</Card>
			) }
			{ notice && (
				<Notice
					status={ notice.type }
					isDismissible
					onRemove={ () => setNotice( null ) }
				>
					{ notice.message }
				</Notice>
			) }
			<TabPanel
				className="job-connect-tab-panel"
				tabs={ TABS }
				initialTabName="general"
			>
				{ ( tab ) => (
					<div className="job-connect-tab-content">
						{ renderTab( tab.name ) }
					</div>
				) }
			</TabPanel>
			<div className="job-connect-save-actions">
				<Button
					isPrimary
					onClick={ saveSettings }
					disabled={ saving }
				>
					{ saving ? <Spinner /> : __( 'Save changes', 'job-connect' ) }
				</Button>
			</div>
		</div>
	);
}
