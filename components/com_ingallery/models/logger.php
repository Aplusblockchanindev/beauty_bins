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
defined( '_JEXEC' ) or die(':)');


abstract class IngalleryLogger{
    static $_loggerRegistered = false;
    
    static function log($msg){
        $config = JComponentHelper::getParams('com_ingallery');
        if((int)$config->get('log_errors',0)!=1){
            return;
        }
        self::registerLogger();
        JLog::add($msg, JLog::ERROR, 'com_ingallery');
    }
    
    static function registerLogger(){
        if(self::$_loggerRegistered){
            return;
        }
        self::$_loggerRegistered = true;

        jimport('joomla.log.log');
        JLog::addLogger(
            array(
                 'text_file' => 'com_ingallery.php',
                 'text_entry_format' => '{DATETIME} {MESSAGE}'
            ),
            JLog::ALL,
            array('com_ingallery')
        );
    }
}
