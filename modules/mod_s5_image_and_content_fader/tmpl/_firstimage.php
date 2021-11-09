<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_news
 * @copyright	Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$images  = json_decode($item->images);
?>
<img id="myGallery_height_img" alt="<?php echo $images->image_intro; ?>" src="<?php if (strpos($images->image_intro,'http') === false){ echo JURI::root(); }?><?php echo $images->image_intro; ?>" />






