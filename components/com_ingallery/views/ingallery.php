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

class IngalleryView {

    private $_tmpl;
    private $_data = array();
    private $_config = null;

    public function __construct($tmpl = null) {
        if ($tmpl) {
            $this->setTemplate($tmpl);
        }
    }

    public function get($name, $default = null) {
        if (isset($this->_data[$name])) {
            return $this->_data[$name];
        }
        return $default;
    }

    public function set($name, $value) {
        $this->_data[$name] = $value;
    }

    public function setTemplate($tmpl) {
        $this->_tmpl = $tmpl;
    }

    public function getTemplate() {
        return $this->_tmpl;
    }

    protected function getTemplatePath($tmpl) {
        $app = JFactory::getApplication();
        $overridePath = JPATH_ROOT . '/templates/' . $app->getTemplate() . '/html/com_ingallery/' . $tmpl . '.php';
        if (is_file($overridePath) && is_readable($overridePath)) {
            return $overridePath;
        }
        return __DIR__ . '/tmpl/' . $tmpl . '.php';
    }

    public function loadTemplate($tmpl) {
        $path = $this->getTemplatePath($tmpl);
        if (!is_file($path) || !is_readable($path)) {
            throw new Exception(sprintf(JText::_('COM_INGALLERY_TMPL_FOR_VIEW_NOT_FOUND'), $path, get_class($this)));
        }
        ob_start();
        include($path);
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }

    public function render() {
        return $this->loadTemplate($this->getTemplate());
    }
    
    public function getConfig(){
        if(is_null($this->_config)){
            $this->_config = JComponentHelper::getParams('com_ingallery');
        }
        return $this->_config;
    }
    
    public function getPreviewURL($galleryID, $pictureID, $type){
        $cacheFile = 'media/com_ingallery/cache/'.$type.'_'.$pictureID.'.jpg';
        $config = $this->getConfig();
        if(is_file(JPATH_ROOT.'/'.$cacheFile)){
            switch($config->get('ajax_url_mode','router')){
                case 'relative':
                    $rootURI = JUri::root(true).'/';
                break;
                case 'absolute':
                default:
                    $rootURI = JUri::root();
                break;
            }
            return $rootURI.$cacheFile;
        }
        $siteConfig = JFactory::getConfig();
        $sign = md5($siteConfig->get('secret').':'.$pictureID);
        $url = 'index.php?option=com_ingallery&task=picture.show&galleryID='.$galleryID.'&pictureID='.$pictureID.'&type='.$type.'&sign='.$sign;
        switch($config->get('ajax_url_mode','router')){
            case 'relative':
                $url = JUri::root(true).'/'.$url;
            break;
            case 'router':
                $url = JRoute::link('site', $url, false);
            break;
            case 'absolute':
            default:
                $url = JUri::root().$url;
            break;
        }
        return $url;
    }
    
    public function getVideoURL($galleryID, $pictureID){
        $cacheFile = 'media/com_ingallery/cache/'.$pictureID.'.mp4';
        $config = $this->getConfig();
        if(is_file(JPATH_ROOT.'/'.$cacheFile)){
            switch($config->get('ajax_url_mode','router')){
                case 'relative':
                    $rootURI = JUri::root(true).'/';
                break;
                case 'absolute':
                default:
                    $rootURI = JUri::root();
                break;
            }
            return $rootURI.$cacheFile;
        }
        $siteConfig = JFactory::getConfig();
        $sign = md5($siteConfig->get('secret').':'.$pictureID);
        $url = 'index.php?option=com_ingallery&task=video.show&galleryID='.$galleryID.'&pictureID='.$pictureID.'&sign='.$sign;
        switch($config->get('ajax_url_mode','router')){
            case 'relative':
                $url = JUri::root(true).'/'.$url;
            break;
            case 'router':
                $url = JRoute::link('site', $url, false);
            break;
            case 'absolute':
            default:
                $url = JUri::root().$url;
            break;
        }
        return $url;
    }

    public function getPreviewItem($item, $galleryID) {
        $config = $this->getConfig();
        $isDownload = $config->get('previews_display_mode','download')=='download';
        
        $result = array(
            'id' => $item['id'],
            'code' => $item['code'],
            'date' => $item['date'],
            'time_passed' => self::getTimePassed($item['date']),
            'full_date' => date('d F Y', $item['date']),
            'date_iso' => date('c', $item['date']),
            'likes' => $item['likes']['count'],
            'comments' => $item['comments']['count'],
            'video_url' => $item['video_url'],
            'owner_id' => $item['owner']['id'],
            'owner_username' => isset($item['owner']['username']) ? $item['owner']['username'] : '',
            'owner_name' => isset($item['owner']['name']) ? $item['owner']['name'] : '',
            'owner_pic_url' => isset($item['owner']['picture']) ? $item['owner']['picture'] : '',
            'is_video' => $item['is_video'],
            'thumbnail_src' => $item['thumbnail_src'],
            'display_src' => $item['display_src'],
            'full_width' => $item['dimensions']['width'],
            'full_height' => $item['dimensions']['height'],
            'ratio' => round((int) $item['dimensions']['width'] / (int) $item['dimensions']['height'], 5),
            'code' => $item['code'],
            'caption' => self::getMediaDescription($item['caption']),
            'subgallery' => array()
        );
        if ($result['owner_name'] == '' && isset($item['owner']['full_name'])) {
            $result['owner_name'] = $item['owner']['full_name'];
        }
        if ($result['owner_pic_url'] == '' && isset($item['owner']['profile_pic_url'])) {
            $result['owner_pic_url'] = $item['owner']['profile_pic_url'];
        }
        
        if($isDownload){
            $result['owner_pic_url'] = $this->getPreviewURL($galleryID, $result['owner_username'], 'user');
            $result['thumbnail_src'] = $this->getPreviewURL($galleryID, $result['id'], 'thumbnail');
            $result['display_src'] = $this->getPreviewURL($galleryID, $result['id'], 'picture');
            if($result['is_video']){
                $result['video_url'] = $this->getVideoURL($galleryID, $result['id']);
            }
        }

        foreach ($item['subgallery'] as $subitem) {
            $subitem['ratio'] = round((int) $subitem['width'] / (int) $subitem['height'], 5);
            if($isDownload){
                $subitem['src'] = $this->getPreviewURL($galleryID, $subitem['id'], 'picture');
                if($subitem['is_video']){
                    $subitem['video_url'] = $this->getVideoURL($galleryID, $subitem['id']);
                }
            }
            $result['subgallery'][] = $subitem;
        }
        
        return $result;
    }

    static function getTimePassed($time) {
        $time = max(time() - $time, 1);
        $tokens = array(
            31536000 => JText::_('COM_INGALLERY_YEAR'),
            2592000 => JText::_('COM_INGALLERY_MONTH'),
            604800 => JText::_('COM_INGALLERY_WEEK'),
            86400 => JText::_('COM_INGALLERY_DAY'),
            3600 => JText::_('COM_INGALLERY_HOUR'),
            60 => JText::_('COM_INGALLERY_MINUTE'),
            1 => JText::_('COM_INGALLERY_SECOND')
        );
        $tokensPL = array(
            31536000 => JText::_('COM_INGALLERY_YEARS'),
            2592000 => JText::_('COM_INGALLERY_MONTHS'),
            604800 => JText::_('COM_INGALLERY_WEEKS'),
            86400 => JText::_('COM_INGALLERY_DAYS'),
            3600 => JText::_('COM_INGALLERY_HOURS'),
            60 => JText::_('COM_INGALLERY_MINUTES'),
            1 => JText::_('COM_INGALLERY_SECONDS')
        );
        $tokensPL2 = array(
            31536000 => JText::_('COM_INGALLERY_YEARS2'),
            2592000 => JText::_('COM_INGALLERY_MONTHS2'),
            604800 => JText::_('COM_INGALLERY_WEEKS2'),
            86400 => JText::_('COM_INGALLERY_DAYS2'),
            3600 => JText::_('COM_INGALLERY_HOURS2'),
            60 => JText::_('COM_INGALLERY_MINUTES2'),
            1 => JText::_('COM_INGALLERY_SECONDS2')
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit){
                continue;
            }
            $numberOfUnits = floor($time / $unit);
            $pl_sg = utf8_substr($numberOfUnits, -1);
            if ($pl_sg == 1) {
                $str = $tokens[$unit];
            } else if ($pl_sg > 1 && $pl_sg < 5) {
                $str = $tokensPL[$unit];
            } else {
                $str = $tokensPL2[$unit];
            }
            return $numberOfUnits . ' ' . $str . ' ' . JText::_('COM_INGALLERY_AGO');
        }
    }

    static function getMonthShortName($monthNum) {
        $monthes = array(
            0 => '',
            1 => JText::_('COM_INGALLERY_JAN'),
            2 => JText::_('COM_INGALLERY_FEB'),
            3 => JText::_('COM_INGALLERY_MAR'),
            4 => JText::_('COM_INGALLERY_APR'),
            5 => JText::_('COM_INGALLERY_MAY'),
            6 => JText::_('COM_INGALLERY_JUN'),
            7 => JText::_('COM_INGALLERY_JUL'),
            8 => JText::_('COM_INGALLERY_AUG'),
            9 => JText::_('COM_INGALLERY_SEP'),
            10 => JText::_('COM_INGALLERY_OCT'),
            11 => JText::_('COM_INGALLERY_NOV'),
            12 => JText::_('COM_INGALLERY_DEC'),
        );
        return $monthes[(int) $monthNum];
    }

    static function getMediaDescription($str) {
        $str = preg_replace('~\n~', '<br>', $str);
        $result = preg_replace_callback('~((?:\@[a-z0-9_\-\.]+)|(?:\#\w+))~u', function($mchs) { //~((?:\@[a-z0-9_\-\.]+)|(?:\#\w+)|(?:https?\:\/\/[^\s]+)|(?:www\.[^\s]+))~u
            $type = utf8_substr($mchs[1], 0, 1);
            $value = utf8_substr($mchs[1], 1);
            if ($type == '@') {
                return '<a href="https://www.instagram.com/' . $value . '/" target="_blank" rel="nofollow">' . $mchs[1] . '</a>';
            } else if ($type == '#') {
                return '<a href="https://www.instagram.com/explore/tags/' . $value . '/" target="_blank" rel="nofollow">' . $mchs[1] . '</a>';
            } else if ($type == 'h') {
                return '<a href="' . $mchs[1] . '" target="_blank" rel="nofollow">' . $mchs[1] . '</a>';
            } else if ($type == 'w') {
                return '<a href="http://' . $mchs[1] . '" target="_blank" rel="nofollow">' . $mchs[1] . '</a>';
            }
        }, $str);
        $result = preg_replace('~\n~', '<br>', $result);
        return $result;
    }

}
