<?php
/*------------------------------------------------------------------------
# mod_onpageload_popup - Auto onPageLoad Popup
# ------------------------------------------------------------------------
# author    Infyways Solutions
# copyright Copyright (C) 2020 Infyways Solutions. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.infyways.com
# Technical Support:  Forum - http://support.infyways/com
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access');
?>

<div class="aolp-none" id="aolp-box-<?php echo $mid;?>">
<div class="aolp-box-inner" id="aolp-box-inner-<?php echo $mid;?>">
<?php
echo JHtml::_('content.prepare',$params->get('message_above'), '', 'mod_onpageload_popup');
echo $modHelper->getModule($params->get('mod_id'));
echo JHtml::_('content.prepare',$params->get('message_below'), '', 'mod_onpageload_popup');
?>
</div>
</div>
