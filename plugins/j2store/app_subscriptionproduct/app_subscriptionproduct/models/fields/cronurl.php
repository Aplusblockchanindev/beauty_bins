<?php
defined('_JEXEC') or die;

class JFormFieldCronURL extends JFormField
{
	protected $type = 'cronurl';

	public function getInput() {

		$config = J2Store::config();
		$queue_key = $config->get ('queue_key', '');
		$url = JUri::root().'index.php?option=com_j2store&view=cron&command=appsubscriptionproduct&cron_secret='.$queue_key;
		$changeURL = 'index.php?option=com_j2store&view=configuration#store_settings';
		if(empty( $queue_key )){
			$queue_string = JFactory::getConfig ()->get ( 'sitename','' ).time ();
			$queue_key = md5 ( $queue_string );
			$config->saveOne ( 'queue_key', $queue_key );
		}

		$html = '';
		$html .= '<div class="alert alert-block alert-info"><strong id="j2store_queue_key">'.$url.'</strong>&nbsp;&nbsp;&nbsp;<a href="'.$changeURL.'" class="btn btn-danger">'.JText::_ ( 'Change key' ).'</a>
		<br/><span class="text-warning">'.JText::_('J2STORE_SUBSCRIPTIONAPP_CRON_DESCRIPTION').'</span>
		</div>';
		return $html;
	}

}