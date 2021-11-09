<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
//Params
$border_color	 = $params->get('border_color', '');
$border_size	 = $params->get('border_size', '');
$button			 = $params->get('button', '');
$imagebutton	 = $params->get('imagebutton', '');
$button_pos		 = $params->get('button_pos', 'left');
$button_text	 = $params->get('button_text', JText::_('Search'));
$button_text	 =  JText::_($button_text) ;
$width			 = intval($params->get('width', 20));
$maxlength		 = $width > 20 ? $width : 20;
$text			 = $params->get('text', JText::_('search...'));
$text 			 = JText::_($text) ;
$moduleclass_sfx = $params->get('moduleclass_sfx', '');
if ($imagebutton) {
    $img = modSearchHelper::getSearchImage( $button_text );
}

//S5 Ajax search parameters
$moreResultsText = $params->get('more_results_text', 'Search Results - Click Here For Full Results');
$moreResultsText = JText::_($moreResultsText) ;

$noResultsText = $params->get('no_results_text', 'No results');
$noResultsText = JText::_($noResultsText) ;

$popupWidth = $params->get('popup_width', 400);
$_SESSION['popupWidth'] = $popupWidth;

$searchPhrase = $params->get('searchphrase', 'any');

$ordering = $params->get('ordering', 'newest');

$limit = $params->get('limit', 10);

$bar_font_color = $params->get('bar_font_color', '');
$_SESSION['bar_font_color'] = $bar_font_color;

$bar_font_size = $params->get('bar_font_size', '');
$_SESSION['bar_font_size'] = $bar_font_size;

$bar_bg_color = $params->get('bar_bg_color', '');
$_SESSION['bar_bg_color'] = $bar_bg_color;

$bar_padding_lr = $params->get('bar_padding_lr', '');
$_SESSION['bar_padding_lr'] = $bar_padding_lr;

$bar_padding_tb = $params->get('bar_padding_tb', '');
$_SESSION['bar_padding_tb'] = $bar_padding_tb;

$result1_font_color = $params->get('result1_font_color', '');
$_SESSION['result1_font_color'] = $result1_font_color;

$result2_font_color = $params->get('result2_font_color', '');
$_SESSION['result2_font_color'] = $result2_font_color;

$result1_link_font_color = $params->get('result1_link_font_color', '');
$_SESSION['result1_link_font_color'] = $result1_link_font_color;

$result2_link_font_color = $params->get('result2_link_font_color', '');
$_SESSION['result2_link_font_color'] = $result2_link_font_color;

$result1_readmore_font_color = $params->get('result1_readmore_font_color', '');
$_SESSION['result1_readmore_font_color'] = $result1_readmore_font_color;

$result2_readmore_font_color = $params->get('result2_readmore_font_color', '');
$_SESSION['result2_readmore_font_color'] = $result2_readmore_font_color;

$result_font_size = $params->get('result_font_size', '');
$_SESSION['result_font_size'] = $result_font_size;

$result1_bg_color = $params->get('result1_bg_color', '');
$_SESSION['result1_bg_color'] = $result1_bg_color;

$result2_bg_color = $params->get('result2_bg_color', '');
$_SESSION['result2_bg_color'] = $result2_bg_color;

$result1_bg_hover_color = $params->get('result1_bg_hover_color', '');
$_SESSION['result1_bg_hover_color'] = $result1_bg_hover_color;

$result2_bg_hover_color = $params->get('result2_bg_hover_color', '');
$_SESSION['result2_bg_hover_color'] = $result2_bg_hover_color;

$result_padding_lr = $params->get('result_padding_lr', '');
$_SESSION['result_padding_lr'] = $result_padding_lr;

$result_padding_tb = $params->get('result_padding_tb', '');
$_SESSION['result_padding_tb'] = $result_padding_tb;

$searchword_color = $params->get('searchword_color', '');
$_SESSION['searchword_color'] = $searchword_color;

$readmore_text = $params->get('readmore_text', '');
$_SESSION['readmore_text'] = $readmore_text;

$icon_ml = $params->get('icon_ml', '');
$_SESSION['icon_ml'] = $icon_ml;

$icon_mt = $params->get('icon_mt', '');
$_SESSION['icon_mt'] = $icon_mt;

$icon_height = $params->get('icon_height', '');
$_SESSION['icon_height'] = $icon_height;

$popup_ml = $params->get('popup_ml', '');
$_SESSION['popup_ml'] = $popup_ml;

$popup_mb = $params->get('popup_mb', '');
$_SESSION['popup_mb'] = $popup_mb;

$popup_mr = $params->get('popup_mr', '');
$_SESSION['popup_mr'] = $popup_mr;

$popup_mt = $params->get('popup_mt', '');
$_SESSION['popup_mt'] = $popup_mt;

$bar_border_color = $params->get('bar_border_color', '');
$_SESSION['bar_border_color'] = $bar_border_color;

$result1_border_color = $params->get('result1_border_color', '');
$_SESSION['result1_border_color'] = $result1_border_color;

$result2_border_color = $params->get('result2_border_color', '');
$_SESSION['result2_border_color'] = $result2_border_color;


//Add css and js code to header

$document = & JFactory::getDocument();
$url = JURI::root().'modules/mod_s5_live_search/';
$document->addScript($url.'js/s5_ls_fade.js');
require(JModuleHelper::getLayoutPath('mod_s5_live_search'));



?>

