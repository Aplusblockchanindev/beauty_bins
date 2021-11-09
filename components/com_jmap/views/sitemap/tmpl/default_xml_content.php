<?php
/** 
 * @package JMAP::SITEMAP::components::com_jmap
 * @subpackage views
 * @subpackage sitemap
 * @subpackage tmpl
 * @author Joomla! Extensions Store
 * @copyright (C) 2021 - Joomla! Extensions Store
 * @license GNU/GPLv2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined ( '_JEXEC' ) or die ( 'Restricted access' );

$priority =  $this->sourceparams->get ( 'priority', '0.5' );
$changefreq = $this->sourceparams->get ( 'changefreq', 'daily' );
$showPageBreaks = $this->cparams->get ( 'show_pagebreaks', 1 );
$linkableContentCats = $this->sourceparams->get ( 'linkable_content_cats', 0 );
$arrayCategoryIDs = array();

// Get default menu - home and check if a single article is linked, if so skip to avoid duplicated content
$homeArticleID = false;
$homeCategoryID = false;
$defaultMenu = $this->application->getMenu()->getDefault(JFactory::getLanguage()->getTag());
if(	isset($defaultMenu->query['option']) &&
	isset($defaultMenu->query['view']) &&
	$defaultMenu->query['option'] == 'com_content' &&
	$defaultMenu->query['view'] == 'article') {
		$homeArticleID = (int)$defaultMenu->query['id'];
}
if(	isset($defaultMenu->query['option']) &&
	isset($defaultMenu->query['view']) &&
	$defaultMenu->query['option'] == 'com_content' &&
	$defaultMenu->query['view'] == 'category') {
		$homeCategoryID = (int)$defaultMenu->query['id'];
}

if (count ( $this->source->data ) != 0) {
	require_once (JPATH_BASE . '/components/com_content/helpers/route.php');
	foreach ( $this->source->data as $elm ) {
		// Element category empty da right join
		if(!$elm->id) {
			continue;
		}
		
		// Article found as linked to home, skip and avoid duplicate link
		if((int)$elm->id === $homeArticleID) {
			continue;
		}
		
		$elm->slug = $elm->alias ? ($elm->id . ':' . $elm->alias) : $elm->id;
		$seolink = JRoute::_ ( ContentHelperRoute::getArticleRoute ( $elm->slug, $elm->catslug, $elm->language ) );

		// Skip outputting
		if(array_key_exists($seolink, $this->outputtedLinksBuffer)) {
			continue;
		}
		// Else store to prevent duplication
		$this->outputtedLinksBuffer[$seolink] = true;
		
		// Fallback modified -> created -> current time
		$timestampModified = (isset($elm->modified) && $elm->modified != false && $elm->modified != -1) ? $elm->modified : false;
		$timestampCreated = (isset($elm->created) && $elm->created != false && $elm->created != -1) ? $elm->created : false;
		$timestamp = $timestampModified ? $timestampModified : ($timestampCreated ? $timestampCreated : time());
		$modified = gmdate('Y-m-d\TH:i:s\Z', $timestamp);

		// Store a unique hash of catid
		$arrayCategoryIDs[$elm->catid] = array('modified'=>$elm->cat_modified, 'language'=>$elm->language, 'priority'=>$elm->priority);
		?>
<url>
<loc><?php echo $this->liveSite . $seolink; ?></loc>
<lastmod><?php echo $modified; ?></lastmod>
<changefreq><?php echo $changefreq;?></changefreq>
<priority><?php echo $elm->priority ? $elm->priority : $priority;?></priority>
</url>
<?php
		if(!empty($elm->expandible) && $showPageBreaks) {
			foreach ($elm->expandible as $index=>$subPageBreak) {
				$seolink = JRoute::_ ( ContentHelperRoute::getArticleRoute ( $elm->slug, $elm->catslug, $elm->language ) . '&limitstart=' . ($index + 1));
?>
<url>
<loc><?php echo $this->liveSite . $seolink; ?></loc>
<lastmod><?php echo $modified; ?></lastmod>
<changefreq><?php echo $changefreq;?></changefreq>
<priority><?php echo $elm->priority ? $elm->priority : $priority;?></priority>
</url>
<?php
			}
		}
	}
	
	// Output categories links if any
	if($linkableContentCats && !empty($arrayCategoryIDs)) {
		foreach ($arrayCategoryIDs as $catid=>$catInfo) {
			// Category found as linked to home, skip and avoid duplicate link
			if((int)$catid === $homeCategoryID) {
				continue;
			}
			$seoCatLink = JRoute::_ ( ContentHelperRoute::getCategoryRoute($catid, $catInfo['language'] ) );
			$timestamp = $catInfo['modified'] ? $catInfo['modified'] : time();
			$modified = gmdate('Y-m-d\TH:i:s\Z', $timestamp);
?>
<url>
<loc><?php echo $this->liveSite . $seoCatLink; ?></loc>
<lastmod><?php echo $modified; ?></lastmod>
<changefreq><?php echo $changefreq;?></changefreq>
<priority><?php echo $catInfo['priority'] ? $catInfo['priority'] : $priority;?></priority>
</url>
<?php
		}
	}
}