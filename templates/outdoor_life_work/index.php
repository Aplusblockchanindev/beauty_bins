<?php
require_once('vertex/cms_core_functions.php');
s5_restricted_access_call();
/*
-----------------------------------------
Outdoor Life - Shape 5 Club Design
-----------------------------------------
Site:      shape5.com
Email:     contact@shape5.com
@license:  Copyrighted Commercial Software
@copyright (C) 2017 Shape 5 LLC

*/

?>
<!DOCTYPE HTML>
<html <?php s5_language_call(); ?>>
<head>
  <!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-207152925-1"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());

gtag('config', 'UA-207152925-1');
</script>
<?php s5_head_call(); ?>
<?php
require_once("vertex/parameters.php");
require_once("vertex/general_functions.php");
require_once("vertex/module_calcs.php");
require_once("vertex/includes/vertex_includes_header.php");
?>

<?php if(($s5_fonts_highlight1 != "Arial") && ($s5_fonts_highlight1 != "Helvetica") && ($s5_fonts_highlight1 != "Sans-Serif")) { ?>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=<?php echo str_replace(" ","%20",$s5_fonts_highlight1); if ($s5_fonts_highlight1_style != "") { echo ":".$s5_fonts_highlight1_style; } ?>" />
<?php } ?>

<?php if(($s5_fonts_highlight2 != "Arial") && ($s5_fonts_highlight2 != "Helvetica") && ($s5_fonts_highlight2 != "Sans-Serif")) { ?>
<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=<?php echo str_replace(" ","%20",$s5_fonts_highlight2); if ($s5_fonts_highlight2_style != "") { echo ":".$s5_fonts_highlight2_style; } ?>" />
<?php } ?>

<style type="text/css"> 
.video_text_large, .highlight_font, h1, h2, h3, h4, h5, #s5_nav, #s5_loginreg, #subMenusContainer a, #s5_nav li li a, .package_item_price, .testimonial_name, .button, button, .readon, p.readmore a, .btn, .btn-primary, .overlapping_image_sub {
font-family: <?php echo $s5_fonts_highlight1; ?> !important;
}

.video_text_sub, .large_text_sub, .icon_group_title, .title_left_text_sub, .info_pictures .s5_is_css_8, .testimonial_left_quote, .testimonial_name_sub, .video_text .readon {
font-family: <?php echo $s5_fonts_highlight2; ?> !important;
}

body, .inputbox {font-family: '<?php echo $s5_fonts;?>',Helvetica,Arial,Sans-Serif ;} 

#s5_search_wrap:hover, .btn-link, a, .highlight1_color, .module_round_box_outer ul li a:hover, #s5_bottom_menu_wrap a:hover, ul.menu li.current a, ul.s5_am_innermenu a:hover, #s5_responsive_menu_button:hover, .s5_icon_search_close:hover, .info_pictures_icon {
color:#<?php echo $s5_highlightcolor1; ?>;
}

#s5_bottom_menu_wrap a:hover, .icon_group_icon, ul.menu li.current a, ul.s5_am_innermenu a:hover, .testimonial_left_quote, .testimonial_name_sub {
color:#<?php echo $s5_highlightcolor1; ?> !important;
}

.S5_submenu_item:hover span, .S5_grouped_child_item .S5_submenu_item:hover span, .S5_submenu_item:hover a, .S5_grouped_child_item .S5_submenu_item:hover a {
color:#<?php echo $s5_highlightcolor1; ?> ;
}

.icon_group_icon {
border:solid 2px #<?php echo $s5_highlightcolor1; ?> !important;
}

#s5_nav li.mainMenuParentBtnFocused .s5_level1_span1, #s5_nav li.mainMenuParentBtn:hover .s5_level1_span1, #s5_nav li.active .s5_level1_span1, a.readon, a.pager, button, .button, .pagenav a, .s5_ls_readmore, .readmore a, .module_round_box.highlight1, .jdGallery .carousel .carouselInner .thumbnail.active, .item-page .dropdown-menu li > a:hover, .s5_pricetable_column.recommended .s5_title, .ac-container input:checked + label, .ac-container input:checked + label:hover, .ac-container2 input:checked + label, .ac-container2 input:checked + label:hover, #s5_responsive_mobile_sidebar_login_bottom button, #s5_responsive_mobile_sidebar_register_bottom button, #s5_login, .lage_text_sub_line, #s5_accordion_menu h3:hover, #s5_accordion_menu h3.s5_am_open, .highlight1_title, .overlapping_image_highlight {
background:#<?php echo $s5_highlightcolor1; ?> !important;
}

.package_item_price {
background:#<?php echo $s5_highlightcolor1; ?>;
}

a.readon:hover, a.pager:hover, button:hover, .button:hover, .pagenav a:hover, .s5_ls_readmore:hover, .readmore a:hover, #s5_register:hover, #s5_login:hover, #s5_scrolltopvar {
background:#<?php echo change_Color($s5_highlightcolor1,'+35'); ?> !important;
}

.module_round_box.border_highlight1 {
border:solid 3px #<?php echo $s5_highlightcolor1; ?>;
}

.large_picture_img {
-webkit-box-shadow:-25px -25px 0px 0px rgba(<?php $s5_highlightcolor1_rgb = hex2rgb($s5_highlightcolor1); echo $s5_highlightcolor1_rgb[0]; ?>, <?php echo $s5_highlightcolor1_rgb[1]; ?>, <?php echo $s5_highlightcolor1_rgb[2]; ?>, 1); 
box-shadow:-25px -25px 0px 0px rgba(<?php $s5_highlightcolor1_rgb = hex2rgb($s5_highlightcolor1); echo $s5_highlightcolor1_rgb[0]; ?>, <?php echo $s5_highlightcolor1_rgb[1]; ?>, <?php echo $s5_highlightcolor1_rgb[2]; ?>, 1);
-moz-box-shadow:-25px -25px 0px 0px rgba(<?php $s5_highlightcolor1_rgb = hex2rgb($s5_highlightcolor1); echo $s5_highlightcolor1_rgb[0]; ?>, <?php echo $s5_highlightcolor1_rgb[1]; ?>, <?php echo $s5_highlightcolor1_rgb[2]; ?>, 1); 
}

.testimonials .s5_tab_show_slide_button_active {
-webkit-box-shadow:-7px -7px 0px 0px rgba(<?php $s5_highlightcolor1_rgb = hex2rgb($s5_highlightcolor1); echo $s5_highlightcolor1_rgb[0]; ?>, <?php echo $s5_highlightcolor1_rgb[1]; ?>, <?php echo $s5_highlightcolor1_rgb[2]; ?>, 1); 
box-shadow:-7px -7px 0px 0px rgba(<?php $s5_highlightcolor1_rgb = hex2rgb($s5_highlightcolor1); echo $s5_highlightcolor1_rgb[0]; ?>, <?php echo $s5_highlightcolor1_rgb[1]; ?>, <?php echo $s5_highlightcolor1_rgb[2]; ?>, 1);
-moz-box-shadow:-7px -7px 0px 0px rgba(<?php $s5_highlightcolor1_rgb = hex2rgb($s5_highlightcolor1); echo $s5_highlightcolor1_rgb[0]; ?>, <?php echo $s5_highlightcolor1_rgb[1]; ?>, <?php echo $s5_highlightcolor1_rgb[2]; ?>, 1); 
}

.s5_header_custom1_unpublished #s5_menu_wrap, #s5_menu_wrap.s5_wrap, #s5_menu_wrap.s5_wrap_fmfullwidth, #s5_logo_wrap_outer {
<?php if ($s5_no_custom1_bg_image_url != "") { ?>
background:#<?php echo $s5_no_custom1_bg_color; ?> url(<?php if(strrpos($s5_no_custom1_bg_image_url,"/") <= 0) {echo $LiveSiteUrl; echo "images/";} echo $s5_no_custom1_bg_image_url; ?>) no-repeat top center;
background-size:cover;
<?php } else { ?>
background:#<?php echo $s5_no_custom1_bg_color; ?>;
<?php } ?>
}

.s5_is_css_8 .s5_is_slide_css {
background:rgba(<?php $s5_highlightcolor1_rgb = hex2rgb($s5_highlightcolor1); echo $s5_highlightcolor1_rgb[0]; ?>, <?php echo $s5_highlightcolor1_rgb[1]; ?>, <?php echo $s5_highlightcolor1_rgb[2]; ?>, 0.85);
}

@media screen and (max-width: 960px){
.s5_is_css_8 .s5_is_slide_css {
background:rgba(<?php $s5_highlightcolor1_rgb = hex2rgb($s5_highlightcolor1); echo $s5_highlightcolor1_rgb[0]; ?>, <?php echo $s5_highlightcolor1_rgb[1]; ?>, <?php echo $s5_highlightcolor1_rgb[2]; ?>, 1);
}
}

<?php if ($s5_uppercase == "yes") { ?>
.video_text_large, .uppercase, button, .button, .btn, #s5_nav li, #s5_login, #s5_register, #subMenusContainer a, #s5_nav li li a, h1, h2, h3, h4, h5, .testimonial_name {
text-transform:uppercase;
}
<?php } ?>

<?php if ($s5_hide_subarrows == "yes") { ?>
.mainParentBtn a, #s5_nav li.mainParentBtn:hover a, #s5_nav li.mainMenuParentBtnFocused.mainParentBtn a, .s5_wrap_fmfullwidth .mainParentBtn a {
background:none !important;
}
#s5_nav li.mainParentBtn .s5_level1_span2 a {
padding:0px;
}
<?php } ?>

<?php if ($s5_disable_first_menu == "yes") { ?>
#s5_nav li:first-child {
display:none;
}
<?php } ?>

<?php if ($s5_video_overlay == "no") { ?>
.video_overlay {
background:none !important;
}
<?php } ?>

<?php if ($s5_pos_custom_1 == "unpublished") { ?>
#s5_header {
position:relative !important;
}
<?php } ?>

<?php if ($s5_pos_custom_1 == "published") { ?>
#s5_floating_menu_spacer {
display:none !important;
}
<?php } ?>

<?php if ($s5_pos_right == "published" || $s5_pos_right_inset == "published" || $s5_pos_right_top == "published" || $s5_pos_right_bottom == "published") { ?>
#s5_center_column_wrap_inner {
padding-right:3%;
}
@media screen and (max-width: 1100px){
#s5_center_column_wrap_inner {
padding-right:2%;
}
}
<?php } ?>

<?php if ($s5_pos_left == "published" || $s5_pos_left_inset == "published" || $s5_pos_left_top == "published" || $s5_pos_left_bottom == "published") { ?>
#s5_center_column_wrap_inner {
padding-left:3%;
}
@media screen and (max-width: 1100px){
#s5_center_column_wrap_inner {
padding-left:2%;
}
}
<?php } ?>

@media screen and (max-width: <?php echo $s5_responsive_mobile_bar_trigger; ?>px){
<?php if ($s5_pos_custom_3 != "published" && $s5_pos_custom_4 != "published") { ?>
#s5_header {
display:none;
}
<?php } else { ?>
#s5_menu_wrap_inner {
padding:25px !important;
}
<?php } ?>
.s5_logo_module, .s5_logo, #s5_logo_wrap_outer {
display:block !important;
}
}


<?php if ($s5_pos_bottom_row3_1 == "unpublished" && $s5_pos_bottom_row3_2 == "unpublished" && $s5_pos_bottom_row3_3 == "unpublished" && $s5_pos_bottom_row3_4 == "unpublished" && $s5_pos_bottom_row3_5 == "unpublished" && $s5_pos_bottom_row3_6 == "unpublished" && $s5_pos_custom_6 == "published") { ?>
#s5_pos_custom_6_wrap {
padding-bottom:50px;
}
<?php } ?>

<?php if ($browser == "ie7" || $browser == "ie8" || $browser == "ie9") { ?>
.s5_lr_tab_inner {writing-mode: bt-rl;filter: flipV flipH;}
<?php } ?>

<?php if($s5_thirdparty == "enabled") { ?>
/* k2 stuff */
div.itemHeader h2.itemTitle, div.catItemHeader h3.catItemTitle, h3.userItemTitle a, #comments-form p, #comments-report-form p, #comments-form span, #comments-form .counter, #comments .comment-author, #comments .author-homepage,
#comments-form p, #comments-form #comments-form-buttons, #comments-form #comments-form-error, #comments-form #comments-form-captcha-holder {font-family: '<?php echo $s5_fonts;?>',Helvetica,Arial,Sans-Serif ;} 
<?php } ?>	
.s5_wrap{width:<?php echo $s5_body_width; echo $s5_fixed_fluid ?>;}	
</style>
</head>

<body id="s5_body">

<div id="s5_scrolltotop"></div>

<!-- Top Vertex Calls -->
<?php require_once("vertex/includes/vertex_includes_top.php"); ?>

<!-- Body Padding Div Used For Responsive Spacing -->		
<div id="s5_body_padding">

	<?php if($s5_logo_type != "none") { ?>
		<div id="s5_logo_wrap_outer">
		<div id="s5_logo_wrap" class="s5_logo s5_logo_<?php echo $s5_logo_type; ?>">
			<?php if ($s5_logo_type == "css") { ?>
				<img alt="logo" src="<?php echo $s5_directory_path ?>/images/s5_logo.png" onclick="window.document.location.href='<?php echo $LiveSiteUrl; ?>'" />
			<?php } ?>
			<?php if ($s5_logo_type == "image") { 
				if(strrpos($s5_logo_image_file,"ttp://") > 0) { ?>
					<img alt="logo" src="<?php echo $s5_logo_image_file; ?>" onclick="window.document.location.href='<?php echo $LiveSiteUrl ?>'" />
				<?php } else { ?>
					<img alt="logo" src="<?php echo $LiveSiteUrl; echo $s5_logo_image_file; ?>" onclick="window.document.location.href='<?php echo $LiveSiteUrl ?>'" />
				<?php } ?>
			<?php } ?>
			<?php if ($s5_pos_logo == "published" && $s5_logo_type == "module") { ?>
				<div id="s5_logo_text_wrap">
					<?php s5_module_call('logo','notitle'); ?>
					<div style="clear:both;"></div>
				</div>
			<?php } ?>
			<?php if ($s5_logo_type == "text") { ?>
				<div id="s5_logo_text_wrap">
					<?php echo $s5_logo_text; ?>
					<div style="clear:both;"></div>
				</div>
			<?php } ?>
			<div style="clear:both;"></div>
		</div>	
		</div>	
	<?php } ?>

	<?php if ($s5_pos_custom_1 == "published") { ?>
		<div id="s5_pos_custom_1_2_wrap">
			<div id="s5_pos_custom_1">
				<?php s5_module_call('custom_1','notitle'); ?>
				<div style="clear:both; height:0px"></div>
			</div>
			<?php if ($s5_pos_custom_2 == "published") { ?>
				<div id="s5_pos_custom_2_wrap">
				<div id="s5_pos_custom_2_wrap_inner" class="s5_wrap">
				<div id="s5_pos_custom_2">
					<?php s5_module_call('custom_2','notitle'); ?>
					<div style="clear:both; height:0px"></div>
				</div>
				</div>
				</div>
			<?php } ?>
			<div style="clear:both; height:0px"></div>
		</div>
	<?php } ?>

	<!-- Header -->		
		<header id="s5_header" class="s5_header_custom1_<?php echo $s5_pos_custom_1; ?>">
		<div id="s5_menu_wrap">	
			<div id="s5_menu_wrap2">
				<div id="s5_menu_wrap_inner">

					<?php if ($s5_pos_search == "published" || $s5_pos_custom_3 == "published") { ?>
						<div id="s5_search_custom_3_wrap">
							<?php if ($s5_pos_search == "published") { ?>
								<div onclick="s5_search_open()" id="s5_search_wrap" class="ion-search"></div>
								<div id="s5_search_overlay" class="s5_search_close">
									<div class="ion-close s5_icon_search_close" onclick="s5_search_close()"></div>		
									<div class="s5_wrap">
										<div id="s5_search_pos_wrap">
										<?php s5_module_call('search','round_box'); ?>
										</div>		
									</div>
								</div>
							<?php } ?>
							<?php if ($s5_pos_custom_3 == "published") { ?>
								<div id="s5_pos_custom_3_wrap">
									<?php s5_module_call('custom_3','notitle'); ?>
									<div style="clear:both; height:0px"></div>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
					
					<?php if (($s5_login != "") || ($s5_register != "") || $s5_pos_custom_4 == "published") { ?>	
						<div id="s5_login_custom_4_wrap">
							<?php if (($s5_login != "") || ($s5_register != "")) { ?>	
								<div id="s5_loginreg">	
									<div id="s5_logregtm">		
										<?php if (($s5_login != "" && $s5_pos_login == "published") || ($s5_login != "" && $s5_login_url != "")) { ?>
											<div id="s5_login" class="s5box_login">
												<?php if ($s5_user_id) { echo $s5_loginout; } else { echo $s5_login; } ?>
											</div>						
										<?php } ?>	
										<?php if (($s5_register != "" && $s5_pos_register == "published") || ($s5_register != "" && $s5_register_url != "")) { ?>
											<?php if ($s5_user_id) { } else {?>
												<div id="s5_register" class="s5box_register">
													<?php echo $s5_register;?>				
												</div>
											<?php } ?>							
										<?php } ?>										
									</div>
								</div>
							<?php } ?>
							
							<?php if ($s5_pos_custom_4 == "published") { ?>
								<div id="s5_pos_custom_4_wrap">
									<?php s5_module_call('custom_4','notitle'); ?>
									<div style="clear:both; height:0px"></div>
								</div>
							<?php } ?>
						</div>
					<?php } ?>
					
					<?php if ($s5_show_menu == "show") { ?>	
							<nav id="s5_menu_inner" class="s5_hide">
								<?php include("vertex/s5flex_menu/default.php"); ?>
								<div style="clear:both;"></div>
							</nav>
					<?php } ?>
					
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		</header>
		<?php if ($s5_show_menu == "show") { ?>	
		<script>
			if (document.getElementById("s5_logo_wrap")) {
				jQuery( document ).ready(function() {		
					jQuery( ".s5_logo" ).clone().appendTo( ".s5_logo_spacer" );
				
					var s5_identify_second_logo = document.getElementById("s5_logo_wrap_outer").querySelectorAll('DIV');
					for (var s5_identify_second_logo_y=0; s5_identify_second_logo_y<s5_identify_second_logo.length; s5_identify_second_logo_y++) {
						if (s5_identify_second_logo[s5_identify_second_logo_y].className.indexOf("s5_logo") >= 0) {
							s5_identify_second_logo[s5_identify_second_logo_y].id="s5_logo_wrap2";
						}
					}
				
					var s5_identify_outer_logo = document.getElementById("s5_nav").querySelectorAll('LI');
					for (var s5_identify_outer_logo_y=0; s5_identify_outer_logo_y<s5_identify_outer_logo.length; s5_identify_outer_logo_y++) {
						if (s5_identify_outer_logo[s5_identify_outer_logo_y].className.indexOf("s5_logo_spacer") >= 0) {
							s5_identify_outer_logo[s5_identify_outer_logo_y].id="s5_logo_spacer";
						}
					}
				});		
				var s5_logo_center = 0;
				var s5_menu_center = 0;
				var s5_nav_width = 0;
				function s5_logo_position() {
					document.getElementById("s5_menu_inner").className = "s5_hide";
					document.getElementById("s5_nav").style.left = "0px";
					if (document.getElementById("s5_logo_spacer")) {
						s5_logo_center = document.getElementById("s5_logo_spacer").offsetLeft + (document.getElementById("s5_logo_spacer").offsetWidth/2);
						document.getElementById("s5_nav").style.left = (document.body.clientWidth/2) - s5_logo_center + "px";
					} else {
						document.getElementById("s5_nav").style.float = "left";
						s5_nav_width = document.getElementById("s5_nav").offsetWidth;
						s5_menu_center = document.getElementById("s5_nav").offsetLeft + (document.getElementById("s5_nav").offsetWidth/2);
						document.getElementById("s5_nav").style.left = (document.body.clientWidth/2) - s5_menu_center + "px";
					}
					document.getElementById("s5_menu_inner").className = "";
				}
				jQuery(document).ready( function() { s5_logo_position(); });
				jQuery(window).resize(s5_logo_position);
				window.setTimeout(s5_logo_position,300);
				window.setTimeout(s5_logo_position,600);
				window.setTimeout(s5_logo_position,900);
				window.setTimeout(s5_logo_position,1200);
				window.setTimeout(s5_logo_position,1500);
				window.setTimeout(s5_logo_position,1800);
				window.setTimeout(s5_logo_position,2100);
				window.setTimeout(s5_logo_position,2400);
				window.setTimeout(s5_logo_position,2700);
				window.setTimeout(s5_logo_position,3000);
			}
		</script>		
		<?php } ?>
	<!-- End Header -->	
		
		

	<!-- Top Row1 -->	
		<?php if ($s5_pos_top_row1_1 == "published" || $s5_pos_top_row1_2 == "published" || $s5_pos_top_row1_3 == "published" || $s5_pos_top_row1_4 == "published" || $s5_pos_top_row1_5 == "published" || $s5_pos_top_row1_6 == "published") { ?>
			<section id="s5_top_row1_area1"<?php if ($s5_top_row1_area1_background == "no") { ?> class="s5_slidesection s5_no_custom_bg"<?php } else { ?> class="s5_slidesection s5_yes_custom_bg<?php if ($s5_top_row1_area1_background_color == "FFFFFF" && $s5_top_row1_area1_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
			<div id="s5_top_row1_area2"<?php if ($s5_top_row1_area2_background == "no") { ?> class="s5_no_custom_bg"<?php } else { ?> class="s5_yes_custom_bg<?php if ($s5_top_row1_area2_background_color == "FFFFFF" && $s5_top_row1_area2_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
			<div id="s5_top_row1_area_inner" class="s5_wrap">

				<div id="s5_top_row1_wrap">
				<div id="s5_top_row1">
				<div id="s5_top_row1_inner">
				
					<?php if ($s5_pos_top_row1_1 == "published") { ?>
						<div id="s5_pos_top_row1_1" class="s5_float_left" style="width:<?php echo $s5_pos_top_row1_1_width ?>%">
							<?php s5_module_call('top_row1_1','round_box'); ?>
						</div>
					<?php } ?>
					
					<?php if ($s5_pos_top_row1_2 == "published") { ?>
						<div id="s5_pos_top_row1_2" class="s5_float_left" style="width:<?php echo $s5_pos_top_row1_2_width ?>%">
							<?php s5_module_call('top_row1_2','round_box'); ?>
						</div>
					<?php } ?>
					
					<?php if ($s5_pos_top_row1_3 == "published") { ?>
						<div id="s5_pos_top_row1_3" class="s5_float_left" style="width:<?php echo $s5_pos_top_row1_3_width ?>%">
							<?php s5_module_call('top_row1_3','round_box'); ?>
						</div>
					<?php } ?>
					
					<?php if ($s5_pos_top_row1_4 == "published") { ?>
						<div id="s5_pos_top_row1_4" class="s5_float_left" style="width:<?php echo $s5_pos_top_row1_4_width ?>%">
							<?php s5_module_call('top_row1_4','round_box'); ?>
						</div>
					<?php } ?>
					
					<?php if ($s5_pos_top_row1_5 == "published") { ?>
						<div id="s5_pos_top_row1_5" class="s5_float_left" style="width:<?php echo $s5_pos_top_row1_5_width ?>%">
							<?php s5_module_call('top_row1_5','round_box'); ?>
						</div>
					<?php } ?>
					
					<?php if ($s5_pos_top_row1_6 == "published") { ?>
						<div id="s5_pos_top_row1_6" class="s5_float_left" style="width:<?php echo $s5_pos_top_row1_6_width ?>%">
							<?php s5_module_call('top_row1_6','round_box'); ?>
						</div>
					<?php } ?>
					
					<div style="clear:both; height:0px"></div>
					
				</div>
				</div>
				</div>

		</div>
		</div>
		</section>
		<?php } ?>
	<!-- End Top Row1 -->	
		
		
		
	<!-- Top Row2 -->	
		<?php if ($s5_pos_top_row2_1 == "published" || $s5_pos_top_row2_2 == "published" || $s5_pos_top_row2_3 == "published" || $s5_pos_top_row2_4 == "published" || $s5_pos_top_row2_5 == "published" || $s5_pos_top_row2_6 == "published") { ?>
		<section id="s5_top_row2_area1"<?php if ($s5_top_row2_area1_background == "no") { ?> class="s5_slidesection s5_no_custom_bg"<?php } else { ?> class="s5_slidesection s5_yes_custom_bg<?php if ($s5_top_row2_area1_background_color == "FFFFFF" && $s5_top_row2_area1_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
		<div id="s5_top_row2_area2"<?php if ($s5_top_row2_area2_background == "no") { ?> class="s5_no_custom_bg"<?php } else { ?> class="s5_yes_custom_bg<?php if ($s5_top_row2_area2_background_color == "FFFFFF" && $s5_top_row2_area2_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
		<div id="s5_top_row2_area_inner" class="s5_wrap">			
		
			<div id="s5_top_row2_wrap">
			<div id="s5_top_row2">
			<div id="s5_top_row2_inner">	
			
				<?php if ($s5_pos_top_row2_1 == "published") { ?>
					<div id="s5_pos_top_row2_1" class="s5_float_left" style="width:<?php echo $s5_pos_top_row2_1_width ?>%">
						<?php s5_module_call('top_row2_1','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_top_row2_2 == "published") { ?>
					<div id="s5_pos_top_row2_2" class="s5_float_left" style="width:<?php echo $s5_pos_top_row2_2_width ?>%">
						<?php s5_module_call('top_row2_2','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_top_row2_3 == "published") { ?>
					<div id="s5_pos_top_row2_3" class="s5_float_left" style="width:<?php echo $s5_pos_top_row2_3_width ?>%">
						<?php s5_module_call('top_row2_3','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_top_row2_4 == "published") { ?>
					<div id="s5_pos_top_row2_4" class="s5_float_left" style="width:<?php echo $s5_pos_top_row2_4_width ?>%">
						<?php s5_module_call('top_row2_4','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_top_row2_5 == "published") { ?>
					<div id="s5_pos_top_row2_5" class="s5_float_left" style="width:<?php echo $s5_pos_top_row2_5_width ?>%">
						<?php s5_module_call('top_row2_5','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_top_row2_6 == "published") { ?>
					<div id="s5_pos_top_row2_6" class="s5_float_left" style="width:<?php echo $s5_pos_top_row2_6_width ?>%">
						<?php s5_module_call('top_row2_6','round_box'); ?>
					</div>
				<?php } ?>			
				
				<div style="clear:both; height:0px"></div>
				
			</div>
			</div>	
			</div>	
				
		</div>
		</div>
		</section>
		<?php } ?>
	<!-- End Top Row2 -->
	
	
	
	<!-- Top Row3 -->	
		<?php if ($s5_pos_top_row3_1 == "published" || $s5_pos_top_row3_2 == "published" || $s5_pos_top_row3_3 == "published" || $s5_pos_top_row3_4 == "published" || $s5_pos_top_row3_5 == "published" || $s5_pos_top_row3_6 == "published") { ?>
		<section id="s5_top_row3_area1"<?php if ($s5_top_row3_area1_background == "no") { ?> class="s5_slidesection s5_no_custom_bg"<?php } else { ?> class="s5_slidesection s5_yes_custom_bg<?php if ($s5_top_row3_area1_background_color == "FFFFFF" && $s5_top_row3_area1_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>	
		<div id="s5_top_row3_area2"<?php if ($s5_top_row3_area2_background == "no") { ?> class="s5_no_custom_bg"<?php } else { ?> class="s5_yes_custom_bg<?php if ($s5_top_row3_area2_background_color == "FFFFFF" && $s5_top_row3_area2_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
		<div id="s5_top_row3_area_inner" class="s5_wrap">
		
			<div id="s5_top_row3_wrap">
			<div id="s5_top_row3">
			<div id="s5_top_row3_inner">
			
				<?php if ($s5_pos_top_row3_1 == "published") { ?>
					<div id="s5_pos_top_row3_1" class="s5_float_left" style="width:<?php echo $s5_pos_top_row3_1_width ?>%">
						<?php s5_module_call('top_row3_1','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_top_row3_2 == "published") { ?>
					<div id="s5_pos_top_row3_2" class="s5_float_left" style="width:<?php echo $s5_pos_top_row3_2_width ?>%">
						<?php s5_module_call('top_row3_2','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_top_row3_3 == "published") { ?>
					<div id="s5_pos_top_row3_3" class="s5_float_left" style="width:<?php echo $s5_pos_top_row3_3_width ?>%">
						<?php s5_module_call('top_row3_3','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_top_row3_4 == "published") { ?>
					<div id="s5_pos_top_row3_4" class="s5_float_left" style="width:<?php echo $s5_pos_top_row3_4_width ?>%">
						<?php s5_module_call('top_row3_4','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_top_row3_5 == "published") { ?>
					<div id="s5_pos_top_row3_5" class="s5_float_left" style="width:<?php echo $s5_pos_top_row3_5_width ?>%">
						<?php s5_module_call('top_row3_5','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_top_row3_6 == "published") { ?>
					<div id="s5_pos_top_row3_6" class="s5_float_left" style="width:<?php echo $s5_pos_top_row3_6_width ?>%">
						<?php s5_module_call('top_row3_6','round_box'); ?>
					</div>
				<?php } ?>			
				
				<div style="clear:both; height:0px"></div>

			</div>
			</div>
			</div>

		</div>
		</div>
		</section>
		<?php } ?>
	<!-- End Top Row3 -->	
	
	
	<?php if ($s5_pos_custom_5 == "published") { ?>
		<div id="s5_pos_custom_5_wrap">
			<?php s5_module_call('custom_5','notitle'); ?>
			<div style="clear:both; height:0px"></div>
		</div>
	<?php } ?>
		
		
		
	<!-- Center area -->	
		<?php if ($s5_show_component == "yes" || $s5_pos_left_top == "published" || $s5_pos_left == "published" || $s5_pos_left_inset == "published" || $s5_pos_left_bottom == "published" || $s5_pos_right_top == "published" || $s5_pos_right == "published" || $s5_pos_right_inset == "published" || $s5_pos_right_bottom == "published" || $s5_pos_middle_top_1 == "published" || $s5_pos_middle_top_2 == "published" || $s5_pos_middle_top_3 == "published" || $s5_pos_middle_top_4 == "published" || $s5_pos_middle_top_5 == "published" || $s5_pos_middle_top_6 == "published" || $s5_pos_above_body_1 == "published" || $s5_pos_above_body_2 == "published" || $s5_pos_above_body_3 == "published" || $s5_pos_above_body_4 == "published" || $s5_pos_above_body_5 == "published" || $s5_pos_above_body_6 == "published" || $s5_pos_middle_bottom_1 == "published" || $s5_pos_middle_bottom_2 == "published" || $s5_pos_middle_bottom_3 == "published" || $s5_pos_middle_bottom_4 == "published" || $s5_pos_middle_bottom_5 == "published" || $s5_pos_middle_bottom_6 == "published" || $s5_pos_below_body_1 == "published" || $s5_pos_below_body_2 == "published" || $s5_pos_below_body_3 == "published" || $s5_pos_below_body_4 == "published" || $s5_pos_below_body_5 == "published" || $s5_pos_below_body_6 == "published" || $s5_pos_above_columns_1 == "published" ||  $s5_pos_above_columns_2 == "published" ||  $s5_pos_above_columns_3 == "published" ||  $s5_pos_above_columns_4 == "published" ||  $s5_pos_above_columns_5 == "published" ||  $s5_pos_above_columns_6 == "published" ||  $s5_pos_below_columns_1 == "published" ||  $s5_pos_below_columns_2 == "published" ||  $s5_pos_below_columns_3 == "published" ||  $s5_pos_below_columns_4 == "published" ||  $s5_pos_below_columns_5 == "published" ||  $s5_pos_below_columns_6 == "published") { ?>
		<section id="s5_center_area1"<?php if ($s5_center_area1_background == "no") { ?> class="s5_slidesection s5_no_custom_bg"<?php } else { ?> class="s5_slidesection s5_yes_custom_bg<?php if ($s5_center_area1_background_color == "FFFFFF" && $s5_center_area1_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
		<div id="s5_center_area2"<?php if ($s5_center_area2_background == "no") { ?> class="s5_no_custom_bg"<?php } else { ?> class="s5_yes_custom_bg<?php if ($s5_center_area2_background_color == "FFFFFF" && $s5_center_area2_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
		<div id="s5_center_area_inner" class="s5_wrap">
		
		<!-- Above Columns Wrap -->	
			<?php if ($s5_pos_above_columns_1 == "published" || $s5_pos_above_columns_2 == "published" || $s5_pos_above_columns_3 == "published" || $s5_pos_above_columns_4 == "published" || $s5_pos_above_columns_5 == "published" || $s5_pos_above_columns_6 == "published") { ?>
			<section id="s5_above_columns_wrap1"<?php if ($s5_above_columns_wrap1_background == "no") { ?> class="s5_no_custom_bg"<?php } else { ?> class="s5_yes_custom_bg<?php if ($s5_above_columns_wrap1_background_color == "FFFFFF" && $s5_above_columns_wrap1_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>	
			<div id="s5_above_columns_wrap2"<?php if ($s5_above_columns_wrap2_background == "no") { ?> class="s5_no_custom_bg"<?php } else { ?> class="s5_yes_custom_bg<?php if ($s5_above_columns_wrap2_background_color == "FFFFFF" && $s5_above_columns_wrap2_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
			<div id="s5_above_columns_inner">

				<?php if ($s5_pos_above_columns_1 == "published") { ?>
					<div id="s5_above_columns_1" class="s5_float_left" style="width:<?php echo $s5_pos_above_columns_1_width ?>%">
						<?php s5_module_call('above_columns_1','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_above_columns_2 == "published") { ?>
					<div id="s5_above_columns_2" class="s5_float_left" style="width:<?php echo $s5_pos_above_columns_2_width ?>%">
						<?php s5_module_call('above_columns_2','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_above_columns_3 == "published") { ?>
					<div id="s5_above_columns_3" class="s5_float_left" style="width:<?php echo $s5_pos_above_columns_3_width ?>%">
						<?php s5_module_call('above_columns_3','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_above_columns_4 == "published") { ?>
					<div id="s5_above_columns_4" class="s5_float_left" style="width:<?php echo $s5_pos_above_columns_4_width ?>%">
						<?php s5_module_call('above_columns_4','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_above_columns_5 == "published") { ?>
					<div id="s5_above_columns_5" class="s5_float_left" style="width:<?php echo $s5_pos_above_columns_5_width ?>%">
						<?php s5_module_call('above_columns_5','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_above_columns_6 == "published") { ?>
					<div id="s5_above_columns_6" class="s5_float_left" style="width:<?php echo $s5_pos_above_columns_6_width ?>%">
						<?php s5_module_call('above_columns_6','round_box'); ?>
					</div>
				<?php } ?>	
				
				<div style="clear:both; height:0px"></div>

			</div>
			</div>
			</section>
			<?php } ?>
		<!-- End Above Columns Wrap -->			
				
			<!-- Columns wrap, contains left, right and center columns -->	
			<section id="s5_columns_wrap"<?php if ($s5_columns_wrap_background == "no") { ?> class="s5_no_custom_bg"<?php } else { ?> class="s5_yes_custom_bg<?php if ($s5_columns_wrap_background_color == "FFFFFF" && $s5_columns_wrap_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
			<div id="s5_columns_wrap_inner"<?php if ($s5_columns_wrap_inner_background == "no") { ?> class="s5_no_custom_bg"<?php } else { ?> class="s5_yes_custom_bg<?php if ($s5_columns_wrap_inner_background_color == "FFFFFF" && $s5_columns_wrap_inner_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
				
				<section id="s5_center_column_wrap">
				<div id="s5_center_column_wrap_inner" style="margin-left:<?php echo $s5_center_column_margin_left ?>px; margin-right:<?php echo $s5_center_column_margin_right ?>px;">
					
					<?php if ($s5_pos_middle_top_1 == "published" || $s5_pos_middle_top_2 == "published" || $s5_pos_middle_top_3 == "published" || $s5_pos_middle_top_4 == "published" || $s5_pos_middle_top_5 == "published" || $s5_pos_middle_top_6 == "published") { ?>
			
						<section id="s5_middle_top_wrap">
							
							<div id="s5_middle_top">
							<div id="s5_middle_top_inner">
							
								<?php if ($s5_pos_middle_top_1 == "published") { ?>
									<div id="s5_pos_middle_top_1" class="s5_float_left" style="width:<?php echo $s5_pos_middle_top_1_width ?>%">
										<?php s5_module_call('middle_top_1','round_box'); ?>
									</div>
								<?php } ?>
								
								<?php if ($s5_pos_middle_top_2 == "published") { ?>
									<div id="s5_pos_middle_top_2" class="s5_float_left" style="width:<?php echo $s5_pos_middle_top_2_width ?>%">
										<?php s5_module_call('middle_top_2','round_box'); ?>
									</div>
								<?php } ?>
								
								<?php if ($s5_pos_middle_top_3 == "published") { ?>
									<div id="s5_pos_middle_top_3" class="s5_float_left" style="width:<?php echo $s5_pos_middle_top_3_width ?>%">
										<?php s5_module_call('middle_top_3','round_box'); ?>
									</div>
								<?php } ?>
								
								<?php if ($s5_pos_middle_top_4 == "published") { ?>
									<div id="s5_pos_middle_top_4" class="s5_float_left" style="width:<?php echo $s5_pos_middle_top_4_width ?>%">
										<?php s5_module_call('middle_top_4','round_box'); ?>
									</div>
								<?php } ?>
								
								<?php if ($s5_pos_middle_top_5 == "published") { ?>
									<div id="s5_pos_middle_top_5" class="s5_float_left" style="width:<?php echo $s5_pos_middle_top_5_width ?>%">
										<?php s5_module_call('middle_top_5','round_box'); ?>
									</div>
								<?php } ?>
								
								<?php if ($s5_pos_middle_top_6 == "published") { ?>
									<div id="s5_pos_middle_top_6" class="s5_float_left" style="width:<?php echo $s5_pos_middle_top_6_width ?>%">
										<?php s5_module_call('middle_top_6','round_box'); ?>
									</div>
								<?php } ?>		
								
								<div style="clear:both; height:0px"></div>

							</div>
							</div>
						
						</section>

					<?php } ?>
					
					<?php if ($s5_show_component == "yes" || $s5_pos_above_body_1 == "published" || $s5_pos_above_body_2 == "published" || $s5_pos_above_body_3 == "published" || $s5_pos_above_body_4 == "published" || $s5_pos_above_body_5 == "published" || $s5_pos_above_body_6 == "published" || $s5_pos_below_body_1 == "published" || $s5_pos_below_body_2 == "published" || $s5_pos_below_body_3 == "published" || $s5_pos_below_body_4 == "published" || $s5_pos_below_body_5 == "published" || $s5_pos_below_body_6 == "published") { ?>
						
						<section id="s5_component_wrap">
						<div id="s5_component_wrap_inner">
						
							<?php if ($s5_pos_above_body_1 == "published" || $s5_pos_above_body_2 == "published" || $s5_pos_above_body_3 == "published" || $s5_pos_above_body_4 == "published" || $s5_pos_above_body_5 == "published" || $s5_pos_above_body_6 == "published") { ?>
						
								<section id="s5_above_body_wrap">
									
									<div id="s5_above_body">
									<div id="s5_above_body_inner">
									
										<?php if ($s5_pos_above_body_1 == "published") { ?>
											<div id="s5_pos_above_body_1" class="s5_float_left" style="width:<?php echo $s5_pos_above_body_1_width ?>%">
												<?php s5_module_call('above_body_1','round_box'); ?>
											</div>
										<?php } ?>
										
										<?php if ($s5_pos_above_body_2 == "published") { ?>
											<div id="s5_pos_above_body_2" class="s5_float_left" style="width:<?php echo $s5_pos_above_body_2_width ?>%">
												<?php s5_module_call('above_body_2','round_box'); ?>
											</div>
										<?php } ?>
										
										<?php if ($s5_pos_above_body_3 == "published") { ?>
											<div id="s5_pos_above_body_3" class="s5_float_left" style="width:<?php echo $s5_pos_above_body_3_width ?>%">
												<?php s5_module_call('above_body_3','round_box'); ?>
											</div>
										<?php } ?>
										
										<?php if ($s5_pos_above_body_4 == "published") { ?>
											<div id="s5_pos_above_body_4" class="s5_float_left" style="width:<?php echo $s5_pos_above_body_4_width ?>%">
												<?php s5_module_call('above_body_4','round_box'); ?>
											</div>
										<?php } ?>
										
										<?php if ($s5_pos_above_body_5 == "published") { ?>
											<div id="s5_pos_above_body_5" class="s5_float_left" style="width:<?php echo $s5_pos_above_body_5_width ?>%">
												<?php s5_module_call('above_body_5','round_box'); ?>
											</div>
										<?php } ?>
										
										<?php if ($s5_pos_above_body_6 == "published") { ?>
											<div id="s5_pos_above_body_6" class="s5_float_left" style="width:<?php echo $s5_pos_above_body_6_width ?>%">
												<?php s5_module_call('above_body_6','round_box'); ?>
											</div>
										<?php } ?>			
										
										<div style="clear:both; height:0px"></div>

									</div>
									</div>
								
								</section>

							<?php } ?>
									
							<?php if ($s5_show_component == "yes") { ?>
							<main>
								<?php s5_component_call(); ?>
								<div style="clear:both;height:0px"></div>
							</main>
							<?php } ?>
							
							<?php if ($s5_pos_below_body_1 == "published" || $s5_pos_below_body_2 == "published" || $s5_pos_below_body_3 == "published" || $s5_pos_below_body_4 == "published" || $s5_pos_below_body_5 == "published" || $s5_pos_below_body_6 == "published") { ?>
						
								<section id="s5_below_body_wrap">			
								
									<div id="s5_below_body">
									<div id="s5_below_body_inner">
									
										<?php if ($s5_pos_below_body_1 == "published") { ?>
											<div id="s5_pos_below_body_1" class="s5_float_left" style="width:<?php echo $s5_pos_below_body_1_width ?>%">
												<?php s5_module_call('below_body_1','round_box'); ?>
											</div>
										<?php } ?>
										
										<?php if ($s5_pos_below_body_2 == "published") { ?>
											<div id="s5_pos_below_body_2" class="s5_float_left" style="width:<?php echo $s5_pos_below_body_2_width ?>%">
												<?php s5_module_call('below_body_2','round_box'); ?>
											</div>
										<?php } ?>
										
										<?php if ($s5_pos_below_body_3 == "published") { ?>
											<div id="s5_pos_below_body_3" class="s5_float_left" style="width:<?php echo $s5_pos_below_body_3_width ?>%">
												<?php s5_module_call('below_body_3','round_box'); ?>
											</div>
										<?php } ?>
										
										<?php if ($s5_pos_below_body_4 == "published") { ?>
											<div id="s5_pos_below_body_4" class="s5_float_left" style="width:<?php echo $s5_pos_below_body_4_width ?>%">
												<?php s5_module_call('below_body_4','round_box'); ?>
											</div>
										<?php } ?>
										
										<?php if ($s5_pos_below_body_5 == "published") { ?>
											<div id="s5_pos_below_body_5" class="s5_float_left" style="width:<?php echo $s5_pos_below_body_5_width ?>%">
												<?php s5_module_call('below_body_5','round_box'); ?>
											</div>
										<?php } ?>
										
										<?php if ($s5_pos_below_body_6 == "published") { ?>
											<div id="s5_pos_below_body_6" class="s5_float_left" style="width:<?php echo $s5_pos_below_body_6_width ?>%">
												<?php s5_module_call('below_body_6','round_box'); ?>
											</div>
										<?php } ?>		
										
										<div style="clear:both; height:0px"></div>

									</div>
									</div>
								</section>

							<?php } ?>
							
						</div>
						</section>
						
					<?php } ?>
					
					<?php if ($s5_pos_middle_bottom_1 == "published" || $s5_pos_middle_bottom_2 == "published" || $s5_pos_middle_bottom_3 == "published" || $s5_pos_middle_bottom_4 == "published" || $s5_pos_middle_bottom_5 == "published" || $s5_pos_middle_bottom_6 == "published") { ?>
					
						<section id="s5_middle_bottom_wrap">
							
							<div id="s5_middle_bottom">
							<div id="s5_middle_bottom_inner">
							
								<?php if ($s5_pos_middle_bottom_1 == "published") { ?>
									<div id="s5_pos_middle_bottom_1" class="s5_float_left" style="width:<?php echo $s5_pos_middle_bottom_1_width ?>%">
										<?php s5_module_call('middle_bottom_1','round_box'); ?>
									</div>
								<?php } ?>
								
								<?php if ($s5_pos_middle_bottom_2 == "published") { ?>
									<div id="s5_pos_middle_bottom_2" class="s5_float_left" style="width:<?php echo $s5_pos_middle_bottom_2_width ?>%">
										<?php s5_module_call('middle_bottom_2','round_box'); ?>
									</div>
								<?php } ?>
								
								<?php if ($s5_pos_middle_bottom_3 == "published") { ?>
									<div id="s5_pos_middle_bottom_3" class="s5_float_left" style="width:<?php echo $s5_pos_middle_bottom_3_width ?>%">
										<?php s5_module_call('middle_bottom_3','round_box'); ?>
									</div>
								<?php } ?>
								
								<?php if ($s5_pos_middle_bottom_4 == "published") { ?>
									<div id="s5_pos_middle_bottom_4" class="s5_float_left" style="width:<?php echo $s5_pos_middle_bottom_4_width ?>%">
										<?php s5_module_call('middle_bottom_4','round_box'); ?>
									</div>
								<?php } ?>
								
								<?php if ($s5_pos_middle_bottom_5 == "published") { ?>
									<div id="s5_pos_middle_bottom_5" class="s5_float_left" style="width:<?php echo $s5_pos_middle_bottom_5_width ?>%">
										<?php s5_module_call('middle_bottom_5','round_box'); ?>
									</div>
								<?php } ?>
								
								<?php if ($s5_pos_middle_bottom_6 == "published") { ?>
									<div id="s5_pos_middle_bottom_6" class="s5_float_left" style="width:<?php echo $s5_pos_middle_bottom_6_width ?>%">
										<?php s5_module_call('middle_bottom_6','round_box'); ?>
									</div>
								<?php } ?>	
								
								<div style="clear:both; height:0px"></div>

							</div>
							</div>
						
						</section>

					<?php } ?>
					
				</div>
				</section>
				<!-- Left column -->	
				<?php if($s5_pos_left == "published" || $s5_pos_left_inset == "published" || $s5_pos_left_top == "published" || $s5_pos_left_bottom == "published") { ?>
					<aside id="s5_left_column_wrap" class="s5_float_left"<?php if ($s5_columns_fixed_fluid == "px") { ?> style="width:<?php echo $s5_left_column_width ?>px"<?php } ?>>
					<div id="s5_left_column_wrap_inner">
						<?php if($s5_pos_left_top == "published") { ?>
							<div id="s5_left_top_wrap" class="s5_float_left"<?php if ($s5_columns_fixed_fluid == "px") { ?> style="width:<?php echo $s5_left_column_width ?>px"<?php } ?>>
								<?php s5_module_call('left_top','round_box'); ?>
							</div>
						<?php } ?>
						<?php if($s5_pos_left == "published") { ?>
							<div id="s5_left_wrap" class="s5_float_left"<?php if ($s5_columns_fixed_fluid == "px") { ?> style="width:<?php echo $s5_left_width ?>px"<?php } ?>>
								<?php s5_module_call('left','round_box'); ?>
							</div>
						<?php } ?>
						<?php if($s5_pos_left_inset == "published") { ?>
							<div id="s5_left_inset_wrap" class="s5_float_left"<?php if ($s5_columns_fixed_fluid == "px") { ?> style="width:<?php echo $s5_left_inset_width ?>px"<?php } ?>>
								<?php s5_module_call('left_inset','round_box'); ?>
							</div>
						<?php } ?>
						<?php if($s5_pos_left_bottom == "published") { ?>
							<div id="s5_left_bottom_wrap" class="s5_float_left"<?php if ($s5_columns_fixed_fluid == "px") { ?> style="width:<?php echo $s5_left_column_width ?>px"<?php } ?>>
								<?php s5_module_call('left_bottom','round_box'); ?>
							</div>
						<?php } ?>
						<div style="clear:both;height:0px;"></div>
					</div>
					</aside>
				<?php } ?>
				<!-- End Left column -->	
				<!-- Right column -->	
				<?php if($s5_pos_right == "published" || $s5_pos_right_inset == "published" || $s5_pos_right_top == "published" || $s5_pos_right_bottom == "published") { ?>
					<aside id="s5_right_column_wrap" class="s5_float_left"<?php if ($s5_columns_fixed_fluid == "px") { ?> style="width:<?php echo $s5_right_column_width ?>px; margin-left:-<?php echo $s5_right_column_width + $s5_left_column_width ?>px"<?php } ?>>
					<div id="s5_right_column_wrap_inner">
						<?php if($s5_pos_right_top == "published") { ?>
							<div id="s5_right_top_wrap" class="s5_float_left"<?php if ($s5_columns_fixed_fluid == "px") { ?> style="width:<?php echo $s5_right_column_width ?>px"<?php } ?>>
								<?php s5_module_call('right_top','round_box'); ?>
							</div>
						<?php } ?>
						<?php if($s5_pos_right_inset == "published") { ?>
							<div id="s5_right_inset_wrap" class="s5_float_left"<?php if ($s5_columns_fixed_fluid == "px") { ?> style="width:<?php echo $s5_right_inset_width ?>px"<?php } ?>>
								<?php s5_module_call('right_inset','round_box'); ?>
							</div>
						<?php } ?>
						<?php if($s5_pos_right == "published") { ?>
							<div id="s5_right_wrap" class="s5_float_left"<?php if ($s5_columns_fixed_fluid == "px") { ?> style="width:<?php echo $s5_right_width ?>px"<?php } ?>>
								<?php s5_module_call('right','round_box'); ?>
							</div>
						<?php } ?>
						<?php if($s5_pos_right_bottom == "published") { ?>
							<div id="s5_right_bottom_wrap" class="s5_float_left"<?php if ($s5_columns_fixed_fluid == "px") { ?> style="width:<?php echo $s5_right_column_width ?>px"<?php } ?>>
								<?php s5_module_call('right_bottom','round_box'); ?>
							</div>
						<?php } ?>
						<div style="clear:both;height:0px;"></div>
					</div>
					</aside>
				<?php } ?>
				<!-- End Right column -->	
				<div style="clear:both;height:0px;"></div>
			</div>
			</section>
			<!-- End columns wrap -->	
			
		<!-- Below Columns Wrap -->	
			<?php if ($s5_pos_below_columns_1 == "published" || $s5_pos_below_columns_2 == "published" || $s5_pos_below_columns_3 == "published" || $s5_pos_below_columns_4 == "published" || $s5_pos_below_columns_5 == "published" || $s5_pos_below_columns_6 == "published") { ?>
			<section id="s5_below_columns_wrap1"<?php if ($s5_below_columns_wrap1_background == "no") { ?> class="s5_no_custom_bg"<?php } else { ?> class="s5_yes_custom_bg<?php if ($s5_below_columns_wrap1_background_color == "FFFFFF" && $s5_below_columns_wrap1_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>	
			<div id="s5_below_columns_wrap2"<?php if ($s5_below_columns_wrap2_background == "no") { ?> class="s5_no_custom_bg"<?php } else { ?> class="s5_yes_custom_bg<?php if ($s5_below_columns_wrap2_background_color == "FFFFFF" && $s5_below_columns_wrap2_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
			<div id="s5_below_columns_inner">

						<?php if ($s5_pos_below_columns_1 == "published") { ?>
							<div id="s5_below_columns_1" class="s5_float_left" style="width:<?php echo $s5_pos_below_columns_1_width ?>%">
								<?php s5_module_call('below_columns_1','round_box'); ?>
							</div>
						<?php } ?>
						
						<?php if ($s5_pos_below_columns_2 == "published") { ?>
							<div id="s5_below_columns_2" class="s5_float_left" style="width:<?php echo $s5_pos_below_columns_2_width ?>%">
								<?php s5_module_call('below_columns_2','round_box'); ?>
							</div>
						<?php } ?>
						
						<?php if ($s5_pos_below_columns_3 == "published") { ?>
							<div id="s5_below_columns_3" class="s5_float_left" style="width:<?php echo $s5_pos_below_columns_3_width ?>%">
								<?php s5_module_call('below_columns_3','round_box'); ?>
							</div>
						<?php } ?>
						
						<?php if ($s5_pos_below_columns_4 == "published") { ?>
							<div id="s5_below_columns_4" class="s5_float_left" style="width:<?php echo $s5_pos_below_columns_4_width ?>%">
								<?php s5_module_call('below_columns_4','round_box'); ?>
							</div>
						<?php } ?>
						
						<?php if ($s5_pos_below_columns_5 == "published") { ?>
							<div id="s5_below_columns_5" class="s5_float_left" style="width:<?php echo $s5_pos_below_columns_5_width ?>%">
								<?php s5_module_call('below_columns_5','round_box'); ?>
							</div>
						<?php } ?>
						
						<?php if ($s5_pos_below_columns_6 == "published") { ?>
							<div id="s5_below_columns_6" class="s5_float_left" style="width:<?php echo $s5_pos_below_columns_6_width ?>%">
								<?php s5_module_call('below_columns_6','round_box'); ?>
							</div>
						<?php } ?>		
						
						<div style="clear:both; height:0px"></div>

			</div>
			</div>
			</section>
			<?php } ?>
		<!-- End Below Columns Wrap -->				
			
			
		</div>
		</div>
		</section>
		<?php } ?>
	<!-- End Center area -->	
	
	
	<?php if ($s5_pos_custom_6 == "published") { ?>
		<div id="s5_pos_custom_6_wrap">
			<?php s5_module_call('custom_6','notitle'); ?>
			<div style="clear:both; height:0px"></div>
		</div>
	<?php } ?>
	
	<!-- Bottom Row1 -->	
		<?php if ($s5_pos_bottom_row1_1 == "published" || $s5_pos_bottom_row1_2 == "published" || $s5_pos_bottom_row1_3 == "published" || $s5_pos_bottom_row1_4 == "published" || $s5_pos_bottom_row1_5 == "published" || $s5_pos_bottom_row1_6 == "published") { ?>
			<section id="s5_bottom_row1_area1"<?php if ($s5_bottom_row1_area1_background == "no") { ?> class="s5_slidesection s5_no_custom_bg"<?php } else { ?> class="s5_slidesection s5_yes_custom_bg<?php if ($s5_bottom_row1_area1_background_color == "FFFFFF" && $s5_bottom_row1_area1_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
			<div id="s5_bottom_row1_area2"<?php if ($s5_bottom_row1_area2_background == "no") { ?> class="s5_no_custom_bg"<?php } else { ?> class="s5_yes_custom_bg<?php if ($s5_bottom_row1_area2_background_color == "FFFFFF" && $s5_bottom_row1_area2_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
			<div id="s5_bottom_row1_area_inner" class="s5_wrap">

				<div id="s5_bottom_row1_wrap">
				<div id="s5_bottom_row1">
				<div id="s5_bottom_row1_inner">
				
					<?php if ($s5_pos_bottom_row1_1 == "published") { ?>
						<div id="s5_pos_bottom_row1_1" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row1_1_width ?>%">
							<?php s5_module_call('bottom_row1_1','round_box'); ?>
						</div>
					<?php } ?>
					
					<?php if ($s5_pos_bottom_row1_2 == "published") { ?>
						<div id="s5_pos_bottom_row1_2" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row1_2_width ?>%">
							<?php s5_module_call('bottom_row1_2','round_box'); ?>
						</div>
					<?php } ?>
					
					<?php if ($s5_pos_bottom_row1_3 == "published") { ?>
						<div id="s5_pos_bottom_row1_3" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row1_3_width ?>%">
							<?php s5_module_call('bottom_row1_3','round_box'); ?>
						</div>
					<?php } ?>
					
					<?php if ($s5_pos_bottom_row1_4 == "published") { ?>
						<div id="s5_pos_bottom_row1_4" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row1_4_width ?>%">
							<?php s5_module_call('bottom_row1_4','round_box'); ?>
						</div>
					<?php } ?>
					
					<?php if ($s5_pos_bottom_row1_5 == "published") { ?>
						<div id="s5_pos_bottom_row1_5" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row1_5_width ?>%">
							<?php s5_module_call('bottom_row1_5','round_box'); ?>
						</div>
					<?php } ?>
					
					<?php if ($s5_pos_bottom_row1_6 == "published") { ?>
						<div id="s5_pos_bottom_row1_6" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row1_6_width ?>%">
							<?php s5_module_call('bottom_row1_6','round_box'); ?>
						</div>
					<?php } ?>
					
					<div style="clear:both; height:0px"></div>

				</div>
				</div>
				</div>

		</div>
		</div>
		</section>
		<?php } ?>
	<!-- End Bottom Row1 -->	
		
		
	<!-- Bottom Row2 -->	
		<?php if ($s5_pos_bottom_row2_1 == "published" || $s5_pos_bottom_row2_2 == "published" || $s5_pos_bottom_row2_3 == "published" || $s5_pos_bottom_row2_4 == "published" || $s5_pos_bottom_row2_5 == "published" || $s5_pos_bottom_row2_6 == "published") { ?>
		<section id="s5_bottom_row2_area1"<?php if ($s5_bottom_row2_area1_background == "no") { ?> class="s5_slidesection s5_no_custom_bg"<?php } else { ?> class="s5_slidesection s5_yes_custom_bg<?php if ($s5_bottom_row2_area1_background_color == "FFFFFF" && $s5_bottom_row2_area1_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
		<div id="s5_bottom_row2_area2"<?php if ($s5_bottom_row2_area2_background == "no") { ?> class="s5_no_custom_bg"<?php } else { ?> class="s5_yes_custom_bg<?php if ($s5_bottom_row2_area2_background_color == "FFFFFF" && $s5_bottom_row2_area2_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
		<div id="s5_bottom_row2_area_inner" class="s5_wrap">			
		
			<div id="s5_bottom_row2_wrap">
			<div id="s5_bottom_row2">
			<div id="s5_bottom_row2_inner">					
				<?php if ($s5_pos_bottom_row2_1 == "published") { ?>
					<div id="s5_pos_bottom_row2_1" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row2_1_width ?>%">
						<?php s5_module_call('bottom_row2_1','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_bottom_row2_2 == "published") { ?>
					<div id="s5_pos_bottom_row2_2" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row2_2_width ?>%">
						<?php s5_module_call('bottom_row2_2','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_bottom_row2_3 == "published") { ?>
					<div id="s5_pos_bottom_row2_3" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row2_3_width ?>%">
						<?php s5_module_call('bottom_row2_3','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_bottom_row2_4 == "published") { ?>
					<div id="s5_pos_bottom_row2_4" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row2_4_width ?>%">
						<?php s5_module_call('bottom_row2_4','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_bottom_row2_5 == "published") { ?>
					<div id="s5_pos_bottom_row2_5" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row2_5_width ?>%">
						<?php s5_module_call('bottom_row2_5','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_bottom_row2_6 == "published") { ?>
					<div id="s5_pos_bottom_row2_6" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row2_6_width ?>%">
						<?php s5_module_call('bottom_row2_6','round_box'); ?>
					</div>
				<?php } ?>		
				
				<div style="clear:both; height:0px"></div>
				
			</div>
			</div>	
			</div>	
				
		</div>
		</div>
		</section>
		<?php } ?>
	<!-- End Bottom Row2 -->
	
	
	
	<!-- Bottom Row3 -->	
		<?php if ($s5_pos_bottom_row3_1 == "published" || $s5_pos_bottom_row3_2 == "published" || $s5_pos_bottom_row3_3 == "published" || $s5_pos_bottom_row3_4 == "published" || $s5_pos_bottom_row3_5 == "published" || $s5_pos_bottom_row3_6 == "published" || $s5_pos_custom_7 == "published") { ?>
		<section id="s5_bottom_row3_area1"<?php if ($s5_bottom_row3_area1_background == "no") { ?> class="s5_slidesection s5_no_custom_bg"<?php } else { ?> class="s5_slidesection s5_yes_custom_bg<?php if ($s5_bottom_row3_area1_background_color == "FFFFFF" && $s5_bottom_row3_area1_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>	
		<div id="s5_bottom_row3_area2"<?php if ($s5_bottom_row3_area2_background == "no") { ?> class="s5_no_custom_bg"<?php } else { ?> class="s5_yes_custom_bg<?php if ($s5_bottom_row3_area2_background_color == "FFFFFF" && $s5_bottom_row3_area2_background_image == "") { ?> s5_yes_custom_bg_white<?php } ?>"<?php } ?>>
		<div id="s5_bottom_row3_area_inner" class="s5_wrap">
		
			<div id="s5_bottom_row3_wrap">
			<div id="s5_bottom_row3">
			<div id="s5_bottom_row3_inner">
			
				<?php if ($s5_pos_custom_7 == "published") { ?>
					<div id="s5_pos_custom_7_wrap">
						<?php s5_module_call('custom_7','notitle'); ?>
						<div style="clear:both; height:0px"></div>
					</div>
				<?php } ?>
			
				<?php if ($s5_pos_bottom_row3_1 == "published") { ?>
					<div id="s5_pos_bottom_row3_1" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row3_1_width ?>%">
						<?php s5_module_call('bottom_row3_1','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_bottom_row3_2 == "published") { ?>
					<div id="s5_pos_bottom_row3_2" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row3_2_width ?>%">
						<?php s5_module_call('bottom_row3_2','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_bottom_row3_3 == "published") { ?>
					<div id="s5_pos_bottom_row3_3" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row3_3_width ?>%">
						<?php s5_module_call('bottom_row3_3','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_bottom_row3_4 == "published") { ?>
					<div id="s5_pos_bottom_row3_4" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row3_4_width ?>%">
						<?php s5_module_call('bottom_row3_4','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_bottom_row3_5 == "published") { ?>
					<div id="s5_pos_bottom_row3_5" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row3_5_width ?>%">
						<?php s5_module_call('bottom_row3_5','round_box'); ?>
					</div>
				<?php } ?>
				
				<?php if ($s5_pos_bottom_row3_6 == "published") { ?>
					<div id="s5_pos_bottom_row3_6" class="s5_float_left" style="width:<?php echo $s5_pos_bottom_row3_6_width ?>%">
						<?php s5_module_call('bottom_row3_6','round_box'); ?>
					</div>
				<?php } ?>	
				
				<div style="clear:both; height:0px"></div>

			</div>
			</div>
			</div>

		</div>
		</div>
		</section>
		<?php } ?>
	<!-- End Bottom Row3 -->
	
	
	<?php if($s5_pos_language == "published" || $s5_font_resizer == "yes" || $s5_pos_breadcrumb == "published") { ?>
		<div id="s5_breadcrumb_font_lang_wrap">
		<div id="s5_breadcrumb_font_lang_wrap_inner">
			<?php if($s5_pos_language == "published") { ?>
				<div id="s5_language_wrap">
					<?php require_once("vertex/language_position.php"); ?>
				</div>
			<?php } ?>

			<?php if($s5_font_resizer == "yes") { ?>
				<div id="fontControls"></div>
			<?php } ?>
			
			<?php if ($s5_pos_breadcrumb == "published") { ?>
				<div id="s5_breadcrumb_wrap">
					<?php s5_module_call('breadcrumb','notitle'); ?>
				</div>
				<div style="clear:both;"></div>
			<?php } ?>
		</div>
		</div>
	<?php } ?>
	
	
	<!-- Footer Area -->
		<footer id="s5_footer_area1" class="s5_slidesection">
		<div id="s5_footer_area2">
		<div id="s5_footer_area_inner">
		
			<?php if($s5_pos_footer == "published") { ?>
				<div id="s5_footer_module">
					<?php s5_module_call('footer','notitle'); ?>
				</div>	
			<?php } else { ?>
				<div id="s5_footer">
					<?php require_once("vertex/footer.php"); ?>
				</div>
			<?php } ?>
			
			<?php if($s5_pos_bottom_menu == "published") { ?>
				<div id="s5_bottom_menu_wrap">
					<?php s5_module_call('bottom_menu','notitle'); ?>
				</div>	
			<?php } ?>
			<div style="clear:both; height:0px"></div>
			
		</div>
		</div>
		</footer>
	<!-- End Footer Area -->
	
	<?php s5_module_call('debug','round_box'); ?>
	
	<!-- Bottom Vertex Calls -->
	<?php require_once("vertex/includes/vertex_includes_bottom.php"); ?>
	
</div>
<!-- End Body Padding -->
	
	
<?php if ($s5_pos_search == "published") { ?>
<script>
function s5_search_open() {
	document.getElementById('s5_search_overlay').className = "s5_search_open";
	if (document.getElementById("s5_drop_down_container")) { 
		document.getElementById("s5_drop_down_container").style.display = "none"; 
	}
}
function s5_search_close() {
	document.getElementById('s5_search_overlay').className = "s5_search_close";
	if (document.getElementById("s5_drop_down_container")) { 
		document.getElementById("s5_drop_down_container").style.display = "block"; 
	}
}
</script>
<?php } ?>

</body>
</html>