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

class IngalleryModelGalleries extends JModelList
{

	public function __construct($config = array())
	{
		if (empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
				'a.id', 'a.title', 'a.created_by_name', 'a.created_at', 'a.updated_by_name', 'a.updated_at'
			);

		}

		parent::__construct($config);
	}

	protected function getListQuery()
	{
		$db = $this->getDbo();
		$query = $db->getQuery(true);
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$query->select(
			$this->getState(
				'list.select',
				'a.id, a.title, a.checked_out, a.checked_out_time, a.created_at, a.created_by, a.created_by_name, a.updated_at, a.updated_by, a.updated_by_name'
			)
		);
		$query->from('#__ingallery_gals AS a');

		$query->select('uc.name AS editor')
			->join('LEFT', '#__users AS uc ON uc.id=a.checked_out');

        
		$orderCol = $this->state->get('list.ordering', 'a.id');
		$orderDirn = $this->state->get('list.direction', 'desc');

		$query->order($db->escape($orderCol . ' ' . $orderDirn));

		return $query;
	}

}
