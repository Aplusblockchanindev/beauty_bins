<?php
/*------------------------------------------------------------------------
# mod_onpageload_popup - Auto onPageLoad Popup
# ------------------------------------------------------------------------
# author    Infyways Solutions
# copyright Copyright (C) 2019 Infyways Solutions. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.infyways.com
# Technical Support:  Forum - http://support.infyways/com
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access');
JFormHelper::loadFieldClass('list');
class JFormFieldGetModule extends JFormFieldList
{
	protected $type = 'GetModule';
	protected function getOptions()
	{

                $app = JFactory::getApplication();
                $modules = $app->input->get('getmodule'); 
                $db = JFactory::getDbo();
                $query = $db->getQuery(true);
                $options = array();
                $query->select('id As value,title As text,module as module');                
                $query->from($db->quoteName('#__modules'));
                $query->where($db->quoteName('client_id')." = ".$db->quote(0));
                $query->order('id ASC');
				$db->setQuery($query);

		try
		{
			$options = $db->loadObjectList();
				foreach($options as $key => $option) {
				if($option->module=="mod_onpageload_popup"){
					unset($options[$key]);
				}	
		
	}
	
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());
		}

        // Put "Select an option" on the top of the list.
		//array_unshift($options, JHtml::_('select.option', '0', JText::_('No Module Selected')));
		
		

		return array_merge(parent::getOptions(), $options);
	}
}
