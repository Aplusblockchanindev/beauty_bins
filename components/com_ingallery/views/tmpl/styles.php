<?php
/**
 * @package    inGallery
 * @subpackage com_ingallery
 * @license  http://www.gnu.org/licenseses/gpl-3.0.html GNU/GPL
 *
 * Created by Oleg Micriucov for Joomla! 3.x
 * https://allforjoomla.com
 *
 */
defined('_JEXEC') or die(':)');

$cfg = $this->get('cfg');
$galCSSid = $this->get('galleryCSSid');
?>
<style type="text/css">
#<?php echo $galCSSid;?>{background:<?php echo $cfg['colors_gallery_bg'];?>;}
#<?php echo $galCSSid;?> .ingallery-album{border-color:<?php echo $cfg['colors_album_btn_text'];?>;color:<?php echo $cfg['colors_album_btn_text'];?>;background:<?php echo $cfg['colors_album_btn_bg'];?>;}
#<?php echo $galCSSid;?> .ingallery-album:hover,
#<?php echo $galCSSid;?> .ingallery-album.active{color:<?php echo $cfg['colors_album_btn_hover_text'];?>;background:<?php echo $cfg['colors_album_btn_hover_bg'];?>;}
#<?php echo $galCSSid;?> .ingallery-item-overlay{background:<?php echo $cfg['colors_thumb_overlay_bg'];?>;}
#<?php echo $galCSSid;?> .ingallery-item-overlay .ingallery-item-stats{color:<?php echo $cfg['colors_thumb_overlay_text'];?>;}
#<?php echo $galCSSid;?> .ingallery-loadmore-btn{border-color:<?php echo $cfg['colors_more_btn_bg'];?>;color:<?php echo $cfg['colors_more_btn_text'];?>;background:<?php echo $cfg['colors_more_btn_bg'];?>;}
#<?php echo $galCSSid;?> .ingallery-loadmore-btn:hover{color:<?php echo $cfg['colors_more_btn_hover_text'];?>;background:<?php echo $cfg['colors_more_btn_hover_bg'];?>;}
<?php
if($cfg['layout_preloader_img']!=''){
    echo '#'.$galCSSid.' .ingallery-item-img, ';
    echo '.'.$galCSSid.' #ingallery-popup-wrap-img{background-image:url("'.$cfg['layout_preloader_img'].'");}'."\n";
}
?>
</style>