<?php
/**
 * Abstract base form class for Job Connect.
 *
 * @package Job_Connect
 */

defined( 'ABSPATH' ) || exit;

/**
 * Abstract_JC_Form class.
 */
abstract class Abstract_JC_Form {

	/**
	 * Form name.
	 *
	 * @var string
	 */
	public $form_name = '';

	/**
	 * Output the form.
	 */
	abstract public function output();
}
