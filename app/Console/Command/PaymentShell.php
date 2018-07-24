<?php

App::uses('ClassRegistry', 'Utility');
App::uses('AppShell', 'Console/Command');

class PaymentShell extends AppShell
{

	public $uses = ['WaitingPayment', 'Payment', 'Variable'];

	public function initialize()
	{
		ClassRegistry::init('Payment');
		parent::initialize();
	}

	public function run()
	{
		$this->out('Running..');
		set_time_limit(60 * 60 * 24);
		ini_set('memory_limit', '384M');

        $chanels = array(
            Payment::CHANEL_GOOGLE      => 'Error',
            Payment::CHANEL_PAYPAL      => 'Error',
            Payment::CHANEL_PAYMENTWALL => 'Error',
            Payment::CHANEL_NL_ALE      => 'Error',
        );

        foreach($chanels as $chanel => $name) {
            if (method_exists($this, '__' . $name)) {
                $time = strtotime('-15 minute');
                if( $chanel == Payment::CHANEL_NL_ALE) $time = strtotime('-1 week');
                call_user_func(array($this, '__' . $name), $chanel, $time );
            }
        }
	}

    private function __Error($chanel, $time){
        $lastPid = $this->Variable->getVar('payment_last_pid_checked_by_chanel_' . $chanel);
        if( !$lastPid ) $lastPid = 0;

        $this->out($this->nl(0));
        $this->out("chanel: " . $chanel . ' - lastPid: ' . $lastPid);

        $this->WaitingPayment->bindModel(array(
            'belongsTo' => array('Game', 'User')
        ));
        $watingPayments = $this->WaitingPayment->find('all', array(
            'conditions' => array(
                'WaitingPayment.id >'  => $lastPid,
                'WaitingPayment.chanel'  => $chanel,
                'WaitingPayment.status <>'  => WaitingPayment::STATUS_COMPLETED,
                'WaitingPayment.time < '  => $time
            ),
            'contain' => array("User", "Game")
        ));

        if( empty($watingPayments) ) {
            return false;
        }

        # check từng giao dịch trong trạng thái chờ
        foreach ($watingPayments as $wating){
            # this WatingPayment was checked
            if (!empty($wating['WaitingPayment']['id']) && $lastPid >= $wating['WaitingPayment']['id']) {
                $this->out("Checked pid: " . $wating['WaitingPayment']['id']);
                continue;
            } else {
                $this->out("saved pid: " . $wating['WaitingPayment']['id']);
                $this->Variable->setVar("payment_last_pid_checked_by_chanel_" . $chanel, $wating['WaitingPayment']['id']);
            }

            $this->WaitingPayment->id = $wating['WaitingPayment']['id'];
            $this->WaitingPayment->saveField('status', WaitingPayment::STATUS_ERROR, array('callbacks' => false));
        }
    }

	public function getTop()
	{
		$Game = ClassRegistry::init('Game');
		$Bonus = ClassRegistry::init('Bonus');
		$Payment = ClassRegistry::init('Payment');

		$Bonus->enablePublishable('find', false);
		/*$getLogTopPayment = $Bonus->find('all', [
			'conditions' => [
				'user_id' => 163797,
				'created >=' => date('Y-m-d H:i:s', strtotime('monday this week')),
			],
		]);*/

		$Game->recursive = -1;
		$games = $Game->find('all', [
//			'fields' => [
//				'id', 'title', 'os'
//			],
			'conditions' => [
				'top' => true,
				'os' => 'android',
			],
		]);

		$getModayOfWeek = isset($this->args[0]) ? strtotime($this->args[0]) : strtotime('monday this week');
		$getSundayOfWeek = isset($this->args[1]) ? strtotime($this->args[1] . ' 23:59:59') : strtotime(date('Y-m-d 23:59:29', strtotime('sunday this week')));

		foreach ($games as $game) {
			#lay thong tin cua android va ios
			$gameall = $Game->getSimilarGameId($game['Game']);

			$getTopPaymentByGame = $Payment->query("
				SELECT user_id,  game_id, SUM(price) AS total_price
				FROM payments
				WHERE game_id in (" . implode(', ', $gameall) . ")
					AND time > '" . $getModayOfWeek . "' AND time < '" . $getSundayOfWeek . "'
				GROUP BY user_id
				ORDER BY total_price DESC
				LIMIT 10
			");

			# them du lieu vao bang bonus
			$bonusMoney = 0;
			$dataToInsert = [];
			for ($i = 0; $i < count($getTopPaymentByGame); $i++) {
				# check
                /*
				$getLogTopPayment = $Bonus->find('all', [
					'conditions' => [
						'user_id' => $getTopPaymentByGame[$i]['payments']['user_id'],
						'created >=' => date('Y-m-d H:i:s', $getModayOfWeek),
					],
				]);

				if (!empty($getLogTopPayment)) {
					goto end;
				}
				unset($getLogTopPayment);
                */

				# tinh so tien bonus
				if ($i == 0) {
					$bonusMoney = $getTopPaymentByGame[$i][0]['total_price'] * 20 / 100;
				}

				if ($i == 1) {
					$bonusMoney = $getTopPaymentByGame[$i][0]['total_price'] * 15 / 100;
				}

				if ($i >= 2 && $i <= 4) {
					$bonusMoney = $getTopPaymentByGame[$i][0]['total_price'] * 10 / 100;
				}

				if ($i >= 5 && $i <= 9) {
					$bonusMoney = $getTopPaymentByGame[$i][0]['total_price'] * 5 / 100;
				}

				# them du lieu vao bang bonus
				$dataToInsert[] = [
					'order_id' => microtime(true) * 10000,
					'user_id' => $getTopPaymentByGame[$i]['payments']['user_id'],
					'game_id' => $getTopPaymentByGame[$i]['payments']['game_id'],
					'price' => $getTopPaymentByGame[$i][0]['total_price'],
					'bonus' => $bonusMoney,
					'status' => 0,
					'chanel' => Payment::CHANEL_BONUS,
				];

				end:

			}

			if ($Bonus->saveMany($dataToInsert)) {
				$this->out('Get Top Payment Success For Game ID:' . $game['Game']['title']);
			} else {
				$this->out('Get Top Payment Failed For Game ID:' . $game['Game']['title']);
			}
		}
	}

	public function processPaypal(){
        App::import('Lib', 'RedisQueue');
        $Redis = new RedisQueue();
        $Redis->key = 'payment_job_process_ale';
        $Redis->expire(5*60); // 10s
        $pay_id = $Redis->get();

        if( empty($pay_id) ) $pay_id = 129984;

        $Payment = ClassRegistry::init('Payment');
        $Payment->recursive = -1;
        $paypals = $Payment->find('all', array(
            'fields'    => array('Payment.id', 'Payment.price', 'Payment.price_end'),
            'conditions' => array(
                'Payment.id >'      => $pay_id,
                'Payment.chanel'    => Payment::CHANEL_NL_ALE,
            ),
            'order' => array('Payment.id' => 'asc'),
            'limit' => 200
        ));

        if( !empty($paypals)){
            foreach ($paypals as $paypal){
                $price_org = ($paypal['Payment']['price']/(1.3)) ;
                $price_end = 0.965*$price_org - 7700;

                $Payment->id = $paypal['Payment']['id'];
                $Payment->saveField('price_end', $price_end, array('callbacks' => false));
                $pay_id_tmp = $paypal['Payment']['id'];

                $this->out('<success>Pid: ' . $pay_id_tmp . ' - Saved</success>');
            }

            $Redis->set($pay_id_tmp);

        }else{
            $this->out('<warning>No record was found</warning>');
        }
    }
}
