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
 
defined('_JEXEC') or die;
error_reporting(0);
class JFormFieldCategories extends JFormField
{

	var $type = 'categories';

	function getInput()
	{
		$categories = array();
		$categories = JHtml::_('category.options', 'com_content');

		$option = new JObject;
		$option->value = '';
		$option->text = JText::_('S5_DISQUS_COMMENTS_NO_CATEGORY');
		array_unshift($categories, $option);

		$option = new JObject;
		$option->value = 'all';
		$option->text = JText::_('S5_DISQUS_COMMENTS_SELECT_ALL_CATEGORIES');
		array_unshift($categories, $option);

		return JHTML::_('select.genericlist', $categories, $this->name.'[]', 'class="inputbox" style="width:220px;" multiple="multiple" size="12"', 'value', 'text', $this->value);
	}

}
