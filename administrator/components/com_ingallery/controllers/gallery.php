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

class IngalleryControllerGallery extends JControllerForm {

    protected function allowAdd($data = array()) {
        $user = JFactory::getUser();

        return ($user->authorise('core.create', 'com_ingallery'));
    }

    protected function allowEdit($data = array(), $key = 'id') {
        return parent::allowEdit($data, $key);
    }

    public function batch($model = null) {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $model = $this->getModel('Gallery');

        $this->setRedirect('index.php?option=com_ingallery&view=galleries');

        return parent::batch($model);
    }

    public function copy() {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
        $IDs = $this->input->post->get('cid', array(), 'array');

        try {
            if (count($IDs) == 0) {
                throw new Exception(JText::_('JLIB_HTML_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST'));
            }
            foreach ($IDs as $id) {
                $table = JTable::getInstance('Galleries', 'IngalleryTable');
                $table->load($id);
                if ((int) $table->id <= 0 || (int) $table->id != $id) {
                    throw new Exception(sprintf(JText::_('COM_INGALLERY_GALLERY_NOT_FOUND'), $id));
                }
                $table->id = null;
                $table->title .= ' ' . JText::_('JGLOBAL_COPY');
                $table->store();
                $this->setMessage(JText::_('COM_INGALLERY_GALLERY_COPYED'));
            }
        } catch (Exception $e) {
            $this->setError($e->getMessage());
            $this->setMessage($e->getMessage(), 'error');
        }

        $this->setRedirect('index.php?option=com_ingallery&view=galleries');
    }

    public function save($key = null, $urlVar = null) {
        $app = JFactory::getApplication();
        $recordId = $this->input->getInt('id');
        $context = "$this->option.edit.$this->context";
        $galleryData = $this->input->post->get('gallery', array(), 'array');
        $jformData = $this->input->post->get('jform', array(), 'array');

        try {
            $gallery = new IngalleryModel();
            $gallery->bindInput($galleryData);
            if (!$gallery->check()) {
                throw new Exception($gallery->getError());
            }
            $jformData['config'] = $gallery->getJSON();
            $this->input->post->set('jform', $jformData);
        } catch (Exception $e) {
            $app->setUserState($context . '.data', $input['gallery']);
            $this->setError($e->getMessage());
            $this->setMessage($e->getMessage(), 'error');
            $this->setRedirect(
                    JRoute::_(
                            'index.php?option=' . $this->option . '&view=' . $this->view_item
                            . $this->getRedirectToItemAppend($recordId, 'id'), false
                    )
            );
            return false;
        }
        return parent::save($key, $urlVar);
    }

    public function preview() {
        $app = JFactory::getApplication();

        try {
            $galleryID = rand(11111, 99999);
            $input = $this->input->getArray(array('gallery' => 'array'));
            $page = $this->input->getInt('page', 1);
            $view = new IngalleryView();
            $gallery = new IngalleryModel();
            $gallery->bindInput($input['gallery']);
            if (!$gallery->check()) {
                throw new Exception($gallery->getError());
            }
            
            $view->set('albums', $gallery->get('albums'));
            $view->set('cfg', $gallery->get('cfg'));
            $view->set('page', $page);
            $view->set('items', $gallery->getItemsPage($page));
            $view->set('has_more', $gallery->hasItemsPage($page + 1));
            $view->set('id', $galleryID);

            $view->setTemplate('gallery');
            
            $responce = array(
                'status' => 'success',
                'has_more' => $gallery->hasItemsPage($page + 1),
                'html' => $view->render()
            );
            
            $cache = $gallery::getCache(999);
            $cache->store($gallery->getJSON(), 'tmp_config_'.$galleryID);
            
        } catch (Exception $e) {
            $responce = array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
        echo json_encode($responce);
        $app->close();
    }

    public function comments() {
        $app = JFactory::getApplication();

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
            $responce = array(
                'status' => 'error',
                'message' => $e->getMessage()
            );
        }
        echo json_encode($responce);
        $app->close();
    }

}
