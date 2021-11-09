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
defined('_JEXEC') or die;

$doc = JFactory::getDocument();

JHtml::_('jquery.framework');
JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$licenseKey = JComponentHelper::getParams('com_ingallery')->get('purchase_code','');
if($licenseKey==''){
    echo '<form name="adminForm" id="adminForm" method="post" action="'.JRoute::_('index.php?option=com_ingallery').'">'
            .'<input type="hidden" name="task" />'
            .JHtml::_('form.token')
            .'</form>';
    echo '<div class="ingallery-message ing-error"><div class="ing-error-title">'.JText::_('COM_INGALLERY_NOT_ACTIVATED_TITLE').'</div><div class="">'.sprintf(JText::_('COM_INGALLERY_NOT_ACTIVATED_MSG'),JRoute::_('index.php?option=com_config&view=component&component=com_ingallery')).'</div></div>';
    return;
}


$doc->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "gallery.cancel" || document.formvalidator.isValid(document.getElementById("item-form"))) {
			
			Joomla.submitform(task, document.getElementById("item-form"));
		}
        else{
            alert("'.$this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')).'");
        }
	};
');
?>
<form action="<?php echo JRoute::_('index.php?option=com_ingallery&layout=edit&id=' . (int)$this->item->id); ?>" method="post" name="adminForm" id="item-form" class="ingAjaxForm form-validate">

	<?php echo JLayoutHelper::render('joomla.edit.title_alias', $this); ?>

    <div id="ingalleryBackendWrap">
        <div id="ingAppWrap">
            <div id="ingalleryApp" class="ingallery-app" data-ingallery='<?php echo $this->gallery->getJSON();?>' data-id="<?php echo (int)$this->item->id;?>"></div>
        </div>
        <div id="ingalleryDemoWrap">

        </div>
    </div>
	<input type="hidden" name="task" value="" id="ingAction" />
	<?php echo JHtml::_('form.token'); ?>
</form>
