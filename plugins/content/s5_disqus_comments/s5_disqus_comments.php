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
 
defined('_JEXEC') or die ;

jimport('joomla.plugin.plugin');
jimport('joomla.html.parameter');

class plgContentS5_disqus_comments extends JPlugin
{

	var $plg_name = "s5_disqus_comments";

	function plgContentS5_disqus_comments2(&$subject, $params)
	{
		parent::__construct($subject, $params);

		if (!defined('DS'))
		{
			define('DS', DIRECTORY_SEPARATOR);
		}
	}

	function onContentPrepare($context, &$row, &$params, $page = 0)
	{
		$array = array(
			'com_content.article',	
			'com_content.frontpage',	
			'com_content.category',	
			'com_content.featured'	
		);
		if (in_array($context, $array)) {
			$this->renderDisqus($row, $params, $page);
		}
	}

	function renderDisqus(&$row, $params, $page)
	{
		$mainframe = JFactory::getApplication();
		$document = JFactory::getDocument();
		$user = JFactory::getUser();
		$app = JFactory::getApplication();

		$view = $app->input->getCmd('view');
		$page = $app->input->getCmd('page');
		$option = $app->input->getCmd('option');
		$itemid = $app->input->getInt('Itemid');

		$sitePath = JPATH_SITE;
		$siteUrl = JURI::root(true);

		if (!$itemid)
			$itemid = 77777;

		$this->loadLanguage();

		$properties = get_object_vars($row);
		if (!array_key_exists('catid', $properties))
			return;
		if (!$row->id || $option == 'com_rokdownloads')
			return;

		$pluginParams = $this->params;
		$s5_disqus_comments_featured = $pluginParams->get('s5_disqus_comments_featured', '0');

		if($s5_disqus_comments_featured == 0 && $row->featured == 1 && $view=="featured"){
			 return;
		 }
		 	
		$s5_disqus_comments_subdomain = trim($pluginParams->get('s5_disqus_comments_subdomain', ''));
		$s5_disqus_comments_language = $pluginParams->get('s5_disqus_comments_language');
		$s5_disqus_comments_listing_count = $pluginParams->get('s5_disqus_comments_listing_count', 1);
		$s5_disqus_comments_article_count = $pluginParams->get('s5_disqus_comments_article_count', 1);
		$s5_disqus_comments_categories = $pluginParams->get('s5_disqus_comments_categories', '');
		$s5_disqus_comments_menus = $pluginParams->get('s5_disqus_comments_menus', '');
		if(empty($s5_disqus_comments_categories) || in_array('',$s5_disqus_comments_categories))
		{
		if(empty($s5_disqus_comments_menus) || in_array('',$s5_disqus_comments_menus))
			return;
		}
		
		
		$params = new JRegistry($params);
		$parsedInModule = $params->get('parsedInModule');

		if (!$s5_disqus_comments_subdomain)
		{
			if (!isset($this->noticeRaised))
			{
				$this->noticeRaised = true;
				JError::raiseNotice('', JText::_('S5_DISQUS_COMMENTS_ENTER_DISQUS_SUBDOMAIN'));
			}
			return;
		}
		else
		{
			$s5_disqus_comments_subdomain = str_replace(array(
				'http://',
				'.disqus.com/',
				'.disqus.com'
			), array(
				'',
				'',
				''
			), $s5_disqus_comments_subdomain);
		}

		$currectCategory = $row->catid;

		$s5_disqus_comments_categories = (array)$s5_disqus_comments_categories;
		if (sizeof($s5_disqus_comments_categories) == 1 && $s5_disqus_comments_categories[0] == 'all')
		{
			$categories[] = $currectCategory;
		}
		else
		{
			$categories = $s5_disqus_comments_categories;
		}
		$s5_disqus_comments_menus = (array)$s5_disqus_comments_menus;
		if (sizeof($s5_disqus_comments_menus) == 1 && $s5_disqus_comments_menus[0] == 'all')
		{
			$menus[] = $itemid;
		}
		else
		{
			$menus = $s5_disqus_comments_menus;
		}
		require_once (dirname(__FILE__).DS.$this->plg_name.DS.'includes'.DS.'helper.php');
		require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

		$output = new stdClass;

		$websiteURL = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off") ? "https://".$_SERVER['HTTP_HOST'] : "http://".$_SERVER['HTTP_HOST'];

		$levels = $user->getAuthorisedViewLevels();
		if (in_array($row->access, $levels))
		{
			if ($view == 'article')
			{
				$itemURL = $row->readmore_link;
			}
			else
			{
				$itemURL = JRoute::_(ContentHelperRoute::getArticleRoute($row->slug, $row->catid));
			}
		}

		$itemURLbrowser = explode("#", $websiteURL.$_SERVER['REQUEST_URI']);
		$itemURLbrowser = $itemURLbrowser[0];

		$output->itemURL = $websiteURL.$itemURL;
		$output->itemURLrelative = $itemURL;
		$output->itemURLbrowser = $itemURLbrowser;
		$output->disqusIdentifier = substr(md5($s5_disqus_comments_subdomain), 0, 10).'_id'.$row->id;
		$output->s5_disqus_comments_subdomain = $s5_disqus_comments_subdomain;
		// echo $s5_disqus_comments_featured ."  ". $row->featured   ."  ". $view."  "; //print_r( $menus); 
		//echo $view;exit;
		if ((in_array($currectCategory, $categories) || in_array($itemid, $menus) || ($s5_disqus_comments_featured == 1 && $row->featured == 1 && $view=='featured'))&& $option == 'com_content' && ($view == 'article' || $view=='category'))
		{ 
			$output->comments = "
			<div id=\"disqus_thread\"></div>
			<script type=\"text/javascript\">
				//<![CDATA[
				var disqus_shortname = '".$s5_disqus_comments_subdomain."';
				var disqus_url = '".$output->itemURL."';
				var disqus_identifier = '".substr(md5($s5_disqus_comments_subdomain), 0, 10)."_id".$row->id."';
				var disqus_config = function(){
					this.language = '{$s5_disqus_comments_language}';
				};
				(function() {
					var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
					dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
					(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
				})();
				//]]>
			</script>
			<noscript>
				<a href=\"http://".$s5_disqus_comments_subdomain.".disqus.com/?url=ref\">".JText::_("S5_DISQUS_COMMENTS_VIEW_THE_DISCUSSION_THREAD")."</a>
			</noscript>
			";
		}

		if (in_array($currectCategory, $categories) || in_array($itemid, $menus) || ($s5_disqus_comments_featured == 1 && $row->featured == 1 && ($view=='featured' || $view=='article')) )
		{

			if (!defined('S5_DISQUS_COMMENTS'))
				define('S5_DISQUS_COMMENTS', true);

			if ($app->input->getCmd('format') == 'html' || $app->input->getCmd('format') == '')
			{

				// CSS
				$plgCSS = DisqusHelper::getTemplatePath($this->plg_name, 'css/s5_disqus_comments.css');
				$plgCSS = $plgCSS->http;

				$document->addStyleSheet($plgCSS);

				JHtml::_('behavior.framework');
			}


			if (($option == 'com_content' && $view == 'article') && $parsedInModule != 1)
			{

				ob_start();
				$dsqArticlePath = DisqusHelper::getTemplatePath($this->plg_name, 'article.php');
				$dsqArticlePath = $dsqArticlePath->file;
				include ($dsqArticlePath);
				$getArticleTemplate = ob_get_contents();
				ob_end_clean();

				$row->text = $getArticleTemplate;

			}
			else if ($s5_disqus_comments_listing_count && (($option == 'com_content' && ($view == 'frontpage' || $view == "featured" || $view == 'category')) || $parsedInModule == 1))
			{
				$row->text = $row->introtext;

				ob_start();
				$dsqListingPath = DisqusHelper::getTemplatePath($this->plg_name, 'listing.php');
				$dsqListingPath = $dsqListingPath->file;
				include ($dsqListingPath);
				$getListingTemplate = ob_get_contents();
				ob_end_clean();

				$row->text = $getListingTemplate;

			}

		}

	}

}