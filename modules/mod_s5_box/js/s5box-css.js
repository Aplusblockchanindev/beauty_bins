jQuery(document).ready(function() {
	jQuery(document.body).append('<div id="cboxOverlay" class="s5-box-overlay-closed" style="cursor: pointer;display:block;z-index:-100;height:0px;"></div><div id="colorbox" class="" style="left: 0;margin:auto;padding-bottom: 0;padding-right: 0;position:absolute;right: 0;top: 100;width: auto;z-index: -100;"><div id="cboxWrapper"><div><div id="cboxTopLeft" style="float: left;"></div><div id="cboxTopCenter" style="float: left; width: auto;"></div><div id="cboxTopRight" style="float: left;"></div></div><div><div id="cboxMiddleLeft" style="float: left;"></div><div id="cboxContent" style="float: left;"><div id="cboxLoadedContent" style="overflow: auto;"></div><div class="cboxClose" id="cboxClose" style="float: left;">close</div></div><div id="cboxMiddleRight" style="float: left;"></div></div><div><div id="cboxBottomLeft" style="float: left;"></div><div id="cboxBottomCenter" style="float: left; width: auto;"></div><div id="cboxBottomRight" style="float: left;"></div></div></div></div>');
	
	function s5_close_box_overlay() {
		jQuery('#cboxOverlay').css("height", "0px");	
		jQuery('#cboxOverlay').css("zIndex", "-100");
	}
				
	//* hides for s5 box's */		
	jQuery('.cboxClose').click(function() {
		window.setTimeout(s5_close_box_overlay,s5_box_speed);
		jQuery('#cboxOverlay').css('z-index', -100);	
		jQuery('#colorbox').css('z-index', -100);		
		jQuery('.moduletable-s5_box').css("display", "none");
		jQuery('#cboxOverlay').removeClass('s5-box-overlay');	
		jQuery('#cboxOverlay').addClass('s5-box-overlay-closed');
		jQuery('#colorbox').removeClass('s5-box-effect');
		jQuery('#colorbox').css("display", "none");
		});	
		
	//* hides overlay for all s5 box's */		
	jQuery('#cboxOverlay').click(function() {		
		window.setTimeout(s5_close_box_overlay,s5_box_speed);
		jQuery('#cboxOverlay').css('z-index', -100);	
		jQuery('#colorbox').css('z-index', -100);
		jQuery('.moduletable-s5_box').css("display", "none");
		jQuery('#cboxOverlay').removeClass('s5-box-overlay');	
		jQuery('#cboxOverlay').addClass('s5-box-overlay-closed');
		jQuery('#colorbox').removeClass('s5-box-effect');
		jQuery('#colorbox').css("display", "none");		});
			
	jQuery('.s5box_login').click(function() {
		jQuery('#colorbox').css("display", "block");
		jQuery('#cboxOverlay').addClass('s5-box-overlay');
		jQuery('#cboxContent').addClass('s5-box-width');
		jQuery('#cboxOverlay').removeClass('s5-box-overlay-closed');
		jQuery('#cboxOverlay').css('z-index', 9999);
		jQuery('#cboxOverlay').css("height", "100%");			
		jQuery('#colorbox').css('z-index', 9999);
		if (document.documentElement.scrollTop >= 100) {
			var s5_box_align_top = document.documentElement.scrollTop + 100;
		} else {
			s5_box_align_top = 0;
		}
		jQuery('#colorbox').css('top', s5_box_align_top);
		jQuery('#colorbox').css('bottom', 0);
		jQuery('#colorbox').addClass('s5-box-effect');
		jQuery('.moduletable-s5_box').css("display", "block");	});
			
	jQuery('.s5box_register').click(function() {
		jQuery('#colorbox').css("display", "block");
		jQuery('#cboxOverlay').addClass('s5-box-overlay');
		jQuery('#cboxContent').addClass('s5-box-width');
		jQuery('#cboxOverlay').removeClass('s5-box-overlay-closed');
		jQuery('#cboxOverlay').css('z-index', 9999);
		jQuery('#cboxOverlay').css("height", "100%");
		jQuery('#colorbox').css('z-index', 9999);
		if (document.documentElement.scrollTop >= 100) {
			var s5_box_align_top = document.documentElement.scrollTop + 100;
		} else {
			s5_box_align_top = 0;
		}
		if (s5_box_align_top < 100) {
			s5_box_align_top = 100;
		}
		jQuery('#colorbox').css('top', s5_box_align_top);
		jQuery('#colorbox').css('bottom', 0);
		jQuery('#colorbox').addClass('s5-box-effect');
		jQuery('.moduletable-s5_box').css("display", "block");	});		

	jQuery('.s5box_one').click(function() {
		jQuery('#colorbox').css("display", "block");
		jQuery('#cboxOverlay').addClass('s5-box-overlay');
		jQuery('#cboxContent').addClass('s5-box-width');
		jQuery('#cboxOverlay').removeClass('s5-box-overlay-closed');
		jQuery('#cboxOverlay').css('z-index', 9999);
		jQuery('#cboxOverlay').css("height", "100%");
		jQuery('#colorbox').css('z-index', 9999);
		if (document.documentElement.scrollTop >= 100) {
			var s5_box_align_top = document.documentElement.scrollTop + 100;
		} else {
			s5_box_align_top = 0;
		}
		if (s5_box_align_top < 100) {
			s5_box_align_top = 100;
		}
		jQuery('#colorbox').css('top', s5_box_align_top);
		jQuery('#colorbox').css('bottom', 0);
		jQuery('#colorbox').addClass('s5-box-effect');
		jQuery('.moduletable-s5_box').css("display", "block");	});		
		
	jQuery('.s5box_two').click(function() {
		jQuery('#colorbox').css("display", "block");
		jQuery('#cboxOverlay').addClass('s5-box-overlay');
		jQuery('#cboxContent').addClass('s5-box-width');
		jQuery('#cboxOverlay').removeClass('s5-box-overlay-closed');
		jQuery('#cboxOverlay').css('z-index', 9999);
		jQuery('#cboxOverlay').css("height", "100%");	
		jQuery('#colorbox').css('z-index', 9999);
		if (document.documentElement.scrollTop >= 100) {
			var s5_box_align_top = document.documentElement.scrollTop + 100;
		} else {
			s5_box_align_top = 0;
		}
		if (s5_box_align_top < 100) {
			s5_box_align_top = 100;
		}
		jQuery('#colorbox').css('top', s5_box_align_top);
		jQuery('#colorbox').css('bottom', 0);
		jQuery('#colorbox').addClass('s5-box-effect');
		jQuery('.moduletable-s5_box').css("display", "block");	});		
		
	jQuery('.s5box_three').click(function() {
		jQuery('#colorbox').css("display", "block");
		jQuery('#cboxOverlay').addClass('s5-box-overlay');
		jQuery('#cboxContent').addClass('s5-box-width');
		jQuery('#cboxOverlay').removeClass('s5-box-overlay-closed');
		jQuery('#cboxOverlay').css('z-index', 9999);
		jQuery('#cboxOverlay').css("height", "100%");	
		jQuery('#colorbox').css('z-index', 9999);
		if (document.documentElement.scrollTop >= 100) {
			var s5_box_align_top = document.documentElement.scrollTop + 100;
		} else {
			s5_box_align_top = 0;
		}
		if (s5_box_align_top < 100) {
			s5_box_align_top = 100;
		}
		jQuery('#colorbox').css('top', s5_box_align_top);
		jQuery('#colorbox').css('bottom', 0);
		jQuery('#colorbox').addClass('s5-box-effect');
		jQuery('.moduletable-s5_box').css("display", "block");	});				
		
	jQuery('.s5box_four').click(function() {
		jQuery('#colorbox').css("display", "block");
		jQuery('#cboxOverlay').addClass('s5-box-overlay');
		jQuery('#cboxContent').addClass('s5-box-width');
		jQuery('#cboxOverlay').removeClass('s5-box-overlay-closed');
		jQuery('#cboxOverlay').css('z-index', 9999);
		jQuery('#cboxOverlay').css("height", "100%");	
		jQuery('#colorbox').css('z-index', 9999);
		if (document.documentElement.scrollTop >= 100) {
			var s5_box_align_top = document.documentElement.scrollTop + 100;
		} else {
			s5_box_align_top = 0;
		}
		if (s5_box_align_top < 100) {
			s5_box_align_top = 100;
		}
		jQuery('#colorbox').css('top', s5_box_align_top);
		jQuery('#colorbox').css('bottom', 0);
		jQuery('#colorbox').addClass('s5-box-effect');
		jQuery('.moduletable-s5_box').css("display", "block");	});
		
	jQuery('.s5box_five').click(function() {
		jQuery('#colorbox').css("display", "block");
		jQuery('#cboxOverlay').addClass('s5-box-overlay');
		jQuery('#cboxContent').addClass('s5-box-width');
		jQuery('#cboxOverlay').removeClass('s5-box-overlay-closed');
		jQuery('#cboxOverlay').css('z-index', 9999);
		jQuery('#cboxOverlay').css("height", "100%");
		jQuery('#colorbox').css('z-index', 9999);
		if (document.documentElement.scrollTop >= 100) {
			var s5_box_align_top = document.documentElement.scrollTop + 100;
		} else {
			s5_box_align_top = 0;
		}
		if (s5_box_align_top < 100) {
			s5_box_align_top = 100;
		}
		jQuery('#colorbox').css('top', s5_box_align_top);
		jQuery('#colorbox').css('bottom', 0);
		jQuery('#colorbox').addClass('s5-box-effect');
		jQuery('.moduletable-s5_box').css("display", "block");	});	
		
	jQuery('.s5box_six').click(function() {
		jQuery('#colorbox').css("display", "block");
		jQuery('#cboxOverlay').addClass('s5-box-overlay');
		jQuery('#cboxContent').addClass('s5-box-width');
		jQuery('#cboxOverlay').removeClass('s5-box-overlay-closed');
		jQuery('#cboxOverlay').css('z-index', 9999);
		jQuery('#cboxOverlay').css("height", "100%");	
		jQuery('#colorbox').css('z-index', 9999);
		if (document.documentElement.scrollTop >= 100) {
			var s5_box_align_top = document.documentElement.scrollTop + 100;
		} else {
			s5_box_align_top = 0;
		}
		if (s5_box_align_top < 100) {
			s5_box_align_top = 100;
		}
		jQuery('#colorbox').css('top', s5_box_align_top);
		jQuery('#colorbox').css('bottom', 0);
		jQuery('#colorbox').addClass('s5-box-effect');
		jQuery('.moduletable-s5_box').css("display", "block");	});	
		
	jQuery('.s5box_seven').click(function() {
		jQuery('#colorbox').css("display", "block");
		jQuery('#cboxOverlay').addClass('s5-box-overlay');
		jQuery('#cboxContent').addClass('s5-box-width');
		jQuery('#cboxOverlay').removeClass('s5-box-overlay-closed');
		jQuery('#cboxOverlay').css('z-index', 9999);
		jQuery('#cboxOverlay').css("height", "100%");	
		jQuery('#colorbox').css('z-index', 9999);
		if (document.documentElement.scrollTop >= 100) {
			var s5_box_align_top = document.documentElement.scrollTop + 100;
		} else {
			s5_box_align_top = 0;
		}
		if (s5_box_align_top < 100) {
			s5_box_align_top = 100;
		}
		jQuery('#colorbox').css('top', s5_box_align_top);
		jQuery('#colorbox').css('bottom', 0);
		jQuery('#colorbox').addClass('s5-box-effect');
		jQuery('.moduletable-s5_box').css("display", "block");	});					
			
	jQuery('.s5box_eight').click(function() {
		jQuery('#colorbox').css("display", "block");
		jQuery('#cboxOverlay').addClass('s5-box-overlay');
		jQuery('#cboxContent').addClass('s5-box-width');
		jQuery('#cboxOverlay').removeClass('s5-box-overlay-closed');
		jQuery('#cboxOverlay').css('z-index', 9999);
		jQuery('#cboxOverlay').css("height", "100%");
		jQuery('#colorbox').css('z-index', 9999);
		if (document.documentElement.scrollTop >= 100) {
			var s5_box_align_top = document.documentElement.scrollTop + 100;
		} else {
			s5_box_align_top = 0;
		}
		if (s5_box_align_top < 100) {
			s5_box_align_top = 100;
		}
		jQuery('#colorbox').css('top', s5_box_align_top);
		jQuery('#colorbox').css('bottom', 0);
		jQuery('#colorbox').addClass('s5-box-effect');
		jQuery('.moduletable-s5_box').css("display", "block");	});		

	jQuery('.s5box_nine').click(function() {
		jQuery('#colorbox').css("display", "block");
		jQuery('#cboxOverlay').addClass('s5-box-overlay');
		jQuery('#cboxContent').addClass('s5-box-width');
		jQuery('#cboxOverlay').removeClass('s5-box-overlay-closed');
		jQuery('#cboxOverlay').css('z-index', 9999);
		jQuery('#cboxOverlay').css("height", "100%");	
		jQuery('#colorbox').css('z-index', 9999);
		if (document.documentElement.scrollTop >= 100) {
			var s5_box_align_top = document.documentElement.scrollTop + 100;
		} else {
			s5_box_align_top = 0;
		}
		if (s5_box_align_top < 100) {
			s5_box_align_top = 100;
		}
		jQuery('#colorbox').css('top', s5_box_align_top);
		jQuery('#colorbox').css('bottom', 0);
		jQuery('#colorbox').addClass('s5-box-effect');
		jQuery('.moduletable-s5_box').css("display", "block");	});		

	jQuery('.s5box_ten').click(function() {
		jQuery('#colorbox').css("display", "block");
		jQuery('#cboxOverlay').addClass('s5-box-overlay');
		jQuery('#cboxContent').addClass('s5-box-width');
		jQuery('#cboxOverlay').removeClass('s5-box-overlay-closed');
		jQuery('#cboxOverlay').css('z-index', 9999);
		jQuery('#cboxOverlay').css("height", "100%");	
		jQuery('#colorbox').css('z-index', 9999);
		if (document.documentElement.scrollTop >= 100) {
			var s5_box_align_top = document.documentElement.scrollTop + 100;
		} else {
			s5_box_align_top = 0;
		}
		if (s5_box_align_top < 100) {
			s5_box_align_top = 100;
		}
		jQuery('#colorbox').css('top', s5_box_align_top);
		jQuery('#colorbox').css('bottom', 0);
		jQuery('#colorbox').addClass('s5-box-effect');
		jQuery('.moduletable-s5_box').css("display", "block");	});	
		
});	