<?php

use Aws\Common\Aws;
use Aws\Sqs\Enum\QueueAttribute;

App::uses('Hash', 'Utility');
App::import('Lib', 'RedisQueue');

/**
 * Get email from sqs and send vie ses
 */
class SendEmailTask extends Shell {

	public $uses = array('EmailMarketing', 'EmailFeedback');

	public function pushRedisQueue($emailMarketing, $data)
	{
		try {
			$Redis = $this->getRedis($emailMarketing);
			
			foreach ($data as $v) {
				if (strpos($v['address'], 'myapp.com') !== false) {
					continue;
				}
				
				# dont send this email, it was blocked
				if ($this->EmailFeedback->wasBlocked($v['address'], $emailMarketing['EmailMarketing']['game_id'])) {
					continue;
				}				
				$Redis->rPush(array(
					'address' => $v['address'],
					'params' => $v['params'],
					'id' => $emailMarketing['EmailMarketing']['id']
				));	
			}
		} catch (Exception $e) {
			CakeLog::error($e->getMessage() . ' ' . $emailMarketing['EmailMarketing']['id'], 'email');
			$this->out('Error happen : ' . $e->getMessage());
		}
	}


	public function getRedis($email)
	{
		$this->out('Get redis queue: ' . 'email-marketing-id-' . $email['EmailMarketing']['id']);
		$Redis = new RedisQueue('default', 'email-marketing-id-' .$email['EmailMarketing']['id']);
		return $Redis;
	}


	/**
	 * get messages from amazon sqs and send email
	 **/
	public function send($email)
	{
		require ROOT . DS . 'vendors' . DS . 'aws' . DS . 'aws-autoloader.php';
		$aws = Aws::factory(APP . 'Config' . DS . 'aws.php');
		$SesClient = $aws->get('Ses');

		$Redis = $this->getRedis($email);

		$this->out('Sending: ' . $email['EmailMarketing']['id']);
		$count = 0;
		while (true){
			if (!env('testing') && ($count == 0 || $count == 1000)) {
				$SesClient->getSendStatistics();
				$quota = $SesClient->getSendQuota();
				$data = $quota->getAll();
				if (($data['Max24HourSend'] - $data['SentLast24Hours']) < 10000) {
					CakeLog::error("Close to sending limit: " . print_r($data, true), 'email');
					break;
				}
			}

			$m = $Redis->lPop();
			
			$count++;
			
			if ($m == false) {
				break;
			}
			try {
				$this->out($m['id'] . '-' . $m['address']);
				$this->EmailMarketing->send($m['id'], $m['address'], $m['params']);
			} catch(PDOException $e) {
				CakeLog::error("LOST connection mysql during sending");
			} catch (Exception $e) {
				CakeLog::error("Error during send email: " . $e->getMessage() . ', address: ' . $m['address'], 'email');
			}	
		}
		return true;
	}

	public function queueIsEmpty($email)
	{
		$Redis = $this->getRedis($email);
		$size = $Redis->lSize();
		if (empty($size)) {
			return true;
		}
		return false;
	}

	public function markAsCompleted($email)
	{
		$this->EmailMarketing->id = $email['EmailMarketing']['id'];
		$this->EmailMarketing->saveField('status', EmailMarketing::SEND_COMPLETED, array('callbacks' => false));
	}

	public function getTotalQueue($email)
	{
		$Redis = $this->getRedis($email);
		return $Redis->lSize();
	}
}