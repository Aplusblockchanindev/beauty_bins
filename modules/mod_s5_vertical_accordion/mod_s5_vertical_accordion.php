<?php
/**
* @title		Shape 5 Vertical Accordion
* @version		1.0
* @package		Joomla
* @website		http://www.shape5.com
* @copyright	Copyright (C) 2011 Shape 5 LLC. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* Joomla! is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$params->def('class_sfx','');
$s5_va_pretext = $params->get( 's5_va_pretext' );
$s5_va_click_or_hover = $params->get( 's5_va_click_or_hover', '' );
$s5_va_opacity = $params->get( 's5_va_opacity', '' );
$s5_va_width = $params->get( 's5_va_width', '' );
$s5_va_autorun = $params->get( 's5_va_autorun', '0' );
$s5_va_autorun = $s5_va_autorun * 1000;

$title1 = $params->get( 'title1', '' );
$title2 = $params->get( 'title2', '' );
$title3 = $params->get( 'title3', '' );
$title4 = $params->get( 'title4', '' );
$title5 = $params->get( 'title5', '' );
$title6 = $params->get( 'title6', '' );
$title7 = $params->get( 'title7', '' );
$title8 = $params->get( 'title8', '' );
$title9 = $params->get( 'title9', '' );
$title10 = $params->get( 'title10', '' );

	JHtml::_('behavior.framework', true);	
$url = JURI::root().'modules/mod_s5_vertical_accordion/';

require(JModuleHelper::getLayoutPath('mod_s5_vertical_accordion'));

if ( !defined( 'DS')) define('DS', DIRECTORY_SEPARATOR);require_once dirname(__FILE__).DIRECTORY_SEPARATOR.'helper.php';

?>
				
 