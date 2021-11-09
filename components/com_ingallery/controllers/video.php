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

use Joomla\Registry\Registry;

class IngalleryControllerVideo extends JControllerLegacy {

    public function show() {
        $app = JFactory::getApplication();
        $config = JComponentHelper::getParams('com_ingallery');
        $siteConfig = JFactory::getConfig();
        
        try {
            $galleryID = (int)$this->input->getInt('galleryID', 0);
            $pictureID = $this->input->getVar('pictureID', '');
            $sign = $this->input->getVar('sign', '');
            
            if(!preg_match('~^[a-z\-_0-9\.]+$~i', $pictureID) || !preg_match('~^[a-z0-9]+$~i', $sign)){
                throw new Exception(JText::_('COM_INGALLERY_WRONG_GALLERY_FORMAT'));
            }
            if($sign!=md5($siteConfig->get('secret').':'.$pictureID)){
                throw new Exception('Wrong sign');
            }
            
            $table = JTable::getInstance('Galleries', 'IngalleryTable');
            $gallery = new IngalleryModel();
            $table->load($galleryID);
            if ((int) $table->id <= 0 || (int) $table->id != $galleryID) {
                $cache = $gallery::getCache(999);
                $json = $cache->get('tmp_config_'.$galleryID);
                if($json===false){
                    throw new Exception(sprintf(JText::_('COM_INGALLERY_GALLERY_NOT_FOUND'), $galleryID));
                }
            }
            else{
                $json = $table->config;
            }
            if (!$gallery->setConfig($json)) {
                throw new Exception($gallery->getError());
            }
            
            foreach($gallery->getItems() as $item){
                if($item['id']==$pictureID){
                    return $this->_displayVideo($item);
                }
                if(count($item['subgallery'])){
                    foreach($item['subgallery'] as $subItem){
                        if($subItem['id']==$pictureID){
                            return $this->_displayVideo($subItem);
                        }
                    }
                }
            }
            
            echo 'Video Not Found.';
            
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        
        $app->close();
    }
    
    private function _displayVideo($item){
        $app = JFactory::getApplication();
        $cacheFile = 'media/com_ingallery/cache/'.$item['id'].'.mp4';
        
        if(!is_file(JPATH_ROOT.'/'.$cacheFile)){
            $httpOption = new Registry;
            $httpOption->set('userAgent', 'Instagram');
            if (class_exists('\Joomla\CMS\Http\HttpFactory')) {
                $http = \Joomla\CMS\Http\HttpFactory::getHttp($httpOption);
            } else if (class_exists('JHttpFactory')) {
                $http = JHttpFactory::getHttp($httpOption);
            } else {
                $http = HttpFactory::getHttp($httpOption);
            }
            $response = $http->get($item['video_url'], [], 20);
            if((int)$response->code!=200){
                var_dump($response);
                $app->close();
            };
            file_put_contents(JPATH_ROOT.'/'.$cacheFile, $response->body);
        }
        
        header('Content-Type: video/mp4', true);
        readfile(JPATH_ROOT.'/'.$cacheFile);
        
        $app->close();
    }

}
