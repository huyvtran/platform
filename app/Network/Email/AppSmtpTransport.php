<?php

App::uses('SmtpTransport', 'Network/Email');
App::uses('Validation', 'Utility');
App::uses('Utility', 'Lib');

class AppSmtpTransport extends SmtpTransport {


	/**
	 * Auto check email server is valid, dont send if email was bounced
	 * Validate email in database , and use hippo service
	 *
	 *	@param websiteUrl (Optional) use for create unsubcribeLink variable in email
	 * 	@param gameId (Optional) use for create unsubcribeLink variable in email (not required but recommend)
	 *	@param requiredVerify (Optional) verify address in database is valid for sending
	 *	@param user (Optional) required when requiredVerify varible is prevent
	 *	@param forceSend (Optional) force send this email even it was unsubscribed
	 *	
	 */
	public function send(CakeEmail $email)
	{
		$paramsEmail = $email->to();
		if (count($paramsEmail) != 1) {
			return parent::send($email);
		}
		$emailAddress = key($paramsEmail);

		if (!Validation::email($emailAddress)) {
			return false;
		}
		
		# check email server is valid
		if (preg_match('/[^\@]*\@(.*)/', $emailAddress, $matches)) {
			if (!in_array($matches[1], array('yahoo.com', 'gmail.com', 'hotmail.com', 'live.com', 'qq.com', 'mobgame.vn'))) {
				if ($matches[1] == 'haitacmobi') {
					return false;
				} else {
					$result = Validation::email($emailAddress, true);
					if ($result == false) {
						return false;
					}
				}
			}
		}
			
		$User = ClassRegistry::init('User');

		# verify address
		if (!empty($vars['requiredVerify']) && !empty($vars['user'])) {
			$user = $vars['user'];
			ClassRegistry::init('EmailFeedback');
			$EmailFeedback = new EmailFeedback();
			$send = false;
			if (   !empty($user['email'])
				&& $EmailFeedback->wasBlocked($emailAddress) === false
				&&  (   (empty($user['email_verified']) && $user['email_temp_verified'] == 1)
				    ||  !empty($user['email_verified'])
				    )
				) {
				$send = true;
			}

			if (!$send) {
				if ($user['email_temp_verified'] == null) {
					try {
						$result = Utility::tempVerify($emailAddress);
					} catch (Exception $e) {
						return false;
					}

					if (!empty($user['id'])) {
	                   	if ($result) {
	                   		$User = ClassRegistry::init('User');
	                        $User->id = $user['id'];
	                        $User->saveField('email_temp_verified', User::EMAIL_TMP_VERIFIED, array('callbacks' => false));
	                    } else {
	                        $User->id = $user['id'];
	                        $User->saveField('email_temp_verified', User::EMAIL_TMP_FAKE, array('callbacks' => false));
	                    }
                    }
				}
			}
		}

		/** TODO 
			Just can be force if its in unsubscribe list only
		**/
		if (empty($vars['forceSend'])) {
			if (Utility::wasBlocked($emailAddress)) {
				return false;
			}
		}
		$email->addHeaders(array('X-Mailer' => 'MobgameMail'));
		
		return parent::send($email);
	}

}
