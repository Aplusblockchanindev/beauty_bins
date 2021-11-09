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
JHtml::_('jquery.framework');
JPluginHelper::importPlugin('content');
$document = JFactory::getDocument();
$base_url=JURI::root();
$modHelper = new modAutoHelper;
$min = $minify?".min":"";
$document->addStyleSheet(JURI::root().DIRP."css/jquery.aolp$min.css");
if($animation!="none"){$document->addStyleSheet(JURI::root().DIRP."css/animate.min.css");}
$modHelper->addScript(DIRP."js/jquery.aolp$min.js",$jsfiles);
$modHelper->addScript(DIRP."js/jquery.aolp-media$min.js",$jsfiles);
$modHelper->addScript(DIRP."js/jquery-cookie$min.js",$jsfiles);
require dirname(__FILE__) .DS.'../layouts/js.php';
require dirname(__FILE__) .DS.'../layouts/css.php';

//Disable after login
$login=1;
if($loginDisable){
$user = JFactory::getUser(); 
$login = !($user->guest) ? 0 : 1;
}

//For Mobile Devices
if($mobileDevices){
require_once(JPATH_ROOT.'/modules/mod_onpageload_popup/mobiledetect.php');
$detect = new AutoPopupMobileDetect();	
$mobile=$detect->isMobile();
}
else{
$mobile=0;
}

?>

<?php if(!$mobile){?>
<?php if($login){?>
<div class="aolp-container" id="aopl-<?php echo $mid;?>">
<div class="aolp-wrapper"><?php $modHelper->createContent($input_method,$params,$mid);?></div>
</div>
<?php }?>
<?php }?>