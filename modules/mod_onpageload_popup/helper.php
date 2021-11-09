<?php
/*------------------------------------------------------------------------
# mod_onpageload_popup - Auto onPageLoad Popup
# ------------------------------------------------------------------------
# author    Infyways Solutions
# copyright Copyright (C) 2021 Infyways Solutions. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.infyways.com
# Technical Support:  Forum - http://support.infyways/com
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access');

class modAutoHelper{
public static function getModule($id){
	if($id){
		$document = JFactory::getDocument();
		$renderer = $document->loadRenderer('module');
		$contents = '';
		$database = JFactory::getDBO();
		$database->setQuery("SELECT * FROM #__modules WHERE id='$id' ");
		$modules = $database->loadObject();
		$contents = $renderer->render($modules);		
		return $contents;
		}
		else{
			return false;
		}
	}
	
public function getContents($id){
	if($id){
		$database = JFactory::getDBO();
		$sql = "select * from #__content where id='$id' and state=1";
		$database->setQuery($sql);
		$row = $database->loadObject();	
		$row = $row->introtext;
		$row = JHtml::_('content.prepare',$row, '', 'mod_onpageload_popup');
		return $row;
	}
	else{
		return false;
	}
	
}
public function addScript($script,$loc)
{
    $document = JFactory::getDocument();
    $base = JURI::root();
    if($loc){
        $document->addScript($base.$script);
    }
    else{
       echo "<script src=\"$base$script\" type=\"text/javascript\"></script>";
    }
    
}
    
public function addjQueryDec($jquery,$loc){
    $document = JFactory::getDocument();
    if($loc){
    $document->addscriptdeclaration(" jQuery(document).ready(function(){ {$jquery} });");
    }
    else{
        echo "<script type=\"text/javascript\">jQuery(document).ready(function() { $jquery });</script>";
    }
}
 
        
public function createContent($input,$params,$mid)
   {
    require("layouts/link.php");
    switch ($input) {
    case 0:
    require("layouts/articles.php");
    break;
    case 1:
    require("layouts/editor.php");
    break;
    }
   }
    
    
}