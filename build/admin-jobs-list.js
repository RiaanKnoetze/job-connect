/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/admin/jobs-list/JobPreviewModal.js"
/*!************************************************!*\
  !*** ./src/admin/jobs-list/JobPreviewModal.js ***!
  \************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ JobPreviewModal)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);

/**
 * Job preview modal for the All jobs list table.
 *
 * @package Job_Connect
 */



const {
  ajaxUrl,
  nonce,
  i18n
} = window.jcAdminJobsList || {};
function JobPreviewModal() {
  const [isOpen, setIsOpen] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(false);
  const [content, setContent] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)('');
  const [loading, setLoading] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(false);
  const [error, setError] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(null);
  const [jobId, setJobId] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(null);
  const fetchPreview = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useCallback)(id => {
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
    fetch(url.toString()).then(res => res.json()).then(data => {
      setLoading(false);
      if (data.success && data.data && data.data.html) {
        setContent(data.data.html);
      } else {
        setError(data.data && data.data.message ? data.data.message : i18n && i18n.error || 'Could not load preview.');
      }
    }).catch(() => {
      setLoading(false);
      setError(i18n && i18n.error || 'Could not load preview.');
    });
  }, []);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    const onPreview = e => {
      const id = e.detail && e.detail.jobId;
      if (id) fetchPreview(Number(id));
    };
    window.addEventListener('jc-open-job-preview', onPreview);
    return () => window.removeEventListener('jc-open-job-preview', onPreview);
  }, [fetchPreview]);
  (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useEffect)(() => {
    const onClick = e => {
      const btn = e.target.closest('.jc-job-preview-btn');
      if (btn && btn.dataset.jobId) {
        e.preventDefault();
        window.dispatchEvent(new CustomEvent('jc-open-job-preview', {
          detail: {
            jobId: btn.dataset.jobId
          }
        }));
      }
    };
    document.body.addEventListener('click', onClick);
    return () => document.body.removeEventListener('click', onClick);
  }, []);
  if (!isOpen) return null;
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Modal, {
    title: i18n && i18n.preview ? i18n.preview : 'Preview',
    onRequestClose: () => setIsOpen(false),
    className: "jc-job-preview-modal",
    style: {
      maxWidth: '800px'
    }
  }, loading && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "jc-job-preview-loading"
  }, i18n && i18n.loading ? i18n.loading : 'Loading…'), error && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "jc-job-preview-error",
    role: "alert"
  }, error), !loading && !error && content && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "jc-job-preview-content job-listing-single"
    // eslint-disable-next-line react/no-danger
    ,
    dangerouslySetInnerHTML: {
      __html: content
    }
  }));
}

/***/ },

/***/ "react"
/*!************************!*\
  !*** external "React" ***!
  \************************/
(module) {

module.exports = window["React"];

/***/ },

/***/ "@wordpress/components"
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
(module) {

module.exports = window["wp"]["components"];

/***/ },

/***/ "@wordpress/element"
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
(module) {

module.exports = window["wp"]["element"];

/***/ }

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		if (!(moduleId in __webpack_modules__)) {
/******/ 			delete __webpack_module_cache__[moduleId];
/******/ 			var e = new Error("Cannot find module '" + moduleId + "'");
/******/ 			e.code = 'MODULE_NOT_FOUND';
/******/ 			throw e;
/******/ 		}
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry needs to be wrapped in an IIFE because it needs to be isolated against other modules in the chunk.
(() => {
/*!**************************************!*\
  !*** ./src/admin/jobs-list/index.js ***!
  \**************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _JobPreviewModal__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./JobPreviewModal */ "./src/admin/jobs-list/JobPreviewModal.js");

/**
 * Admin jobs list: preview modal and status actions.
 *
 * @package Job_Connect
 */




// Mount modal root (body is available in admin).
const rootEl = document.createElement('div');
rootEl.id = 'jc-job-preview-root';
document.body.appendChild(rootEl);
const root = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createRoot)(rootEl);
root.render((0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_JobPreviewModal__WEBPACK_IMPORTED_MODULE_2__["default"], null));

// Status action buttons: trigger AJAX and reload on success.
const config = window.jcAdminJobsList || {};
document.body.addEventListener('click', e => {
  const btn = e.target.closest('.jc-job-action');
  if (!btn || !btn.dataset.action || !btn.dataset.postId || !btn.dataset.nonce) return;
  e.preventDefault();
  const {
    action,
    postId,
    nonce
  } = btn.dataset;
  const formData = new FormData();
  formData.append('action', 'jc_job_set_status');
  formData.append('status', action);
  formData.append('post_id', postId);
  formData.append('nonce', nonce);
  fetch(config.ajaxUrl || '', {
    method: 'POST',
    body: formData,
    credentials: 'same-origin'
  }).then(res => res.json()).then(data => {
    if (data.success) {
      window.location.reload();
    } else {
      alert(data.data && data.data.message ? data.data.message : config.i18n && config.i18n.error || 'Request failed.');
    }
  }).catch(() => {
    alert(config.i18n && config.i18n.error || 'Request failed.');
  });
});
})();

/******/ })()
;
//# sourceMappingURL=admin-jobs-list.js.map