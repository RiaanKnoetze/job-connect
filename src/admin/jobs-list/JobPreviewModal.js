/**
 * Job preview modal for the All jobs list table.
 *
 * @package Job_Connect
 */

import { useState, useEffect, useCallback } from '@wordpress/element';
import { Modal } from '@wordpress/components';

const { ajaxUrl, nonce, i18n } = window.jcAdminJobsList || {};

export default function JobPreviewModal() {
	const [isOpen, setIsOpen] = useState(false);
	const [content, setContent] = useState('');
	const [loading, setLoading] = useState(false);
	const [error, setError] = useState(null);
	const [jobId, setJobId] = useState(null);

	const fetchPreview = useCallback((id) => {
		if (!id || !ajaxUrl || !nonce) return;
		setJobId(id);
		setIsOpen(true);
		setLoading(true);
		setError(null);
		setContent('');

		const url = new URL(ajaxUrl);
		url.searchParams.set('action', 'jc_job_preview');
		url.searchParams.set('nonce', nonce);
		url.searchParams.set('job_id', id);

		fetch(url.toString())
			.then((res) => res.json())
			.then((data) => {
				setLoading(false);
				if (data.success && data.data && data.data.html) {
					setContent(data.data.html);
				} else {
					setError(data.data && data.data.message ? data.data.message : (i18n && i18n.error) || 'Could not load preview.');
				}
			})
			.catch(() => {
				setLoading(false);
				setError((i18n && i18n.error) || 'Could not load preview.');
			});
	}, []);

	useEffect(() => {
		const onPreview = (e) => {
			const id = e.detail && e.detail.jobId;
			if (id) fetchPreview(Number(id));
		};
		window.addEventListener('jc-open-job-preview', onPreview);
		return () => window.removeEventListener('jc-open-job-preview', onPreview);
	}, [fetchPreview]);

	useEffect(() => {
		const onClick = (e) => {
			const btn = e.target.closest('.jc-job-preview-btn');
			if (btn && btn.dataset.jobId) {
				e.preventDefault();
				window.dispatchEvent(new CustomEvent('jc-open-job-preview', { detail: { jobId: btn.dataset.jobId } }));
			}
		};
		document.body.addEventListener('click', onClick);
		return () => document.body.removeEventListener('click', onClick);
	}, []);

	if (!isOpen) return null;

	return (
		<Modal
			title={i18n && i18n.preview ? i18n.preview : 'Preview'}
			onRequestClose={() => setIsOpen(false)}
			className="jc-job-preview-modal"
			style={{ maxWidth: '800px' }}
		>
			{loading && (
				<p className="jc-job-preview-loading">
					{i18n && i18n.loading ? i18n.loading : 'Loading…'}
				</p>
			)}
			{error && (
				<p className="jc-job-preview-error" role="alert">
					{error}
				</p>
			)}
			{!loading && !error && content && (
				<div
					className="jc-job-preview-content job-listing-single"
					// eslint-disable-next-line react/no-danger
					dangerouslySetInnerHTML={{ __html: content }}
				/>
			)}
		</Modal>
	);
}
