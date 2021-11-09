<?php
/**
 * @package    inGallery
 * @subpackage plg_system_ingallery
 * @license  http://www.gnu.org/licenseses/gpl-3.0.html GNU/GPL
 *
 * Created by Oleg Micriucov for Joomla! 3.x
 * https://allforjoomla.com
 *
 */
defined('_JEXEC') or die(':)');

class plgSystemIngallery extends JPlugin {

    private $_static_content_version = '1.217.7';
    private static $scriptsLoaded = false;
    private $_v = null;
    private $_cfg = null;

    public function onAfterRender() {
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        if ($this->_isAdmin() || $doc->getType() != 'html')
            return;

        $html = $this->_getResponseBody();
        $delim = utf8_strpos($html, '<body');
        if ($delim === false) {
            return true;
        }
        $htmlHead = utf8_substr($html, 0, $delim);
        $htmlBody = utf8_substr($html, $delim);
        if (strpos($htmlBody, '[ingallery') === false) {
            return true;
        }

        $htmlBody = preg_replace_callback('~\[ingallery([^\]]+)\]~', array($this, '_renderShortcode'), $htmlBody);

        $scriptsLoadMode = $this->_getConfig()->get('load_mode', 'all');
        if ($scriptsLoadMode != 'all' && !self::$scriptsLoaded) {
            self::$scriptsLoaded = true;
            $scripts = '';
            
            if ((int) $this->_getConfig()->get('load_masonry', 1) > 0) {
                $scripts .= '<script src="' . JUri::root(true) . '/media/com_ingallery/js/masonry.pkgd.min.js?v=' . $this->_static_content_version . '"></script>';
            }
            $scripts.= '<script src="'.JUri::root(true).'/media/com_ingallery/js/frontend.js?v='.$this->_static_content_version.'"></script>';

            $htmlBody = preg_replace('~<\/body>~', $scripts . '</body>', $htmlBody, 1);
        }
        $this->_setResponseBody($htmlHead . $htmlBody);
        return true;
    }
    
    private function _isAdmin(){
        $app = JFactory::getApplication();
        return ((method_exists($app, 'isAdmin') && $app->isAdmin()) || (method_exists($app, 'isClient') && $app->isClient('administrator')));
    }

    private function _setResponseBody($html) {
        if (version_compare($this->_getCoreVersion(), '4.0.0', 'ge')) {
            $app = JFactory::getApplication();
            return $app->setBody($html);
        }
        JResponse::setBody($html);
    }

    private function _getResponseBody() {
        if (version_compare($this->_getCoreVersion(), '4.0.0', 'ge')) {
            $app = JFactory::getApplication();
            return $app->getBody();
        }
        return JResponse::getBody();
    }

    private function _getCoreVersion() {
        if ($this->_v === null) {
            $this->_v = new JVersion;
        }
        $v = $this->_v->getShortVersion();
        if (strpos($v, '-') !== false) {
            $v = explode('-', $v);
            $v = $v[0];
        }
        return $v;
    }

    public function onBeforeCompileHead() {
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        if ($doc->getType() != 'html') {
            return;
        }
        if ($this->_isAdmin()) {
            $this->_loadBackendStaticContent();
        } else {
            $this->_loadFrontendStaticContent();
        }
    }

    private function _loadBackendStaticContent() {
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $lang = JFactory::getLanguage();
        $db = JFactory::getDbo();

        if ($app->input->getCmd('option', '') != 'com_ingallery') {
            return;
        }

        $jsVars = array(
            'ajax_url' => JUri::base(true) . '/index.php?option=com_ingallery',
            'lang' => array(
                'albums' => JText::_('COM_INGALLERY_ALBUMS'),
                'layout' => JText::_('COM_INGALLERY_LAYOUT'),
                'display' => JText::_('COM_INGALLERY_DISPLAY'),
                'colors' => JText::_('COM_INGALLERY_COLORS'),
                'new_album' => JText::_('COM_INGALLERY_NEW_ALBUM'),
                'album_name' => JText::_('COM_INGALLERY_ALBUM_NAME'),
                'sources' => JText::_('COM_INGALLERY_SOURCES'),
                'sources_help' => JText::_('COM_INGALLERY_LIST_OF_SOURCES_TO_GET_ME'),
                'filters' => JText::_('COM_INGALLERY_FILTERS'),
                'filters_help' => JText::_('COM_INGALLERY_LIST_OF_FILTERS_TO_FILTER'),
                'limit' => JText::_('COM_INGALLERY_LIMIT_ITEMS'),
                'limit_help' => JText::_('COM_INGALLERY_LIMIT_MEDIA_ITEMS_IN_CURR'),
                'type' => JText::_('COM_INGALLERY_TYPE'),
                'cols' => JText::_('COM_INGALLERY_COLUMNS'),
                'rows' => JText::_('COM_INGALLERY_ROWS'),
                'gutter' => JText::_('COM_INGALLERY_GUTTER'),
                'size_in_px' => JText::_('COM_INGALLERY_SIZE_IN_PIXELS'),
                'style' => JText::_('COM_INGALLERY_STYLE'),
                'display_mode' => JText::_('COM_INGALLERY_DISPLAY_MODE'),
                'display_on_thumbs' => JText::_('COM_INGALLERY_DISPLAY_ON_THUMBNAILS'),
                'likes' => JText::_('COM_INGALLERY_LIKES_COUNTER'),
                'comments' => JText::_('COM_INGALLERY_COMMENTS_COUNTER'),
                'description' => JText::_('COM_INGALLERY_MEDIA_DESCRIPTION'),
                'display_in_popup' => JText::_('COM_INGALLERY_DISPLAY_IN_POPUP_WINDOW'),
                'comments_list' => JText::_('COM_INGALLERY_COMMENTS'),
                'username' => JText::_('COM_INGALLERY_USERNAME'),
                'link_user_to_instagram' => JText::_('COM_INGALLERY_LINK_TO_INSTAGRAM_PROFILE'),
                'link_to_instagram' => JText::_('COM_INGALLERY_LINK_TO_INSTAGRAM'),
                'created_date' => JText::_('COM_INGALLERY_CREATED_DATE'),
                'show_in_popup' => JText::_('COM_INGALLERY_SHOW_IN_POPUP_WINDOW'),
                'add_filter' => JText::_('COM_INGALLERY_ADD_FILTER'),
                'except' => JText::_('COM_INGALLERY_EXCEPT'),
                'only' => JText::_('COM_INGALLERY_ONLY'),
                'add_album' => JText::_('COM_INGALLERY_ADD_NEW_ALBUM'),
                'cache_lifetime' => JText::_('COM_INGALLERY_CACHE_LIFETIME'),
                'cache_lifetime_help' => JText::_('COM_INGALLERY_CACHE_LIFETIME_IN_SECONDS'),
                'show_albums' => JText::_('COM_INGALLERY_SHOW_ALBUMS_NAMES'),
                'show_loadmore' => JText::_('COM_INGALLERY_ENABLE_LOAD_MORE_'),
                'loadmore_text' => JText::_('COM_INGALLERY_TEXT_ON_A_LOAD_MORE_BUTTO'),
                'video_plays' => JText::_('COM_INGALLERY_VIDEO_PLAYS_COUNTER'),
                'system_error' => JText::_('COM_INGALLERY_SYTEM_ERROR_PLEASE_REFRES'),
                'error_title' => JText::_('COM_INGALLERY_UNFORTUNATELY_AN_ERROR_OC'),
                'congrats' => JText::_('COM_INGALLERY_CONGRATULATIONS_'),
                'user_block' => JText::_('COM_INGALLERY_INSTAGRAM_USER_BLOCK'),
                'popup_img_size' => JText::_('COM_INGALLERY_POPUP_IMAGE_SIZE'),
                'popup_img_size_help' => JText::_('COM_INGALLERY_FOR_SMOOTH_VIEWING_EXPERI'),
                'try_to_fit' => JText::_('COM_INGALLERY_TRY_TO_FIT_EQUAL_SIZE'),
                'full_size' => JText::_('COM_INGALLERY_USE_IMAGE_SIZE'),
                'loop_video' => JText::_('COM_INGALLERY_LOOP_VIDEO'),
                'album_btns_colors' => JText::_('COM_INGALLERY_ALBUM_BUTTONS_COLORS'),
                'gallery_bg' => JText::_('COM_INGALLERY_GALLERY_BACKGROUND'),
                'album_btn_bg' => JText::_('COM_INGALLERY_ALBUM_BUTTON_BACKGROUND'),
                'album_btn_text' => JText::_('COM_INGALLERY_ALBUM_BUTTON_TEXT_COLOR'),
                'album_btn_hover_bg' => JText::_('COM_INGALLERY_ALBUM_BUTTON_BACKGROUND_O'),
                'album_btn_hover_text' => JText::_('COM_INGALLERY_ALBUM_BUTTON_TEXT_COLOR_O'),
                'more_btn_bg' => JText::_('COM_INGALLERY__LOAD_MORE_BUTTON_BACKGRO'),
                'more_btn_text' => JText::_('COM_INGALLERY__LOAD_MORE_BUTTON_TEXT_CO'),
                'more_btn_hover_bg' => JText::_('COM_INGALLERY__LOAD_MORE_BUTTON_BACKGRO2'),
                'more_btn_hover_text' => JText::_('COM_INGALLERY__LOAD_MORE_BUTTON_TEXT_CO2'),
                'more_btn_colors' => JText::_('COM_INGALLERY__LOAD_MORE_BUTTON_COLORS'),
                'thumbs_colors' => JText::_('COM_INGALLERY_THUMBNAILS_COLORS'),
                'thumb_overlay_bg' => JText::_('COM_INGALLERY_THUMBNAILS_OVERLAY_BACKGR'),
                'thumb_overlay_text' => JText::_('COM_INGALLERY_THUMBNAILS_OVERLAY_TEXT_C'),
                'infinite_scroll' => JText::_('COM_INGALLERY_INFINITE_SCROLLING'),
                'grid' => JText::_('COM_INGALLERY_GRID'),
                'carousel' => JText::_('COM_INGALLERY_CAROUSEL'),
                'local_responsiveness' => JText::_('COM_INGALLERY_LOCAL_RESPONSIVENESS'),
                'autoscroll' => JText::_('COM_INGALLERY_AUTOSCROLL'),
                'autoscroll_speed' => JText::_('COM_INGALLERY_AUTOSCROLL_SPEED'),
                'autoscroll_speed_sec' => JText::_('COM_INGALLERY_AUTOSCROLL_SPEED_SEC'),
                'masonry' => JText::_('COM_INGALLERY_MASONRY'),
                'img_preloader' => JText::_('COM_INGALLERY_IMG_PRELOADER'),
                'img_preloader_descr' => JText::_('COM_INGALLERY_IMG_PRELOADER_DESCR'),
                'mobile_optimization' => JText::_('COM_INGALLERY_MOBILE_OPTIMIZATION'),
                'add' => JText::_('COM_INGALLERY_ADD'),
                'screen_width' => JText::_('COM_INGALLERY_SCREEN_WIDTH'),
                'masonrycols' => JText::_('COM_INGALLERY_MASONRYCOLS'),
                'header' => JText::_('COM_INGALLERY_HEADER'),
                'nothanks' => JText::_('COM_INGALLERY_NO_THANKS'),
                'enjoyingall' => JText::_('COM_INGALLERY_ENJOY_INGALLERY'),
                'clicktorate' => JText::_('COM_INGALLERY_CLICK_TO_RATE'),
                'sharereview' => JText::_('COM_INGALLERY_SHARE_REVIEW'),
                'rateus' => JText::_('COM_INGALLERY_RATE_US'),
                'thanks4review' => JText::_('COM_INGALLERY_THANKS_FOR_REVIEW'),
                'thanks4review2' => JText::_('COM_INGALLERY_THANKS_FOR_REVIEW2'),
                'thanks4review3' => JText::_('COM_INGALLERY_THANKS_FOR_REVIEW3'),
                'warning' => JText::_('COM_INGALLERY_WARNING'),
                'sources_warning' => JText::_('COM_INGALLERY_SOURCES_WARNING')
            )
        );

        $doc->addCustomTag('<script type="application/json" id="ingallery-cfg">' . json_encode($jsVars) . '</script>');

        $doc->addScript(JUri::root(true) . '/media/com_ingallery/colorpicker/js/jquery.minicolors.js?v=' . $this->_static_content_version);

        if ((int) $this->_getConfig()->get('load_masonry', 1) > 0) {
            $doc->addScript(JUri::root(true) . '/media/com_ingallery/js/masonry.pkgd.min.js?v=' . $this->_static_content_version);
        }
        $doc->addScript(JUri::root(true).'/media/com_ingallery/js/backend.js?v='.$this->_static_content_version);

        if ((int) $this->_getConfig()->get('load_gfont', 1)) {
            JHtml::_('stylesheet', 'https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700,700i&subset=cyrillic,cyrillic-ext,latin-ext');
        }
        $doc->addStyleSheet(JUri::root(true) . '/media/com_ingallery/colorpicker/css/jquery.minicolors.css?v=' . $this->_static_content_version);
        
        $doc->addStyleSheet(JUri::root(true).'/media/com_ingallery/css/backend.css?v='.$this->_static_content_version);

        if ($app->input->cookie->get('ingallery_backend_review_deny', null) === null) {
            $q = $db->getQuery(true)
                    ->select('created_at')
                    ->from('#__ingallery_gals')
                    ->order('id ASC')
            ;
            $db->setQuery($q, 0, 1);
            $firstDate = $db->loadResult();
            if (preg_match('~^\d{4}\-\d{2}-\d{2} \d{2}\:\d{2}\:\d{2}$~', $firstDate)) {
                $firstTime = strtotime($firstDate);
                if ($firstTime < (time() + 3 * 24 * 60 * 60)) {
                    $doc->addScriptDeclaration("\n" . '
                    (function(){window.inGalleryAskRate = true;})();
                    ' . "\n");
                }
            }
        }
    }

    private function _loadFrontendStaticContent() {
        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();
        $lang = JFactory::getLanguage();

        $this->_loadLang();

        $jsVars = array(
            'ajax_url' => $this->_getAjaxURI('gallery.view'),
            'lang' => array(
                'error_title' => JText::_('COM_INGALLERY_UNFORTUNATELY_AN_ERROR_OC'),
                'system_error' => JText::_('COM_INGALLERY_SYTEM_ERROR_PLEASE_REFRES'),
            )
        );
        $doc->addCustomTag('<script type="application/json" id="ingallery-cfg">' . json_encode($jsVars) . '</script>');

        if ((int) $this->_getConfig()->get('load_gfont', 1)) {
            JHtml::_('stylesheet', 'https://fonts.googleapis.com/css?family=Open+Sans:400,400i,600,600i,700,700i&subset=cyrillic,cyrillic-ext,latin-ext');
        }
        $doc->addStyleSheet(JUri::root(true).'/media/com_ingallery/css/frontend.css?v='.$this->_static_content_version);

        JHtml::_('jquery.framework');
        JHtml::_('behavior.core');

        $scriptsLoadMode = $this->_getConfig()->get('load_mode', 'all');
        if ($scriptsLoadMode == 'all' && !self::$scriptsLoaded) {
            self::$scriptsLoaded = true;

            if ((int) $this->_getConfig()->get('load_masonry', 1) > 0) {
                $doc->addScript(JUri::root(true) . '/media/com_ingallery/js/masonry.pkgd.min.js?v=' . $this->_static_content_version);
            }
            $doc->addScript(JUri::root(true).'/media/com_ingallery/js/frontend.js?v='.$this->_static_content_version);
        }
    }
    
    private function _getAjaxURI($task){
        $lang = JFactory::getLanguage();
        switch($this->_getConfig()->get('ajax_url_mode','router')){
            case 'relative':
                $ajaxURL = JUri::root(true).'/index.php?option=com_ingallery&task='.$task.'&language='.$lang->getTag();
            break;
            case 'router':
                $ajaxURL = JRoute::_('index.php?option=com_ingallery&task='.$task.'&language='.$lang->getTag(), false);
            break;
            case 'absolute':
            default:
                $ajaxURL = JUri::root().'index.php?option=com_ingallery&task='.$task.'&language='.$lang->getTag();
            break;
        }
        return $ajaxURL;
    }

    private function _getConfig() {
        if ($this->_cfg === null) {
            $this->_cfg = JComponentHelper::getParams('com_ingallery');
        }
        return $this->_cfg;
    }

    public function _renderShortcode($matches) {
        $config = JComponentHelper::getParams('com_ingallery');
        $attribs = $this->_shortcodeAttribs($matches[1]);
        if (!isset($attribs['id']) || (int) $attribs['id'] <= 0) {
            return $matches[0];
        }
        $result = '<div class="ingallery-container" data-id="' . $attribs['id'] . '"></div>';

        return $result;
    }

    public function _shortcodeAttribs($rawAttribs) {
        $attribs = array();
        if (is_array($rawAttribs)) {
            $attribs = $rawAttribs;
        } else {
            preg_match_all('~([a-zA-Z0-9_\-]+)="([^"]+)"~', $rawAttribs, $mchs);
            foreach ($mchs[1] as $k => $v) {
                $attribs[$v] = $mchs[2][$k];
            }
        }
        $defaults = array(
            'id' => 0,
        );
        return array_merge($defaults, $attribs);
    }

    public function onExtensionAfterSave($context, $table, $isNew = true) {
        if ($isNew || $context != 'com_config.component' || $table->name != 'com_ingallery') {
            return;
        }
        $params = new JRegistry($table->params);
        $db = JFactory::getDbo();
        $query = $db->getQuery(true)
                ->update('#__update_sites')
                ->set('extra_query=' . $db->quote('purchase_code=' . $params->get('purchase_code', '')))
                ->where('name=' . $db->quote('InGallery'));
        $db->setQuery($query);
        $db->execute();
    }

    private function _loadLang() {
        $lang = JFactory::getLanguage();
        $lang->load('com_ingallery', JPATH_ADMINISTRATOR);
    }

}
