<?php /**

 * @title		Shape 5 MailChimp Signup

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



$document = JFactory::getDocument();

$document->addCustomTag('<link rel="stylesheet" href="'.$url.'css/default.css" type="text/css" />');

$document->addCustomTag('<script src="'.$url.'js/mailing-list.js" type="text/javascript"></script>');

?>



<script type="text/javascript">var s5mailchimpurl = "<?php echo $url; ?>";</script>

<?php if ($mailchimp_pretext != "") { ?><p id="description"><?php echo $mailchimp_pretext; ?></p><?php } ?>



<form id="signup" action="<?=$_SERVER['PHP_SELF']; ?>" method="get" class="mailchimp_signup_form">

	<fieldset>

		<span id="response"><? // require_once(''.$url.'files/store-address.php'); if($_GET['submit']){ echo storeAddress(); } ?></span>

		<input type="text" name="email" class="inputbox mailchimp_signup" id="email" onblur="if (this.value=='') this.value='<?php echo $mailchimp_emailaddresstext; ?>';" onfocus="if (this.value=='<?php echo $mailchimp_emailaddresstext; ?>') this.value='';" value="<?php echo $mailchimp_emailaddresstext; ?>" />

		<button name="submit" class="button mailchimp_signup"><?php echo $mailchimp_subscribe; ?></button>

	</fieldset>

</form>      











