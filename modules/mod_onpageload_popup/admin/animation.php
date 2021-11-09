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

class JFormFieldAnimation extends JFormField
{
	protected $type = 'animation';
	
    public function getInput() {	
		$document = JFactory::getDocument();
		$css ="";
		$document->addStyleDeclaration($css);		
			 
	
//Attributes
$animation_effects  = isset($this->value)?$this->value : "";

//Animation List
$animations = array(
'Fading'=>[
	'fadeIn' => 'Fade In',
				'fadeInDown' => 'Fade In Down',
				'fadeInDownBig' => 'Fade In Down Big',
				'fadeInLeft' => 'Fade In Left',
				'fadeInLeftBig' => 'Fade In Left Big',
				'fadeInRight' => 'Fade In Right',
				'fadeInRightBig' => 'Fade In Right Big',
				'fadeInUp' => 'Fade In Up',
				'fadeInUpBig' => 'Fade In Up Big',
				'fadeInTopLeft' => 'Fade In Top Left',
				'fadeInTopRight' => 'Fade In Top Right',
				'fadeInBottomLeft' => 'Fade In Bottom Left',
				'fadeInBottomRight' => 'Fade In Bottom Right'
],

'Zooming'=>[
		'zoomIn' => 'Zoom In',
				'zoomInDown' => 'Zoom In Down',
				'zoomInLeft' => 'Zoom In Left',
				'zoomInRight' => 'Zoom In Right',
				'zoomInUp' => 'Zoom In Up'
],
'Bouncing'=>[
		'bounceIn' => 'Bounce In',
				'bounceInDown' => 'Bounce In Down',
				'bounceInLeft' => 'Bounce In Left',
				'bounceInRight' => 'Bounce In Right',
				'bounceInUp' => 'Bounce In Up'
],

'Sliding'=>[
				'slideInDown' => 'Slide In Down',
				'slideInLeft' => 'Slide In Left',
				'slideInRight' => 'Slide In Right',
				'slideInUp' => 'Slide In Up'
],
'Attention Seekers'=>[
				'bounce' => 'Bounce',
				'flash' => 'Flash',
				'pulse' => 'Pulse',
				'rubberBand' => 'Rubber Band',
				'shakeX' => 'Shake X',
				'shakeY' => 'Shake X',
				'headShake' => 'Head Shake',
				'swing' => 'Swing',
				'tada' => 'Tada',
				'wobble' => 'Wobble',
				'jello' => 'Jello',
				'heartBeat' => 'Heart Beat'
],
'Light Speed'=>[
				'lightSpeedInRight' => 'Light Speed In Right',
				'lightSpeedInLeft' => 'Light Speed In Left'
],

'Specials'=>[
				'rollIn' => 'Roll In',
				'jackInTheBox' => 'Jack In The Box'
]

);				
$posit = '';

foreach( $animations   as $key => $animation ) {
	
  $posit .= '<optgroup label="'.$key.'">';
  
 foreach($animation as $key =>$animat){
  $posit .='<option value="'.$key.'" ' . ( $animation_effects == $key ? 'selected="selected"' : '' ) . '>' 
             . $animat 
             . '</option>';
			 
 }
 $posit .='</optgroup>';
	
}
$html = '
<section class="is-appearance">
	<div class="is-animation-option">
	<span><select id="'.$this->id.'" name="'.$this->name.'"><option value="">None</option>'.$posit.'</select></span>
	</div>
</section>';        
		return $html;
	}
	
}

