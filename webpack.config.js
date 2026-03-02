/**
 * Webpack configuration for Job Connect.
 *
 * @package Job_Connect
 */

const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );

module.exports = {
	...defaultConfig,
	entry: {
		admin: path.resolve( __dirname, 'src/admin/settings/index.js' ),
		'admin-jobs-list': path.resolve( __dirname, 'src/admin/jobs-list/index.js' ),
	},
	output: {
		path: path.resolve( __dirname, 'build' ),
		filename: '[name].js',
	},
};
