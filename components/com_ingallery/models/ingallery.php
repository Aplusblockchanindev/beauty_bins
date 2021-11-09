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

class IngalleryModel {

    const CACHE_KEY = 'com_ingallery';
    const IG_LOCK_CACHE_KEY = 'IGRequestsLock';
    const IG_ERROR_CACHE_KEY = 'IGError';
    const MATCH_IG_USER = '~^\@([a-zA-Z0-9_\-\.]+)$~';
    const MATCH_IG_HASHTAG = '~^\#(\w+)$~u';
    const MATCH_IG_MEDIA_CODE_CLASS = '[a-zA-Z0-9_\-]+';
    const MATCH_IG_LOCATION_URI = '~^https\://www\.instagram\.com/explore/locations/([0-9]+)~i';

    private static $_lastRequestTime = 0;
    private $_data;
    private $_validParamNames = array(
        'layout_type',
        'layout_cols',
        'layout_rows',
        'layout_gutter',
        'layout_show_albums',
        'layout_show_loadmore',
        'layout_loadmore_text',
        'display_style',
        'display_link_mode',
        'display_thumbs_header',
        'display_thumbs_date',
        'display_thumbs_likes',
        'display_thumbs_comments',
        'display_thumbs_instalink',
        'display_thumbs_plays',
        'display_popup_likes',
        'display_popup_comments',
        'display_popup_comments_list',
        'display_popup_description',
        'display_popup_username',
        'display_popup_instagram_link',
        'display_popup_date',
        'display_popup_user',
        'display_popup_plays',
        'display_popup_img_size',
        'display_popup_loop_video',
        'display_thumbs_description',
        'colors_gallery_bg',
        'colors_album_btn_bg',
        'colors_album_btn_text',
        'colors_album_btn_hover_bg',
        'colors_album_btn_hover_text',
        'colors_more_btn_bg',
        'colors_more_btn_text',
        'colors_more_btn_hover_bg',
        'colors_more_btn_hover_text',
        'colors_thumb_overlay_bg',
        'colors_thumb_overlay_text',
        'layout_infinite_scroll',
        'layout_autoscroll',
        'layout_autoscroll_speed',
        'layout_preloader_img',
        'layout_responsive'
    );
    private $_checkboxes = array(
        'layout_show_albums',
        'layout_show_loadmore',
        'layout_infinite_scroll',
        'layout_rtl',
        'layout_autoscroll',
        'display_thumbs_header',
        'display_thumbs_date',
        'display_thumbs_likes',
        'display_thumbs_comments',
        'display_thumbs_instalink',
        'display_thumbs_plays',
        'display_thumbs_description',
        'display_popup_user',
        'display_popup_instagram_link',
        'display_popup_likes',
        'display_popup_comments',
        'display_popup_plays',
        'display_popup_date',
        'display_popup_description',
        'display_popup_comments_list',
        'display_popup_loop_video',
    );
    private $_items = array();
    private $_error = '';
    private static $_cookie = array();

    public function __construct($cfg = null) {
        $this->_data = array(
            'albums' => array(
                array(
                    'id' => 'someid',
                    'name' => JText::_('COM_INGALLERY_NEW_ALBUM') . ' #1',
                    'sources' => array(),
                    'filters' => array(
                        'except' => array(),
                        'only' => array(),
                    ),
                    'limit_items' => 20,
                    'cache_lifetime' => 36000
                )
            ),
            'cfg' => array(
                'layout_type' => 'grid',
                'layout_cols' => 3,
                'layout_rows' => 4,
                'layout_gutter' => '30',
                'layout_responsive' => array(),
                'layout_show_albums' => 1,
                'layout_show_loadmore' => 1,
                'layout_loadmore_text' => JText::_('COM_INGALLERY_LOAD_MORE'),
                'layout_preloader_img' => '',
                'layout_infinite_scroll' => 0,
                'layout_rtl' => 0,
                'layout_autoscroll' => 0,
                'layout_autoscroll_speed' => 2,
                'display_style' => 'default',
                'display_link_mode' => 'popup',
                'display_popup_img_size' => 'try_to_fit',
                'display_thumbs_header' => 1,
                'display_thumbs_date' => 1,
                'display_thumbs_likes' => 1,
                'display_thumbs_comments' => 1,
                'display_thumbs_instalink' => 1,
                'display_thumbs_plays' => 1,
                'display_thumbs_description' => 1,
                'display_popup_user' => 1,
                'display_popup_instagram_link' => 1,
                'display_popup_likes' => 1,
                'display_popup_comments' => 1,
                'display_popup_plays' => 1,
                'display_popup_date' => 1,
                'display_popup_description' => 1,
                'display_popup_comments_list' => 1,
                'display_popup_loop_video' => 0,
                'colors_gallery_bg' => 'rgba(255,255,255,0)',
                'colors_album_btn_bg' => 'rgba(255,255,255,1)',
                'colors_album_btn_text' => 'rgba(0,185,255,1)',
                'colors_album_btn_hover_bg' => 'rgba(0,185,255,1)',
                'colors_album_btn_hover_text' => 'rgba(255,255,255,1)',
                'colors_more_btn_bg' => 'rgba(214,103,205,1)',
                'colors_more_btn_text' => 'rgba(255,255,255,1)',
                'colors_more_btn_hover_bg' => 'rgba(255,255,255,1)',
                'colors_more_btn_hover_text' => 'rgba(214,103,205,1)',
                'colors_thumb_overlay_bg' => 'rgba(0,0,0,0.5)',
                'colors_thumb_overlay_text' => 'rgba(255,255,255,1)',
            ),
        );
        if (!is_null($cfg)) {
            $this->setConfig($cfg);
        }
    }

    public function setConfig($cfgString) {
        $cfg = json_decode($cfgString, true);
        if ($cfg === false) {
            $this->_error = JText::_('COM_INGALLERY_WRONG_GALLERY_FORMAT');
            return false;
        }
        return $this->bindInput($cfg);
    }

    public function getAlbumDefaults() {
        return array(
            'id' => 'someid',
            'name' => JText::_('COM_INGALLERY_NEW_ALBUM') . ' #1',
            'sources' => array(),
            'filters' => array(
                'except' => array(),
                'only' => array(),
            ),
            'limit_items' => 0,
            'cache_lifetime' => 36000
        );
    }

    public function getItems($start = 0, $limit = 0) {
        $this->loadItems();
        if ($start == 0 && $limit == 0) {
            return $this->_items;
        } else if ($start > 0 && $limit == 0) {
            return array_slice($this->_items, $start);
        } else {
            return array_slice($this->_items, $start, $limit);
        }
    }

    public function countItems() {
        $this->loadItems();
        return count($this->_items);
    }

    public function loadItems() {
        if (count($this->_items)) {
            return false;
        }
        $items = array();
        foreach ($this->get('albums') as $album) {
            $aItems = array();
            foreach ($album['sources'] as $source) {
                $aItems = array_merge($aItems, self::getMediaListFromSource($source, $album));
            }
            $aItems = self::filterMedia($aItems, $album['filters']);
            usort($aItems, 'self::orderMedia');
            if ($album['limit_items'] > 0 && count($aItems) > $album['limit_items']) {
                $aItems = array_slice($aItems, 0, $album['limit_items']);
            }
            $items = array_merge($items, $aItems);
        }
        unset($aItems);
        $this->_items = $items;
        unset($items);
        usort($this->_items, 'self::orderMedia');
        return true;
    }

    public function hasItemsPage($pageNum) {
        $limit = (int) $this->get('cfg/layout_cols') * (int) $this->get('cfg/layout_rows');
        $pageMultiplier = max(0, ($pageNum - 1));
        $amount = $pageMultiplier * $limit;
        return $this->countItems() > $amount;
    }

    public function getItemsPage($pageNum) {
        if ($this->get('cfg/layout_type') == 'masonry') {
            $limit = ((int) $this->get('cfg/layout_cols') + 1) * 2 - 3;
            $limit *= (int) $this->get('cfg/layout_rows');
        } else {
            $limit = (int) $this->get('cfg/layout_cols') * (int) $this->get('cfg/layout_rows');
        }
        $start = ($pageNum - 1) * $limit;
        $this->loadItems();
        return $this->getItems($start, $limit);
    }

    public function get($name, $default = null) {
        if (isset($this->_data[$name])) {
            return $this->_data[$name];
        } else if (utf8_strpos($name, '/') !== false) {
            $parts = explode('/', $name);
            $result = $this->_data;
            foreach ($parts as $part) {
                if (isset($result[$part])) {
                    $result = $result[$part];
                } else {
                    $result = $default;
                    break;
                }
            }
            return $result;
        }
        return $default;
    }

    public function set($name, $val) {
        if (utf8_strpos($name, '/') !== false) {
            $parts = explode('/', $name);
            $lastPart = count($parts) - 1;
            $pointer = &$this->_data;
            foreach ($parts as $k => $part) {
                if ($k == $lastPart) {
                    $pointer[$part] = $val;
                    break;
                }
                if (!isset($pointer[$part]) || !is_array($pointer[$part])) {
                    $pointer[$part] = array();
                }
                $pointer = &$pointer[$part];
            }
            unset($pointer);
        } else {
            $this->_data[$name] = $val;
        }
    }

    public function getJSON() {
        $obj = $this->_data;
        foreach ($obj['albums'] as $k => $v) {
            $obj['albums'][$k]['sources'] = implode(',', $obj['albums'][$k]['sources']);
            $obj['albums'][$k]['filters']['except'] = implode(',', $obj['albums'][$k]['filters']['except']);
            $obj['albums'][$k]['filters']['only'] = implode(',', $obj['albums'][$k]['filters']['only']);
        }
        if (!isset($obj['cfg']['layout_responsive'])) {
            $obj['cfg']['layout_responsive'] = array();
        }
        return json_encode($obj, JSON_HEX_APOS);
    }

    public function bindInput($input) {
        if (isset($input['albums']) && is_array($input['albums'])) {
            $this->_data['albums'] = array();
            foreach ($input['albums'] as $inputAlbum) {
                $album = array(
                    'name' => '',
                    'sources' => array(),
                    'filters' => array(
                        'except' => array(),
                        'only' => array(),
                    ),
                    'limit_items' => 0,
                    'cache_lifetime' => 36000
                );
                if (isset($inputAlbum['name'])) {
                    $album['name'] = trim($inputAlbum['name']);
                }
                if (isset($inputAlbum['sources'])) {
                    $album['sources'] = self::sourcesToArray($inputAlbum['sources']);
                }
                if (isset($inputAlbum['filters'])) {
                    if (isset($inputAlbum['filters']['only'])) {
                        $album['filters']['only'] = self::sourcesToArray($inputAlbum['filters']['only'], array('videos'));
                    }
                    if (isset($inputAlbum['filters']['except'])) {
                        $album['filters']['except'] = self::sourcesToArray($inputAlbum['filters']['except'], array('videos'));
                    }
                }
                if (isset($inputAlbum['limit_items'])) {
                    $album['limit_items'] = abs((int) $inputAlbum['limit_items']);
                }
                if (isset($inputAlbum['cache_lifetime'])) {
                    $album['cache_lifetime'] = abs((int) $inputAlbum['cache_lifetime']);
                }
                $album['id'] = md5(json_encode($album));
                $this->_data['albums'][] = $album;
            }
        }

        if (isset($input['cfg']) && is_array($input['cfg'])) {
            foreach ($this->_validParamNames as $paramName) {
                if (!isset($input['cfg'][$paramName])) {
                    if (in_array($paramName, $this->_checkboxes)) {
                        $input['cfg'][$paramName] = 0;
                    } else {
                        continue;
                    }
                }
                if (isset($input['cfg'][$paramName]) && !is_array($input['cfg'][$paramName])) {
                    $this->_data['cfg'][$paramName] = $input['cfg'][$paramName];
                } else if ($paramName == 'layout_preloader_img') {
                    $this->_data['cfg'][$paramName] = '';
                } else if ($paramName == 'layout_responsive') {
                    $this->_data['cfg'][$paramName] = array();
                    if (isset($input['cfg'][$paramName]['width']) && is_array($input['cfg'][$paramName]['width'])) {
                        foreach ($input['cfg'][$paramName]['width'] as $k => $w) {
                            $cols = (int) $input['cfg'][$paramName]['cols'][$k];
                            //$rows = (int)$input['cfg'][$paramName]['rows'][$k];
                            $gutter = (int) $input['cfg'][$paramName]['gutter'][$k];
                            if ((int) $w == 0 || $cols == 0 /* || $rows==0 */) {
                                continue;
                            }
                            $this->_data['cfg'][$paramName][(int) $w] = array(
                                'cols' => $cols,
                                'rows' => 0,
                                'gutter' => $gutter
                            );
                        }
                    } else if (isset($input['cfg'][$paramName]) && is_array($input['cfg'][$paramName])) {
                        foreach ($input['cfg'][$paramName] as $w => $pv) {
                            $cols = (int) $pv['cols'];
                            //$rows = (int)$pv['rows'];
                            $gutter = (int) $pv['gutter'];
                            if ((int) $w == 0 || $cols == 0 /* || $rows==0 */) {
                                continue;
                            }
                            $this->_data['cfg'][$paramName][$w] = array(
                                'cols' => $cols,
                                'rows' => 0,
                                'gutter' => $gutter
                            );
                        }
                    }
                    ksort($this->_data['cfg'][$paramName]);
                    $this->_data['cfg'][$paramName] = array_reverse($this->_data['cfg'][$paramName], true);
                } else {
                    $this->_data['cfg'][$paramName] = 0;
                }
            }
        }
        return true;
    }

    public function check() {
        if (!is_array($this->get('albums')) || count($this->get('albums')) == 0) {
            $this->_error = JText::_('COM_INGALLERY_YOU_NEED_TO_CREATE_AT_LEAST_ONE_ALBUM');
            return false;
        }
        foreach ($this->get('albums') as $album) {
            if (trim($album['name']) == '') {
                $this->_error = JText::_('COM_INGALLERY_EVERY_ALBUM_MUST_HAVE_A_NAME');
                return false;
            } else if (count($album['sources']) == 0) {
                $this->_error = JText::_('COM_INGALLERY_EVERY_ALBUM_MUST_HAVE_AT_LEAST_ONE_SOURCE');
                return false;
            }
        }
        return true;
    }

    public function getError() {
        return $this->_error;
    }

    public static function sourcesToArray($str, $add = array()) {
        $result = array();
        if (is_string($str)) {
            $parts = explode(',', $str);
            foreach ($parts as $part) {
                $part = trim($part);
                preg_match('~^(https\:\/\/www\.instagram\.com\/p\/' . self::MATCH_IG_MEDIA_CODE_CLASS . ')~', $part, $img);
                preg_match(self::MATCH_IG_LOCATION_URI, $part, $location);
                if (preg_match(self::MATCH_IG_USER, $part)) {
                    $result[] = strtolower($part);
                } else if (preg_match(self::MATCH_IG_HASHTAG, $part)) {
                    $result[] = $part;
                } else if (is_array($img) && isset($img[1])) {
                    $result[] = $img[1];
                } else if (is_array($location) && isset($location[1])) {
                    $result[] = $part;
                } else if (in_array($part, $add)) {
                    $result[] = $part;
                }
            }
        }
        return array_unique($result);
    }

    public static function getSourceData($source) {
        if (preg_match(self::MATCH_IG_USER, $source)) {
            return array(
                'type' => 'user',
                'value' => utf8_substr($source, 1),
            );
        } else if (preg_match(self::MATCH_IG_HASHTAG, $source)) {
            return array(
                'type' => 'hashtag',
                'value' => utf8_substr($source, 1),
            );
        } else if (preg_match(self::MATCH_IG_LOCATION_URI, $source)) {
            preg_match(self::MATCH_IG_LOCATION_URI, $source, $match);
            return array(
                'type' => 'location',
                'value' => $match[1],
            );
        } else {
            preg_match('~^https\:\/\/www\.instagram\.com\/p\/(' . self::MATCH_IG_MEDIA_CODE_CLASS . ')~', $source, $img);
            if (is_array($img) && isset($img[1])) {
                return array(
                    'type' => 'picture',
                    'value' => $img[1],
                );
            }
        }
        return false;
    }

    public static function loadHashtagData($_hashtag, $cacheLifetime = 36000) {
        return self::getApiData('HashtagData', $_hashtag);
    }
    
    public static function loadUserData($username, $cacheLifetime = 36000) {       
        return self::getApiData('UserData', $username);
    }
    
    public static function loadItemData($code, $cacheLifetime = 36000) {       
        return self::getApiData('ItemData', $code);
    }
    
    public static function loadLocationData($locationID, $cacheLifetime = 36000) {       
        return self::getApiData('LocationData', $locationID);
    }

    public static function isAlbumReachedLimit($album, $numItems) {
        if (count($album['filters']['except']) == 0 && count($album['filters']['only']) == 0 && $album['limit_items'] > 0 && $numItems >= $album['limit_items']) {
            return true;
        }
        return false;
    }

    public static function getUserByID($id, $mediaCode) {
        $cacheID = 'user_' . $id;
        $cache = self::getCache(2629000); // one month in seconds
        $cached = $cache->get($cacheID);
        if ($cached !== false) {
            return json_decode($cached, true);
        }
        $data = self::loadItemData($mediaCode);
        if (isset($data['owner']['username'])) {
            $cache->store(json_encode($data['owner']), $cacheID);
            if(!isset($data['owner']['picture']) && isset($data['owner']['profile_pic_url'])){
                $data['owner']['picture'] = $data['owner']['profile_pic_url'];
            }
            return $data['owner'];
        }
        return false;
    }

    public static function getMediaComments($mediaID, $cacheLifetime) {
        $data = self::loadItemData($mediaID);
        $comments = array();
        if (isset($data['edge_media_to_parent_comment']['edges']) && is_array($data['edge_media_to_parent_comment']['edges'])) {
            foreach ($data['edge_media_to_parent_comment']['edges'] as $edge) {
                $edge['node']['user'] = $edge['node']['owner'];
                $comments[] = $edge['node'];
            }
        }
        if (count($comments) == 0) {
            throw new Exception(JText::_('COM_INGALLERY_NO_COMMENTS_FOUND'));
        }
        return $comments;
    }

    public static function getMediaListFromSource($sourceStr, $album, $bindData = array()) {
        $result = array();
        $cacheLifetime = (int) $album['cache_lifetime'];
        $source = self::getSourceData($sourceStr);
        $config = JComponentHelper::getParams('com_ingallery');
        $maxSteps = ceil((int) $config->get('ig_max_items', 100) / 50);
        if ($source && $source['type'] == 'user') {
            $userData = self::loadUserData($source['value'], $cacheLifetime);
            if(is_array($userData['edges'])){
                foreach($userData['edges'] as $node){
                    $result[] = self::makeGalleryItem($node['node'], $album);
                    if (self::isAlbumReachedLimit($album, count($result))) {
                        break;
                    }
                }
            }
            try{
                if($userData['page_info']['has_next_page']){
                    $cursor = $userData['page_info']['end_cursor'];
                    for ($step = 0; $step < $maxSteps; $step++) {
                        if (self::isAlbumReachedLimit($album, count($result))) {
                            break;
                        }
                        $media = self::loadDataFromURI(self::getApiData('URI', 'user') . '&variables={"id":"' . $userData['id'] . '","first":50,"after":"' . $cursor . '"}', $cacheLifetime);
                        if (is_array($media) && isset($media['data']['user']['edge_owner_to_timeline_media']['edges']) && is_array($media['data']['user']['edge_owner_to_timeline_media']['edges']) && count($media['data']['user']['edge_owner_to_timeline_media']['edges'])) {
                            foreach ($media['data']['user']['edge_owner_to_timeline_media']['edges'] as $node) {
                                if (isset($node['node']) && is_array($node['node'])) {
                                    $node['node']['owner'] = $userData;
                                    $result[] = self::makeGalleryItem($node['node'], $album);
                                    if (self::isAlbumReachedLimit($album, count($result))) {
                                        break;
                                    }
                                }
                            }
                            if (isset($media['data']['user']['edge_owner_to_timeline_media']['page_info']) && isset($media['data']['user']['edge_owner_to_timeline_media']['page_info']['has_next_page']) && $media['data']['user']['edge_owner_to_timeline_media']['page_info']['has_next_page'] && isset($media['data']['user']['edge_owner_to_timeline_media']['page_info']['end_cursor'])) {
                                $cursor = $media['data']['user']['edge_owner_to_timeline_media']['page_info']['end_cursor'];
                            } else {
                                break;
                            }
                        } else {
                            break;
                        }
                    }
                    unset($media);
                }
            }
            catch (Exception $e){
                if($config->get('ig_error_action', 'display_loaded')=='throw_error' || count($result)==0){
                    throw $e;
                }
            }
            unset($userData);
        } else if ($source && $source['type'] == 'hashtag') {
            $hashtagData = self::loadHashtagData($source['value'], $cacheLifetime);
            if(is_array($hashtagData['edges'])){
                foreach($hashtagData['edges'] as $node){
                    $result[] = self::makeGalleryItem($node['node'], $album);
                    if (self::isAlbumReachedLimit($album, count($result))) {
                        break;
                    }
                }
            }
            try{
                if($hashtagData['page_info']['has_next_page']){
                    $cursor = $hashtagData['page_info']['end_cursor'];
                    for ($step = 0; $step < $maxSteps; $step++) {
                        if (self::isAlbumReachedLimit($album, count($result))) {
                            break;
                        }
                        $media = self::loadDataFromURI(self::getApiData('URI', 'hashtag') . '&variables={"tag_name":"' . urlencode($source['value']) . '","first":50,"after":"' . $cursor . '"}', $cacheLifetime);
                        if (is_array($media) && isset($media['data']['hashtag']['edge_hashtag_to_media']['edges']) && is_array($media['data']['hashtag']['edge_hashtag_to_media']['edges']) && count($media['data']['hashtag']['edge_hashtag_to_media']['edges'])) {
                            foreach ($media['data']['hashtag']['edge_hashtag_to_media']['edges'] as $node) {
                                if (isset($node['node']) && is_array($node['node'])) {
                                    $result[] = self::makeGalleryItem($node['node'], $album);
                                    if (self::isAlbumReachedLimit($album, count($result))) {
                                        break;
                                    }
                                }
                            }
                            if (isset($media['data']['hashtag']['edge_hashtag_to_media']['page_info']['has_next_page']) && isset($media['data']['hashtag']['edge_hashtag_to_media']['page_info']['end_cursor']) && $media['data']['hashtag']['edge_hashtag_to_media']['page_info']['has_next_page'] && $media['data']['hashtag']['edge_hashtag_to_media']['page_info']['end_cursor']!='') {
                                $cursor = $media['data']['hashtag']['edge_hashtag_to_media']['page_info']['end_cursor'];
                            } else {
                                break;
                            }
                        } else {
                            break;
                        }
                    }
                    unset($media);
                }
            }
            catch (Exception $e){
                if($config->get('ig_error_action', 'display_loaded')=='throw_error' || count($result)==0){
                    throw $e;
                }
            }
            unset($hashtagData);
        } 
        else if ($source && $source['type'] == 'location') {
            $locationData = self::loadLocationData($source['value'], $cacheLifetime);
            if(is_array($locationData['edges'])){
                foreach($locationData['edges'] as $node){
                    $result[] = self::makeGalleryItem($node['node'], $album);
                    if (self::isAlbumReachedLimit($album, count($result))) {
                        break;
                    }
                }
            }
            try{
                if($locationData['page_info']['has_next_page']){
                    $cursor = $locationData['page_info']['end_cursor'];
                    for ($step = 0; $step < $maxSteps; $step++) {
                        if (self::isAlbumReachedLimit($album, count($result))) {
                            break;
                        }
                        $media = self::loadDataFromURI(self::getApiData('URI', 'location') . '&variables={"id":"' . $source['value'] . '","first":50,"after":"' . $cursor . '"}', $cacheLifetime);
                        if (is_array($media) && isset($media['data']['location']['edge_location_to_media']['edges']) && is_array($media['data']['location']['edge_location_to_media']['edges']) && count($media['data']['location']['edge_location_to_media']['edges'])) {
                            foreach ($media['data']['location']['edge_location_to_media']['edges'] as $node) {
                                if (isset($node['node']) && is_array($node['node'])) {
                                    $result[] = self::makeGalleryItem($node['node'], $album);
                                    if (self::isAlbumReachedLimit($album, count($result))) {
                                        break;
                                    }
                                }
                            }
                            if (isset($media['data']['location']['edge_location_to_media']['page_info']) && isset($media['data']['location']['edge_location_to_media']['page_info']['has_next_page']) && $media['data']['location']['edge_location_to_media']['page_info']['has_next_page'] && isset($media['data']['location']['edge_location_to_media']['page_info']['end_cursor'])) {
                                $cursor = $media['data']['location']['edge_location_to_media']['page_info']['end_cursor'];
                            } else {
                                break;
                            }
                        } else {
                            break;
                        }
                    }
                    unset($media);
                }
            }
            catch (Exception $e){
                if($config->get('ig_error_action', 'display_loaded')=='throw_error' || count($result)==0){
                    throw $e;
                }
            }
            unset($locationData);
        } else if ($source && $source['type'] == 'picture') {
            $itemData = self::loadItemData($source['value'], $cacheLifetime);
            $result[] = self::makeGalleryItem($itemData, $album, $bindData);
        }
        return $result;
    }

    public static function makeGalleryItem($node, $album, $bindData = array()) {

        $item = array_merge(array('caption' => '',), $bindData, $node);
        $item['album_id'] = $album['id'];

        $item['video_url'] = (isset($item['video_url']) ? $item['video_url'] : '');
        $item['is_video'] = (isset($item['is_video']) ? $item['is_video'] : false);
        $item['thumbnail_src'] = (isset($item['thumbnail_src']) ? $item['thumbnail_src'] : @$item['display_src']);
        $item['thumbnail_src'] = (isset($item['display_url']) ? $item['display_url'] : $item['thumbnail_src']);
        $item['date'] = (isset($item['taken_at_timestamp']) ? $item['taken_at_timestamp'] : $item['date']);
        $item['code'] = (isset($item['shortcode']) ? $item['shortcode'] : $item['code']);
        $item['likes'] = (isset($item['edge_liked_by']) ? $item['edge_liked_by'] : @$item['likes']);
        if (isset($item['edge_media_preview_like'])) {
            $item['likes'] = array('count' => $item['edge_media_preview_like']['count']);
        }
        if (!isset($item['comments'])) {
            $item['comments'] = array('count' => 0);
        }
        if (isset($node['edge_media_to_comment'])) {
            $item['comments'] = $node['edge_media_to_comment'];
        } else if (isset($node['edge_media_to_parent_comment'])) {
            $item['comments'] = $node['edge_media_to_parent_comment'];
        } else if (isset($node['edge_media_preview_comment'])) {
            $item['comments'] = $node['edge_media_preview_comment'];
        }

        $item['display_src'] = (isset($item['display_url']) ? $item['display_url'] : $item['display_src']);
        $item['caption'] = (isset($item['edge_media_to_caption']['edges'][0]['node']['text']) ? $item['edge_media_to_caption']['edges'][0]['node']['text'] : $item['caption']);

        if (isset($item['edge_media_to_caption'])) {
            unset($item['edge_media_to_caption']);
        }

        if(!isset($item['owner']['username']) && isset($item['owner']['id']) && $item['owner']['id'] != 0) {
            $item['owner'] = self::getUserByID($item['owner']['id'], $item['code']);
        }
        if(!isset($item['owner']['picture']) && isset($item['owner']['profile_pic_url'])){
            $item['owner']['picture'] = $item['owner']['profile_pic_url'];
        }
        
        preg_match_all('~\#(\w+)~u', $item['caption'], $hashtags);
        $item['hashtags'] = array();
        if (isset($hashtags[1]) && is_array($hashtags[1])) {
            foreach ($hashtags[1] as $hashtag) {
                $item['hashtags'][] = $hashtag;
            }
        }

        if ($item['is_video'] && (!isset($item['video_url']) || $item['video_url']=='')) {
            $videoData = self::getMediaListFromSource('https://www.instagram.com/p/' . $item['code'] . '/', $album);
            if (isset($videoData[0]['id'])&&$videoData[0]['id']==$item['id']&&isset($videoData[0]['video_url'])) {
                $item['video_url'] = $videoData[0]['video_url'];
            }
        }

        $item['subgallery'] = array();

        if (isset($node['__typename']) && $node['__typename'] == 'GraphSidecar' && !isset($node['edge_sidecar_to_children'])) {
            $gallery = self::getMediaListFromSource('https://www.instagram.com/p/' . $item['code'] . '/', $album);
            if (count($gallery) == 1) {
                $gallery = $gallery[0];
            }
            if (
                    isset($gallery['edge_sidecar_to_children']) && isset($gallery['edge_sidecar_to_children']['edges']) && is_array($gallery['edge_sidecar_to_children']['edges'])
            ) {

                foreach ($gallery['edge_sidecar_to_children']['edges'] as $imgItem) {
                    if (
                            isset($imgItem['node']) && is_array($imgItem['node']) && isset($imgItem['node']['__typename']) && isset($imgItem['node']['display_url']) && ($imgItem['node']['__typename'] == 'GraphImage' || $imgItem['node']['__typename'] == 'GraphVideo') && $imgItem['node']['display_url'] != ''
                    ) {
                        $img = array(
                            'id' => $imgItem['node']['id'],
                            'src' => $imgItem['node']['display_url'],
                            'width' => $imgItem['node']['dimensions']['width'],
                            'height' => $imgItem['node']['dimensions']['height'],
                            'is_video' => (bool) ($imgItem['node']['__typename'] == 'GraphVideo'),
                            'video_url' => ($imgItem['node']['__typename'] == 'GraphVideo' ? $imgItem['node']['video_url'] : '')
                        );
                        $item['subgallery'][] = $img;
                    }
                }
            }
        } else if (isset($node['edge_sidecar_to_children']) && isset($node['edge_sidecar_to_children']['edges']) && is_array($node['edge_sidecar_to_children']['edges'])) {

            foreach ($node['edge_sidecar_to_children']['edges'] as $imgItem) {
                if (
                        isset($imgItem['node']) && is_array($imgItem['node']) && isset($imgItem['node']['__typename']) && isset($imgItem['node']['display_url']) && ($imgItem['node']['__typename'] == 'GraphImage' || $imgItem['node']['__typename'] == 'GraphVideo') && $imgItem['node']['display_url'] != ''
                ) {
                    $img = array(
                        'id' => $imgItem['node']['id'],
                        'src' => $imgItem['node']['display_url'],
                        'width' => $imgItem['node']['dimensions']['width'],
                        'height' => $imgItem['node']['dimensions']['height'],
                        'is_video' => (bool) ($imgItem['node']['__typename'] == 'GraphVideo'),
                        'video_url' => ($imgItem['node']['__typename'] == 'GraphVideo' ? $imgItem['node']['video_url'] : '')
                    );
                    $item['subgallery'][] = $img;
                }
            }
        }
        if (!isset($item['likes']['count'])) {
            $item['likes']['count'] = 0;
        }
        
        return $item;
    }

    public static function setCookie($cookie) {
        self::$_cookie = array_merge(self::$_cookie, $cookie);
        $cache = self::getCache(99999);
        $cacheID = 'cookie';
        $cache->store(json_encode(self::$_cookie), $cacheID);
    }

    public static function getCookie() {
        $cache = self::getCache(99999);
        $cacheID = 'cookie';
        $cached = $cache->get($cacheID);
        if ($cached) {
            self::$_cookie = json_decode($cached, true);
        }
        return self::$_cookie;
    }

    public static function loadDataFromURI($url, $cacheLifetime=0) {
        $cache = self::getCache($cacheLifetime);
        $cacheID = md5($url.'|'.$cacheLifetime);
        $cached = $cache->get($cacheID);
        if ($cacheLifetime > 0 && $cached) {
            return json_decode($cached, true);
        } else {
            $cache->remove($cacheID);
        }
        $responce = self::request($url, array(), array('Cookie' => self::getCookie()));
        
        if(!self::isJSON($responce['body'])) {
            throw new Exception('Instagram server responce error. Request: "' . $url . '". Responce: ' . print_r($responce, true));
        }

        if ($cacheLifetime > 0) {
            $cache->store($responce['body'], $cacheID);
        }
        return json_decode($responce['body'], true);
    }

    public static function filterMedia($items, $filters) {
        $uniqueItems = array();
        $addedIDs = array();
        foreach ($items as $item) {
            if (in_array($item['code'], $addedIDs)) {
                continue;
            }
            $uniqueItems[] = $item;
            $addedIDs[] = $item['code'];
        }
        $items = $uniqueItems;
        unset($uniqueItems);
        $result = array();
        if (count($filters['except']) == 0 && count($filters['only']) == 0) {
            return $items;
        }
        $addedIDs = array();
        if (count($filters['only']) > 0) {
            foreach ($filters['only'] as $onlySource) {
                if ($onlySource == 'videos') {
                    foreach ($items as $item) {
                        if (in_array($item['code'], $addedIDs)) {
                            continue;
                        }
                        if ($item['is_video']) {
                            $result[] = $item;
                            $addedIDs[] = $item['code'];
                        }
                    }
                } else {
                    $source = self::getSourceData($onlySource);
                    if ($source) {
                        foreach ($items as $item) {
                            if (in_array($item['code'], $addedIDs)) {
                                continue;
                            }
                            $toAdd = false;
                            if ($source['type'] == 'user' && $source['value'] == $item['owner']['username']) {
                                $toAdd = true;
                            } else if ($source['type'] == 'hashtag' && in_array($source['value'], $item['hashtags'])) {
                                $toAdd = true;
                            } else if ($source['type'] == 'picture' && $source['value'] == $item['code']) {
                                $toAdd = true;
                            }
                            if ($toAdd) {
                                $result[] = $item;
                                $addedIDs[] = $item['code'];
                            }
                        }
                    }
                }
            }
        } else {
            $result = $items;
        }
        if (count($filters['except']) > 0) {
            $exIDs = array();
            foreach ($filters['except'] as $exSource) {
                if ($exSource == 'videos') {
                    foreach ($result as $item) {
                        if ($item['is_video']) {
                            $exIDs[] = $item['code'];
                        }
                    }
                } else {
                    $source = self::getSourceData($exSource);
                    if ($source) {
                        foreach ($result as $item) {
                            if ($source['type'] == 'user' && $source['value'] == $item['owner']['username']) {
                                $exIDs[] = $item['code'];
                            } else if ($source['type'] == 'hashtag' && in_array($source['value'], $item['hashtags'])) {
                                $exIDs[] = $item['code'];
                            } else if ($source['type'] == 'picture' && $source['value'] == $item['code']) {
                                $exIDs[] = $item['code'];
                            }
                        }
                    }
                }
            }
            $result2 = array();
            foreach ($result as $item) {
                if (!in_array($item['code'], $exIDs)) {
                    $result2[] = $item;
                }
            }
            $result = $result2;
        }

        return $result;
    }

    public static function orderMedia($a, $b) {
        if ($a['date'] == $b['date']) {
            return 0;
        }
        return ($a['date'] < $b['date']) ? 1 : -1;
    }

    public static function isJSON($string) {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
    }

    public static function getCache($cacheLifetime) { // seconds
        $conf = JFactory::getConfig();
        $cache = JCache::getInstance('', array(
                    'defaultgroup' => self::CACHE_KEY,
                    'cachebase' => $conf->get('cache_path', JPATH_ROOT . '/cache'),
                    'language' => 'en-GB',
                    'lifetime' => ($cacheLifetime > 0 ? $cacheLifetime/60 : 0),
                    'caching' => ($cacheLifetime > 0)
        ));
        return $cache;
    }

    public static function getApiData($type, $_data='') {
        static $_result;
        if (!is_array($_result)) {
            $_result = array();
        }
        $key = 'api_data_'.md5($type.$_data);
        if (isset($_result[$key])) {
            return $_result[$key];
        }
        $cache = self::getCache(86400);
        $cacheID = $key;
        $cached = $cache->get($cacheID);
        if ($cached) {
            return $cached;
        }
        $config = JComponentHelper::getParams('com_ingallery');
        $URI = JUri::getInstance();
        if($type=='UserData'){
            $apiURL = 'https://api.allforjoomla.com/ingallery/getUser/?user='.$_data;
        }
        else if($type=='HashtagData'){
            $apiURL = 'https://api.allforjoomla.com/ingallery/getHashtag/?hashtag='.$_data;
        }
        else if($type=='ItemData'){
            $apiURL = 'https://api.allforjoomla.com/ingallery/getItem/?code='.$_data;
        }
        else if($type=='LocationData'){
            $apiURL = 'https://api.allforjoomla.com/ingallery/getLocation/?location='.$_data;
        }
        else{
            $apiURL = 'https://api.allforjoomla.com/ingallery/getUrl/?scope='.$_data;
        }
        $responce = self::request($apiURL, array(
                    'domain' => $URI->getHost(),
                    'license_key' => $config->get('purchase_code', ''),
        ), null, 30, true);
        
        $bPos = utf8_strpos($responce['body'], '{"code":');
        if ($bPos !== false && $bPos !== 0) {
            $responce['body'] = utf8_substr($responce['body'], $bPos);
        }
        
        if ((int) $responce['code'] != 200 || !self::isJSON($responce['body'])) {
            throw new Exception('API responce error: ' . print_r($responce, true));
        }
        $result = json_decode($responce['body'], true);
        if (!is_array($result) || !isset($result['code'])) {
            throw new Exception('API responce error: ' . json_last_error_msg());
        }
        if ((int) $result['code'] != 200) {
            throw new Exception('API {'.$type.':'.$_data.'} error: ' . $responce['body']);
        }
        $_result[$key] = $result['body'];
        $cache->store($result['body'], $cacheID);
        return $result['body'];
    }

    public static function requestProxy($_uri, $_data, $_headers) {
        if (!function_exists('curl_init')) {
            throw new Exception(JText::_('COM_INGALLERY_NEEDS_CURL'));
        }
        $config = JComponentHelper::getParams('com_ingallery');

        $options = array();
        $options[CURLOPT_URL] = $_uri;
        $options[CURLOPT_PROXY] = $config->get('proxy_url');
        if ($config->get('proxy_auth', '')!='') {
            $options[CURLOPT_PROXYUSERPWD] = $config->get('proxy_auth');
        }

        if ($config->get('proxy_type', 'http') == 'socks4') {
            $options[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS4;
        } else if ($config->get('proxy_type', 'http') == 'socks5') {
            $options[CURLOPT_PROXYTYPE] = CURLPROXY_SOCKS5;
        }

        if (count($_data)) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $_data;
        }
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_SSL_VERIFYPEER] = false;
        $options[CURLOPT_SSL_VERIFYHOST] = false;
        $options[CURLOPT_HEADER] = true;
        $options[CURLOPT_TIMEOUT] = 20;

        if (count($_data)) {
            $options[CURLOPT_POST] = true;
            $options[CURLOPT_POSTFIELDS] = $_data;
        }

        if (isset($_headers['User-Agent'])) {
            $options[CURLOPT_USERAGENT] = $_headers['User-Agent'];
            unset($_headers['User-Agent']);
        }
        if (isset($_headers['Referer'])) {
            $options[CURLOPT_REFERER] = $_headers['Referer'];
            unset($_headers['Referer']);
        }
        if (isset($_headers['Cookie'])) {
            $options[CURLOPT_COOKIE] = $_headers['Cookie'];
            unset($_headers['Cookie']);
        }
        $options[CURLOPT_HTTPHEADER] = array();
        foreach ($_headers as $k => $v) {
            $options[CURLOPT_HTTPHEADER][] = $k . ': ' . $v;
        }
        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $responce = curl_exec($ch);
        $info = curl_getinfo($ch);
        
        if (!is_string($responce)) {
            $message = curl_error($ch);
            curl_close($ch);
            if(empty($message)) {
                $message = 'No PROXY HTTP response received';
            }
            throw new Exception('PROXY error: ' . $message);
        }
        curl_close($ch);

        $tmp = explode("\r\n\r\n", $responce, 3);
        if (count($tmp) > 2) {
            list($proxyCode, $header, $body) = $tmp;
        } else {
            $proxyCode = 0;
            $header = $tmp[0];
            $body = $tmp[1];
        }

        $result = array(
            'code' => (int) $info['http_code'],
            'headers' => array(),
            'cookie' => array(),
            'body' => $body
        );

        $headers = explode("\r\n", $header);
        foreach ($headers as $headr) {
            $pos = utf8_strpos($headr, ':');
            if ($pos === false) {
                continue;
            }
            $hName = strtolower(trim(utf8_substr($headr, 0, $pos)));
            $hValue = trim(utf8_substr($headr, ($pos + 1)));
            if ($hName == 'set-cookie') {
                preg_match('~^([^=]+)=([^;\r\n]+)~', $hValue, $matches);
                $result['cookie'][$matches[1]] = str_replace('"', '', $matches[2]);
            } else {
                $result['headers'][$hName] = $hValue;
            }
        }

        return $result;
    }

    public static function request($_uri, $_data = null, $_headers = null, $_timeout = 30, $forceNoProxy=false) {
        if (self::getIGrequestsLock()) {
            throw new Exception(JText::_('COM_INGALLERY_IG_REQUESTS_PER_HOUR_LIMIT_REACHED'));
        }
        
        $eCache = self::getCache(10);
        $eCacheID = 'ig_error_'.md5($_uri);
        $cached = $eCache->get($eCacheID);
        if($cached) {
            throw new Exception($cached);
        }
        
        $config = JComponentHelper::getParams('com_ingallery');

        if (self::$_lastRequestTime > 0 && self::$_lastRequestTime + (int) $config->get('ig_requests_rate_limit', 0) < time()) {
            sleep((int) $config->get('ig_requests_rate_limit', 0));
        }
        self::$_lastRequestTime = time();

        if (!is_array($_data)) {
            $_data = array();
        }
        if (!is_array($_headers)) {
            $_headers = array();
        }
        $_headers = array_merge(array(
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:76.0) Gecko/20100101 Firefox/76.0',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language' => 'en,en-US;q=0.7',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Connection' => 'keep-alive',
            'Upgrade-Insecure-Requests' => 1,
            'Cookie' => array()
                ), $_headers);

        $cookie = $_headers['Cookie'];
        $_headers['Cookie'] = array();
        foreach ($cookie as $cName => $cVal) {
            $_headers['Cookie'][] = $cName . '=' . $cVal;
        }
        $_headers['Cookie'] = implode('; ', $_headers['Cookie']);

        if (isset($cookie['csrftoken'])) {
            $_headers['X-Csrftoken'] = $cookie['csrftoken'];
        }

        if (!$forceNoProxy && (int) $config->get('proxy_use', 0) == 1 && $config->get('proxy_url', '') != '') {
            $result = self::requestProxy($_uri, $_data, $_headers);
        } else {
            $httpOption = new Registry;
            $httpOption->set('userAgent', $_headers['User-Agent']);
            if($config->get('curl_interface', '')!=''){
                $httpOption->set('transport.curl', [CURLOPT_INTERFACE=>$config->get('curl_interface', '')]);
            }

            if (class_exists('\Joomla\CMS\Http\HttpFactory')) {
                $http = \Joomla\CMS\Http\HttpFactory::getHttp($httpOption);
            } else if (class_exists('JHttpFactory')) {
                $http = JHttpFactory::getHttp($httpOption);
            } else {
                $http = HttpFactory::getHttp($httpOption);
            }

            if (count($_data)) {
                $response = $http->post($_uri, $_data, $_headers, 20);
            } else {
                $response = $http->get($_uri, $_headers, 20);
            }
            $result = array(
                'code' => (int) $response->code,
                'headers' => array(),
                'cookie' => array(),
                'body' => $response->body
            );
            foreach ($response->headers as $headr) {
                if(is_array($headr)){
                    $headr = $headr[0];
                }
                $pos = utf8_strpos($headr, ':');
                if ($pos === false) {
                    continue;
                }
                $hName = strtolower(trim(utf8_substr($headr, 0, $pos)));
                $hValue = trim(utf8_substr($headr, ($pos + 1)));
                if ($hName == 'set-cookie') {
                    preg_match('~^([^=]+)=([^;\r\n]+)~', $hValue, $matches);
                    $result['cookie'][$matches[1]] = str_replace('"', '', $matches[2]);
                } else {
                    $result['headers'][$hName] = $hValue;
                }
            }
        }

        self::setCookie($result['cookie']);
        
        if ($result['code'] == 302 && isset($result['headers']['location']) && preg_match('~^'.preg_quote('https://www.instagram.com/accounts/login/').'~i',$result['headers']['location'])) {
            $eCache->store(JText::_('COM_INGALLERY_IG_REQUIRE_LOGIN'), $eCacheID);
            throw new Exception(JText::_('COM_INGALLERY_IG_REQUIRE_LOGIN'));
        }
        if ($result['code'] == 429) {
            self::setIGrequestsLock();
            throw new Exception(JText::_('COM_INGALLERY_IG_REQUESTS_PER_HOUR_LIMIT_REACHED'));
        }
        
        if (isset($result['headers']['content-encoding']) && $result['headers']['content-encoding'] == 'gzip') {
            $result['body'] = gzdecode($result['body']);
        }
        
        if ($result['code'] != 200) {
            $body = json_decode($result['body'], true);
            if ($body && isset($body['message'])) {
                $error = 'Instagram error: ' . $body['message'];
            } else {
                $error = 'Instagram error: ' . $result['body'];
            }
            $eCache->store($error, $eCacheID);
            throw new Exception($error);
        }
        $eCache->remove($eCacheID);
        return $result;
    }

    public static function getIGrequestsLock() {
        $config = JComponentHelper::getParams('com_ingallery');
        if ((int) $config->get('ig_requests_lock', 1) != 1) {
            return false;
        }
        $cacheID = self::IG_LOCK_CACHE_KEY;
        $cache = self::getCache((int) $config->get('ig_requests_lock_time', 1800));
        $cached = $cache->get($cacheID);
        if ($cached === false) {
            return false;
        }
        return true;
    }

    public static function setIGrequestsLock() {
        $config = JComponentHelper::getParams('com_ingallery');
        if ((int) $config->get('ig_requests_lock', 1) != 1) {
            return;
        }
        $cacheID = self::IG_LOCK_CACHE_KEY;
        $cache = self::getCache((int) $config->get('ig_requests_lock_time', 1800));
        $cached = $cache->get($cacheID);
        if ($cached === false) {
            $cache->store('lock', $cacheID);
        }
    }

}
