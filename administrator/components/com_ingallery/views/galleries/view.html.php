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

class IngalleryViewGalleries extends JViewLegacy
{
	protected $items;

	protected $pagination;

	protected $state;

	public function display($tpl = null)
	{
		$this->items         = $this->get('Items');
		$this->pagination    = $this->get('Pagination');
		$this->state         = $this->get('State');
        $this->filterForm    = $this->get('FilterForm');
		$this->activeFilters = $this->get('ActiveFilters');

		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode("\n", $errors));

			return false;
		}

		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			//$this->sidebar = JHtmlSidebar::render();
		}

		parent::display($tpl);
	}

	protected function addToolbar()
	{
		$canDo = JHelperContent::getActions('com_ingallery');
		$user  = JFactory::getUser();

		$bar = JToolbar::getInstance('toolbar');

		JToolbarHelper::title(JText::_('COM_INGALLERY_GALLERIES'), 'stack article');

		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_ingallery', 'core.create'))) > 0)
		{
			JToolbarHelper::addNew('gallery.add');
            JToolbarHelper::custom('gallery.copy', 'copy', '', 'COM_INGALLERY_DUPLICATE', true);
		}

		if ($canDo->get('core.delete'))
		{
			JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'galleries.delete');
		}

		if ($user->authorise('core.admin', 'com_ingallery') || $user->authorise('core.options', 'com_ingallery'))
		{
			JToolbarHelper::preferences('com_ingallery');
		}

	}

	protected function getSortFields()
	{
		return array(
			'a.ordering'     => JText::_('JGRID_HEADING_ORDERING'),
			'a.state'        => JText::_('JSTATUS'),
			'a.title'        => JText::_('JGLOBAL_TITLE'),
			'category_title' => JText::_('JCATEGORY'),
			'access_level'   => JText::_('JGRID_HEADING_ACCESS'),
			'a.created_by'   => JText::_('JAUTHOR'),
			'language'       => JText::_('JGRID_HEADING_LANGUAGE'),
			'a.created'      => JText::_('JDATE'),
			'a.id'           => JText::_('JGRID_HEADING_ID'),
			'a.featured'     => JText::_('JFEATURED')
		);
	}
}
