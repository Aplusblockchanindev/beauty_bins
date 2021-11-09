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

class IngalleryTableGalleries extends JTable{

	function __construct(&$db){
		parent::__construct('#__ingallery_gals', 'id', $db);
	}
	
	function check(){
		$this->title = trim($this->title);
		if($this->title==''){
			$this->setError(JText::_('COM_INGALLERY_SET_GALLERY_TITLE'));
			return false;
		}
		return parent::check();
	}
    
    public function store($updateNulls = false){
        $user = JFactory::getUser();
        $db = $this->getDbo();
        if((int)$this->id==0){
            $this->created_at = JFactory::getDate()->toSql();
            $this->updated_at = $db->getNullDate();
            $this->created_by = $user->get('id');
            $this->created_by_name = $user->get('name').' ('.$user->get('username').')';
            $this->updated_at = '0000-00-00 00:00:00';
            $this->updated_by = 0;
            $this->updated_by_name = '';
        }
        else{
            $this->updated_at = JFactory::getDate()->toSql();
            $this->updated_by = $user->get('id');
            $this->updated_by_name = $user->get('name').' ('.$user->get('username').')';
        }
        
        return parent::store($updateNulls);
    }

}