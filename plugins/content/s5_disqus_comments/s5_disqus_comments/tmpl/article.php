<?php
/**
 * @title		S5 Disqus Comments
 * @author		Modifications and additions by Shape5 - http://www.shape5.com, some original work by JoomlaWorks - http://www.joomlaworks.net
 * @copyright	Copyright (c) 2006 - 2014 JoomlaWorks Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
 
defined('_JEXEC') or die;
?>

<?php if($s5_disqus_comments_article_count): ?>
<div class="s5_disqus_comments_view_comments">
		<a href="#disqus_thread" data-disqus-identifier="<?php echo $output->disqusIdentifier; ?>"><?php echo JText::_("S5_DISQUS_COMMENTS_VIEW_COMMENTS"); ?></a>
	<div class="clr"></div>
</div>
<?php endif; ?>

<?php echo $row->text; ?>

<div class="s5_disqus_comments_area">
	<?php echo $output->comments; ?>
</div>

<div style="clear:both;height:0px;"></div>
<?php if(!defined('count_script')){ ?>
	<script id="dsq-count-scr" src="//<?php echo $output->s5_disqus_comments_subdomain?>.disqus.com/count.js" async></script>
<?php
    define('count_script', 'true');
} ?>
