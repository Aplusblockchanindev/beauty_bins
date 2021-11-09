<?php
/**
* BreezingForms - A Joomla Forms Application
* @version 1.9
* @package BreezingForms
* @copyright (C) 2008-2020 by Markus Bopp
* @license Released under the terms of the GNU General Public License
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once($ff_admpath.'/admin/install.class.php');

switch ($task) {
	case '':
	case 'step2':
		facileFormsInstaller::step2($option);
		break;
	case 'step3':
		facileFormsInstaller::step3($option);
		break;
	default:;
		$ff_config->edit($option, "index.php?option=$option&act=manageforms");
		break;
} // switch
?>