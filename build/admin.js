/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/admin/settings/App.js"
/*!***********************************!*\
  !*** ./src/admin/settings/App.js ***!
  \***********************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ App)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/api-fetch */ "@wordpress/api-fetch");
/* harmony import */ var _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _sections_GeneralSection__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! ./sections/GeneralSection */ "./src/admin/settings/sections/GeneralSection.js");
/* harmony import */ var _sections_JobListingsSection__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! ./sections/JobListingsSection */ "./src/admin/settings/sections/JobListingsSection.js");
/* harmony import */ var _sections_JobSubmissionSection__WEBPACK_IMPORTED_MODULE_7__ = __webpack_require__(/*! ./sections/JobSubmissionSection */ "./src/admin/settings/sections/JobSubmissionSection.js");
/* harmony import */ var _sections_RecaptchaSection__WEBPACK_IMPORTED_MODULE_8__ = __webpack_require__(/*! ./sections/RecaptchaSection */ "./src/admin/settings/sections/RecaptchaSection.js");
/* harmony import */ var _sections_PagesSection__WEBPACK_IMPORTED_MODULE_9__ = __webpack_require__(/*! ./sections/PagesSection */ "./src/admin/settings/sections/PagesSection.js");
/* harmony import */ var _sections_JobVisibilitySection__WEBPACK_IMPORTED_MODULE_10__ = __webpack_require__(/*! ./sections/JobVisibilitySection */ "./src/admin/settings/sections/JobVisibilitySection.js");
/* harmony import */ var _sections_EmailNotificationsSection__WEBPACK_IMPORTED_MODULE_11__ = __webpack_require__(/*! ./sections/EmailNotificationsSection */ "./src/admin/settings/sections/EmailNotificationsSection.js");

/**
 * Job Connect Settings – main app with tabs.
 *
 * @package Job_Connect
 */












const TABS = [{
  name: 'general',
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('General', 'job-connect')
}, {
  name: 'job_listings',
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Job Listings', 'job-connect')
}, {
  name: 'job_submission',
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Job Submission', 'job-connect')
}, {
  name: 'recaptcha',
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('ReCAPTCHA', 'job-connect')
}, {
  name: 'pages',
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Pages', 'job-connect')
}, {
  name: 'job_visibility',
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Job Visibility', 'job-connect')
}, {
  name: 'email_notifications',
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Email Notifications', 'job-connect')
}];
function App() {
  const [settings, setSettings] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(window.jobConnectAdmin?.settings || {});
  const [saving, setSaving] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(false);
  const [notice, setNotice] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(null);
  const updateSetting = (key, value) => {
    setSettings(prev => ({
      ...prev,
      [key]: value
    }));
  };
  const saveSettings = async () => {
    setSaving(true);
    setNotice(null);
    try {
      const response = await _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default()({
        path: '/jc/v1/settings',
        method: 'POST',
        data: {
          settings
        },
        headers: {
          'X-WP-Nonce': window.jobConnectAdmin?.nonce || '',
          'Content-Type': 'application/json'
        }
      });
      setSettings(response);
      setNotice({
        type: 'success',
        message: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Settings saved.', 'job-connect')
      });
    } catch (err) {
      setNotice({
        type: 'error',
        message: err.message || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Failed to save settings.', 'job-connect')
      });
    } finally {
      setSaving(false);
    }
  };
  const renderTab = tabName => {
    const common = {
      settings,
      updateSetting
    };
    switch (tabName) {
      case 'general':
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_sections_GeneralSection__WEBPACK_IMPORTED_MODULE_5__["default"], {
          ...common
        });
      case 'job_listings':
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_sections_JobListingsSection__WEBPACK_IMPORTED_MODULE_6__["default"], {
          ...common
        });
      case 'job_submission':
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_sections_JobSubmissionSection__WEBPACK_IMPORTED_MODULE_7__["default"], {
          ...common
        });
      case 'recaptcha':
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_sections_RecaptchaSection__WEBPACK_IMPORTED_MODULE_8__["default"], {
          ...common
        });
      case 'pages':
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_sections_PagesSection__WEBPACK_IMPORTED_MODULE_9__["default"], {
          ...common
        });
      case 'job_visibility':
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_sections_JobVisibilitySection__WEBPACK_IMPORTED_MODULE_10__["default"], {
          ...common
        });
      case 'email_notifications':
        return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_sections_EmailNotificationsSection__WEBPACK_IMPORTED_MODULE_11__["default"], {
          ...common
        });
      default:
        return null;
    }
  };
  const setupWizardDone = settings.jc_setup_wizard_done === '1';
  const [runningWizard, setRunningWizard] = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.useState)(false);
  const runSetupWizard = async () => {
    setRunningWizard(true);
    setNotice(null);
    try {
      const response = await _wordpress_api_fetch__WEBPACK_IMPORTED_MODULE_4___default()({
        path: '/jc/v1/setup-wizard',
        method: 'POST',
        headers: {
          'X-WP-Nonce': window.jobConnectAdmin?.nonce || '',
          'Content-Type': 'application/json'
        }
      });
      setSettings(response);
      setNotice({
        type: 'success',
        message: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Setup complete. Default pages have been created and assigned.', 'job-connect')
      });
    } catch (err) {
      setNotice({
        type: 'error',
        message: err.message || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Setup wizard failed.', 'job-connect')
      });
    } finally {
      setRunningWizard(false);
    }
  };
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "job-connect-settings-app"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h1", {
    className: "job-connect-settings-title"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Job Connect Settings', 'job-connect')), !setupWizardDone && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Card, {
    className: "job-connect-setup-wizard-card",
    style: {
      marginBottom: '1.5em'
    }
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.CardHeader, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("strong", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('First time setup', 'job-connect'))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.CardBody, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Create default pages (Jobs, Submit a Job, Job Dashboard) and assign them in Pages settings.', 'job-connect')), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {
    isPrimary: true,
    onClick: runSetupWizard,
    disabled: runningWizard
  }, runningWizard ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Spinner, null) : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Create default pages', 'job-connect')))), notice && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Notice, {
    status: notice.type,
    isDismissible: true,
    onRemove: () => setNotice(null)
  }, notice.message), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.TabPanel, {
    className: "job-connect-tab-panel",
    tabs: TABS,
    initialTabName: "general"
  }, tab => (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "job-connect-tab-content"
  }, renderTab(tab.name))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "job-connect-save-actions"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Button, {
    isPrimary: true,
    onClick: saveSettings,
    disabled: saving
  }, saving ? (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_3__.Spinner, null) : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_2__.__)('Save changes', 'job-connect'))));
}

/***/ },

/***/ "./src/admin/settings/sections/EmailNotificationsSection.js"
/*!******************************************************************!*\
  !*** ./src/admin/settings/sections/EmailNotificationsSection.js ***!
  \******************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ EmailNotificationsSection)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);

/**
 * Email notifications section.
 *
 * @package Job_Connect
 */



function EmailNotificationsSection({
  settings,
  updateSetting
}) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Card, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CardHeader, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", {
    className: "job-connect-section-title"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Email Notifications', 'job-connect'))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CardBody, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Email admin when a new job is submitted', 'job-connect'),
    checked: settings.jc_email_admin_new_job === '1',
    onChange: v => updateSetting('jc_email_admin_new_job', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Email admin when a job is updated', 'job-connect'),
    checked: settings.jc_email_admin_updated_job === '1',
    onChange: v => updateSetting('jc_email_admin_updated_job', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Email admin about expiring jobs', 'job-connect'),
    checked: settings.jc_email_admin_expiring_job === '1',
    onChange: v => updateSetting('jc_email_admin_expiring_job', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Days before expiry to email admin', 'job-connect'),
    type: "number",
    value: settings.jc_admin_expiring_job_days || '7',
    onChange: v => updateSetting('jc_admin_expiring_job_days', v || '7')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Email employer when their job is expiring', 'job-connect'),
    checked: settings.jc_email_employer_expiring_job === '1',
    onChange: v => updateSetting('jc_email_employer_expiring_job', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Days before expiry to email employer', 'job-connect'),
    type: "number",
    value: settings.jc_employer_expiring_job_days || '7',
    onChange: v => updateSetting('jc_employer_expiring_job_days', v || '7')
  })));
}

/***/ },

/***/ "./src/admin/settings/sections/GeneralSection.js"
/*!*******************************************************!*\
  !*** ./src/admin/settings/sections/GeneralSection.js ***!
  \*******************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ GeneralSection)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);

/**
 * General settings section.
 *
 * @package Job_Connect
 */



function GeneralSection({
  settings,
  updateSetting
}) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Card, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CardHeader, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", {
    className: "job-connect-section-title"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('General', 'job-connect'))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CardBody, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Date format', 'job-connect'),
    value: settings.jc_date_format || 'relative',
    options: [{
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Relative (e.g. 1 day ago)', 'job-connect'),
      value: 'relative'
    }, {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Default date format', 'job-connect'),
      value: 'default'
    }],
    onChange: v => updateSetting('jc_date_format', v)
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Google Maps API key', 'job-connect'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Required for location geocoding. Get a key from Google Maps API.', 'job-connect'),
    value: settings.jc_google_maps_api_key || '',
    onChange: v => updateSetting('jc_google_maps_api_key', v || '')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Delete data when plugin is uninstalled', 'job-connect'),
    checked: settings.jc_delete_data_on_uninstall === '1',
    onChange: v => updateSetting('jc_delete_data_on_uninstall', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Bypass trash for job listings on uninstall', 'job-connect'),
    checked: settings.jc_bypass_trash_on_uninstall === '1',
    onChange: v => updateSetting('jc_bypass_trash_on_uninstall', v ? '1' : '0')
  })));
}

/***/ },

/***/ "./src/admin/settings/sections/JobListingsSection.js"
/*!***********************************************************!*\
  !*** ./src/admin/settings/sections/JobListingsSection.js ***!
  \***********************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ JobListingsSection)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);

/**
 * Job listings settings section.
 *
 * @package Job_Connect
 */



function JobListingsSection({
  settings,
  updateSetting
}) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Card, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CardHeader, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", {
    className: "job-connect-section-title"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Job Listings', 'job-connect'))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CardBody, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Listings per page', 'job-connect'),
    type: "number",
    value: settings.jc_per_page || '10',
    onChange: v => updateSetting('jc_per_page', v || '10')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Pagination type', 'job-connect'),
    value: settings.jc_pagination_type || 'load_more',
    options: [{
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Load more button', 'job-connect'),
      value: 'load_more'
    }, {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Page numbers', 'job-connect'),
      value: 'pagination'
    }],
    onChange: v => updateSetting('jc_pagination_type', v)
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Hide filled listings', 'job-connect'),
    checked: settings.jc_hide_filled_positions === '1',
    onChange: v => updateSetting('jc_hide_filled_positions', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Hide expired listings', 'job-connect'),
    checked: settings.jc_hide_expired === '1',
    onChange: v => updateSetting('jc_hide_expired', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Hide content in expired single listings', 'job-connect'),
    checked: settings.jc_hide_expired_content === '1',
    onChange: v => updateSetting('jc_hide_expired_content', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enable categories', 'job-connect'),
    checked: settings.jc_enable_categories === '1',
    onChange: v => updateSetting('jc_enable_categories', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enable job types', 'job-connect'),
    checked: settings.jc_enable_types !== '0',
    onChange: v => updateSetting('jc_enable_types', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enable remote position field', 'job-connect'),
    checked: settings.jc_enable_remote_position === '1',
    onChange: v => updateSetting('jc_enable_remote_position', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enable salary field', 'job-connect'),
    checked: settings.jc_enable_salary === '1',
    onChange: v => updateSetting('jc_enable_salary', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Display full location address when geocoded', 'job-connect'),
    checked: settings.jc_display_location_address === '1',
    onChange: v => updateSetting('jc_display_location_address', v ? '1' : '0')
  })));
}

/***/ },

/***/ "./src/admin/settings/sections/JobSubmissionSection.js"
/*!*************************************************************!*\
  !*** ./src/admin/settings/sections/JobSubmissionSection.js ***!
  \*************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ JobSubmissionSection)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);

/**
 * Job submission settings section.
 *
 * @package Job_Connect
 */



const pages = window.jobConnectAdmin?.pages || [];
const roles = window.jobConnectAdmin?.roles || {};
const roleOptions = Object.entries(roles).map(([value, label]) => ({
  value,
  label
}));
function JobSubmissionSection({
  settings,
  updateSetting
}) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Card, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CardHeader, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", {
    className: "job-connect-section-title"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Job Submission', 'job-connect'))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CardBody, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Require an account to submit listings', 'job-connect'),
    checked: settings.jc_user_requires_account === '1',
    onChange: v => updateSetting('jc_user_requires_account', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Enable account creation during submission', 'job-connect'),
    checked: settings.jc_enable_registration === '1',
    onChange: v => updateSetting('jc_enable_registration', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Account role for new users', 'job-connect'),
    value: settings.jc_registration_role || 'employer',
    options: roleOptions.length ? roleOptions : [{
      value: 'employer',
      label: 'Employer'
    }],
    onChange: v => updateSetting('jc_registration_role', v)
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Generate username from email', 'job-connect'),
    checked: settings.jc_generate_username_from_email === '1',
    onChange: v => updateSetting('jc_generate_username_from_email', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Email new users a link to set password', 'job-connect'),
    checked: settings.jc_use_standard_password_setup_email === '1',
    onChange: v => updateSetting('jc_use_standard_password_setup_email', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Require admin approval for new listings', 'job-connect'),
    checked: settings.jc_submission_requires_approval === '1',
    onChange: v => updateSetting('jc_submission_requires_approval', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Allow editing of pending listings', 'job-connect'),
    checked: settings.jc_user_can_edit_pending_submissions === '1',
    onChange: v => updateSetting('jc_user_can_edit_pending_submissions', v ? '1' : '0')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Allow editing of published listings', 'job-connect'),
    value: settings.jc_user_edit_published_submissions || 'yes',
    options: [{
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Users cannot edit', 'job-connect'),
      value: 'no'
    }, {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Users can edit without approval', 'job-connect'),
      value: 'yes'
    }, {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Users can edit, edits require approval', 'job-connect'),
      value: 'yes_moderated'
    }],
    onChange: v => updateSetting('jc_user_edit_published_submissions', v)
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Listing duration (days)', 'job-connect'),
    type: "number",
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Leave blank for no expiry.', 'job-connect'),
    value: settings.jc_submission_duration || '30',
    onChange: v => updateSetting('jc_submission_duration', v || '')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Listing limit per user', 'job-connect'),
    type: "number",
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Leave blank for unlimited.', 'job-connect'),
    value: settings.jc_submission_limit || '',
    onChange: v => updateSetting('jc_submission_limit', v || '')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Application method', 'job-connect'),
    value: settings.jc_allowed_application_method || '',
    options: [{
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Email or URL', 'job-connect'),
      value: ''
    }, {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Email only', 'job-connect'),
      value: 'email'
    }, {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('URL only', 'job-connect'),
      value: 'url'
    }],
    onChange: v => updateSetting('jc_allowed_application_method', v || '')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Require Terms and Conditions checkbox', 'job-connect'),
    checked: settings.jc_show_agreement_job_submission === '1',
    onChange: v => updateSetting('jc_show_agreement_job_submission', v ? '1' : '0')
  })));
}

/***/ },

/***/ "./src/admin/settings/sections/JobVisibilitySection.js"
/*!*************************************************************!*\
  !*** ./src/admin/settings/sections/JobVisibilitySection.js ***!
  \*************************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ JobVisibilitySection)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);

/**
 * Job visibility (capabilities) section.
 *
 * @package Job_Connect
 */



function JobVisibilitySection({
  settings
}) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Card, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CardHeader, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", {
    className: "job-connect-section-title"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Job Visibility', 'job-connect'))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CardBody, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "description"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Browse and view capabilities control who can see the job list and single job pages. Leave empty for everyone (public).', 'job-connect')), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", {
    className: "description"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Advanced: configure via capability/role names in a future release.', 'job-connect'))));
}

/***/ },

/***/ "./src/admin/settings/sections/PagesSection.js"
/*!*****************************************************!*\
  !*** ./src/admin/settings/sections/PagesSection.js ***!
  \*****************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ PagesSection)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);

/**
 * Pages settings section.
 *
 * @package Job_Connect
 */



const rawPages = window.jobConnectAdmin?.pages || [];
const pageOptions = rawPages.length ? rawPages : [{
  value: '',
  label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('— Select —', 'job-connect')
}];
function PagesSection({
  settings,
  updateSetting
}) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Card, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CardHeader, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", {
    className: "job-connect-section-title"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Pages', 'job-connect'))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CardBody, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Submit job form page', 'job-connect'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Page containing [submit_job_form] shortcode.', 'job-connect'),
    value: String(settings.jc_submit_job_form_page_id || ''),
    options: pageOptions,
    onChange: v => updateSetting('jc_submit_job_form_page_id', v ? String(v) : '')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Job dashboard page', 'job-connect'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Page containing [job_dashboard] shortcode.', 'job-connect'),
    value: String(settings.jc_job_dashboard_page_id || ''),
    options: pageOptions,
    onChange: v => updateSetting('jc_job_dashboard_page_id', v ? String(v) : '')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Job listings page', 'job-connect'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Page containing [jobs] shortcode.', 'job-connect'),
    value: String(settings.jc_jobs_page_id || ''),
    options: pageOptions,
    onChange: v => updateSetting('jc_jobs_page_id', v ? String(v) : '')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.SelectControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Terms and conditions page', 'job-connect'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Linked when T&C checkbox is enabled.', 'job-connect'),
    value: String(settings.jc_terms_and_conditions_page_id || ''),
    options: pageOptions,
    onChange: v => updateSetting('jc_terms_and_conditions_page_id', v ? String(v) : '')
  })));
}

/***/ },

/***/ "./src/admin/settings/sections/RecaptchaSection.js"
/*!*********************************************************!*\
  !*** ./src/admin/settings/sections/RecaptchaSection.js ***!
  \*********************************************************/
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (/* binding */ RecaptchaSection)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__);

/**
 * ReCAPTCHA settings section.
 *
 * @package Job_Connect
 */



function RecaptchaSection({
  settings,
  updateSetting
}) {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.Card, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CardHeader, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("h2", {
    className: "job-connect-section-title"
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('ReCAPTCHA', 'job-connect'))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CardBody, null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Field label', 'job-connect'),
    value: settings.jc_recaptcha_label || (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Are you human?', 'job-connect'),
    onChange: v => updateSetting('jc_recaptcha_label', v || '')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Site key', 'job-connect'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('From Google reCAPTCHA admin.', 'job-connect'),
    value: settings.jc_recaptcha_site_key || '',
    onChange: v => updateSetting('jc_recaptcha_site_key', v || '')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.TextControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Secret key', 'job-connect'),
    help: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('From Google reCAPTCHA admin.', 'job-connect'),
    value: settings.jc_recaptcha_secret_key || '',
    onChange: v => updateSetting('jc_recaptcha_secret_key', v || '')
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_2__.CheckboxControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_1__.__)('Show CAPTCHA on job submission form', 'job-connect'),
    checked: settings.jc_enable_recaptcha_job_submission === '1',
    onChange: v => updateSetting('jc_enable_recaptcha_job_submission', v ? '1' : '0')
  })));
}

/***/ },

/***/ "react"
/*!************************!*\
  !*** external "React" ***!
  \************************/
(module) {

module.exports = window["React"];

/***/ },

/***/ "@wordpress/api-fetch"
/*!**********************************!*\
  !*** external ["wp","apiFetch"] ***!
  \**********************************/
(module) {

module.exports = window["wp"]["apiFetch"];

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

/***/ },

/***/ "@wordpress/i18n"
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
(module) {

module.exports = window["wp"]["i18n"];

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
/*!*************************************!*\
  !*** ./src/admin/settings/index.js ***!
  \*************************************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var _App__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./App */ "./src/admin/settings/App.js");

/**
 * Job Connect Settings – React app entry.
 *
 * @package Job_Connect
 */



const root = document.getElementById('job-connect-settings-root');
if (root) {
  const rootInstance = (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_1__.createRoot)(root);
  rootInstance.render((0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_App__WEBPACK_IMPORTED_MODULE_2__["default"], null));
}
})();

/******/ })()
;
//# sourceMappingURL=admin.js.map