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

class IngalleryController extends JControllerLegacy
{
	
	protected $default_view = 'galleries';

	public function display($cachable = false, $urlparams = array())
	{
		$view   = $this->input->get('view', 'galleries');
		$layout = $this->input->get('layout', 'default');
		$id     = $this->input->getInt('id');

	  	if ($view == 'gallery' && $layout == 'edit' && !$this->checkEditId('com_ingallery.edit.gallery', $id))
		{
			$this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
			$this->setMessage($this->getError(), 'error');
			$this->setRedirect(JRoute::_('index.php?option=com_ingallery&view=galleries', false));

			return false;
		}

		return parent::display();
	}
}