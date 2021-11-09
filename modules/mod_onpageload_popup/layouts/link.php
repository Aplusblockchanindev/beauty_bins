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
$onclickHide = $params->get('onclickHide',0);
$stickyElementbgColor = $params->get('stickyElementbgColor');
$stickyElementColor = $params->get('stickyElementColor');
$popMedia = $params->get('popupMedia','');
$popupImage = $params->get('popupImage','');
$onclickElement = $params->get('onclickElement',1);
$onclickText = $params->get('onclickText',"");
$onclickImage = $params->get('onclickImage',1);


/*Conditons*/
$background = $onclickHide?"style=\"background:$stickyElementbgColor;color:$stickyElementColor;\"":"";
$onclickHide = $onclickHide?"onclickLink":"onclickLink-hide";
$iframe = $input==2?" aolp.iframe":"";
$link = $input==2?$popMedia:$popupImage;
$href = $input==0||$input==1?"#aolp-box-$mid":$link;
$hrefText = $onclickElement?$onclickText:"<img class=\"onclickPopup-image\" src='".JURI::root().$onclickImage."'/>";


?>


<a id="aolp-<?php echo $mid;?>" class="auto-popup<?php echo $mid;?> <?php echo $onclickHide.$iframe;?> stickyElement <?php echo $params->get('stickyElement');?>" <?php echo $background;?> href="<?php echo $href;?>"> <?php  
if($onclickHide){echo $hrefText;}?></a>
