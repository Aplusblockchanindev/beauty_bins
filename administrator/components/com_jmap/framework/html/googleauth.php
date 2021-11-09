<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Form Field class for the Joomla Platform.
 * Provides radio button inputs
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/command.radio.html#command.radio
 * @since       11.1
 */
class JFormFieldGoogleAuth extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	protected $type = 'GoogleAuth';

	/**
	 * Method to get the radio button field input markup.
	 *
	 * @return  string  The field input markup.
	 *
	 * @since   11.1
	 */
	protected function getInput() {
		$app = JFactory::getApplication();
		if($app->input->get('option') == 'com_config') {
			return;
		}
		
		$googleModel = JModelLegacy::getInstance('Google', 'JMapModel');
		if(!$googleModel->getComponentParams()->get('enable_google_indexing_api', 0)) {
			return '<span data-content="' . JText::_('COM_JMAP_START_GOOGLE_INDEXING_DISABLED_DESC') . '" class="label label-danger hasPopover"><span class="icon-warning"></span>' .
											JText::_('COM_JMAP_START_GOOGLE_INDEXING_DISABLED') . '</span>';
		}
		
		// Composer autoloader
		if (PHP_VERSION_ID >= 70205) {
			require_once JPATH_COMPONENT_ADMINISTRATOR. '/framework/composer/autoload_real.php';
			ComposerAutoloaderInitcb4c0ac1dedbbba2f0b42e9cdf4d93d7::getLoader();
		} else {
			return '<span data-content="' . JText::_('COM_JMAP_PHP_VERSION_UNSUPPORTED_DESC') . '" class="label label-danger hasPopover">' .
											JText::_('COM_JMAP_PHP_VERSION_UNSUPPORTED') . '</span>';
		}
		
		$authLink = $googleModel->indexingAPIAuthUpdate();
		if ($googleModel->getComponentParams()->get('google_indexing_authcode') && $googleModel->getComponentParams()->get('google_indexing_authtoken')) {
			return 	'<span id="google_authentication_reset" data-content="' . JText::_('COM_JMAP_GOOGLE_AUTHENTICATION_LOGOUT_DESC') . '" class="label label-success hasPopover hasButton">' .
					'<span class="icon-lock"></span>' . JText::_('COM_JMAP_GOOGLE_AUTHENTICATION_LOGOUT') . '</span>';
		} else {
			return 	'<a target="_blank" href="' . $authLink . '" data-content="' . JText::_('COM_JMAP_START_GOOGLE_AUTHENTICATION_DESC') . '" class="label label-primary hasPopover hasButton">' .
					'<span class="icon-lock"></span>' . JText::_('COM_JMAP_START_GOOGLE_AUTHENTICATION') . '</a>';
		}
	}
}
