<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

if ($s5_va_width == "100" || $s5_va_width == "100%") { ?>
<script type="text/javascript">//<![CDATA[
    document.write('<style>#s5_va_accordion{width:100% !important;}</style>');
//]]></script>
<?php } ?>

<div id="s5_va_accordion"<?php if ($s5_va_width != "100" && $s5_va_width != "100%") { ?> style="width:<?php echo $s5_va_width ?>px"<?php } ?>>
<div id="s5_va_accordion_inner">
	<?php if(JModuleHelper::getModules('s5_va_1')) { ?>
		<h3 class="s5_va_toggler<?php if($version->RELEASE>='3.0') { ?> s5_va_active<?php } ?>">
			<span class="s5_va_h3_inner_span">
				<?php echo $title1 ?>
			</span>
		</h3>
		<div class="s5_va_element">
			<div class="s5_va_element_inner">
				<?php
					$myblurb_modules = JModuleHelper::getModules( 's5_va_1' );
					foreach ($myblurb_modules as $myblurb) {
					$_options = array( 'style' => 'raw' );
					echo JModuleHelper::renderModule( $myblurb, $_options );
					}
				?>
			</div>
		</div>
	<?php } ?>
	<?php if(JModuleHelper::getModules('s5_va_2')) { ?>
		<h3 class="s5_va_toggler<?php if($version->RELEASE>='3.0') { ?> s5_va_inactive<?php } ?>">
			<span class="s5_va_h3_inner_span">
				<?php echo $title2 ?>
			</span>
		</h3>
		<div class="s5_va_element">
			<div class="s5_va_element_inner">
				<?php
					$myblurb_modules = JModuleHelper::getModules( 's5_va_2' );
					foreach ($myblurb_modules as $myblurb) {
					$_options = array( 'style' => 'raw' );
					echo JModuleHelper::renderModule( $myblurb, $_options );
					}
				?>
			</div>
		</div>
	<?php } ?>
	<?php if(JModuleHelper::getModules('s5_va_3')) { ?>
		<h3 class="s5_va_toggler<?php if($version->RELEASE>='3.0') { ?> s5_va_inactive<?php } ?>">
			<span class="s5_va_h3_inner_span">
				<?php echo $title3 ?>
			</span>
		</h3>
		<div class="s5_va_element">
			<div class="s5_va_element_inner">
				<?php
					$myblurb_modules = JModuleHelper::getModules( 's5_va_3' );
					foreach ($myblurb_modules as $myblurb) {
					$_options = array( 'style' => 'raw' );
					echo JModuleHelper::renderModule( $myblurb, $_options );
					}
				?>
			</div>
		</div>
	<?php } ?>
	<?php if(JModuleHelper::getModules('s5_va_4')) { ?>
		<h3 class="s5_va_toggler<?php if($version->RELEASE>='3.0') { ?> s5_va_inactive<?php } ?>">
			<span class="s5_va_h3_inner_span">
				<?php echo $title4 ?>
			</span>
		</h3>
		<div class="s5_va_element">
			<div class="s5_va_element_inner">
				<?php
					$myblurb_modules = JModuleHelper::getModules( 's5_va_4' );
					foreach ($myblurb_modules as $myblurb) {
					$_options = array( 'style' => 'raw' );
					echo JModuleHelper::renderModule( $myblurb, $_options );
					}
				?>
			</div>
		</div>
	<?php } ?>
	<?php if(JModuleHelper::getModules('s5_va_5')) { ?>
		<h3 class="s5_va_toggler<?php if($version->RELEASE>='3.0') { ?> s5_va_inactive<?php } ?>">
			<span class="s5_va_h3_inner_span">
				<?php echo $title5 ?>
			</span>
		</h3>
		<div class="s5_va_element">
			<div class="s5_va_element_inner">
				<?php
					$myblurb_modules = JModuleHelper::getModules( 's5_va_5' );
					foreach ($myblurb_modules as $myblurb) {
					$_options = array( 'style' => 'raw' );
					echo JModuleHelper::renderModule( $myblurb, $_options );
					}
				?>
			</div>
		</div>
	<?php } ?>
	<?php if(JModuleHelper::getModules('s5_va_6')) { ?>
		<h3 class="s5_va_toggler<?php if($version->RELEASE>='3.0') { ?> s5_va_inactive<?php } ?>">
			<span class="s5_va_h3_inner_span">
				<?php echo $title6 ?>
			</span>
		</h3>
		<div class="s5_va_element">
			<div class="s5_va_element_inner">
				<?php
					$myblurb_modules = JModuleHelper::getModules( 's5_va_6' );
					foreach ($myblurb_modules as $myblurb) {
					$_options = array( 'style' => 'raw' );
					echo JModuleHelper::renderModule( $myblurb, $_options );
					}
				?>
			</div>
		</div>
	<?php } ?>
	<?php if(JModuleHelper::getModules('s5_va_7')) { ?>
		<h3 class="s5_va_toggler<?php if($version->RELEASE>='3.0') { ?> s5_va_inactive<?php } ?>">
			<span class="s5_va_h3_inner_span">
				<?php echo $title7 ?>
			</span>
		</h3>
		<div class="s5_va_element">
			<div class="s5_va_element_inner">
				<?php
					$myblurb_modules = JModuleHelper::getModules( 's5_va_7' );
					foreach ($myblurb_modules as $myblurb) {
					$_options = array( 'style' => 'raw' );
					echo JModuleHelper::renderModule( $myblurb, $_options );
					}
				?>
			</div>
		</div>
	<?php } ?>
	<?php if(JModuleHelper::getModules('s5_va_8')) { ?>
		<h3 class="s5_va_toggler<?php if($version->RELEASE>='3.0') { ?> s5_va_inactive<?php } ?>">
			<span class="s5_va_h3_inner_span">
				<?php echo $title8 ?>
			</span>
		</h3>
		<div class="s5_va_element">
			<div class="s5_va_element_inner">
				<?php
					$myblurb_modules = JModuleHelper::getModules( 's5_va_8' );
					foreach ($myblurb_modules as $myblurb) {
					$_options = array( 'style' => 'raw' );
					echo JModuleHelper::renderModule( $myblurb, $_options );
					}
				?>
			</div>
		</div>
	<?php } ?>
	<?php if(JModuleHelper::getModules('s5_va_9')) { ?>
		<h3 class="s5_va_toggler<?php if($version->RELEASE>='3.0') { ?> s5_va_inactive<?php } ?>">
			<span class="s5_va_h3_inner_span">
				<?php echo $title9 ?>
			</span>
		</h3>
		<div class="s5_va_element">
			<div class="s5_va_element_inner">
				<?php
					$myblurb_modules = JModuleHelper::getModules( 's5_va_9' );
					foreach ($myblurb_modules as $myblurb) {
					$_options = array( 'style' => 'raw' );
					echo JModuleHelper::renderModule( $myblurb, $_options );
					}
				?>
			</div>
		</div>
	<?php } ?>
	<?php if(JModuleHelper::getModules('s5_va_10')) { ?>
		<h3 class="s5_va_toggler<?php if($version->RELEASE>='3.0') { ?> s5_va_inactive<?php } ?>">
			<span class="s5_va_h3_inner_span">
				<?php echo $title10 ?>
			</span>
		</h3>
		<div class="s5_va_element">
			<div class="s5_va_element_inner">
				<?php
					$myblurb_modules = JModuleHelper::getModules( 's5_va_10' );
					foreach ($myblurb_modules as $myblurb) {
					$_options = array( 'style' => 'raw' );
					echo JModuleHelper::renderModule( $myblurb, $_options );
					}
				?>
			</div>
		</div>
	<?php } ?>
</div>
</div>

<?php 

	$template_vertex = "no";
	$app = JFactory::getApplication();
	$template = $app->getTemplate();
	$template_json_location = $_SERVER['DOCUMENT_ROOT'].JURI::root(true).'/templates/'.$template.'/vertex.json';
	if(file_exists($template_json_location)) { 
	$template_vertex = "yes";

?>
<?php if($template_vertex == "no"){ ?>
	<script type="text/javascript">//<![CDATA[
	if(jQuery.easing.easeOutExpo==undefined){
    document.write('<script src="<?php echo JURI::base(); ?>modules/mod_s5_vertical_accordion/js/jquery-ui.min.js"><\/script>');
    }
	//]]></script>
	<script type="text/javascript">jQuery.noConflict();</script>
<?php } } ?>	

<script type="text/javascript">

<?php if ($s5_va_autorun != 0) { ?>		
var s5_va_accordion_timer = 0;
var s5_va_accordion_current = 0;
var s5_va_accordion_toggler = 0;
var s5_va_accordion_element = 0;
var s5_va_accordion_len = 0;
<?php } ?>

jQuery(document).ready( (function($) {

	// var accordion = new Accordion($$('.s5_va_toggler'),$$('.s5_va_element'), { pre-MooTools More 
	
	s5_va_accordion_toggler = $('.s5_va_toggler');
	s5_va_accordion_element = $('.s5_va_element');
	s5_va_accordion_len = s5_va_accordion_toggler.length;
	
	jQuery(s5_va_accordion_element).eq(0).find('img').each(function(i,d){ jQuery(d).load(function(){jQuery(s5_va_accordion_element).eq(0).css('height',jQuery(s5_va_accordion_element).eq(0).children(0).outerHeight());});});

	jQuery(s5_va_accordion_toggler).each(function(i,d){
			if(i==0) $(s5_va_accordion_element[i]).css({'overflow':'hidden','height':$(s5_va_accordion_element[i]).children(0).outerHeight(),'opacity':1});
			else $(s5_va_accordion_element[i]).css({'height':0,'opacity':0});
			$(d).<?php if ($s5_va_click_or_hover == "mouse") { ?>mouseenter<?php } else { ?>click<?php } ?>(function(e){	
				var obj=s5_va_accordion_element[i];
				$(obj).stop();
				jQuery(obj).animate({'opacity':1,'height':$(obj).children(0).height()},{queue:false,duration:1000,easing:'easeOutExpo'});

					for(var j=0;j<s5_va_accordion_len;j++) {
						if(j!=i ){
							$(s5_va_accordion_element[j]).animate({'opacity':<?php if ($s5_va_opacity == "no") { ?>1<?php } ?><?php if ($s5_va_opacity == "yes") { ?>0<?php } ?>,'height':'0'},{queue:false,duration:1000,easing:'easeOutExpo'});
							var s5_va_classes = document.getElementById("s5_va_accordion_inner").getElementsByTagName("H3");
							for (var s5_va_classes_y=0; s5_va_classes_y<s5_va_classes.length; s5_va_classes_y++) {
								if (s5_va_classes[s5_va_classes_y].className.indexOf("s5_va_toggler") >= 0 || s5_va_classes[s5_va_classes_y].className.indexOf("s5_va_active") >= 0) {
									s5_va_classes[s5_va_classes_y].className = "s5_va_inactive";
								}
							}
							$(this).addClass('s5_va_active');
							$(this).removeClass('s5_va_inactive');
						}else
							$(s5_va_accordion_element[j]).animate({'opacity':1,'height':$(s5_va_accordion_element[j]).children(0).outerHeight()},{queue:false,duration:1000,easing:'easeOutExpo',complete:function(){$(this).css({'height':'auto'});}});
					}
			});
	});
	<?php if ($s5_va_autorun != 0) { ?>	
	function accordionAutoRun(){
	//<![CDATA[
		if(s5_va_accordion_current < s5_va_accordion_len - 1){
			s5_va_accordion_current++;
		}
		else{
			s5_va_accordion_current = 0;
		}	
	//]]>
		jQuery(s5_va_accordion_toggler[s5_va_accordion_current]).trigger('click');
	};
		
	<?php if ($s5_va_click_or_hover == "mouse") { ?>
	//$('.s5_va_toggler').addEvent('mouseenter', function() { this.fireEvent('click'); });
	<?php } ?>
	s5_va_accordion_timer = setInterval(accordionAutoRun, <?php echo $s5_va_autorun; ?>);
	$('#s5_va_accordion').bind('mouseenter', function() { clearInterval(s5_va_accordion_timer); });
	$('#s5_va_accordion').bind('mouseleave', function() { s5_va_accordion_timer = setInterval(accordionAutoRun, <?php echo $s5_va_autorun; ?>); });
	<?php } ?>
	
	document.getElementById("s5_va_accordion").style.display = "block";
	
}
)(jQuery));


jQuery(s5_va_accordion_toggler[0]).trigger('click');


<?php if ($s5_va_click_or_hover == "mouse") { ?>
//$('.s5_va_toggler').addEvent('mouseenter', function() { this.fireEvent('click'); });
<?php } ?>

</script>
