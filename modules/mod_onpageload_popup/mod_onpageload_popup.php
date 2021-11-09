<?php
/*------------------------------------------------------------------------
# mod_onpageload_popup - Auto onPageLoad Popup
# ------------------------------------------------------------------------
# author    Infyways Solutions
# copyright Copyright (C) 2021 Infyways Solutions. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.infyways.com
# Technical Support:  Forum - http://support.infyways/com
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access');
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if(!defined('DIRP'))define('DIRP',"modules/mod_onpageload_popup/tmpl/");
require_once dirname(__FILE__) .DS.'helper.php';

$mid = $module->id;
$height= $params->get('height',300);
$width= $params->get('width',500);
$input_method= $params->get('input_method');
$message_above= $params->get('message_above');
$message_below= $params->get('message_below');
$mode= $params->get('mode',1);
$cookie_expire= $params->get('cookie_expire',1);
$jsfiles = $params->get('jsfiles',1);
$opacity = $params->get('opacity',0.5);
$modal = $params->get('modal',1);
$use = $params->get('use');
$message1 = $params->get('message1');
$message2 = $params->get('message2');
$open_timer = $params->get('open_timer');
$close_timer = $params->get('close_timer');
$auto_close = $params->get('auto_close',0);
$auto_open = $params->get('auto_open',0);
$bgColor = $params->get('bgColor','rgba(0, 0, 0, 0.5)');
$mod_id=$params->get('mod_id','');
$popBgColor = $params->get('popBgColor','rgba(255, 255, 255, 1)');
$popTxtColor = $params->get('popTxtColor','rgb(0, 0, 0)');
$bgImage = $params->get('bgImage',0);
$closeButtonStyle = $params->get('closeButtonStyle',1);
$cookieSet = $params->get('cookieSet','afterLoad');
$popupMedia =$params->get('popupMedia','');
$popupPadding = $params->get('popupPadding',10);
$popupMargin = $params->get('popupMargin',20);
$mobileDevices = $params->get('mobileDevices',0);
$loginDisable = $params->get('loginDisable',0);
$cookie= $params->get('cookie',1);
$popBorderRadius = $params->get('popBorderRadius',4)."px";
$scrollPage = $params->get('scrollPage',0);
$trigger = $params->get('trigger',0);
$scrollamount = $params->get('scrollamount',100);
$closeButtonPosition = $params->get('closeButtonPosition',1);


$hideLink = $params->get('hideLink',0);
$closeClick = $params->get('closeClick',1);
$minify = $params->get('minify',0);
$extraCSS = $params->get('extraCSS');
$borderSize = $params->get('borderSize',10)."px";
$borderType = $params->get('borderType',"none");
$borderColor = $params->get('borderColor',"rgba(227, 0, 0, 1)");
$bgUserRepeat = $params->get('bgUserRepeat',"repeat");
$bgUserImg = $params->get('bgUserImg',"");
$bgUserSize = $params->get('bgUserSize',"auto");
$bgUserAttachment = $params->get('bgUserAttachment',"scroll");
$bgUserPosition = $params->get('bgUserPosition',"center");
$flatButtonColor = $params->get('flatButtonColor',"#000000");
$flatButtonSize = $params->get('flatButtonSize',"50px");
$popupImage = $params->get('popupImage',"");
$popupImgUrl = $params->get('popupImgUrl',"");
$popupImgUrlTarget = $params->get('popupImgUrlTarget',"");

$autoSize = $params->get('autoSize',"false");
$robject = $params->get('robject',"body");
$stickyElement = $params->get('stickyElement',"");
$stickyElementbgColor = $params->get('stickyElementbgColor',"");
$stickyElementColor = $params->get('stickyElementColor',"");

//Animation
$animation = $params->get('animation',"");
$animationDuration = $params->get('animationDuration',"");



require(JModuleHelper::getLayoutPath('mod_onpageload_popup'));