/**
 * ReCAPTCHA settings section.
 *
 * @package Job_Connect
 */

import { __ } from '@wordpress/i18n';
import { Card, CardBody, TextControl, CheckboxControl } from '@wordpress/components';

export default function RecaptchaSection( { settings, updateSetting } ) {
	return (
		<div className="jc-settings-section">
			<div className="jc-settings-section__description">
				<h2>{ __( 'ReCAPTCHA', 'job-connect' ) }</h2>
				<p>{ __( 'Protect your forms from spam with Google reCAPTCHA.', 'job-connect' ) }</p>
			</div>
			<Card className="jc-settings-section__card">
				<CardBody>
					<TextControl
						label={ __( 'Field label', 'job-connect' ) }
						value={ settings.jc_recaptcha_label || __( 'Are you human?', 'job-connect' ) }
						onChange={ ( v ) => updateSetting( 'jc_recaptcha_label', v || '' ) }
					/>
					<TextControl
						label={ __( 'Site key', 'job-connect' ) }
						help={ __( 'From Google reCAPTCHA admin.', 'job-connect' ) }
						value={ settings.jc_recaptcha_site_key || '' }
						onChange={ ( v ) => updateSetting( 'jc_recaptcha_site_key', v || '' ) }
					/>
					<TextControl
						label={ __( 'Secret key', 'job-connect' ) }
						help={ __( 'From Google reCAPTCHA admin.', 'job-connect' ) }
						value={ settings.jc_recaptcha_secret_key || '' }
						onChange={ ( v ) => updateSetting( 'jc_recaptcha_secret_key', v || '' ) }
					/>
					<CheckboxControl
						label={ __( 'Show CAPTCHA on job submission form', 'job-connect' ) }
						checked={ settings.jc_enable_recaptcha_job_submission === '1' }
						onChange={ ( v ) => updateSetting( 'jc_enable_recaptcha_job_submission', v ? '1' : '0' ) }
					/>
				</CardBody>
			</Card>
		</div>
	);
}
