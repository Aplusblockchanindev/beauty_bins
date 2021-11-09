<?php /**
 * @title		Shape 5 MailChimp Module
 * @version		1.0
 * @package		Joomla
 * @website		http://www.shape5.com
 * @copyright	Copyright (C) 2009 Shape 5 LLC. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
// no direct access
defined('_JEXEC') or die('Restricted access');
$url = JURI::root().'modules/mod_s5_mailchimp_signup/';

$mailchimp_pretext = $params->get('mailchimp_pretext', '');
$mailchimp_emailaddresstext = $params->get('mailchimp_emailaddresstext', '');
$mailchimp_subscribe = $params->get('mailchimp_subscribe', '');
$mailchimp_apikey = $params->get('mailchimp_apikey', '');
$mailchimp_listid = $params->get('mailchimp_listid', '');


$session = JFactory::getSession();
$session->set('mailchimp_apikey', $mailchimp_apikey);
$session->set('mailchimp_listid', $mailchimp_listid);

require (JModuleHelper::getLayoutPath('mod_s5_mailchimp_signup'));