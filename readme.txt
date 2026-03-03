=== Job Connect ===

Contributors: jobconnect
Tags: job board, jobs, employment, listings, careers
Requires at least: 6.4
Tested up to: 6.6
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A modern job board plugin for WordPress.

== Description ==

Job Connect provides a full-featured job board: job listings, employer submission form, job dashboard, categories and types, email notifications, and a modern admin settings interface built with React.

= Features =

* Custom post type for job listings
* Job types and categories (taxonomies)
* Shortcodes: [jobs], [job], [job_summary], [submit_job_form], [job_dashboard]
* React-based settings page (Job Connect > Settings)
* REST API for settings and (optional) public job list
* Email notifications (admin and employer)
* Cron for expiring jobs
* Template overrides via theme (job-connect/ folder)

= Shortcodes =

* `[jobs]` – List jobs with optional filters
* `[job id="123"]` – Single job by ID
* `[job_summary id="123"]` – Job summary
* `[submit_job_form]` – Submit a job (requires login if configured)
* `[job_dashboard]` – Employer dashboard

== Installation ==

1. Upload the plugin folder to wp-content/plugins/
2. Activate the plugin
3. Go to Job Connect > Settings to configure
4. Create pages and add the shortcodes; assign pages in Settings > Pages

== Development ==

Frontend pages use Tailwind CSS. After changing templates or `src/frontend/frontend.css`, rebuild the CSS:

  npm install
  npm run build:css

Or run the full build (admin JS + frontend CSS):

  npm run build

== Changelog ==

= 1.0.0 =
* Initial release.
