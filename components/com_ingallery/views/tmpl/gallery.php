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
$items = $this->get('items');
$this->set('galleryCSSid','ingallery-'.$this->get('id'));

$galCSSid = $this->get('galleryCSSid');

$carouselCfg = '';
if($cfg['layout_type']=='carousel'){
	$carouselCfg = 'data-slick=\'{';
    if($cfg['layout_rows']>1){
        $carouselCfg.= '"rows":'.$cfg['layout_rows'].',';
        $carouselCfg.= '"slidesToShow":1,';
        $carouselCfg.= '"slidesToScroll":1,';
    }
    else{
        $carouselCfg.= '"slidesToShow":'.$cfg['layout_cols'].',';
        $carouselCfg.= '"slidesToScroll":'.$cfg['layout_cols'].',';
    }
    
    $carouselCfg.= '"slidesPerRow":'.$cfg['layout_cols'].',';
	$carouselCfg.= '"infinite":'.($cfg['layout_infinite_scroll']?'true':'false').',';
    $carouselCfg.= '"responsive":[';
    if(count($cfg['layout_responsive'])){
        $countResps = count($cfg['layout_responsive']);
        $i=0;
        foreach($cfg['layout_responsive'] as $w=>$resp){
            $i++;
            $carouselCfg.= '{"breakpoint": '.$w.',"settings":{';
            if($cfg['layout_rows']>1){
                $carouselCfg.= '"rows":'.$cfg['layout_rows'].',';
                $carouselCfg.= '"slidesToShow":1,';
                $carouselCfg.= '"slidesToScroll":1,';
            }
            else{
                $carouselCfg.= '"slidesToShow":'.$resp['cols'].',';
                $carouselCfg.= '"slidesToScroll":'.$resp['cols'].',';
            }
            $carouselCfg.= '"slidesPerRow":'.$resp['cols'].'}}'.($i<$countResps?',':'');
        }
    }
    else{
        $carouselCfg.= '{"breakpoint": 500,"settings":{"slidesToShow":1,"slidesToScroll":1}}';
    }
    
    $carouselCfg.= ']';
    
    if($cfg['layout_autoscroll']==1){
        $carouselCfg.= ',"autoplay":true';
        $carouselCfg.= ',"autoplaySpeed":'.round($cfg['layout_autoscroll_speed']*1000);
    }
	$carouselCfg.= '}\'';
}

$classes = array();
$classes[] = 'ingallery';
$classes[] = 'ingallery-layout-'.$cfg['layout_type'];
$classes[] = 'ingallery-style-'.$cfg['display_style'];
if($this->get('cfg/layout_rtl',0)==1){
	$classes[] = 'ingallery-rtl';
}

if(count($items)==0){
    echo '<div class="ingallery-message ing-error"><div class="ingallery-message-title">'.JText::_('COM_INGALLERY_UNFORTUNATELY_AN_ERROR_OC').'</div><div class="ingallery-message-text">'.JText::_('COM_INGALLERY_NO_MEDIA_FOUND').'</div></div>';
    return;
}
?>
<div class="<?php echo implode(' ',$classes);?>" id="<?php echo $galCSSid;?>" data-id="<?php echo $this->get('id');?>" data-filtered="0" data-page="<?php echo $this->get('page');?>" data-cfg="<?php echo str_replace('"','&quot;',json_encode($cfg));?>" <?php echo ($this->get('cfg/layout_rtl',0)==1?'dir="rtl"':'');?>>
	<?php
    if($cfg['layout_type']!='carousel' && $cfg['layout_show_albums'] && count($this->get('albums'))>1){
    ?>
        <div class="ingallery-albums">
            <?php
            $a = 0;
            foreach($this->get('albums') as $album){
                $a++;
                echo '<span class="ingallery-album" data-id="'.$album['id'].'">'.$album['name'].'</span>';
            }
            if($a>1){
                echo '<span class="ingallery-album active" data-id="0">'.JText::_('COM_INGALLERY_ALL_ALBUMS').'</span>';
            }
            ?>
        </div>
    <?php
    }
    ?>
    <div class="ingallery-items" <?php echo $carouselCfg;?>>
        <?php
        if(strpos($cfg['layout_type'],'masonry')!==false){
            echo '<div class="grid-sizer"></div>';
        }
        foreach($items as $item){
            $this->set('item',$item);
            echo '<div class="ingallery-cell" data-album="'.$item['album_id'].'">';
                echo $this->loadTemplate('item');
            echo '</div>';
        }
        ?>
    </div>
    <?php
    if(($cfg['layout_show_loadmore'] || $cfg['layout_infinite_scroll']) && $this->get('has_more')){
        ?>
        <div class="ingallery-loadmore <?php echo ($cfg['layout_infinite_scroll']?'ingallery-inf-scroll':'');?>">
            <span class="ingallery-loadmore-btn"><?php echo JText::_($cfg['layout_loadmore_text']);?></span>
        </div>
        <?php	
    } 
	?>
</div>
<?php
echo $this->loadTemplate('styles');
