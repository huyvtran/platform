<?php

use Aws\Common\Aws;
use Aws\Sqs\Enum\QueueAttribute;

App::uses('Validation', 'Utility');
App::uses('AppShell', 'Console/Command');

class EmailMarketingShell extends AppShell {

	public $tasks = array('SendEmail');

	public $uses = array('User', 'Game', 'EmailMarketing', 'Account', 'EmailFeedback', 'Email');

	public function pushSqs()
	{
		$this->out('Starting pushSqs date '. date('Y-m-d H:i:s') .' ...');
		set_time_limit(60 * 60 * 24);
		ini_set('memory_limit', '512M');

		$email = $this->EmailMarketing->find('first', array(
			'conditions' => array(
				'EmailMarketing.published_date >' => date('Y-m-d 00:00:00', strtotime('-5 days')),
				'EmailMarketing.published_date <' => date('Y-m-d 23:59:59', time()),
				'status' => EmailMarketing::SEND_PUSHLISHED
			),
			'recursive' => -1
		));

		if (!empty($email)) {
			$this->EmailMarketing->id = $email['EmailMarketing']['id'];
			$this->EmailMarketing->saveField('status', EmailMarketing::SEND_QUEUEING, array('callbacks' => false));

			$Redis = $this->SendEmail->getRedis($email);
			$Redis->delete();

			$this->out('Found a email needed to push');

			# Get email addresses and then push to sqs
			$page = 1;
			$limit = 100;
			while ($data = $this->__getData($email, $page, $limit)) {
				if ($data !== true) { // continue to loop but dont process this when data is true bool
					$this->out('.', false);
					if (is_array($data)) {
						if ($email['EmailMarketing']['type'] == EmailMarketing::TYPE_ALL) {
							$this->__sendMarketingEmail($email, $data);
						}
						if ($email['EmailMarketing']['type'] == EmailMarketing::TYPE_GIFTCODE) {
							$this->__sendGiftcodes($email, $data);
						}
					}
				}
                $this->out( 'Email Marketing pushSqs page: ' . $page );
				$page++;
			}

			$this->EmailMarketing->saveField('status', EmailMarketing::SEND_QUEUED, array('callbacks' => false));
			$this->EmailMarketing->saveField('total', $this->SendEmail->getTotalQueue($email), array('callbacks' => false));
			# Create 5 job for push notification
			for ($i = 0; $i < 1; $i++) {
				CakeResque::enqueue(
					'default',
					'EmailMarketing',
					array('send')
				);
			}

		} else {
			$this->out("Didn't find any email");
		}
		$this->out("END");
	}

	public function send()
	{
//		exec("ps aux | grep 'EmailMarketing send'  2>&1", $output, $result);
//
//		if (count($output) > 8) {
//			$this->err("Too much processes, can not start");
//			return false;
//		}

        $this->out('Starting push date '. date('Y-m-d H:i:s') .' ...');
		set_time_limit(200);
		ini_set('memory_limit', '384M');

		# Find notification don't push to apns yet 
		$emails = $this->EmailMarketing->find('all', array(
			'conditions' => array(
				'EmailMarketing.published_date >' => date('Y-m-d 00:00:00', strtotime('-5 days')),
				'EmailMarketing.published_date <' => date('Y-m-d 23:59:59', time()),
				'EmailMarketing.status' => EmailMarketing::SEND_QUEUED
			),
			'recursive' => -1,
			'order' => array('EmailMarketing.published_date' => 'ASC'),
			'contain' => array('Game')
		));

		if (!empty($emails)) {
			$email = current($emails);
			foreach ($emails as $k => $v) {
				if ($v['EmailMarketing']['total'] < $email['EmailMarketing']['total']) {
					$email = $v;
				}
			}

			$this->out('Found a email and send it now : ' . $email['EmailMarketing']['id']);

			$this->SendEmail->send($email);
			# Check if all tokens is pushed
			if ($this->SendEmail->queueIsEmpty($email)) {
				$this->SendEmail->markAsCompleted($email);
			}
		} else {
			$this->out('Dont have any email need to send.');
		}
	}

    private function __getData($email, $page, $limit = 100)
    {
        $data = array();

        $from = ($page - 1) * $limit;
        switch ($email['EmailMarketing']['type']) {
            case EmailMarketing::TYPE_ALL:
                $data_email = $this->__checkEmailDuplicate($email);
                $data = explode("\n", $email['EmailMarketing']['data']['addresses']);
                $data = array_map('trim', $data);
                $data = array_slice($data, $from, $limit);
                if (!empty($data_email)) {
                    foreach ($data as $key => $value) {
                        if (in_array(trim($value), $data_email)) {
                            unset($data[$key]);
                        }
                    }
                }
                break;
            case EmailMarketing::TYPE_GIFTCODE:
                $data = $this->__getAddressesGiftcode($email, $page, $limit);
                break;
        }
        return $data;
    }

    private function __checkEmailDuplicate($email)
    {
        $data_email = array();
        # chưa set duplicate email
        if (!empty($email['EmailMarketing']['data']['duplicate_email']) && $email['EmailMarketing']['data']['duplicate_email'] == 1) {
            if (!empty($email['EmailMarketing']['data']['game_id_duplicate'])) {
                $account = ClassRegistry::init('Account');
                $user    = ClassRegistry::init('User');
                $user_id = $account->find('list', array(
                    'conditions' => array(
                        'game_id' => $email['EmailMarketing']['data']['game_id_duplicate']
                    ),
                    'fields' => array('user_id'),
                ));

                $data_email = $user->find('list', array(
                    'conditions' => array(
                        'id' => $user_id,
                        'email_verified' => 1,
                        'NOT' =>  array(
                            'email LIKE' => '%myapp%',
                            'email IS NULL'
                        ),
                    ),
                    'fields' => array('email'),
                ));
            }
            if (isset($email['EmailMarketing']['data']['addresses_duplicate']) && $email['EmailMarketing']['data']['addresses_duplicate'] != '') {
                $data_email = explode("\n", $email['EmailMarketing']['data']['duplicate_email']);
                $data_email = array_map('trim', $data_email);
            }
        }
        return $data_email;
    }

    private function __getAddressesGiftcode($email, $page, $limit = 100)
    {
        $from = ($page - 1) * $limit;

        # get list emails
        $data_email = $this->__checkEmailDuplicate($email);
        $addresses = explode("\n", $email['EmailMarketing']['data']['addresses']);
        $addresses = array_map('trim', $addresses);
        $addresses = array_slice($addresses, $from, $limit);
        if (!empty($data_email)) {
            foreach ($addresses as $key => $value) {
                if (in_array(trim($value), $data_email)) {
                    unset($addresses[$key]);
                }
            }
        }

        # get list giftcodes
        $giftcodes = explode("\n", $email['EmailMarketing']['data']['giftcodes']);

        if (!empty($giftcodes)) {
            $giftcodes = array_slice($giftcodes, $from, $limit);
            $giftcodes = array_map('trim', $giftcodes);
        }

        if (empty($addresses)) {
            return false;
        }
        return array('giftcodes' => $giftcodes, 'addresses' => $addresses);
    }

    private function __checkName($name, $username, $email)
    {
        if ($name != null && $name != '' && $name != 0) {
            $return = $name;
        } else if ($username != '' ) {
            $return = $username;
        } else {
            $return = $email;
        }
        return $return;
    }

    private function __getName($email)
    {
        $User = ClassRegistry::init('User');
        $tmp = $User->find('first', array(
            'conditions' => array('email' => $email),
            'fields' => array('name', 'username', 'email'),
            'recursive' => -1,
        ));
        if (!empty($tmp)) {
            return $this->__checkName($tmp['User']['name'], $tmp['User']['username'], $tmp['User']['email']);
        } else {
            return $email;
        }
    }

    private function __sendMarketingEmail($email, $data)
    {
        foreach($data as $value) {
            $email_address = $value;
            $friendlyName  = $this->__getName($value);

            $emailData[] = array(
                'address' => $email_address,
                'params'  => array(
                    '@friendlyName' => $friendlyName,
                    '@email'        => $email_address
                )
            );
        }

        if (!empty($emailData)) {
            try {
                # Check duplicate in many emails, chưa dùng
                if (!empty($email['EmailMarketing']['relating_users_on_email_marketing_ids'])) {
                    $key = 'check_duplicate_email_marketing_' . str_replace(', ', '_', $email['EmailMarketing']['relating_users_on_email_marketing_ids']);
                    $Redis = $this->__connect();
                    foreach ($emailData as $k => $value) {
                        if ($Redis->zRank($key, trim($value['address'])) === false) {
                            $Redis->zAdd($key, 1, trim($value['address']));
                        } else {
                            unset($emailData[$k]);
                        }
                    }
                }

                $this->SendEmail->pushRedisQueue($email, $emailData);
            } catch (Exception $e) {
                CakeLog::error($e->getMessage(), 'email');
            }
        }
    }

    private function __sendGiftcodes($email, $data)
    {
        foreach($data['addresses'] as $k => $address) {
            # in case, use a giftcode, we won't get giftcode from data
            if (!empty($data['giftcodes'][$k])) {
                $giftcode = $data['giftcodes'][$k];
            } else {
                continue;
            }

            $email_address = $address;
            $friendlyName  = $this->__getName($address);

            $this->out($email_address);
            $emailData[] = array(
                'address' => $email_address,
                'params'  => array(
                    '@email'        => $email_address,
                    '@friendlyName' => $friendlyName,
                    '@giftcode'     => $giftcode,
                )
            );
        }

        try {
            $this->SendEmail->pushRedisQueue($email, $emailData);
        } catch (Exception $e) {
            CakeLog::error('error email marketing pushSqs: ' . $e->getMessage());
        }
    }

    private function __connect()
    {
        if (!isset($this->Redis)) {
            $settings = Configure::read('EmailMarketing.default');
            $this->Redis = new Redis();
            $this->Redis->pconnect($settings['server'], $settings['port'], $settings['timeout']);
            $this->Redis->setOption(Redis::OPT_PREFIX, $settings['port']['prefix']);
            $this->Redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
        }
        return $this->Redis;
    }
}
