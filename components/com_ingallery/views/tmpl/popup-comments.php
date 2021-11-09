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

foreach($this->get('comments') as $comment){
	echo '<div class="ingallery-popup-content-comments-item">';
		echo '<strong><a href="https://www.instagram.com/'.$comment['user']['username'].'/" target="_blank" rel="nofollow">@'.$comment['user']['username'].'</a></strong>';
		echo '<span>'.self::getMediaDescription($comment['text']).'</span>';
	echo '</div>';
}