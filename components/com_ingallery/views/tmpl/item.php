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
$item = $this->get('item');
$imgRatio = round($item['dimensions']['width']/$item['dimensions']['height'],2);
$attr = ($imgRatio>1?'height':'width');
$previewItem = $this->getPreviewItem($item, $this->get('id'));
$previewItemCode = str_replace('"','&quot;',json_encode($previewItem));

$imgPlaceholder = JUri::root().'media/com_ingallery/images/blank.png';
?>
<div class="ingallery-item">
	<?php
    if($cfg['display_style']=='flipcards'){
		?>
        <div class="ingallery-item-box">
        	<div class="ingallery-style-flipcards-front">
            	<div class="ingallery-item-img <?php echo ($item['is_video']?'ingallery-item-video':'');?>">
                    <img src="<?php echo $imgPlaceholder;?>" data-src="<?php echo $previewItem['thumbnail_src'];?>" style="<?php echo $attr;?>:101% !important" crossorigin="anonymous" />
                    <?php echo (count($item['subgallery'])?'<div class="ingallery-item-subgallery-icon"><i class="ing-icon-picture"></i></div>':'');?>
                </div>
             </div>
            <div class="ingallery-style-flipcards-back">
            	<a href="https://www.instagram.com/p/<?php echo $item['code'];?>/" class="ingallery-item-link ingallery-item-link-<?php echo $cfg['display_link_mode'];?>" target="_blank" data-item="<?php echo $previewItemCode;?>">
                	<div class="ingallery-item-img <?php echo ($item['is_video']?'ingallery-item-video':'');?>">
                        <img src="<?php echo $imgPlaceholder;?>" data-src="<?php echo $previewItem['thumbnail_src'];?>" style="<?php echo $attr;?>:101% !important" crossorigin="anonymous" />
                        <?php echo (count($item['subgallery'])?'<div class="ingallery-item-subgallery-icon"><i class="ing-icon-picture"></i></div>':'');?>
                    </div>
                    <div class="ingallery-item-overlay">
						<?php
                        if($cfg['display_thumbs_likes'] || $cfg['display_thumbs_comments'] || /*($cfg['display_thumbs_plays']&&$item['is_video']) ||*/ $cfg['display_thumbs_description']){
                            echo '<div class="ingallery-item-stats">';
                                if($cfg['display_thumbs_likes']){
                                    echo '<span class="ingallery-item-stats-likes"><i class="ing-icon-heart-1"></i>'.$item['likes']['count'].'</span>';
                                }
                                if($cfg['display_thumbs_comments']){
                                    echo '<span class="ingallery-item-stats-comments"><i class="ing-icon-comment"></i>'.$item['comments']['count'].'</span>';
                                }
                                if($cfg['display_thumbs_description']){
                                    echo '<div class="ingallery-item-stats-caption">'.$item['caption'].'</div>';
                                }
                            echo '</div>';
                        }
                        ?>
                    </div>
                </a>
            </div>
        </div>
        <?php
	}
    else if($cfg['display_style']=='circles'){
		?>
        <a href="https://www.instagram.com/p/<?php echo $item['code'];?>/" class="ingallery-item-link ingallery-item-box ingallery-item-link-<?php echo $cfg['display_link_mode'];?>" target="_blank" data-item="<?php echo $previewItemCode;?>">
        	<div class="ingallery-style-circles-front">
                <div class="ingallery-item-img <?php echo ($item['is_video']?'ingallery-item-video':'');?>">
                    <img src="<?php echo $imgPlaceholder;?>" data-src="<?php echo $previewItem['thumbnail_src'];?>" style="<?php echo $attr;?>:101% !important" crossorigin="anonymous" />
                    <?php echo (count($item['subgallery'])?'<div class="ingallery-item-subgallery-icon"><i class="ing-icon-picture"></i></div>':'');?>
                </div>
            </div>
            <div class="ingallery-style-circles-back">
                    <div class="ingallery-item-overlay">
						<?php
                        if($cfg['display_thumbs_likes'] || $cfg['display_thumbs_comments'] || /*($cfg['display_thumbs_plays']&&$item['is_video']) ||*/ $cfg['display_thumbs_description']){
                            echo '<div class="ingallery-item-stats">';
                                if($cfg['display_thumbs_likes']){
                                    echo '<span class="ingallery-item-stats-likes"><i class="ing-icon-heart-1"></i>'.$item['likes']['count'].'</span>';
                                }
                                if($cfg['display_thumbs_comments']){
                                    echo '<span class="ingallery-item-stats-comments"><i class="ing-icon-comment-1"></i>'.$item['comments']['count'].'</span>';
                                }
                                if($cfg['display_thumbs_description']){
                                    echo '<div class="ingallery-item-stats-caption">'.$item['caption'].'</div>';
                                }
                            echo '</div>';
                        }
                        ?>
                    </div>
            </div>
        </a>
        <?php
	}
    else if($cfg['display_style']=='circles2'){
		?>
        <a href="https://www.instagram.com/p/<?php echo $item['code'];?>/" class="ingallery-item-link ingallery-item-box ingallery-item-link-<?php echo $cfg['display_link_mode'];?>" target="_blank" data-item="<?php echo $previewItemCode;?>">
        	<div class="ingallery-style-circles2-front">
                <div class="ingallery-item-img <?php echo ($item['is_video']?'ingallery-item-video':'');?>">
                    <img src="<?php echo $imgPlaceholder;?>" data-src="<?php echo $previewItem['thumbnail_src'];?>" style="<?php echo $attr;?>:101% !important" crossorigin="anonymous" />
                    <?php echo (count($item['subgallery'])?'<div class="ingallery-item-subgallery-icon"><i class="ing-icon-picture"></i></div>':'');?>
                </div>
            </div>
            <div class="ingallery-style-circles2-back">
                    <div class="ingallery-item-overlay">
						<?php
                        if($cfg['display_thumbs_likes'] || $cfg['display_thumbs_comments'] || /*($cfg['display_thumbs_plays']&&$item['is_video']) ||*/ $cfg['display_thumbs_description']){
                            echo '<div class="ingallery-item-stats">';
                                if($cfg['display_thumbs_likes']){
                                    echo '<span class="ingallery-item-stats-likes"><i class="ing-icon-heart-1"></i>'.$item['likes']['count'].'</span>';
                                }
                                if($cfg['display_thumbs_comments']){
                                    echo '<span class="ingallery-item-stats-comments"><i class="ing-icon-comment-1"></i>'.$item['comments']['count'].'</span>';
                                }
                                if($cfg['display_thumbs_description']){
                                    echo '<div class="ingallery-item-stats-caption">'.$item['caption'].'</div>';
                                }
                            echo '</div>';
                        }
                        ?>
                    </div>
            </div>
        </a>
        <?php
	}
	else if($cfg['display_style']=='dribbble'){
		?>
		<a href="https://www.instagram.com/p/<?php echo $item['code'];?>/" class="ingallery-item-link ingallery-item-link-<?php echo $cfg['display_link_mode'];?>" target="_blank" data-item="<?php echo $previewItemCode;?>">
			<div class="ingallery-style-dribbble-wrap ingallery-item-box">
                <div class="ingallery-item-img <?php echo ($item['is_video']?'ingallery-item-video':'');?>">
                    <img src="<?php echo $imgPlaceholder;?>" data-src="<?php echo $previewItem['thumbnail_src'];?>" style="<?php echo $attr;?>:101% !important" crossorigin="anonymous" />
                    <?php echo (count($item['subgallery'])?'<div class="ingallery-item-subgallery-icon"><i class="ing-icon-picture"></i></div>':'');?>
                </div>
                <div class="ingallery-item-overlay">
                    <?php
                    if($cfg['display_thumbs_description']){
                        echo '<div class="ingallery-item-stats">';
                            if($cfg['display_thumbs_description']){
                                echo '<div class="ingallery-item-stats-caption">'.$item['caption'].'</div>';
                            }
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <?php
            if($cfg['display_thumbs_likes'] || $cfg['display_thumbs_comments']){
				echo '<div class="ingallery-style-dribbble-stats">';
					if($cfg['display_thumbs_likes']){
						echo '<span class="ingallery-item-stats-likes"><i class="ing-icon-heart-1"></i>'.$item['likes']['count'].'</span>';
					}
					if($cfg['display_thumbs_comments']){
						echo '<span class="ingallery-item-stats-comments"><i class="ing-icon-comment-1"></i>'.$item['comments']['count'].'</span>';
					}
				echo '</div>';
			}
			?>
		</a>
		<?php
	}
    else if($cfg['display_style']=='grayscale'){
		?>
        <a href="https://www.instagram.com/p/<?php echo $item['code'];?>/" class="ingallery-item-link ingallery-item-box ingallery-item-link-<?php echo $cfg['display_link_mode'];?>" target="_blank" data-item="<?php echo $previewItemCode;?>">
        	<div class="ingallery-style-grayscale-front">
                <div class="ingallery-item-img <?php echo ($item['is_video']?'ingallery-item-video':'');?>">
                    <img src="<?php echo $imgPlaceholder;?>" data-src="<?php echo $previewItem['thumbnail_src'];?>" style="<?php echo $attr;?>:101% !important" crossorigin="anonymous" />
                    <?php echo (count($item['subgallery'])?'<div class="ingallery-item-subgallery-icon"><i class="ing-icon-picture"></i></div>':'');?>
                </div>
            </div>
            <div class="ingallery-style-grayscale-back">
                <div class="ingallery-item-img <?php echo ($item['is_video']?'ingallery-item-video':'');?>">
                    <img src="<?php echo $imgPlaceholder;?>" data-src="<?php echo $previewItem['thumbnail_src'];?>" style="<?php echo $attr;?>:101% !important" crossorigin="anonymous" />
                    <?php echo (count($item['subgallery'])?'<div class="ingallery-item-subgallery-icon"><i class="ing-icon-picture"></i></div>':'');?>
                </div>
                <div class="ingallery-item-overlay">
                    <?php
                    if($cfg['display_thumbs_likes'] || $cfg['display_thumbs_comments'] || $cfg['display_thumbs_description']){
                        echo '<div class="ingallery-item-stats">';
                            if($cfg['display_thumbs_likes']){
                                echo '<span class="ingallery-item-stats-likes"><i class="ing-icon-heart-1"></i>'.$item['likes']['count'].'</span>';
                            }
                            if($cfg['display_thumbs_comments']){
                                echo '<span class="ingallery-item-stats-comments"><i class="ing-icon-comment-1"></i>'.$item['comments']['count'].'</span>';
                            }
                            if($cfg['display_thumbs_description']){
                                echo '<div class="ingallery-item-stats-caption">'.$item['caption'].'</div>';
                            }
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </a>
        <?php
	}
	else if($cfg['display_style']=='card'){
        echo '<div class="ingallery-item-card">';
            if($cfg['display_thumbs_header']){
                ?>
                <div class="ingallery-item-header">
                    <div class="ingallery-item-owner">
                        <a href="https://www.instagram.com/<?php echo $previewItem['owner_username'];?>/" title="<?php echo $previewItem['owner_name'];?>" target="_blank">
                            <img src="<?php echo $previewItem['owner_pic_url'];?>" crossorigin="anonymous" /> <?php echo $previewItem['owner_username'];?>
                        </a>
                    </div>
                    <?php
                    if($cfg['display_thumbs_instalink']){
                        echo '<div class="ingallery-item-link"><a href="https://www.instagram.com/p/'.$item['code'].'/" target="_blank"><i class="ing-icon-instagram"></i></a></div>';
                    }
                    ?>
                </div>
                <?php
            }
            ?>
            <a href="https://www.instagram.com/p/<?php echo $item['code'];?>/" class="ingallery-item-link ingallery-item-link-<?php echo $cfg['display_link_mode'];?>" target="_blank" data-item="<?php echo $previewItemCode;?>">
                <div class="ingallery-item-image <?php echo ($item['is_video']?'ingallery-item-video':'');?>">
                    <img src="<?php echo $imgPlaceholder;?>" data-src="<?php echo $previewItem['thumbnail_src'];?>" crossorigin="anonymous" />
                    <?php echo (count($item['subgallery'])?'<div class="ingallery-item-subgallery-icon"><i class="ing-icon-picture"></i></div>':'');?>
                </div>
            </a>
            <?php
            if($cfg['display_thumbs_likes'] || $cfg['display_thumbs_comments'] || $cfg['display_thumbs_date']){
                echo '<div class="ingallery-item-info">';
                    if($cfg['display_thumbs_likes']){
                        echo '<span class="ingallery-item-likes"><i class="ing-icon-heart-1"></i><span>'.$item['likes']['count'].'</span></span>';
                    }
                    if($cfg['display_thumbs_comments']){
                        echo '<span class="ingallery-item-comments"><i class="ing-icon-comment-1"></i><span>'.$item['comments']['count'].'</span></span>';
                    }
                    if($cfg['display_thumbs_date']){
                        $time = strtotime($previewItem['date_iso']);
                        echo '<span class="ingallery-item-date"><time title="'.$previewItem['full_date'].'" datetime="'.$previewItem['date_iso'].'"><b>'.date('d',$time).'</b><i>'.self::getMonthShortName(date('n',$time)).'</i></time></span>';
                    }
                echo '</div>';
            }
            if($cfg['display_thumbs_description']){
                echo '<div class="ingallery-item-caption">'.$item['caption'].'</div>';
            }
        echo '</div>';
    }
    else{
		?>
		<a href="https://www.instagram.com/p/<?php echo $item['code'];?>/" class="ingallery-item-link ingallery-item-box ingallery-item-link-<?php echo $cfg['display_link_mode'];?>" target="_blank" data-item="<?php echo $previewItemCode;?>">
			<div class="ingallery-item-img <?php echo ($item['is_video']?'ingallery-item-video':'').' '.(count($item['subgallery'])?'ingallery-item-subgallery':'');?>">
				<img src="<?php echo $imgPlaceholder;?>" data-src="<?php echo $previewItem['thumbnail_src'];?>" style="<?php echo $attr;?>:101% !important" crossorigin="anonymous" />
                <?php echo (count($item['subgallery'])?'<div class="ingallery-item-subgallery-icon"><i class="ing-icon-picture"></i></div>':'');?>
			</div>
			<div class="ingallery-item-overlay">
				<?php
				if($cfg['display_thumbs_likes'] || $cfg['display_thumbs_comments'] || /*($cfg['display_thumbs_plays']&&$item['is_video']) ||*/ $cfg['display_thumbs_description']){
					echo '<div class="ingallery-item-stats">';
						if($cfg['display_thumbs_likes']){
							echo '<span class="ingallery-item-stats-likes"><i class="ing-icon-heart-1"></i>'.$item['likes']['count'].'</span>';
						}
						if($cfg['display_thumbs_comments']){
							echo '<span class="ingallery-item-stats-comments"><i class="ing-icon-comment-1"></i>'.$item['comments']['count'].'</span>';
						}
						if($cfg['display_thumbs_description']){
							echo '<div class="ingallery-item-stats-caption">'.$item['caption'].'</div>';
						}
					echo '</div>';
				}
				?>
			</div>
		</a>
		<?php
	}
	?>
</div>