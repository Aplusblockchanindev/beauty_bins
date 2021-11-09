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
defined('_JEXEC') or die(':)');


class IngalleryViewGallery extends JViewLegacy
{
	protected $form;

	protected $item;

	protected $state;

	protected $assoc;


	public function display($tpl = null)
	{
        
		$this->form  = $this->get('Form');
		$this->item  = $this->get('Item');
		$this->state = $this->get('State');
		$this->canDo = JHelperContent::getActions('com_ingallery');
		$this->assoc = $this->get('Assoc');
        $input = JFactory::getApplication()->input;
        $id = (int)$input->getInt('id');
        
        $this->gallery = new IngalleryModel();
        
        
		if(count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}
        
        if((int)$this->item->id>0){
            if(!$this->gallery->setConfig($this->item->config)){
                JError::raiseError(500, $this->gallery->getError());
                return false;
            }
        }

		$input->set('hidemainmenu', true);
		$this->addToolbar();
		parent::display($tpl);
	}

	protected function addToolbar()
	{
		$user       = JFactory::getUser();
		$userId     = $user->get('id');
		$isNew      = ($this->item->id == 0);
		$checkedOut = !($this->item->checked_out == 0 || $this->item->checked_out == $userId);

		// Need to load the menu language file as mod_menu hasn't been loaded yet.
		$lang = JFactory::getLanguage();
		$lang->load('com_ingallery', JPATH_BASE, null, false, true)
		|| $lang->load('com_ingallery', JPATH_ADMINISTRATOR . '/components/com_ingallery', null, false, true);

		// Get the results for each action.
		$canDo = $this->canDo;
		$title = JText::_('COM_INGALLERY_BASE_' . ($isNew ? 'ADD' : 'EDIT') . '_TITLE');

		/**
		 * Prepare the toolbar.
		 * If it is new we get: `tag tag-add add`
		 * else we get `tag tag-edit edit`
		 */
		JToolbarHelper::title($title, 'ingallery-bar ingallery-bar-' . ($isNew ? 'add add' : 'edit edit'));

		// For new records, check the create permission.
		if ($isNew)
		{
			JToolbarHelper::apply('gallery.apply');
			JToolbarHelper::save('gallery.save');
			JToolbarHelper::cancel('gallery.cancel');
		}
		else
		{
			// Since it's an existing record, check the edit permission, or fall back to edit own if the owner.
			$itemEditable = $canDo->get('core.edit') || ($canDo->get('core.edit.own') && $this->item->created_by == $userId);

			// Can't save the record if it's checked out and editable
			if (!$checkedOut && $itemEditable)
			{
				JToolbarHelper::apply('gallery.apply');
				JToolbarHelper::save('gallery.save');
			}


			if ($this->state->params->get('save_history', 0) && $itemEditable)
			{
				JToolbarHelper::versions('com_ingallery.gallery', $this->item->id);
			}

			JToolbarHelper::cancel('gallery.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
