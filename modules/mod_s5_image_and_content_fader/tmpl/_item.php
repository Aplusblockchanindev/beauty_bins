<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_news
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$item_heading = $params->get('item_heading', 'h');

$images  = json_decode($item->images);



// get author ID
$authorId = $item->created_by;

// get user profile field data
// $authordetail = JUserHelper::getProfile($authorId)->profile['aboutme'];
// $authorwebsite = JUserHelper::getProfile($authorId)->profile['website'];

?>


<div class="imageElement" style="z-index:0;">

	

	


		<?php if ($s5_hideh2 == "true") { ?>
		<h3>
		<?php if ($s5_hidecattitle == "true") { ?>
		<div class="s5_iacf_cat">
		<?php
		$db = JFactory::getDbo();
		$id = $item->catid;
		$db->setQuery("SELECT cat.title FROM #__categories cat WHERE cat.id='$id'");
		$category_title = $db->loadResult();
		echo "$category_title"; ?>
		</div>
		<br/>
		<?php } ?>
		
		<?php echo $item->title; ?></h3>
		<?php } ?>
		
		
		<?php if ($s5_hidetext == "true") { ?>
			<?php $introtext_holder = $item->introtext; 
			$introtext_holder = str_replace("<div","<span",$introtext_holder);
			$introtext_holder = str_replace("</div>","</span>",$introtext_holder);
			$introtext_holder = str_replace("<p","<span",$introtext_holder);
			$introtext_holder = str_replace("</p>","</span>",$introtext_holder);
			?>
			<p style="text-shadow:1px 1px #000000;"><?php echo $introtext_holder; ?>
			
			<?php if (isset($item->link) && $item->readmore != 0 && $params->get('readmore')) : ?>
				<?php echo '<a class="readmore readon" href="' . $item->link . '">' . $item->linkText . '</a>'; ?>
			<?php endif; ?>
	
		
		
		<?php if ($s5_showauthor == "true" || $s5_showdate == "true") { ?>
			<span class="s5_iacf_wrap">			
				<?php if ($s5_showdate == "true") {?>
					<span class="s5_iacf_author">
					<?php $author = ":"; ?><?php echo JText::sprintf('COM_CONTENT_WRITTEN_BY', $author); ?> <?php echo $item->created_by_alias ? $item->created_by_alias : $item->author; ?>
					</span>	
				<?php } ?>	
				<?php if ($s5_showauthor == "true") {?>
					<span class="s5_iacf_date">
					<?php $pubdate = JHtml::_('date',$item->created, JText::_('DATE_FORMAT_LC3')); ?><?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', $pubdate); ?>
					</span>
				<?php } ?>
			</span>
		<?php } ?>
		
		</p>
			<?php } else {?>
			
			<p></p>
			<?php } ?>
		
		<?php if ($s5_articlelink == "false")  { ?>
			<a href="javascript:;" title="open image" class="open"></a>
		<?php } else { ?>
			<?php echo '<a title="open image" class="open" href="' . $item->link . '">' . $item->linkText . '</a>'; ?>
		<?php } ?>
		
		<img alt="<?php echo $images->image_intro; ?>" src="<?php if (strpos($images->image_intro,'http') === false){ echo JURI::root(); }?><?php echo $images->image_intro; ?>" class="full" />
		<img alt="<?php echo $images->image_intro; ?>" src="<?php if (strpos($images->image_intro,'http') === false){ echo JURI::root(); }?><?php echo $images->image_intro; ?>" class="thumbnail" />				
</div>



