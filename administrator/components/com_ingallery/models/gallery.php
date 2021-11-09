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

class IngalleryModelGallery extends JModelAdmin
{
	protected $text_prefix = 'COM_INGALLERY';

	public $typeAlias = 'com_ingallery.gallery';

	protected $associationsContext = 'com_ingallery.item';

	
	protected function canDelete($record)
	{
		if (!empty($record->id))
		{
			$user = JFactory::getUser();

			return $user->authorise('core.delete', 'com_ingallery');
		}

		return false;
	}

	public function getTable($type = 'Galleries', $prefix = 'IngalleryTable', $config = array())
	{
		return JTable::getInstance($type, $prefix, $config);
	}

	
	public function getForm($data = array(), $loadData = true)
	{
		$form = $this->loadForm('com_ingallery.gallery', 'gallery', array('control' => 'jform', 'load_data' => $loadData));

		if (empty($form))
		{
			return false;
		}

		return $form;
	}

	protected function loadFormData()
	{
		$app = JFactory::getApplication();
		$data = $app->getUserState('com_ingallery.edit.gallery.data', array());

		if (empty($data)){
			$data = $this->getItem();
		}

		$this->preprocessData('com_ingallery.gallery', $data);

		return $data;
	}


	public function save($data)
	{
		$input  = JFactory::getApplication()->input;
		$filter = JFilterInput::getInstance();
        
        $ignores = array(
            'created_at',
            'created_by',
            'created_by_name',
            'updated_at',
            'updated_by',
            'updated_by_name',
        );
        foreach($ignores as $ignore){
            if (isset($data[$ignore])){
                unset($data[$ignore]);
            }
        }

		return parent::save($data);
	}

	protected function cleanCache($group = null, $client_id = 0)
	{
        //$cache = IngalleryModel::getCache();
		//$cache->clean();
        
		//parent::cleanCache();
	}

}
