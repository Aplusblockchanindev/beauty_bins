<?php
/**
@version 2.0: mod_s5_image_and_content_fader
Author: Shape 5 - Professional Template Community
Available for download at www.shape5.com
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


/* START: Adding slides to an array */
$s5_fader_list = array();
if ($picture1_s5_iacf != '') {
    $s5_fader_list[] = array(
        'picture' => $picture1_s5_iacf,
        'title' => $title1,
        'text' => $picture1text_s5_iacf,
        'link'=>$picture1link_s5_iacf==''?'"javascript:;"':$picture1link_s5_iacf,
    );
}
if ($picture2_s5_iacf != '') {
    $s5_fader_list[] = array(
        'picture' => $picture2_s5_iacf,
        'title' => $title2,
        'text' => $picture2text_s5_iacf,
        'link'=>$picture2link_s5_iacf==''?'"javascript:;"':$picture2link_s5_iacf,
    );
}
if ($picture3_s5_iacf != '') {
    $s5_fader_list[] = array(
        'picture' => $picture3_s5_iacf,
        'title' => $title3,
        'text' => $picture3text_s5_iacf,
        'link'=>$picture3link_s5_iacf==''?'"javascript:;"':$picture3link_s5_iacf,
    );
}
if ($picture4_s5_iacf != '') {
    $s5_fader_list[] = array(
        'picture' => $picture4_s5_iacf,
        'title' => $title4,
        'text' => $picture4text_s5_iacf,
        'link'=>$picture4link_s5_iacf==''?'"javascript:;"':$picture4link_s5_iacf,
    );
}
if ($picture5_s5_iacf != '') {
    $s5_fader_list[] = array(
        'picture' => $picture5_s5_iacf,
        'title' => $title5,
        'text' => $picture5text_s5_iacf,
        'link'=>$picture5link_s5_iacf==''?'"javascript:;"':$picture5link_s5_iacf,
    );
}
if ($picture6_s5_iacf != '') {
    $s5_fader_list[] = array(
        'picture' => $picture6_s5_iacf,
        'title' => $title6,
        'text' => $picture6text_s5_iacf,
        'link'=>$picture6link_s5_iacf==''?'"javascript:;"':$picture6link_s5_iacf,
    );
}
if ($picture7_s5_iacf != '') {
    $s5_fader_list[] = array(
        'picture' => $picture7_s5_iacf,
        'title' => $title7,
        'text' => $picture7text_s5_iacf,
        'link'=>$picture7link_s5_iacf==''?'"javascript:;"':$picture7link_s5_iacf,
    );
}
if ($picture8_s5_iacf != '') {
    $s5_fader_list[] = array(
        'picture' => $picture8_s5_iacf,
        'title' => $title8,
        'text' => $picture8text_s5_iacf,
        'link'=>$picture8link_s5_iacf==''?'"javascript:;"':$picture8link_s5_iacf,
    );
}
if ($picture9_s5_iacf != '') {
    $s5_fader_list[] = array(
        'picture' => $picture9_s5_iacf,
        'title' => $title9,
        'text' => $picture9text_s5_iacf,
        'link'=>$picture9link_s5_iacf==''?'"javascript:;"':$picture9link_s5_iacf,
    );
}
if ($picture10_s5_iacf != '') {
    $s5_fader_list[] = array(
        'picture' => $picture10_s5_iacf,
        'title' => $title10,
        'text' => $picture10text_s5_iacf,
        'link'=>$picture10link_s5_iacf==''?'"javascript:;"':$picture10link_s5_iacf,
    );
}
/* END: Adding slides to an array */

/* START: Randomize order of slides */
if($s5_randomorder == 'yes') {
    shuffle($s5_fader_list);
}
/* END: Randomize order of slides */
?>

<?php if ($pretext_s5_iacf != "") { ?>
<br />
<?php echo $pretext_s5_iacf ?>
<br /><br />
<?php } ?>


<?php
$br = strtolower($_SERVER['HTTP_USER_AGENT']); // what browser.
if(strrpos($br,"msie 6") > 1) {
$iss_ie6_s5_iacf = "yes";} 
else {
$iss_ie6_s5_iacf = "no";}
?>


<?php
$br = strtolower($_SERVER['HTTP_USER_AGENT']); // what browser.
if(strrpos($br,"msie 7") > 1) {
$iss_ie7_s5_iacf = "yes";} 
else {
$iss_ie7_s5_iacf = "no";}
?>

<?php
$br = strtolower($_SERVER['HTTP_USER_AGENT']); // what browser.
if(strrpos($br,"msie 8") > 1) {
$iss_ie8_s5_iacf = "yes";} 
else {
$iss_ie8_s5_iacf = "no";}

$s5ic_url = JURI::root().'modules/mod_s5_image_and_content_fader/';
$doc = JFactory::getDocument();
$doc->addCustomTag('<script type="text/javascript">var s5_randomorder = "'.$s5_randomorder.'";</script>');
$doc->addCustomTag('<script type="text/javascript">var s5_hidecar = "'.$s5_hidecar.'";</script>');
$doc->addCustomTag('<script type="text/javascript">var s5_hidebut = "'.$s5_hidebut.'";</script>');
$doc->addCustomTag('<script type="text/javascript">var s5_hidetext = "'.$s5_hidetext.'";</script>');
$doc->addCustomTag('<script type="text/javascript">var s5_hideh2 = "'.$s5_hideh2.'";</script>');

if ($s5_imagecover == "true") { $doc->addCustomTag('<style>.jdGallery .slideElement {background-size: cover !important;}</style>'); }
?>
	
<?php 

if ($jslibrary == "mootools") {

	$template_vertex = "no";
	$app = JFactory::getApplication();
	$template = $app->getTemplate();
	$template_json_location = $_SERVER['DOCUMENT_ROOT'].JURI::root(true).'/templates/'.$template.'/vertex.json';
	if(file_exists($template_json_location)) { 
	$template_vertex = "yes";
	}
		$doc->addCustomTag('<script type="text/javascript">var s5_dropdowntext = "'.$s5_dropdowntext.'";</script>');
		if($template_vertex == "no"){ ?>
		<script type="text/javascript">//<![CDATA[
		if(jQuery.easing.easeOutExpo==undefined){
		document.write('<script src="<?php echo $s5ic_url; ?>js/jquery-ui.min.js"><\/script>');
		}
		//]]></script>
		<script type="text/javascript">jQuery.noConflict();</script>
		<?php }
		if ($s5_thumbnailstretch == "true") { $doc->addCustomTag('<style>.carouselInner .thumbnail {background-size: 100% 100% !important;;}</style>');	}
		if ($s5_hidefaderimages == "yes") { $doc->addCustomTag('<style>.jdGallery .slideElement {background:none !important;}</style>'); }
		
		
		$doc->addCustomTag('<script type="text/javascript">var s5_slide_opacity='.$s5_slide_opacity.'</script>');
		$doc->addCustomTag('<script type="text/javascript">var s5_verticalhorizontal = "'.$s5_verticalhorizontal.'";</script>');
		
		$doc->addCustomTag('<script src="'.$s5ic_url.'js/jd.gallery.jquery.js" type="text/javascript"></script>');
		$doc->addCustomTag('<script src="'.$s5ic_url.'js/jd.gallery.transitions.jquery.js" type="text/javascript"></script>');
	?>
		
		<link href="<?php echo $s5ic_url;?>css/s5imagecontent.css" rel="stylesheet"  property="stylesheet" type="text/css" media="screen" />

		
		
			<script type="text/javascript">

			
				function s5_icfstartGallery() { 
				document.getElementById("s5_iacf_content_wrap").style.display = 'block';
				window.myGallery = new gallery(jQuery('#myGallery'), {
						timed: true,
						showArrows: <?php echo $s5_hidebut ?>,
						showCarousel: <?php echo $s5_hidecar ?>,
						showInfopane: true,	
						slideShowDuration:<?php echo $s5_opacity_time; ?>,
						slideHideDuration:<?php echo $s5_hide_time; ?>,
						fadeDuration:<?php echo $s5_hide_time; ?>,
						randomOrder:"<?php echo $s5_randomorder; ?>",						
						<?php if($s5_hidetext == "truee") {?>
						textShowCarousel: <?php echo $s5_dropdowntext ?>,
						<?php } ?>	
						delay: <?php echo $s5_delay ?>,
						<?php if ($jseffect	== "fade") { ?>
							defaultTransition: "fade"
						<?php } ?>	
						<?php if ($jseffect	== "continuoushorizontal") { ?>
							defaultTransition: "continuoushorizontal"
						<?php } ?>	
						<?php if ($jseffect	== "continuousvertical") { ?>
						defaultTransition: "continuousvertical"
						<?php } ?>
						<?php if ($jseffect	== "slideleft") { ?>
						    defaultTransition: "slideleft"
						<?php } ?>
                        <?php if ($jseffect	== "slideright") { ?>
						    defaultTransition: "slideright"
						<?php } ?>
					});
					<?php if ($hoverstopplay == "yes") { ?>
					jQuery('#myGallery').bind('mouseover',function(){window.myGallery.clearTimer();});
					jQuery('#myGallery').bind('mouseout',function(){window.myGallery.prepareTimer();});
					<?php } ?>
			}
		function s5_icfstartGalleryload() {
		s5_icfstartGallery();}
		window.setTimeout(s5_icfstartGalleryload,400);		
		<?php if ($fullscreenheight == "yes") { ?>
		jQuery( document ).ready(function() { var s5_custom23winhght2 = jQuery( window ).height(); jQuery('#myGallery').css("height", s5_custom23winhght2); });					
		jQuery( window ).resize(function() { var s5_custom23winhght2 = jQuery( window ).height(); jQuery('#myGallery').css("height", s5_custom23winhght2); });
		<?php } ?>
	</script>
	
		<?php if ($s5_verticalhorizontal == "true") { ?>
	<style>	
		.jdGallery .carousel .carouselWrapper, .jdExtCarousel .carouselWrapper, .jdGallery .carousel, .jdGallery div.carouselContainer {height:<?php echo $height_s5_iacf ?>;}
	</style>
	<?php } ?>
	
	
		<?php if ($IACFmethod == "html") { ?>	
			<div class="content <?php if ($s5_verticalhorizontal == "true") { ?>s5vertical<?php } ?>" style="position:relative;z-index:0">
				<div id="myGallery" style="background:#<?php echo $background_s5_iacf ?>;<?php if ($s5stretchimage != "stretch" && $s5stretchimage != "zoom") { ?>height:<?php echo $height_s5_iacf ?>;<?php } ?>width:<?php echo $width_s5_iacf ?>;">
				<?php if ($s5stretchimage == "stretch" || $s5stretchimage == "zoom") { ?>
					<div id="myGallery_height">
						<img id="myGallery_height_img" alt="<?php echo $picture1_s5_iacf; ?>" src="<?php echo $picture1_s5_iacf; ?>" />
					</div>
				<?php } ?>
				 <div id="s5_iacf_content_wrap" style="display:none">
					<?php foreach ($s5_fader_list as $s5_fader_entry) { ?>
						<div class="imageElement" style="z-index:0;">
							<?php if ($s5_hideh2 == "true") { ?>
							<h3><?php echo $s5_fader_entry['title']; ?></h3>
							<?php } ?>
							<?php if ($s5_hidetext == "true") { ?>
							<p style="text-shadow:1px 1px #000000;"><?php echo $s5_fader_entry['text']; ?></p>
							<?php } else {?>
							<p></p>
							<?php } ?>
							<?php if ($s5_fader_entry['link'] == "" || $s5_fader_entry['link'] == "javascript:;" || $s5_fader_entry['link'] == '"javascript:;"') { ?>
							<a href="javascript:;" title="open image" class="open"></a>
							<?php } else { ?>
							<a href="<?php echo $s5_fader_entry['link']; ?>" title="open image" class="open"></a>
							<?php } ?>
							<img src="<?php echo $s5_fader_entry['picture']; ?>" alt="<?php echo $s5_fader_entry['title']; ?>" class="full" />
							<img src="<?php echo $s5_fader_entry['picture']; ?>" alt="<?php echo $s5_fader_entry['title']; ?>" class="thumbnail" />

						</div>
					<?php } ?>
				</div>
								
				</div>
			</div>		
		<?php } ?>
		<?php if ($IACFmethod == "article") { ?>	
		
			<div class="content <?php if ($s5_verticalhorizontal == "true") { ?>s5vertical<?php } ?>" style="position:relative;z-index:0">
				<div id="myGallery" style="background:#<?php echo $background_s5_iacf ?>;<?php if ($s5stretchimage != "stretch" && $s5stretchimage != "zoom") { ?>height:<?php echo $height_s5_iacf ?>;<?php } ?>width:<?php echo $width_s5_iacf ?>;">
				<?php if ($s5stretchimage == "stretch" || $s5stretchimage == "zoom") { ?>
					<div id="myGallery_height">
					<?php						
					// below grabs first image from first article and then stops the loop	
					$i=0; foreach($list as $item){?>	
							<?php require JModuleHelper::getLayoutPath('mod_s5_image_and_content_fader', '_firstimage'); ?>
					<?php $i++; if($i==1) break; }?>
					</div>
				<?php } ?>
				 <div id="s5_iacf_content_wrap" style="display:none">
					<?php foreach ($list as $item) : ?>	
						<?php require JModuleHelper::getLayoutPath('mod_s5_image_and_content_fader', '_item'); ?>
					<?php endforeach; ?>
				</div>
								
				</div>
			</div>			
		
			

		<?php } ?>
		
<?php } ?>
	


	
<?php if ($jslibrary == "s5effects") { ?>		
	
<div style="z-index:0;position: relative; overflow: hidden; height: <?php echo $height_s5_iacf ?>">

<div id="s5_iacf_outer" style="z-index:1;position: relative; max-height:<?php echo $height_s5_iacf ?>; max-width:<?php echo $width_s5_iacf ?>; overflow:hidden; background:#<?php echo $background_s5_iacf ?>">

<script type="text/javascript">
<?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>
var s5_iacf_inc = 12;
<?php } ?>
<?php if ($iss_ie6_s5_iacf == "no" && $iss_ie7_s5_iacf == "no" && $iss_ie8_s5_iacf == "no") { ?>
var s5_iacf_inc = 18;
<?php } ?>
</script>

<?php if ($picture1_s5_iacf != "") { ?>

<div id="picture1_s5_iacf" style="padding:0px; display:none; height:<?php echo $height_s5_iacf ?>; opacity:.0; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width_s5_iacf ?>; overflow:hidden; background-image: url(<?php echo $picture1_s5_iacf ?>); background-repeat: no-repeat">
<?php if ($picture1link_s5_iacf != "") { ?>
<a href="<?php echo $picture1link_s5_iacf ?>" target="<?php echo $picture1target_s5_iacf ?>">
<img alt="" id="picture1_blank_s5_iacf" style="border:none" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
</a>
<?php } ?>

<?php if ($picture1link_s5_iacf == "") { ?>
<img alt="" style="border:none" id="picture1_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
<?php } ?>

<?php if ($picture1text_s5_iacf != "") { ?>

<div id="picture1text_bg_s5_iacf" style="z-index:1;position: relative; opacity:<?php echo $non_ie_picture1textopac_s5_iacf ?>; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=<?php echo $picture1textopac_s5_iacf ?>); <?php } ?> background:#<?php echo $picture1textbg_s5_iacf ?>">
</div>

<div id="picture1text_s5_iacf" style="text-shadow:1px 1px #000000;z-index:1;height:auto; position: relative; padding:<?php echo $picture1spacing_s5_iacf ?>; color:#<?php echo $picture1textcolor_s5_iacf ?>; font-weight:<?php echo $picture1textweight_s5_iacf ?>; font-size:<?php echo $picture1textsize_s5_iacf ?>">
<h2 style="font-size: 15px;text-shadow:1px 1px #000000;color:#FFFFFF;padding-bottom:3px;font-weight: bold;"><?php echo $title1 ?></h2>
<?php echo $picture1text_s5_iacf ?>
</div>

<script type="text/javascript">
function set_picture1_loaders() {
document.getElementById("picture1_blank_s5_iacf").style.height = "<?php echo $height_s5_iacf ?>";
window.setTimeout('picture1text_load_bg_s5_iacf()',0);
window.setTimeout('picture1text_effect_big()',<?php echo $text_display_effect ?>);
window.setTimeout('picture1text_effect_small()',<?php echo $text_display_time_s5_iacf ?>);
}

function picture1text_load_bg_s5_iacf() {
document.getElementById("picture1text_s5_iacf").style.marginTop = (document.getElementById("picture1text_s5_iacf").offsetHeight * -1) + "px";
document.getElementById("picture1text_bg_s5_iacf").style.height = document.getElementById("picture1text_s5_iacf").offsetHeight + "px";
}

function picture1text_effect_big_timer() {
window.setTimeout('picture1text_effect_big()',10);
}

function picture1text_effect_big() {
var s5_outer_iacf = document.getElementById("s5_iacf_outer").offsetHeight;
if (document.getElementById("picture1_blank_s5_iacf").offsetHeight > s5_outer_iacf - document.getElementById("picture1text_s5_iacf").offsetHeight + 7) {
document.getElementById("picture1_blank_s5_iacf").style.height = document.getElementById("picture1_blank_s5_iacf").offsetHeight - s5_iacf_inc + "px";
picture1text_effect_big_timer();
}
else {
document.getElementById("picture1_blank_s5_iacf").style.height = document.getElementById("s5_iacf_outer").offsetHeight - document.getElementById("picture1text_s5_iacf").offsetHeight + "px";
}
}

function picture1text_effect_small_timer() {
window.setTimeout('picture1text_effect_small()',10);
}

</script>

<?php } ?>

</div>

<?php } ?>

<?php if ($picture2_s5_iacf != "") { ?>

<div id="picture2_s5_iacf" style="padding:0px; display:none; height:<?php echo $height_s5_iacf ?>; opacity:.0; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width_s5_iacf ?>; overflow:hidden; background-image: url(<?php echo $picture2_s5_iacf ?>); background-repeat: no-repeat">
<?php if ($picture2link_s5_iacf != "") { ?>
<a href="<?php echo $picture2link_s5_iacf ?>" target="<?php echo $picture2target_s5_iacf ?>">
<img alt="" style="border:none" id="picture2_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
</a>
<?php } ?>

<?php if ($picture2link_s5_iacf == "") { ?>
<img alt="" style="border:none" id="picture2_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
<?php } ?>

<?php if ($picture2text_s5_iacf != "") { ?>

<div id="picture2text_bg_s5_iacf" style="z-index:1;position: relative; opacity:<?php echo $non_ie_picture2textopac_s5_iacf ?>; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=<?php echo $picture2textopac_s5_iacf ?>); <?php } ?> background:#<?php echo $picture2textbg_s5_iacf ?>">
</div>

<div id="picture2text_s5_iacf" style="text-shadow:1px 1px #000000;z-index:1;height:auto; position: relative; padding:<?php echo $picture2spacing_s5_iacf ?>; color:#<?php echo $picture2textcolor_s5_iacf ?>; font-weight:<?php echo $picture2textweight_s5_iacf ?>; font-size:<?php echo $picture2textsize_s5_iacf ?>">
<h2 style="font-size: 15px;text-shadow:1px 1px #000000;color:#FFFFFF;padding-bottom:3px;font-weight: bold;"><?php echo $title2 ?></h2>
<?php echo $picture2text_s5_iacf ?>
</div>

<script type="text/javascript">
function set_picture2_loaders() {
document.getElementById("picture2_blank_s5_iacf").style.height = "<?php echo $height_s5_iacf ?>";
window.setTimeout('picture2text_load_bg_s5_iacf()',0);
window.setTimeout('picture2text_effect_big()',<?php echo $text_display_effect ?>);
window.setTimeout('picture2text_effect_small()',<?php echo $text_display_time_s5_iacf ?>);
}

function picture2text_load_bg_s5_iacf() {
document.getElementById("picture2text_s5_iacf").style.marginTop = (document.getElementById("picture2text_s5_iacf").offsetHeight * -1) + "px";
document.getElementById("picture2text_bg_s5_iacf").style.height = document.getElementById("picture2text_s5_iacf").offsetHeight + "px";
}

function picture2text_effect_big_timer() {
window.setTimeout('picture2text_effect_big()',10);
}

function picture2text_effect_big() {
var s5_outer_iacf = document.getElementById("s5_iacf_outer").offsetHeight;
if (document.getElementById("picture2_blank_s5_iacf").offsetHeight > s5_outer_iacf - document.getElementById("picture2text_s5_iacf").offsetHeight + 7) {
document.getElementById("picture2_blank_s5_iacf").style.height = document.getElementById("picture2_blank_s5_iacf").offsetHeight - s5_iacf_inc + "px";
picture2text_effect_big_timer();
}
else {
document.getElementById("picture2_blank_s5_iacf").style.height = document.getElementById("s5_iacf_outer").offsetHeight - document.getElementById("picture2text_s5_iacf").offsetHeight + "px";
}
}

function picture2text_effect_small_timer() {
window.setTimeout('picture2text_effect_small()',10);
}

</script>

<?php } ?>

</div>

<?php } ?>

<?php if ($picture3_s5_iacf != "") { ?>

<div id="picture3_s5_iacf" style="padding:0px; display:none; height:<?php echo $height_s5_iacf ?>; opacity:.0; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width_s5_iacf ?>; overflow:hidden; background-image: url(<?php echo $picture3_s5_iacf ?>); background-repeat: no-repeat">
<?php if ($picture3link_s5_iacf != "") { ?>
<a href="<?php echo $picture3link_s5_iacf ?>" target="<?php echo $picture3target_s5_iacf ?>">
<img alt="" style="border:none" id="picture3_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
</a>
<?php } ?>

<?php if ($picture3link_s5_iacf == "") { ?>
<img alt="" style="border:none" id="picture3_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
<?php } ?>

<?php if ($picture3text_s5_iacf != "") { ?>

<div id="picture3text_bg_s5_iacf" style="z-index:1;position: relative; opacity:<?php echo $non_ie_picture3textopac_s5_iacf ?>; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=<?php echo $picture3textopac_s5_iacf ?>); <?php } ?> background:#<?php echo $picture3textbg_s5_iacf ?>">
</div>

<div id="picture3text_s5_iacf" style="text-shadow:1px 1px #000000;z-index:1;height:auto; position: relative; padding:<?php echo $picture3spacing_s5_iacf ?>; color:#<?php echo $picture3textcolor_s5_iacf ?>; font-weight:<?php echo $picture3textweight_s5_iacf ?>; font-size:<?php echo $picture3textsize_s5_iacf ?>">
<h2 style="font-size: 15px;text-shadow:1px 1px #000000;color:#FFFFFF;padding-bottom:3px;font-weight: bold;"><?php echo $title3 ?></h2>
<?php echo $picture3text_s5_iacf ?>
</div>

<script type="text/javascript">
function set_picture3_loaders() {
document.getElementById("picture3_blank_s5_iacf").style.height = "<?php echo $height_s5_iacf ?>";
window.setTimeout('picture3text_load_bg_s5_iacf()',0);
window.setTimeout('picture3text_effect_big()',<?php echo $text_display_effect ?>);
window.setTimeout('picture3text_effect_small()',<?php echo $text_display_time_s5_iacf ?>);
}

function picture3text_load_bg_s5_iacf() {
document.getElementById("picture3text_s5_iacf").style.marginTop = (document.getElementById("picture3text_s5_iacf").offsetHeight * -1) + "px";
document.getElementById("picture3text_bg_s5_iacf").style.height = document.getElementById("picture3text_s5_iacf").offsetHeight + "px";
}

function picture3text_effect_big_timer() {
window.setTimeout('picture3text_effect_big()',10);
}

function picture3text_effect_big() {
var s5_outer_iacf = document.getElementById("s5_iacf_outer").offsetHeight;
if (document.getElementById("picture3_blank_s5_iacf").offsetHeight > s5_outer_iacf - document.getElementById("picture3text_s5_iacf").offsetHeight + 7) {
document.getElementById("picture3_blank_s5_iacf").style.height = document.getElementById("picture3_blank_s5_iacf").offsetHeight - s5_iacf_inc + "px";
picture3text_effect_big_timer();
}
else {
document.getElementById("picture3_blank_s5_iacf").style.height = document.getElementById("s5_iacf_outer").offsetHeight - document.getElementById("picture3text_s5_iacf").offsetHeight + "px";
}
}

function picture3text_effect_small_timer() {
window.setTimeout('picture3text_effect_small()',10);
}

</script>

<?php } ?>

</div>

<?php } ?>

<?php if ($picture4_s5_iacf != "") { ?>

<div id="picture4_s5_iacf" style="padding:0px; display:none; height:<?php echo $height_s5_iacf ?>; opacity:.0; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width_s5_iacf ?>; overflow:hidden; background-image: url(<?php echo $picture4_s5_iacf ?>); background-repeat: no-repeat">
<?php if ($picture4link_s5_iacf != "") { ?>
<a href="<?php echo $picture4link_s5_iacf ?>" target="<?php echo $picture4target_s5_iacf ?>">
<img alt="" style="border:none" id="picture4_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
</a>
<?php } ?>

<?php if ($picture4link_s5_iacf == "") { ?>
<img alt="" style="border:none" id="picture4_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
<?php } ?>

<?php if ($picture4text_s5_iacf != "") { ?>

<div id="picture4text_bg_s5_iacf" style="z-index:1;position: relative; opacity:<?php echo $non_ie_picture4textopac_s5_iacf ?>; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=<?php echo $picture4textopac_s5_iacf ?>); <?php } ?> background:#<?php echo $picture4textbg_s5_iacf ?>">
</div>

<div id="picture4text_s5_iacf" style="text-shadow:1px 1px #000000;z-index:1;height:auto; position: relative; padding:<?php echo $picture4spacing_s5_iacf ?>; color:#<?php echo $picture4textcolor_s5_iacf ?>; font-weight:<?php echo $picture4textweight_s5_iacf ?>; font-size:<?php echo $picture4textsize_s5_iacf ?>">
<h2 style="font-size: 15px;text-shadow:1px 1px #000000;color:#FFFFFF;padding-bottom:3px;font-weight: bold;"><?php echo $title4 ?></h2>
<?php echo $picture4text_s5_iacf ?>
</div>

<script type="text/javascript">
function set_picture4_loaders() {
document.getElementById("picture4_blank_s5_iacf").style.height = "<?php echo $height_s5_iacf ?>";
window.setTimeout('picture4text_load_bg_s5_iacf()',0);
window.setTimeout('picture4text_effect_big()',<?php echo $text_display_effect ?>);
window.setTimeout('picture4text_effect_small()',<?php echo $text_display_time_s5_iacf ?>);
}

function picture4text_load_bg_s5_iacf() {
document.getElementById("picture4text_s5_iacf").style.marginTop = (document.getElementById("picture4text_s5_iacf").offsetHeight * -1) + "px";
document.getElementById("picture4text_bg_s5_iacf").style.height = document.getElementById("picture4text_s5_iacf").offsetHeight + "px";
}

function picture4text_effect_big_timer() {
window.setTimeout('picture4text_effect_big()',10);
}

function picture4text_effect_big() {
var s5_outer_iacf = document.getElementById("s5_iacf_outer").offsetHeight;
if (document.getElementById("picture4_blank_s5_iacf").offsetHeight > s5_outer_iacf - document.getElementById("picture4text_s5_iacf").offsetHeight + 7) {
document.getElementById("picture4_blank_s5_iacf").style.height = document.getElementById("picture4_blank_s5_iacf").offsetHeight - s5_iacf_inc + "px";
picture4text_effect_big_timer();
}
else {
document.getElementById("picture4_blank_s5_iacf").style.height = document.getElementById("s5_iacf_outer").offsetHeight - document.getElementById("picture4text_s5_iacf").offsetHeight + "px";
}
}

function picture4text_effect_small_timer() {
window.setTimeout('picture4text_effect_small()',10);
}

</script>

<?php } ?>

</div>

<?php } ?>

<?php if ($picture5_s5_iacf != "") { ?>

<div id="picture5_s5_iacf" style="padding:0px; display:none; height:<?php echo $height_s5_iacf ?>; opacity:.0; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width_s5_iacf ?>; overflow:hidden; background-image: url(<?php echo $picture5_s5_iacf ?>); background-repeat: no-repeat">
<?php if ($picture5link_s5_iacf != "") { ?>
<a href="<?php echo $picture5link_s5_iacf ?>" target="<?php echo $picture5target_s5_iacf ?>">
<img alt="" style="border:none" id="picture5_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
</a>
<?php } ?>

<?php if ($picture5link_s5_iacf == "") { ?>
<img alt="" style="border:none" id="picture5_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
<?php } ?>

<?php if ($picture5text_s5_iacf != "") { ?>

<div id="picture5text_bg_s5_iacf" style="z-index:1;position: relative; opacity:<?php echo $non_ie_picture5textopac_s5_iacf ?>; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=<?php echo $picture5textopac_s5_iacf ?>); <?php } ?> background:#<?php echo $picture5textbg_s5_iacf ?>">
</div>

<div id="picture5text_s5_iacf" style="text-shadow:1px 1px #000000;z-index:1;height:auto; position: relative; padding:<?php echo $picture5spacing_s5_iacf ?>; color:#<?php echo $picture5textcolor_s5_iacf ?>; font-weight:<?php echo $picture5textweight_s5_iacf ?>; font-size:<?php echo $picture5textsize_s5_iacf ?>">
<h2 style="font-size: 15px;text-shadow:1px 1px #000000;color:#FFFFFF;padding-bottom:3px;font-weight: bold;"><?php echo $title5 ?></h2>
<?php echo $picture5text_s5_iacf ?>
</div>

<script type="text/javascript">
function set_picture5_loaders() {
document.getElementById("picture5_blank_s5_iacf").style.height = "<?php echo $height_s5_iacf ?>";
window.setTimeout('picture5text_load_bg_s5_iacf()',0);
window.setTimeout('picture5text_effect_big()',<?php echo $text_display_effect ?>);
window.setTimeout('picture5text_effect_small()',<?php echo $text_display_time_s5_iacf ?>);
}

function picture5text_load_bg_s5_iacf() {
document.getElementById("picture5text_s5_iacf").style.marginTop = (document.getElementById("picture5text_s5_iacf").offsetHeight * -1) + "px";
document.getElementById("picture5text_bg_s5_iacf").style.height = document.getElementById("picture5text_s5_iacf").offsetHeight + "px";
}

function picture5text_effect_big_timer() {
window.setTimeout('picture5text_effect_big()',10);
}

function picture5text_effect_big() {
var s5_outer_iacf = document.getElementById("s5_iacf_outer").offsetHeight;
if (document.getElementById("picture5_blank_s5_iacf").offsetHeight > s5_outer_iacf - document.getElementById("picture5text_s5_iacf").offsetHeight + 7) {
document.getElementById("picture5_blank_s5_iacf").style.height = document.getElementById("picture5_blank_s5_iacf").offsetHeight - s5_iacf_inc + "px";
picture5text_effect_big_timer();
}
else {
document.getElementById("picture5_blank_s5_iacf").style.height = document.getElementById("s5_iacf_outer").offsetHeight - document.getElementById("picture5text_s5_iacf").offsetHeight + "px";
}
}

function picture5text_effect_small_timer() {
window.setTimeout('picture5text_effect_small()',10);
}

</script>

<?php } ?>

</div>

<?php } ?>

<?php if ($picture6_s5_iacf != "") { ?>

<div id="picture6_s5_iacf" style="padding:0px; display:none; height:<?php echo $height_s5_iacf ?>; opacity:.0; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width_s5_iacf ?>; overflow:hidden; background-image: url(<?php echo $picture6_s5_iacf ?>); background-repeat: no-repeat">
<?php if ($picture6link_s5_iacf != "") { ?>
<a href="<?php echo $picture6link_s5_iacf ?>" target="<?php echo $picture6target_s5_iacf ?>">
<img alt="" style="border:none" id="picture6_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
</a>
<?php } ?>

<?php if ($picture6link_s5_iacf == "") { ?>
<img alt="" style="border:none" id="picture6_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
<?php } ?>

<?php if ($picture6text_s5_iacf != "") { ?>

<div id="picture6text_bg_s5_iacf" style="z-index:1;position: relative; opacity:<?php echo $non_ie_picture6textopac_s5_iacf ?>; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=<?php echo $picture6textopac_s5_iacf ?>); <?php } ?> background:#<?php echo $picture6textbg_s5_iacf ?>">
</div>

<div id="picture6text_s5_iacf" style="text-shadow:1px 1px #000000;z-index:1;height:auto; position: relative; padding:<?php echo $picture6spacing_s5_iacf ?>; color:#<?php echo $picture6textcolor_s5_iacf ?>; font-weight:<?php echo $picture6textweight_s5_iacf ?>; font-size:<?php echo $picture6textsize_s5_iacf ?>">
<h2 style="font-size: 15px;text-shadow:1px 1px #000000;color:#FFFFFF;padding-bottom:3px;font-weight: bold;"><?php echo $title6 ?></h2>
<?php echo $picture6text_s5_iacf ?>
</div>

<script type="text/javascript">
function set_picture6_loaders() {
document.getElementById("picture6_blank_s5_iacf").style.height = "<?php echo $height_s5_iacf ?>";
window.setTimeout('picture6text_load_bg_s5_iacf()',0);
window.setTimeout('picture6text_effect_big()',<?php echo $text_display_effect ?>);
window.setTimeout('picture6text_effect_small()',<?php echo $text_display_time_s5_iacf ?>);
}

function picture6text_load_bg_s5_iacf() {
document.getElementById("picture6text_s5_iacf").style.marginTop = (document.getElementById("picture6text_s5_iacf").offsetHeight * -1) + "px";
document.getElementById("picture6text_bg_s5_iacf").style.height = document.getElementById("picture6text_s5_iacf").offsetHeight + "px";
}

function picture6text_effect_big_timer() {
window.setTimeout('picture6text_effect_big()',10);
}

function picture6text_effect_big() {
var s5_outer_iacf = document.getElementById("s5_iacf_outer").offsetHeight;
if (document.getElementById("picture6_blank_s5_iacf").offsetHeight > s5_outer_iacf - document.getElementById("picture6text_s5_iacf").offsetHeight + 7) {
document.getElementById("picture6_blank_s5_iacf").style.height = document.getElementById("picture6_blank_s5_iacf").offsetHeight - s5_iacf_inc + "px";
picture6text_effect_big_timer();
}
else {
document.getElementById("picture6_blank_s5_iacf").style.height = document.getElementById("s5_iacf_outer").offsetHeight - document.getElementById("picture6text_s5_iacf").offsetHeight + "px";
}
}

function picture6text_effect_small_timer() {
window.setTimeout('picture6text_effect_small()',10);
}

</script>

<?php } ?>

</div>

<?php } ?>

<?php if ($picture7_s5_iacf != "") { ?>

<div id="picture7_s5_iacf" style="padding:0px; display:none; height:<?php echo $height_s5_iacf ?>; opacity:.0; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width_s5_iacf ?>; overflow:hidden; background-image: url(<?php echo $picture7_s5_iacf ?>); background-repeat: no-repeat">
<?php if ($picture7link_s5_iacf != "") { ?>
<a href="<?php echo $picture7link_s5_iacf ?>" target="<?php echo $picture7target_s5_iacf ?>">
<img alt="" style="border:none" id="picture7_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
</a>
<?php } ?>

<?php if ($picture7link_s5_iacf == "") { ?>
<img alt="" style="border:none" id="picture7_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
<?php } ?>

<?php if ($picture7text_s5_iacf != "") { ?>

<div id="picture7text_bg_s5_iacf" style="z-index:1;position: relative; opacity:<?php echo $non_ie_picture7textopac_s5_iacf ?>; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=<?php echo $picture7textopac_s5_iacf ?>); <?php } ?> background:#<?php echo $picture7textbg_s5_iacf ?>">
</div>

<div id="picture7text_s5_iacf" style="text-shadow:1px 1px #000000;z-index:1;height:auto; position: relative; padding:<?php echo $picture7spacing_s5_iacf ?>; color:#<?php echo $picture7textcolor_s5_iacf ?>; font-weight:<?php echo $picture7textweight_s5_iacf ?>; font-size:<?php echo $picture7textsize_s5_iacf ?>">
<h2 style="font-size: 15px;text-shadow:1px 1px #000000;color:#FFFFFF;padding-bottom:3px;font-weight: bold;"><?php echo $title7 ?></h2>
<?php echo $picture7text_s5_iacf ?>
</div>

<script type="text/javascript">
function set_picture7_loaders() {
document.getElementById("picture7_blank_s5_iacf").style.height = "<?php echo $height_s5_iacf ?>";
window.setTimeout('picture7text_load_bg_s5_iacf()',0);
window.setTimeout('picture7text_effect_big()',<?php echo $text_display_effect ?>);
window.setTimeout('picture7text_effect_small()',<?php echo $text_display_time_s5_iacf ?>);
}

function picture7text_load_bg_s5_iacf() {
document.getElementById("picture7text_s5_iacf").style.marginTop = (document.getElementById("picture7text_s5_iacf").offsetHeight * -1) + "px";
document.getElementById("picture7text_bg_s5_iacf").style.height = document.getElementById("picture7text_s5_iacf").offsetHeight + "px";
}

function picture7text_effect_big_timer() {
window.setTimeout('picture7text_effect_big()',10);
}

function picture7text_effect_big() {
var s5_outer_iacf = document.getElementById("s5_iacf_outer").offsetHeight;
if (document.getElementById("picture7_blank_s5_iacf").offsetHeight > s5_outer_iacf - document.getElementById("picture7text_s5_iacf").offsetHeight + 7) {
document.getElementById("picture7_blank_s5_iacf").style.height = document.getElementById("picture7_blank_s5_iacf").offsetHeight - s5_iacf_inc + "px";
picture7text_effect_big_timer();
}
else {
document.getElementById("picture7_blank_s5_iacf").style.height = document.getElementById("s5_iacf_outer").offsetHeight - document.getElementById("picture7text_s5_iacf").offsetHeight + "px";
}
}

function picture7text_effect_small_timer() {
window.setTimeout('picture7text_effect_small()',10);
}

</script>

<?php } ?>

</div>

<?php } ?>

<?php if ($picture8_s5_iacf != "") { ?>

<div id="picture8_s5_iacf" style="padding:0px; display:none; height:<?php echo $height_s5_iacf ?>; opacity:.0; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width_s5_iacf ?>; overflow:hidden; background-image: url(<?php echo $picture8_s5_iacf ?>); background-repeat: no-repeat">
<?php if ($picture8link_s5_iacf != "") { ?>
<a href="<?php echo $picture8link_s5_iacf ?>" target="<?php echo $picture8target_s5_iacf ?>">
<img alt="" style="border:none" id="picture8_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
</a>
<?php } ?>

<?php if ($picture8link_s5_iacf == "") { ?>
<img alt="" style="border:none" id="picture8_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
<?php } ?>

<?php if ($picture8text_s5_iacf != "") { ?>

<div id="picture8text_bg_s5_iacf" style="z-index:1;position: relative; opacity:<?php echo $non_ie_picture8textopac_s5_iacf ?>; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=<?php echo $picture8textopac_s5_iacf ?>); <?php } ?> background:#<?php echo $picture8textbg_s5_iacf ?>">
</div>

<div id="picture8text_s5_iacf" style="text-shadow:1px 1px #000000;z-index:1;height:auto; position: relative; padding:<?php echo $picture8spacing_s5_iacf ?>; color:#<?php echo $picture8textcolor_s5_iacf ?>; font-weight:<?php echo $picture8textweight_s5_iacf ?>; font-size:<?php echo $picture8textsize_s5_iacf ?>">
<h2 style="font-size: 15px;text-shadow:1px 1px #000000;color:#FFFFFF;padding-bottom:3px;font-weight: bold;"><?php echo $title8 ?></h2>
<?php echo $picture8text_s5_iacf ?>
</div>

<script type="text/javascript">
function set_picture8_loaders() {
document.getElementById("picture8_blank_s5_iacf").style.height = "<?php echo $height_s5_iacf ?>";
window.setTimeout('picture8text_load_bg_s5_iacf()',0);
window.setTimeout('picture8text_effect_big()',<?php echo $text_display_effect ?>);
window.setTimeout('picture8text_effect_small()',<?php echo $text_display_time_s5_iacf ?>);
}

function picture8text_load_bg_s5_iacf() {
document.getElementById("picture8text_s5_iacf").style.marginTop = (document.getElementById("picture8text_s5_iacf").offsetHeight * -1) + "px";
document.getElementById("picture8text_bg_s5_iacf").style.height = document.getElementById("picture8text_s5_iacf").offsetHeight + "px";
}

function picture8text_effect_big_timer() {
window.setTimeout('picture8text_effect_big()',10);
}

function picture8text_effect_big() {
var s5_outer_iacf = document.getElementById("s5_iacf_outer").offsetHeight;
if (document.getElementById("picture8_blank_s5_iacf").offsetHeight > s5_outer_iacf - document.getElementById("picture8text_s5_iacf").offsetHeight + 7) {
document.getElementById("picture8_blank_s5_iacf").style.height = document.getElementById("picture8_blank_s5_iacf").offsetHeight - s5_iacf_inc + "px";
picture8text_effect_big_timer();
}
else {
document.getElementById("picture8_blank_s5_iacf").style.height = document.getElementById("s5_iacf_outer").offsetHeight - document.getElementById("picture8text_s5_iacf").offsetHeight + "px";
}
}

function picture8text_effect_small_timer() {
window.setTimeout('picture8text_effect_small()',10);
}

</script>

<?php } ?>

</div>

<?php } ?>

<?php if ($picture9_s5_iacf != "") { ?>

<div id="picture9_s5_iacf" style="padding:0px; display:none; height:<?php echo $height_s5_iacf ?>; opacity:.0; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width_s5_iacf ?>; overflow:hidden; background-image: url(<?php echo $picture9_s5_iacf ?>); background-repeat: no-repeat">
<?php if ($picture9link_s5_iacf != "") { ?>
<a href="<?php echo $picture9link_s5_iacf ?>" target="<?php echo $picture9target_s5_iacf ?>">
<img alt="" style="border:none" id="picture9_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
</a>
<?php } ?>

<?php if ($picture9link_s5_iacf == "") { ?>
<img alt="" style="border:none" id="picture9_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
<?php } ?>

<?php if ($picture9text_s5_iacf != "") { ?>

<div id="picture9text_bg_s5_iacf" style="z-index:1;position: relative; opacity:<?php echo $non_ie_picture9textopac_s5_iacf ?>; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=<?php echo $picture9textopac_s5_iacf ?>); <?php } ?> background:#<?php echo $picture9textbg_s5_iacf ?>">
</div>

<div id="picture9text_s5_iacf" style="text-shadow:1px 1px #000000;z-index:1;height:auto; position: relative; padding:<?php echo $picture9spacing_s5_iacf ?>; color:#<?php echo $picture9textcolor_s5_iacf ?>; font-weight:<?php echo $picture9textweight_s5_iacf ?>; font-size:<?php echo $picture9textsize_s5_iacf ?>">
<h2 style="font-size: 15px;text-shadow:1px 1px #000000;color:#FFFFFF;padding-bottom:3px;font-weight: bold;"><?php echo $title9 ?></h2>
<?php echo $picture9text_s5_iacf ?>
</div>

<script type="text/javascript">
function set_picture9_loaders() {
document.getElementById("picture9_blank_s5_iacf").style.height = "<?php echo $height_s5_iacf ?>";
window.setTimeout('picture9text_load_bg_s5_iacf()',0);
window.setTimeout('picture9text_effect_big()',<?php echo $text_display_effect ?>);
window.setTimeout('picture9text_effect_small()',<?php echo $text_display_time_s5_iacf ?>);
}

function picture9text_load_bg_s5_iacf() {
document.getElementById("picture9text_s5_iacf").style.marginTop = (document.getElementById("picture9text_s5_iacf").offsetHeight * -1) + "px";
document.getElementById("picture9text_bg_s5_iacf").style.height = document.getElementById("picture9text_s5_iacf").offsetHeight + "px";
}

function picture9text_effect_big_timer() {
window.setTimeout('picture9text_effect_big()',10);
}

function picture9text_effect_big() {
var s5_outer_iacf = document.getElementById("s5_iacf_outer").offsetHeight;
if (document.getElementById("picture9_blank_s5_iacf").offsetHeight > s5_outer_iacf - document.getElementById("picture9text_s5_iacf").offsetHeight + 7) {
document.getElementById("picture9_blank_s5_iacf").style.height = document.getElementById("picture9_blank_s5_iacf").offsetHeight - s5_iacf_inc + "px";
picture9text_effect_big_timer();
}
else {
document.getElementById("picture9_blank_s5_iacf").style.height = document.getElementById("s5_iacf_outer").offsetHeight - document.getElementById("picture9text_s5_iacf").offsetHeight + "px";
}
}

function picture9text_effect_small_timer() {
window.setTimeout('picture9text_effect_small()',10);
}

</script>

<?php } ?>

</div>

<?php } ?>

<?php if ($picture10_s5_iacf != "") { ?>

<div id="picture10_s5_iacf" style="padding:0px; display:none; height:<?php echo $height_s5_iacf ?>; opacity:.0; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=0); -moz-opacity: 0;<?php } ?> width:<?php echo $width_s5_iacf ?>; overflow:hidden; background-image: url(<?php echo $picture10_s5_iacf ?>); background-repeat: no-repeat">
<?php if ($picture10link_s5_iacf != "") { ?>
<a href="<?php echo $picture10link_s5_iacf ?>" target="<?php echo $picture10target_s5_iacf ?>">
<img alt="" style="border:none" id="picture10_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
</a>
<?php } ?>

<?php if ($picture10link_s5_iacf == "") { ?>
<img alt="" style="border:none" id="picture10_blank_s5_iacf" src="modules/mod_s5_image_and_content_fader/images/blank.gif" height="<?php echo $height_s5_iacf ?>" width="<?php echo $width_s5_iacf ?>"></img>
<?php } ?>

<?php if ($picture10text_s5_iacf != "") { ?>

<div id="picture10text_bg_s5_iacf" style="z-index:1;position: relative; opacity:<?php echo $non_ie_picture10textopac_s5_iacf ?>; <?php if ($iss_ie6_s5_iacf == "yes" || $iss_ie7_s5_iacf == "yes" || $iss_ie8_s5_iacf == "yes") { ?>filter: alpha(opacity=<?php echo $picture10textopac_s5_iacf ?>); <?php } ?> background:#<?php echo $picture10textbg_s5_iacf ?>">
</div>

<div id="picture10text_s5_iacf" style="text-shadow:1px 1px #000000;z-index:1;height:auto; position: relative; padding:<?php echo $picture10spacing_s5_iacf ?>; color:#<?php echo $picture10textcolor_s5_iacf ?>; font-weight:<?php echo $picture10textweight_s5_iacf ?>; font-size:<?php echo $picture10textsize_s5_iacf ?>">
<h2 style="font-size: 15px;text-shadow:1px 1px #000000;color:#FFFFFF;padding-bottom:3px;font-weight: bold;"><?php echo $title10 ?></h2>
<?php echo $picture10text_s5_iacf ?>
</div>

<script type="text/javascript">
function set_picture10_loaders() {
document.getElementById("picture10_blank_s5_iacf").style.height = "<?php echo $height_s5_iacf ?>";
window.setTimeout('picture10text_load_bg_s5_iacf()',0);
window.setTimeout('picture10text_effect_big()',<?php echo $text_display_effect ?>);
window.setTimeout('picture10text_effect_small()',<?php echo $text_display_time_s5_iacf ?>);
}

function picture10text_load_bg_s5_iacf() {
document.getElementById("picture10text_s5_iacf").style.marginTop = (document.getElementById("picture10text_s5_iacf").offsetHeight * -1) + "px";
document.getElementById("picture10text_bg_s5_iacf").style.height = document.getElementById("picture10text_s5_iacf").offsetHeight + "px";
}

function picture10text_effect_big_timer() {
window.setTimeout('picture10text_effect_big()',10);
}

function picture10text_effect_big() {
var s5_outer_iacf = document.getElementById("s5_iacf_outer").offsetHeight;
if (document.getElementById("picture10_blank_s5_iacf").offsetHeight > s5_outer_iacf - document.getElementById("picture10text_s5_iacf").offsetHeight + 7) {
document.getElementById("picture10_blank_s5_iacf").style.height = document.getElementById("picture10_blank_s5_iacf").offsetHeight - s5_iacf_inc + "px";
picture10text_effect_big_timer();
}
else {
document.getElementById("picture10_blank_s5_iacf").style.height = document.getElementById("s5_iacf_outer").offsetHeight - document.getElementById("picture10text_s5_iacf").offsetHeight + "px";
}
}

function picture10text_effect_small_timer() {
window.setTimeout('picture10text_effect_small()',10);
}

</script>

<?php } ?>

</div>

<?php } ?>


</div>

</div>

<script type="text/javascript" src="modules/mod_s5_image_and_content_fader/js/fader.js"></script>

<script type="text/javascript">


function picture1_s5_iacf(id_s5_iacf) {
        document.getElementById(id_s5_iacf).style.display = "block";
	opacity_s5_iacf(id_s5_iacf, 0, 100, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture1_done_s5_iacf()',<?php echo $display_time_s5_iacf ?>);
}

function picture1_done_s5_iacf(){
	picture1_doneload_s5_iacf('picture1_s5_iacf');
}

function picture1_doneload_s5_iacf(id_s5_iacf) {
	opacity_s5_iacf(id_s5_iacf, 100, 0, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture1_next_s5_iacf()',<?php echo $tween_time_s5_iacf ?>);
}

function picture1_next_s5_iacf(id_s5_iacf) {
        document.getElementById('picture1_s5_iacf').style.display = "none";
	if (document.getElementById('picture2_s5_iacf')) {
		picture2_s5_iacf('picture2_s5_iacf');
		<?php if ($picture2text_s5_iacf != "") { ?>
		set_picture2_loaders();
		<?php } ?>
	}
	else {
		picture1_s5_iacf('picture1_s5_iacf');
		<?php if ($picture1text_s5_iacf != "") { ?>
		set_picture1_loaders();
		<?php } ?>
	}
}


function picture2_s5_iacf(id_s5_iacf) {
        document.getElementById(id_s5_iacf).style.display = "block";
	opacity_s5_iacf(id_s5_iacf, 0, 100, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture2_done_s5_iacf()',<?php echo $display_time_s5_iacf ?>);
}

function picture2_done_s5_iacf(){
	picture2_doneload_s5_iacf('picture2_s5_iacf');
}

function picture2_doneload_s5_iacf(id_s5_iacf) {
	opacity_s5_iacf(id_s5_iacf, 100, 0, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture2_next_s5_iacf()',<?php echo $tween_time_s5_iacf ?>);
}

function picture2_next_s5_iacf(id_s5_iacf) {
        document.getElementById('picture2_s5_iacf').style.display = "none";
	if (document.getElementById('picture3_s5_iacf')) {
		picture3_s5_iacf('picture3_s5_iacf');
		<?php if ($picture3text_s5_iacf != "") { ?>
		set_picture3_loaders();
		<?php } ?>
	}
	else {
		picture1_s5_iacf('picture1_s5_iacf');
		<?php if ($picture1text_s5_iacf != "") { ?>
		set_picture1_loaders();
		<?php } ?>
	}
}


function picture3_s5_iacf(id_s5_iacf) {
        document.getElementById(id_s5_iacf).style.display = "block";
	opacity_s5_iacf(id_s5_iacf, 0, 100, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture3_done_s5_iacf()',<?php echo $display_time_s5_iacf ?>);
}

function picture3_done_s5_iacf(){
	picture3_doneload_s5_iacf('picture3_s5_iacf');
}

function picture3_doneload_s5_iacf(id_s5_iacf) {
	opacity_s5_iacf(id_s5_iacf, 100, 0, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture3_next_s5_iacf()',<?php echo $tween_time_s5_iacf ?>);
}

function picture3_next_s5_iacf(id_s5_iacf) {
        document.getElementById('picture3_s5_iacf').style.display = "none";
	if (document.getElementById('picture4_s5_iacf')) {
		picture4_s5_iacf('picture4_s5_iacf');
		<?php if ($picture4text_s5_iacf != "") { ?>
		set_picture4_loaders();
		<?php } ?>
	}
	else {
		picture1_s5_iacf('picture1_s5_iacf');
		<?php if ($picture1text_s5_iacf != "") { ?>
		set_picture1_loaders();
		<?php } ?>
	}
}


function picture4_s5_iacf(id_s5_iacf) {
        document.getElementById(id_s5_iacf).style.display = "block";
	opacity_s5_iacf(id_s5_iacf, 0, 100, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture4_done_s5_iacf()',<?php echo $display_time_s5_iacf ?>);
}

function picture4_done_s5_iacf(){
	picture4_doneload_s5_iacf('picture4_s5_iacf');
}

function picture4_doneload_s5_iacf(id_s5_iacf) {
	opacity_s5_iacf(id_s5_iacf, 100, 0, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture4_next_s5_iacf()',<?php echo $tween_time_s5_iacf ?>);
}

function picture4_next_s5_iacf(id_s5_iacf) {
        document.getElementById('picture4_s5_iacf').style.display = "none";
	if (document.getElementById('picture5_s5_iacf')) {
		picture5_s5_iacf('picture5_s5_iacf');
		<?php if ($picture5text_s5_iacf != "") { ?>
		set_picture5_loaders();
		<?php } ?>
	}
	else {
		picture1_s5_iacf('picture1_s5_iacf');
		<?php if ($picture1text_s5_iacf != "") { ?>
		set_picture1_loaders();
		<?php } ?>
	}
}


function picture5_s5_iacf(id_s5_iacf) {
        document.getElementById(id_s5_iacf).style.display = "block";
	opacity_s5_iacf(id_s5_iacf, 0, 100, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture5_done_s5_iacf()',<?php echo $display_time_s5_iacf ?>);
}

function picture5_done_s5_iacf(){
	picture5_doneload_s5_iacf('picture5_s5_iacf');
}

function picture5_doneload_s5_iacf(id_s5_iacf) {
	opacity_s5_iacf(id_s5_iacf, 100, 0, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture5_next_s5_iacf()',<?php echo $tween_time_s5_iacf ?>);
}

function picture5_next_s5_iacf(id_s5_iacf) {
        document.getElementById('picture5_s5_iacf').style.display = "none";
	if (document.getElementById('picture6_s5_iacf')) {
		picture6_s5_iacf('picture6_s5_iacf');
		<?php if ($picture6text_s5_iacf != "") { ?>
		set_picture6_loaders();
		<?php } ?>
	}
	else {
		picture1_s5_iacf('picture1_s5_iacf');
		<?php if ($picture1text_s5_iacf != "") { ?>
		set_picture1_loaders();
		<?php } ?>
	}
}


function picture6_s5_iacf(id_s5_iacf) {
        document.getElementById(id_s5_iacf).style.display = "block";
	opacity_s5_iacf(id_s5_iacf, 0, 100, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture6_done_s5_iacf()',<?php echo $display_time_s5_iacf ?>);
}

function picture6_done_s5_iacf(){
	picture6_doneload_s5_iacf('picture6_s5_iacf');
}

function picture6_doneload_s5_iacf(id_s5_iacf) {
	opacity_s5_iacf(id_s5_iacf, 100, 0, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture6_next_s5_iacf()',<?php echo $tween_time_s5_iacf ?>);
}

function picture6_next_s5_iacf(id_s5_iacf) {
        document.getElementById('picture6_s5_iacf').style.display = "none";
	if (document.getElementById('picture7_s5_iacf')) {
		picture7_s5_iacf('picture7_s5_iacf');
		<?php if ($picture7text_s5_iacf != "") { ?>
		set_picture7_loaders();
		<?php } ?>
	}
	else {
		picture1_s5_iacf('picture1_s5_iacf');
		<?php if ($picture1text_s5_iacf != "") { ?>
		set_picture1_loaders();
		<?php } ?>
	}
}


function picture7_s5_iacf(id_s5_iacf) {
        document.getElementById(id_s5_iacf).style.display = "block";
	opacity_s5_iacf(id_s5_iacf, 0, 100, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture7_done_s5_iacf()',<?php echo $display_time_s5_iacf ?>);
}

function picture7_done_s5_iacf(){
	picture7_doneload_s5_iacf('picture7_s5_iacf');
}

function picture7_doneload_s5_iacf(id_s5_iacf) {
	opacity_s5_iacf(id_s5_iacf, 100, 0, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture7_next_s5_iacf()',<?php echo $tween_time_s5_iacf ?>);
}

function picture7_next_s5_iacf(id_s5_iacf) {
        document.getElementById('picture7_s5_iacf').style.display = "none";
	if (document.getElementById('picture8_s5_iacf')) {
		picture8_s5_iacf('picture8_s5_iacf');
		<?php if ($picture8text_s5_iacf != "") { ?>
		set_picture8_loaders();
		<?php } ?>
	}
	else {
		picture1_s5_iacf('picture1_s5_iacf');
		<?php if ($picture1text_s5_iacf != "") { ?>
		set_picture1_loaders();
		<?php } ?>
	}
}


function picture8_s5_iacf(id_s5_iacf) {
        document.getElementById(id_s5_iacf).style.display = "block";
	opacity_s5_iacf(id_s5_iacf, 0, 100, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture8_done_s5_iacf()',<?php echo $display_time_s5_iacf ?>);
}

function picture8_done_s5_iacf(){
	picture8_doneload_s5_iacf('picture8_s5_iacf');
}

function picture8_doneload_s5_iacf(id_s5_iacf) {
	opacity_s5_iacf(id_s5_iacf, 100, 0, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture8_next_s5_iacf()',<?php echo $tween_time_s5_iacf ?>);
}

function picture8_next_s5_iacf(id_s5_iacf) {
        document.getElementById('picture8_s5_iacf').style.display = "none";
	if (document.getElementById('picture9_s5_iacf')) {
		picture9_s5_iacf('picture9_s5_iacf');
		<?php if ($picture9text_s5_iacf != "") { ?>
		set_picture9_loaders();
		<?php } ?>
	}
	else {
		picture1_s5_iacf('picture1_s5_iacf');
		<?php if ($picture1text_s5_iacf != "") { ?>
		set_picture1_loaders();
		<?php } ?>
	}
}


function picture9_s5_iacf(id_s5_iacf) {
        document.getElementById(id_s5_iacf).style.display = "block";
	opacity_s5_iacf(id_s5_iacf, 0, 100, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture9_done_s5_iacf()',<?php echo $display_time_s5_iacf ?>);
}

function picture9_done_s5_iacf(){
	picture9_doneload_s5_iacf('picture9_s5_iacf');
}

function picture9_doneload_s5_iacf(id_s5_iacf) {
	opacity_s5_iacf(id_s5_iacf, 100, 0, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture9_next_s5_iacf()',<?php echo $tween_time_s5_iacf ?>);
}

function picture9_next_s5_iacf(id_s5_iacf) {
        document.getElementById('picture9_s5_iacf').style.display = "none";
	if (document.getElementById('picture10_s5_iacf')) {
		picture10_s5_iacf('picture10_s5_iacf');
		<?php if ($picture10text_s5_iacf != "") { ?>
		set_picture10_loaders();
		<?php } ?>
	}
	else {
		picture1_s5_iacf('picture1_s5_iacf');
		<?php if ($picture1text_s5_iacf != "") { ?>
		set_picture1_loaders();
		<?php } ?>
	}
}


function picture10_s5_iacf(id_s5_iacf) {
        document.getElementById(id_s5_iacf).style.display = "block";
	opacity_s5_iacf(id_s5_iacf, 0, 100, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture10_done_s5_iacf()',<?php echo $display_time_s5_iacf ?>);
}

function picture10_done_s5_iacf(){
	picture10_doneload_s5_iacf('picture10_s5_iacf');
}

function picture10_doneload_s5_iacf(id_s5_iacf) {
	opacity_s5_iacf(id_s5_iacf, 100, 0, <?php echo $tween_time_s5_iacf ?>);
        window.setTimeout('picture10_next_s5_iacf()',<?php echo $tween_time_s5_iacf ?>);
}

function picture10_next_s5_iacf(id_s5_iacf) {
    document.getElementById('picture10_s5_iacf').style.display = "none";
	picture1_s5_iacf('picture1_s5_iacf');
	<?php if ($picture1text_s5_iacf != "") { ?>
	set_picture1_loaders();
	<?php } ?>
}

picture1_s5_iacf('picture1_s5_iacf');
<?php if ($picture1text_s5_iacf != "") { ?>
set_picture1_loaders();
<?php } ?>

</script>
<?php } ?>
