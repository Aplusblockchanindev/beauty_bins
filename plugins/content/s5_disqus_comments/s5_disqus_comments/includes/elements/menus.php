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
error_reporting(0);
require_once  realpath(JPATH_ADMINISTRATOR.'/components/com_menus/helpers/menus.php');

class JFormFieldMenus extends JFormField
{

	var $type = 'menus';

	function getInput()
	{
	   
		$groups = array();
		$menus = array();

		$menuType = (string)$this->element['menu_type'];
		$published = $this->element['published'] ? explode(',', (string)$this->element['published']) : array();
		$disable = $this->element['disable'] ? explode(',', (string)$this->element['disable']) : array();

		$items = MenusHelper::getMenuLinks($menuType, 0, 0, $published);

		if ($menuType)
		{
			$groups[$menuType] = array();

			foreach ($items as $link)
			{
				$groups[$menuType][] = JHtml::_('select.option', $link->value, $link->text, 'value', 'text', in_array($link->type, $disable));
			}
		}

		else
		{
			foreach ($items as $menu)
			{
				$groups[$menu->menutype] = array();

				foreach ($menu->links as $link)
				{
					$groups[$menu->menutype][] = JHtml::_('select.option', $link->value, $link->text, 'value', 'text', in_array($link->type, $disable));
				}
			}
		}

		foreach ($groups as $group => $links)
		{
			$menus[] = JHtml::_('select.optgroup', $group);
			foreach ($links as $link)
			{
				$menus[] = $link;
			}
			$menus[] = JHtml::_('select.optgroup', $group);
		}

		$temp = new stdClass;
		@$temp->value = '';
		$temp->text = JText::_('S5_DISQUS_COMMENTS_NO_MENU');

		array_unshift($menus, $temp);

		$temp = new stdClass;
		$temp->value = 'all';
		$temp->text = JText::_('S5_DISQUS_COMMENTS_SELECT_ALL_MENUS');

		array_unshift($menus, $temp);

		$output = JHTML::_('select.genericlist', $menus, $this->name.'[]', 'class="inputbox" style="width:220px;" multiple="multiple" size="12"', 'value', 'text', $this->value);
		return $output;
	}

}
