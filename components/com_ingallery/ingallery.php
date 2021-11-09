<?php
/**
 * @package    inGallery
 * @subpackage com_ingallery
 * @license  http://www.gnu.org/licenseses/gpl-3.0.html GNU/GPL
 *
 * Created by Oleg Micriucov for Joomla! 3.x
 * https://allforjoomla.com
 *
 */
defined( '_JEXEC' ) or die(':)');

require_once(JPATH_ROOT.'/components/com_ingallery/models/ingallery.php');
require_once(JPATH_ROOT.'/components/com_ingallery/models/logger.php');
require_once(JPATH_ROOT.'/components/com_ingallery/views/ingallery.php');
JTable::addIncludePath(JPATH_ADMINISTRATOR.'/components/com_ingallery/tables');

$lang = JFactory::getLanguage();
$lang->load('com_ingallery', JPATH_ADMINISTRATOR);

$controller = JControllerLegacy::getInstance('Ingallery');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
