<?php
 defined('_JEXEC') or die('Restricted access');
?>
{address_company}
{address_title} {address_firstname} {address_lastname}
{address_street}
{address_city} {address_state} {address_post_code}
<?php echo JText::sprintf('TELEPHONE_IN_ADDRESS','{address_telephone}');?>
<br /><strong>Details</strong>
Account Type: {address_whattypeofaccountareyou}
Trash Pickup: {address_daystrashispickedup} between {address_estimatedtrashpickuptime}