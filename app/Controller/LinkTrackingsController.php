<?php

App::uses('AppController', 'Controller');

class LinkTrackingsController extends AppController {

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'default_bootstrap';
		$this->Auth->allow();
	}

	public function track($code, $mail = null)
	{
		$Redis = $this->__connect();
		$id = $this->LinkTracking->unhashStr($code);

		if ($mail != null) {
			$email = $this->LinkTracking->unhashStr($mail);
		} else {
			$email = '';
		}

		$link = $this->LinkTracking->findById($id);
		if (!$link) {
			throw new NotFoundException('Invalid link!');
		}

 		if ($link['LinkTracking']['model'] == 'EmailMarketing') {
			$emailMarketingId = $link['LinkTracking']['foreign_key'];
			$counts = $this->Session->read('LinkTracking.LinkTrackingIds');
            CakeLog::info('link tracking count:' . print_r($counts,true));
			if (!in_array($id, (array) $counts)) {
				$counts[] = $id;
				$this->Session->write('LinkTracking.LinkTrackingIds', $counts);
				$Redis->zIncrBy('LinkTracking.count', 1, $id);
			}
			$clicks = $this->Session->read('LinkTracking.EmailMarketingClicks');
			if (!in_array($emailMarketingId, (array) $clicks)) {
				$clicks[] = $emailMarketingId;
				$this->Session->write('LinkTracking.EmailMarketingClicks', $clicks);
				$Redis->zIncrBy('LinkTracking.EmailMarketing.click', 1, $emailMarketingId);
			}

		}
		die;

		return $this->redirect($link['LinkTracking']['original_link']);
	}

	/**
	 * Model = EmailMarketing
	 */
	public function open($code, $mail = null)
	{
		$id = $this->LinkTracking->unhashStr($code);
		if ($mail != null) {
			$email = $this->LinkTracking->unhashStr($mail);
		} else {
			$email = '';
		}
		$opens = $this->Session->read('LinkTracking.EmailMarketingOpens');
		if (!in_array($id, (array) $opens)) {
			$opens[] = $id;
			$this->Session->write('LinkTracking.EmailMarketingOpens', $opens);
			$Redis = $this->__connect();
			$Redis->zIncrBy('LinkTracking.EmailMarketing.open', 1, $id);
		}

		$name = Router::url('/img/transparent.gif', true);
		header("Content-Type: image/gif");
		readfile($name);
		exit;		
	}

	private function __connect()
	{
		if (!isset($this->Redis)) {
			$settings = Configure::read('LinkTracking.default');
			$this->Redis = new Redis();
			$this->Redis->pconnect($settings['server'], $settings['port'], $settings['timeout']);
			$this->Redis->setOption(Redis::OPT_PREFIX, $settings['port']['prefix']);
			$this->Redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);
		}
		return $this->Redis;
	}
}
