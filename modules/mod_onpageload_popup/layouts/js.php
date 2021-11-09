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
//Close on overlay click
$overlayClose = $closeClick?"":"closeClick: false";

//Hide popup link after cookie set
$hidePopupLink = $hideLink?"":"jQuery(\"#aolp-$module->id\").hide();";


//Youtube and Vimeo
$iframe = $input_method==2? "type: 'iframe',iframe : { preload: false}," :"";
$media = $input_method==2? "media:true,":"";

//Check for Modal
$modal = $modal?"false":"true";
	
//Code for Auto Close
$close = $auto_close?",afterShow: function(){setTimeout('parent.jQuery.aolp.close ()',$close_timer);}":"";

//Code for Auto Open
$open = $auto_open ? "autoPopup$module->id.trigger('click');" : "setTimeout(function () {autoPopup$module->id.trigger('click');}, $open_timer);";

switch($cookie){
    case 0: //Session
    $setCookie="$cookieSet :function(){jQuery.cookie('the_cookie$module->id', 'true' ,{path: '/'});},";
    break;
    case 1: //Days
    $setCookie="$cookieSet :function(){jQuery.cookie('the_cookie$module->id', 'true' ,{ expires: $cookie_expire,path: '/'});},";  
    break;
    case 2:
    $setCookie="$cookieSet :function(){ var cookie_date = new Date(); cookie_date.setTime(cookie_date.getTime() + ($cookie_expire * 60 * 60 * 1000)); jQuery.cookie('the_cookie$module->id', 'true' ,{ expires: cookie_date,path: '/'});},";
    break;
    case 3:
    $setCookie="$cookieSet :function(){ var cookie_date = new Date(); cookie_date.setTime(cookie_date.getTime() + ($cookie_expire * 60 * 1000)); jQuery.cookie('the_cookie$module->id', 'true' ,{ expires: cookie_date,path: '/'});},";
    break;
    case 4:
    $setCookie="$cookieSet :function(){ var cookie_date = new Date(); cookie_date.setTime(cookie_date.getTime() + ($cookie_expire * 1000)); jQuery.cookie('the_cookie$module->id', 'true' ,{ expires: cookie_date,path: '/'});},";
    break;
    
}

$setCookie = $mode?"":$setCookie;
if($input_method==3){
$popImg = $popupImgUrl ?"beforeShow: function() {jQuery(\".aolp-image\").wrap('<a href=\"$popupImgUrl\" target=\"$popupImgUrlTarget\"/>')},":""; 
}
else{
	$popImg="";
}
//Close button style
if($closeButtonStyle){
	$cBstyle = "<a title=\"Close\" id=\"aolp-close$module->id\" class=\"aolp-item aolp-close\" href=\"javascript:;\"></a>";
}
else{
	$cBstyle = "<a title=\"Close\" id=\"aolp-close$module->id\" class=\"aolp-close flat-close-btn $closeButtonPosition \" href=\"javascript:;\">&times;</a>";
}

$popCode="var autoPopup$module->id= jQuery(\"#aolp-$module->id\").aolp({
    	autoSize : $autoSize,
		padding : $popupPadding,
		margin  : $popupMargin,
		modal: $modal,	
		width: $width,
		maxWidth :$width,
		height : $height,
		maxHeight : $height,
		$iframe
		$setCookie
		$popImg
		
		tpl: {
wrap     : '<div class=\"aolp-wrap animate__animated animate__$animation animate__$animationDuration\" tabIndex=\"-1\" id=\"aolp-wrap$module->id\"><div id=\"aolp-skin$module->id\" class=\"aolp-skin\"><div class=\"aolp-outer\"><div class=\"aolp-inner\"></div></div></div></div>',
				closeBtn : '$cBstyle',
			},
		helpers: { $media overlay: { lbwrap: '<div class=\"aolp-overlay\" id=\"aolp-overlay$module->id\"></div>',opacity: $opacity, $overlayClose}}$close
		
});";


// Code for Auto Open

switch($trigger){
    case 1:
    $open="triggered_times = 0;
    jQuery(window).on('scroll', function() {
    var y_scroll_pos = window.pageYOffset;
    var scroll_pos = ".$scrollamount.";   
    if(y_scroll_pos > scroll_pos && triggered_times == 0 ) {
    ".$open."
    triggered_times = 1;   
    }
    });";
    break;
        
    case 2:
    $open =" triggered_times = 0; addPopupEvent(document, 'mouseout', function(evt) {
    if (evt.toElement == null && evt.relatedTarget == null && triggered_times == 0) {
    ".$open."
    triggered_times = 1; 
    };
    });";
    break;
        
    case 3:
    $open = "triggered_times = 0; jQuery(window).scroll(function() {
    if (jQuery('body').height() <= (jQuery(window).height() + jQuery(window).scrollTop()) && triggered_times == 0) {
    ".$open."
    triggered_times = 1;   
    } });";
    break;
    
    case 4:
    $open = "";
    break;

    case 5:
    $open = "jQuery('".$robject."').bind('contextmenu',function(e){
    e.preventDefault();
    if( e.button == 2) { 
    ".$open."
    return false; 
    } 
    return true; 
    }); ";      
}

$modeJs = $mode?$open:"if (jQuery.cookie('the_cookie$module->id')) { $hidePopupLink } else{ $open  }";
$modHelper->addjQueryDec($popCode.$modeJs,$jsfiles);

?>


