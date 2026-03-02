/**
 * Admin jobs list: preview modal and status actions.
 *
 * @package Job_Connect
 */

import { createRoot } from '@wordpress/element';
import JobPreviewModal from './JobPreviewModal';

// Mount modal root (body is available in admin).
const rootEl = document.createElement('div');
rootEl.id = 'jc-job-preview-root';
document.body.appendChild(rootEl);
const root = createRoot(rootEl);
root.render(<JobPreviewModal />);

// Status action buttons: trigger AJAX and reload on success.
const config = window.jcAdminJobsList || {};
document.body.addEventListener('click', (e) => {
	const btn = e.target.closest('.jc-job-action');
	if (!btn || !btn.dataset.action || !btn.dataset.postId || !btn.dataset.nonce) return;
	e.preventDefault();
	const { action, postId, nonce } = btn.dataset;
	const formData = new FormData();
	formData.append('action', 'jc_job_set_status');
	formData.append('status', action);
	formData.append('post_id', postId);
	formData.append('nonce', nonce);

	fetch(config.ajaxUrl || '', {
		method: 'POST',
		body: formData,
		credentials: 'same-origin',
	})
		.then((res) => res.json())
		.then((data) => {
			if (data.success) {
				window.location.reload();
			} else {
				alert(data.data && data.data.message ? data.data.message : (config.i18n && config.i18n.error) || 'Request failed.');
			}
		})
		.catch(() => {
			alert((config.i18n && config.i18n.error) || 'Request failed.');
		});
});
