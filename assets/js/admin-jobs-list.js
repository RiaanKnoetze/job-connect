/**
 * Admin All jobs list: preview modal (WooCommerce-style) and status actions.
 * Vanilla JS so it works on the list table without block editor / wp-components.
 *
 * @package Job_Connect
 */

(function () {
	'use strict';

	var config = window.jcAdminJobsList || {};
	var ajaxUrl = config.ajaxUrl || '';
	var nonce = config.nonce || '';
	var i18n = config.i18n || {};

	function getText(key, fallback) {
		return (i18n[key] !== undefined && i18n[key]) ? i18n[key] : (fallback || key);
	}

	function createModal() {
		var overlay = document.createElement('div');
		overlay.id = 'jc-job-preview-overlay';
		overlay.className = 'jc-job-preview-overlay';
		overlay.setAttribute('aria-hidden', 'true');
		overlay.innerHTML =
			'<div class="jc-job-preview-modal-wrap">' +
			'<div class="jc-job-preview-modal">' +
			'<div class="jc-job-preview-modal-header">' +
			'<div class="jc-job-preview-header-inner">' +
			'<h2 class="jc-job-preview-title">' + (getText('preview', 'Preview')) + '</h2>' +
			'<span class="jc-job-preview-badges"></span>' +
			'</div>' +
			'<button type="button" class="button button-secondary jc-job-preview-copy-link" aria-label="' + (getText('copyLink', 'Copy link')) + '">' + (getText('copyLink', 'Copy link')) + '</button>' +
			'<button type="button" class="jc-job-preview-close" aria-label="' + (getText('close', 'Close')) + '">&times;</button>' +
			'</div>' +
			'<div class="jc-job-preview-body"></div>' +
			'</div></div>';
		document.body.appendChild(overlay);

		var body = overlay.querySelector('.jc-job-preview-body');
		var closeBtn = overlay.querySelector('.jc-job-preview-close');
		var copyLinkBtn = overlay.querySelector('.jc-job-preview-copy-link');

		function close() {
			overlay.classList.remove('is-open');
			overlay.setAttribute('aria-hidden', 'true');
			body.innerHTML = '';
			document.body.style.overflow = '';
		}

		closeBtn.addEventListener('click', close);
		overlay.addEventListener('click', function (e) {
			if (e.target === overlay) {
				close();
			}
		});
		document.addEventListener('keydown', function (e) {
			if (e.key === 'Escape' && overlay.classList.contains('is-open')) {
				close();
			}
		});

		return { overlay: overlay, body: body, close: close, copyLinkBtn: copyLinkBtn };
	}

	var modal = null;

	function setModalHeader(title, featured, filled) {
		var titleEl = modal && modal.overlay && modal.overlay.querySelector('.jc-job-preview-title');
		var badgesEl = modal && modal.overlay && modal.overlay.querySelector('.jc-job-preview-badges');
		if (titleEl) {
			titleEl.textContent = title;
		}
		if (badgesEl) {
			var parts = [];
			if (featured) {
				parts.push('<span class="jc-job-preview-badge jc-job-preview-badge--featured">' + (getText('featured', 'Featured')) + '</span>');
			}
			if (filled) {
				parts.push('<span class="jc-job-preview-badge jc-job-preview-badge--filled">' + (getText('filled', 'Filled')) + '</span>');
			}
			badgesEl.innerHTML = parts.join('');
		}
	}

	function openPreview(jobId) {
		if (!modal) {
			modal = createModal();
		}
		modal.currentJobUrl = '';
		if (modal.copyLinkBtn) {
			modal.copyLinkBtn.disabled = true;
			modal.copyLinkBtn.textContent = getText('copyLink', 'Copy link');
		}
		setModalHeader(getText('preview', 'Preview'), false, false);
		modal.body.innerHTML = '<p class="jc-job-preview-loading">' + (getText('loading', 'Loading…')) + '</p>';
		modal.overlay.classList.add('is-open');
		modal.overlay.setAttribute('aria-hidden', 'false');
		document.body.style.overflow = 'hidden';

		var url = ajaxUrl + (ajaxUrl.indexOf('?') >= 0 ? '&' : '?') +
			'action=jc_job_preview&nonce=' + encodeURIComponent(nonce) + '&job_id=' + encodeURIComponent(jobId);

		fetch(url)
			.then(function (res) { return res.json(); })
			.then(function (data) {
				if (data.success && data.data && data.data.html) {
					var title = (data.data.title && data.data.title.length) ? (getText('preview', 'Preview') + ': ' + data.data.title) : getText('preview', 'Preview');
					setModalHeader(title, !!data.data.featured, !!data.data.filled);
					modal.body.innerHTML = '<div class="jc-job-preview-content job-listing-single">' + data.data.html + '</div>';
					modal.currentJobUrl = data.data.url || '';
					if (modal.copyLinkBtn) {
						modal.copyLinkBtn.disabled = !modal.currentJobUrl;
					}
				} else {
					modal.body.innerHTML = '<p class="jc-job-preview-error" role="alert">' + (data.data && data.data.message ? data.data.message : getText('error', 'Could not load preview.')) + '</p>';
					modal.currentJobUrl = '';
				}
			})
			.catch(function () {
				modal.body.innerHTML = '<p class="jc-job-preview-error" role="alert">' + getText('error', 'Could not load preview.') + '</p>';
				modal.currentJobUrl = '';
			});

	}

	function copyJobLinkToClipboard() {
		var url = modal && modal.currentJobUrl;
		if (!url) return;
		var btn = modal && modal.copyLinkBtn;
		var copiedText = getText('copied', 'Copied!');
		if (navigator.clipboard && navigator.clipboard.writeText) {
			navigator.clipboard.writeText(url).then(function () {
				if (btn) {
					btn.textContent = copiedText;
					setTimeout(function () { btn.textContent = getText('copyLink', 'Copy link'); }, 2000);
				}
			}).catch(function () {
				fallbackCopyToClipboard(url, btn, copiedText);
			});
		} else {
			fallbackCopyToClipboard(url, btn, copiedText);
		}
	}

	function fallbackCopyToClipboard(url, btn, copiedText) {
		var ta = document.createElement('textarea');
		ta.value = url;
		ta.setAttribute('readonly', '');
		ta.style.position = 'absolute';
		ta.style.left = '-9999px';
		document.body.appendChild(ta);
		ta.select();
		try {
			document.execCommand('copy');
			if (btn) {
				btn.textContent = copiedText;
				setTimeout(function () { btn.textContent = getText('copyLink', 'Copy link'); }, 2000);
			}
		} catch (err) {}
		document.body.removeChild(ta);
	}

	// Copy link button: bind once when modal is created
	document.body.addEventListener('click', function (e) {
		if (e.target && e.target.classList && e.target.classList.contains('jc-job-preview-copy-link')) {
			e.preventDefault();
			copyJobLinkToClipboard();
		}
	});

	// Preview button clicks (delegated).
	document.body.addEventListener('click', function (e) {
		var btn = e.target.closest('.jc-job-preview-btn');
		if (btn && btn.dataset.jobId) {
			e.preventDefault();
			openPreview(btn.dataset.jobId);
		}
	});

	// Status action button clicks (delegated).
	document.body.addEventListener('click', function (e) {
		var btn = e.target.closest('.jc-job-action');
		if (!btn || !btn.dataset.action || !btn.dataset.postId || !btn.dataset.nonce) return;
		e.preventDefault();

		var formData = new FormData();
		formData.append('action', 'jc_job_set_status');
		formData.append('status', btn.dataset.action);
		formData.append('post_id', btn.dataset.postId);
		formData.append('nonce', btn.dataset.nonce);

		fetch(ajaxUrl, {
			method: 'POST',
			body: formData,
			credentials: 'same-origin'
		})
			.then(function (res) { return res.json(); })
			.then(function (data) {
				if (data.success) {
					window.location.reload();
				} else {
					alert((data.data && data.data.message) ? data.data.message : getText('error', 'Request failed.'));
				}
			})
			.catch(function () {
				alert(getText('error', 'Request failed.'));
			});
	});
})();
