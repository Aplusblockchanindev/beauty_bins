<?php
/**
 * @title		S5 Disqus Comments
 * @author		Modifications and additions by Shape5 - http://www.shape5.com, some original work by JoomlaWorks - http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2014 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
 
defined('_JEXEC') or die ;

class DisqusHelper
{
	public static function getTemplatePath($pluginName, $file)
	{
		$mainframe = JFactory::getApplication();
		$p = new stdClass;
		$pluginGroup = 'content';

		if (file_exists(JPATH_SITE.'/templates/'.$mainframe->getTemplate().'/html/'.$pluginName.'/'.$file))
		{
			$p->file = JPATH_SITE.'/templates/'.$mainframe->getTemplate().'/html/'.$pluginName.'/'.$file;
			$p->http = JURI::root(true).'/templates/'.$mainframe->getTemplate().'/html/'.$pluginName.'/'.$file;
		}
		else
		{
			$p->file = JPATH_SITE.'/plugins/'.$pluginGroup.'/'.$pluginName.'/'.$pluginName.'/tmpl/'.$file;
			$p->http = JURI::root(true).'/plugins/'.$pluginGroup.'/'.$pluginName.'/'.$pluginName.'/tmpl/'.$file;
		}
		return $p;
	}
}