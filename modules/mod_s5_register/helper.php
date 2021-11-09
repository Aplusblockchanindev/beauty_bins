<?php

defined('_JEXEC') or die;
abstract class ModS5RegisterHelper
{
	static public function onS5registerAjax() {
		$data = [];
		$user = JFactory::getUser();
		$db = JFactory::getDBO();
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$root_url = $app->getUserState('base_url');
		$lang = JFactory::getLanguage();
		$session = JFactory::getSession();

		$extension = 'com_users';
		$base_dir = JPATH_SITE;
		$language_tag = 'en-GB';
		$reload = true;
		$lang->load($extension, $base_dir, $language_tag, $reload);
		
		// change all request var to normal variabled
		$post = $jinput->getArray();
		foreach ($post AS $k => $v) {
			$varName = 's5'.$k;
			$$varName = $v;
		}
// 		$modules5 = 'mod_s5_register';
// 		$base_dirs5 = JPATH_SITE;
// 		$language_tags5 = 'en-GB';
// 		$reloads5 = true;
// 		$lang->load($modules5, $base_dirs5, $language_tags5, $reloads5);
		$email = 0;
		$user_name = 0;
		$html = '';
		//	echo '<pre>';print_r($_REQUEST);exit;
		
		if ($s5jemail != $s5jemail2) $html .= '<br>' . JTEXT::_('COM_USERS_REGISTER_EMAIL2_MESSAGE');
		if ($s5jpass1 != $s5jpass2) $html .= '<br>' . JTEXT::_('COM_USERS_REGISTER_PASSWORD1_MESSAGE');
		$query = 'SELECT id FROM #__users WHERE email = "' . $s5jemail . '"';
		$db->setQuery($query);
		$e_id = $db->loadResult();
		if ($e_id) $html .= '<br>' . JTEXT::_('COM_USERS_REGISTER_EMAIL1_MESSAGE');
		$query1 = 'SELECT id FROM #__users WHERE username = "' . $s5juser . '"';
		$db->setQuery($query1);
		$u_id = $db->loadResult();
		if ($u_id) $html .= '<br>' . JTEXT::_('COM_USERS_REGISTER_USERNAME_MESSAGE');  
			  //$sc = $_SESSION['security_code'];//$app1->getUserState('security_code');
		$sc = $session->get('security_code');
			//error_reporting(0);
		if ($s5captchaval == 1) {
			if ($sc == $s5jcapch && !empty($sc)) {
				unset($sc);
			} else {
				$html .= '<br>' . JTEXT::_('MOD_REGISTER_CAPTCHA_ERROR');
			}
		} // condition   
		if ($html == '') {
			$user_new = new JUser;
			$data['name'] = $s5jform_name;
			$data['username'] = $s5juser;
			$data['email'] = $s5jemail;
			$data['password'] = $s5jpass1;

			$plugin = JPluginHelper::getPlugin('user', 'profile');
			if ($plugin) {
				$data['profile']['address1'] = $s5address1;
				$data['profile']['address2'] = $s5address2;
				$data['profile']['city'] = $s5city;
				$data['profile']['region'] = $s5region;
				$data['profile']['country'] = $s5country;
				$data['profile']['postal_code'] = $s5postal_code;
				$data['profile']['phone'] = $s5phone;
				$data['profile']['website'] = $s5website;
				$data['profile']['favoritebook'] = $s5favoritebook;
				$data['profile']['dob'] = $s5dob;
				$data['profile']['aboutme'] = $s5aboutme;
			}
			$data['groups']['0'] = 2;
			$params = JComponentHelper::getParams('com_users');
			$useractivation = $params->get('useractivation');
			$sendpassword = $params->get('sendpassword', 1);

			// Check if the user needs to activate their account.
			if (($useractivation == 1) || ($useractivation == 2)) {
				$data['activation'] = \Joomla\CMS\Application\ApplicationHelper::getHash(JUserHelper::genRandomPassword());
				$data['block'] = 1;
			}
			if (!$user->bind($data)) {
				echo 'error'. JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError());
					//$this->setError(JText::sprintf('COM_USERS_REGISTRATION_BIND_FAILED', $user->getError()));
					//return false;
			}
				// Load the users plugin group.
			JPluginHelper::importPlugin('user');

				// Store the data.
			if (!$user->save()) {
				echo 'error '.$user->getError();
				exit;
					//$this->setError($user->getError());
					//return false;
			}

			$config = JFactory::getConfig();
			$db = JFactory::getDbo();
			$query = $db->getQuery(true);

				// Compile the notification mail values.
			$data = $user->getProperties();
			$data['fromname'] = $config->get('fromname');
			$data['mailfrom'] = $config->get('mailfrom');
			$data['sitename'] = $config->get('sitename');
			$data['siteurl'] = JUri::root();

			$app = JFactory::getApplication();
			$user = JFactory::getUser();
			$user_id = $user->get('id');            
				//$app->logout($user_id, array());

				// Handle account activation/confirmation emails.
			if ($useractivation == 2) {
					// Set the link to confirm the user email.
				$uri = JUri::getInstance();
				$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
				$data['activate'] = $root_url . 'index.php?option=com_users&task=registration.activate&token=' . $data['activation'];
				$data['siteurl'] = $root_url;

				$emailSubject = JText::sprintf(
					'COM_USERS_EMAIL_ACCOUNT_DETAILS',
					$data['name'],
					$data['sitename']
				);

				if ($sendpassword) {
					$emailBody = JText::sprintf(
						'COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY',
						$data['name'],
						$data['sitename'],
						$data['activate'],
						$data['siteurl'],
						$data['username'],
						$data['password']
					);
				} else {
					$emailBody = JText::sprintf(
						'COM_USERS_EMAIL_REGISTERED_WITH_ADMIN_ACTIVATION_BODY_NOPW',
						$data['name'],
						$data['sitename'],
						$data['activate'],
						$data['siteurl'],
						$data['username']
					);
				}
			} elseif ($useractivation == 1) {
					// Set the link to activate the user account.
				$uri = JUri::getInstance();
				$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
				$data['activate'] = $root_url . 'index.php?option=com_users&task=registration.activate&token=' . $data['activation'];
				$data['siteurl'] = $root_url;

				$emailSubject = JText::sprintf(
					'COM_USERS_EMAIL_ACCOUNT_DETAILS',
					$data['name'],
					$data['sitename']
				);

				if ($sendpassword) {
					$emailBody = JText::sprintf(
						'COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY',
						$data['name'],
						$data['sitename'],
						$data['activate'],
						$data['siteurl'],
						$data['username'],
						$data['password_clear']
					);
				} else {
					$emailBody = JText::sprintf(
						'COM_USERS_EMAIL_REGISTERED_WITH_ACTIVATION_BODY_NOPW',
						$data['name'],
						$data['sitename'],
						$data['activate'],
						$data['siteurl'],
						$data['username']
					);
				}
			} else {
				$uri = JUri::getInstance();
				$base = $uri->toString(array('scheme', 'user', 'pass', 'host', 'port'));
				$data['siteurl'] = $root_url;

				$emailSubject = JText::sprintf(
					'COM_USERS_EMAIL_ACCOUNT_DETAILS',
					$data['name'],
					$data['sitename']
				);

				if ($sendpassword) {
					$emailBody = JText::sprintf(
						'COM_USERS_EMAIL_REGISTERED_BODY',
						$data['name'],
						$data['sitename'],
						$data['siteurl'],
						$data['username'],
						$data['password_clear']
					);
				} else {
					$emailBody = JText::sprintf(
						'COM_USERS_EMAIL_REGISTERED_BODY_NOPW',
						$data['name'],
						$data['sitename'],
						$data['siteurl']
					);
				}
			}
				// Send the registration email.
			$return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $data['email'], $emailSubject, $emailBody);
				// Send Notification mail to administrators
			if (($params->get('useractivation') < 2) && ($params->get('mail_to_admin') == 1)) {
				$emailSubject = JText::sprintf(
					'COM_USERS_EMAIL_ACCOUNT_DETAILS',
					$data['name'],
					$data['sitename']
				);

				$emailBodyAdmin = JText::sprintf(
					'COM_USERS_EMAIL_REGISTERED_NOTIFICATION_TO_ADMIN_BODY',
					$data['name'],
					$data['username'],
					$data['siteurl']
				);

					// Get all admin users
				$query->clear()
					->select($db->quoteName(array('name', 'email', 'sendEmail')))
					->from($db->quoteName('#__users'))
					->where($db->quoteName('sendEmail') . ' = ' . 1);

				$db->setQuery($query);

				try {
					$rows = $db->loadObjectList();
				} catch (RuntimeException $e) {
					echo 'error '.$e->getMessage();
				}

					// Send mail to all superadministrators id
				foreach ($rows as $row) {
					$return = JFactory::getMailer()->sendMail($data['mailfrom'], $data['fromname'], $row->email, $emailSubject, $emailBodyAdmin);

						// Check for an error.
					if ($return !== true) {
// 						echo 'Some error';
					}
				}
			}

				// Check for an error.
			if ($return !== true) {
				echo (JText::_('COM_USERS_REGISTRATION_SEND_MAIL_FAILED'));

					// Send a system message to administrators receiving system mails
				$db = JFactory::getDbo();
				$query->clear()
					->select($db->quoteName(array('name', 'email', 'sendEmail', 'id')))
					->from($db->quoteName('#__users'))
					->where($db->quoteName('block') . ' = ' . (int)0)
					->where($db->quoteName('sendEmail') . ' = ' . (int)1);
				$db->setQuery($query);

				try {
					$sendEmail = $db->loadColumn();
				} catch (RuntimeException $e) {
					echo 'error '.$e->getMessage();
				}

				if (count($sendEmail) > 0) {
					$jdate = new JDate;

						// Build the query to add the messages
					foreach ($sendEmail as $userid) {
						$values = array($db->quote($userid), $db->quote($userid), $db->quote($jdate->toSql()), $db->quote(JText::_('COM_USERS_MAIL_SEND_FAILURE_SUBJECT')), $db->quote(JText::sprintf('COM_USERS_MAIL_SEND_FAILURE_BODY', $return, $data['username'])));
						$query->clear()
							->insert($db->quoteName('#__messages'))
							->columns($db->quoteName(array('user_id_from', 'user_id_to', 'date_time', 'subject', 'message')))
							->values(implode(',', $values));
						$db->setQuery($query);

						try {
							$db->execute();
						} catch (RuntimeException $e) {
							echo 'error '.$e->getMessage();
						}
					}
				}
// 				echo 'REAL error';
			}

			if ($useractivation == 1) {
				echo "useractivate";
			} elseif ($useractivation == 2) {
				echo "adminactivate";
			} else {
				echo 'simple';
			}

			$app = JFactory::getApplication();
			$user = JFactory::getUser();
			$user_id = $user->id;                   
				//$app->logout($user_id, array());
			$user->set('id', 0);
				//unset($_SESSION['security_code']);
			$session->set('security_code', '');
			exit;
		} else {
			$app = JFactory::getApplication();
			$user = JFactory::getUser();
			$user_id = $user->get('id');
			$user->set('id', 0);         
				//$app->logout($user_id, array());
			echo $html;
			exit;
		}
	}
}