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

class IngalleryControllerGallery extends JControllerLegacy {

    public function view() {
        $app = JFactory::getApplication();
        $config = JComponentHelper::getParams('com_ingallery');

        try {
            $gallery_id = $this->input->getInt('id', 0);
            $page = $this->input->getInt('page', 1);

            $table = JTable::getInstance('Galleries', 'IngalleryTable');
            $gallery = new IngalleryModel();
            $view = new IngalleryView();
            $table->load($gallery_id);
            if ((int) $table->id <= 0 || (int) $table->id != $gallery_id) {
                throw new Exception(sprintf(JText::_('COM_INGALLERY_GALLERY_NOT_FOUND'), $gallery_id));
            }
            if (!$gallery->setConfig($table->config)) {
                throw new Exception($gallery->getError());
            }

            $view->set('albums', $gallery->get('albums'));
            $view->set('cfg', $gallery->get('cfg'));
            $view->set('page', $page);
            $view->set('items', $gallery->getItemsPage($page));
            $view->set('has_more', $gallery->hasItemsPage($page + 1));
            $view->set('id', $table->id);

            $view->setTemplate('gallery');

            $responce = array(
                'status' => 'success',
                'id' => $table->id,
                'has_more' => $gallery->hasItemsPage($page + 1),
                'html' => $view->render()
            );
        } catch (Exception $e) {
            $msg = $e->getMessage();
            IngalleryLogger::log($e->getMessage());

            if ((int) $config->get('display_errors', 1) != 1) {
                $msg = JText::_('COM_INGALLERY_SYTEM_ERROR_PLEASE_REFRES');
            }
            $responce = array(
                'status' => 'error',
                'message' => $msg
            );
        }
        header('Content-Type: application/json', true);
        $result = json_encode($responce);
        if (json_last_error() === JSON_ERROR_UTF8) {
            echo json_encode($this->_utf8ize($responce));
        } else {
            echo $result;
        }
        $app->close();
    }

    public function comments() {
        $app = JFactory::getApplication();
        $config = JComponentHelper::getParams('com_ingallery');
        try {
            $mediaCode = $this->input->get('media_code', '');
            if (!preg_match('~^' . IngalleryModel::MATCH_IG_MEDIA_CODE_CLASS . '$~', $mediaCode)) {
                throw new Exception(JText::_('COM_INGALLERY_MEDIA_CODE_IS_NOT_SET'));
            }
            $gallery = new IngalleryModel();
            $albumSettings = $gallery->getAlbumDefaults();

            $view = new IngalleryView();
            $view->set('comments', IngalleryModel::getMediaComments($mediaCode, $albumSettings['cache_lifetime']));
            $view->setTemplate('popup-comments');

            $responce = array(
                'status' => 'success',
                'media_code' => $mediaCode,
                'html' => $view->render()
            );
        } catch (Exception $e) {
            $msg = $e->getMessage();
            IngalleryLogger::log($e->getMessage());

            if ((int) $config->get('display_errors', 1) != 1) {
                $msg = JText::_('COM_INGALLERY_SYTEM_ERROR_PLEASE_REFRES');
            }
            $responce = array(
                'status' => 'error',
                'message' => $msg
            );
        }
        header('Content-Type: application/json', true);
        echo json_encode($responce);
        $app->close();
    }

    private function _utf8ize($mixed) {
        if (is_array($mixed)) {
            foreach ($mixed as $key => $value) {
                $mixed[$key] = $this->_utf8ize($value);
            }
        } else if (is_string($mixed)) {
            return utf8_encode($mixed);
        }
        return $mixed;
    }

}
