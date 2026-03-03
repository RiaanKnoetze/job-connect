/** @type {import('tailwindcss').Config} */
/** Catalyst-style: zinc neutrals, blue focus rings, default theme spacing/shadows */
module.exports = {
	content: [
		'./templates/**/*.php',
	],
	theme: {
		extend: {
			fontFamily: {
				sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif', 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji'],
			},
			colors: {
				/* Catalyst default button is dark/zinc; semantic colors for notices */
				jc: {
					success: { bg: '#dcfce7', border: '#bbf7d0', text: '#166534' },
					warning: { bg: '#fef9c3', border: '#fef08a', text: '#854d0e' },
					error: { bg: '#fee2e2', border: '#fecaca', text: '#991b1b' },
				},
			},
			boxShadow: {
				'input': '0 0 0 1px theme(colors.zinc.300), 0 1px 2px 0 rgb(0 0 0 / 0.05)',
				'input-focus': '0 0 0 3px theme(colors.blue.500 / 0.2), 0 0 0 1px theme(colors.blue.500)',
			},
		},
	},
	plugins: [],
};
