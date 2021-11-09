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

$scrolling = $scrollPage?".aolp-lock{overflow:auto!important}":".aolp-lock{overflow:hidden !important}";

switch($bgImage)
{
    case "user":
    $bgImg="url($base_url/$bgUserImg) $bgUserRepeat $bgUserAttachment $bgUserPosition  $bgColor;" 
	."background-size :$bgUserSize;";
    break;
    case 0:
    $bgImg="url() $bgColor";
    break;
    default:
    $bgImg="url(\"$base_url/modules/mod_onpageload_popup/tmpl/images/bg_$bgImage.png\") repeat scroll 0 0";
    break;          
}

if($closeButtonStyle){
$clBtn="#aolp-close$mid{
background:url('$base_url/modules/mod_onpageload_popup/tmpl/images/close_button_$closeButtonStyle.png');}";
}



//CSS for the Popup
$style="
$scrolling
#aolp-overlay$mid{
	background: $bgImg ;
}
#aolp-skin$mid{
background: $popBgColor;
color:$popTxtColor;
border: $borderSize $borderType $borderColor;
border-radius:$popBorderRadius;
}
$clBtn


#aolp-close$mid.flat-close-btn{
color:$flatButtonColor;
font-size: $flatButtonSize;
}
$extraCSS
";

$document->addStyleDeclaration($style);

?>


