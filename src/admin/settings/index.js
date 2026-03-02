/**
 * Job Connect Settings – React app entry.
 *
 * @package Job_Connect
 */

import { createRoot } from '@wordpress/element';
import App from './App';

const root = document.getElementById( 'job-connect-settings-root' );
if ( root ) {
	const rootInstance = createRoot( root );
	rootInstance.render( <App /> );
}
