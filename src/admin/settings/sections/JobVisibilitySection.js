/**
 * Job visibility (capabilities) section.
 *
 * @package Job_Connect
 */

import { __ } from '@wordpress/i18n';
import { Card, CardBody, CardHeader } from '@wordpress/components';

export default function JobVisibilitySection( { settings } ) {
	return (
		<Card>
			<CardHeader>
				<h2 className="job-connect-section-title">{ __( 'Job Visibility', 'job-connect' ) }</h2>
			</CardHeader>
			<CardBody>
				<p className="description">
					{ __( 'Browse and view capabilities control who can see the job list and single job pages. Leave empty for everyone (public).', 'job-connect' ) }
				</p>
				<p className="description">
					{ __( 'Advanced: configure via capability/role names in a future release.', 'job-connect' ) }
				</p>
			</CardBody>
		</Card>
	);
}
