<?php
/**
 * Edit job form handler.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * JC_Form_Edit_Job class.
 */
class JC_Form_Edit_Job extends Abstract_JC_Form {

	public $form_name = 'edit-job';

	/**
	 * Single instance.
	 *
	 * @var JC_Form_Edit_Job
	 */
	private static $instance = null;

	/**
	 * Get instance.
	 *
	 * @return JC_Form_Edit_Job
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Output the form.
	 */
	public function output() {
		// Phase 4.
	}
}
