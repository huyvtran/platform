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
        $Redis->key = 'payment_job_process_paymentwall_card';
        $Redis->expire(5*60); // 10s
        $pay_id = $Redis->get();

        if( empty($pay_id) ) $pay_id = 142311;

        $Payment = ClassRegistry::init('Payment');
        $Payment->recursive = -1;
        $paypals = $Payment->find('all', array(
            'fields'    => array('Payment.id', 'Payment.price', 'Payment.price_org', 'Payment.price_end'),
            'conditions' => array(
                'Payment.id >'      => $pay_id,
                'Payment.chanel'    => Payment::CHANEL_PAYMENTWALL
            ),
            'order' => array('Payment.id' => 'asc'),
            'limit' => 200
        ));

        if( !empty($paypals)){
            foreach ($paypals as $paypal){
                if( !empty($paypal['Payment']['price_org']) ) continue;

                $price_org = ($paypal['Payment']['price']) ;

                $Payment->id = $paypal['Payment']['id'];
                $Payment->saveField('price_org', $price_org, array('callbacks' => false));
                $pay_id_tmp = $paypal['Payment']['id'];

                $this->out('<success>Pid: ' . $pay_id_tmp . ' - Saved</success>');
            }

            $Redis->set($pay_id_tmp);
            unset($pay_id_tmp);
        }else{
            $this->out('<warning>No record was found</warning>');
        }
    }

    public function shopcard(){
        $this->out('payment shopcard starting  ...');
        set_time_limit(60 * 60);
        ini_set('memory_limit', '384M');

        try {
            $waiting_id = false;
            $starttime = time();
            while (true) {
                # run this command 60s only
                if ((time() - $starttime) > 60) {
                    goto end;
                }

                $this->WaitingPayment->bindModel(array(
                    'hasOne' => array(
                        'CardManual' => array(
                            'foreignKey' => false,
                            'conditions' => array_merge(
                                array('WaitingPayment.order_id = CardManual.order_id')
                            )
                        ),
                    )
                ));

                $conditions = array(
                    'WaitingPayment.time >= ' => strtotime('-1 day'),
                    'WaitingPayment.time <= ' => strtotime('-1 minutes'),
                    'WaitingPayment.chanel' => Payment::CHANEL_SHOPCARD,
                    'WaitingPayment.status' => WaitingPayment::STATUS_QUEUEING,
                );
                if( !empty($waiting_id) ){
                    $conditions = array_merge(array( 'WaitingPayment.id >' => $waiting_id), $conditions);
                }

                $watingPayments = $this->WaitingPayment->find('first', array(
                    'conditions' => $conditions,
                    'contain' => array("CardManual"),
                    'recursive' => -1
                ));

                if( empty($watingPayments['CardManual']['id']) ) goto end;

                $waiting_id = $watingPayments['WaitingPayment']['id'];

                # ktra seri và cardcode đã thành công ko, tránh trùng thẻ
                $watingCheck = $this->WaitingPayment->find('first', array(
                    'fields' => array('id', 'order_id'),
                    'conditions' => array(
                        'WaitingPayment.card_serial' => $watingPayments['CardManual']['card_serial'],
                        'WaitingPayment.card_code' => $watingPayments['CardManual']['card_code'],
                        'WaitingPayment.status' => WaitingPayment::STATUS_COMPLETED,
                    ),
                    'recursive' => -1
                ));

                if ( !empty($watingCheck['WaitingPayment']['id']) ) {
                    $this->WaitingPayment->id = $watingPayments['WaitingPayment']['id'];
                    $this->WaitingPayment->saveField('status', WaitingPayment::STATUS_ERROR, array('callbacks' => false));

                    $this->WaitingPayment->CardManual->id = $watingPayments['CardManual']['id'];
                    $this->WaitingPayment->CardManual->saveField('status', WaitingPayment::STATUS_ERROR, array('callbacks' => false));

                    continue;
                }

                App::uses('ShopCard', 'Payment');
                $LibPay = new ShopCard();
                $data_pay = array(
                    'merchant_id'       => (int)$LibPay->getMerchantId(),
                    'merchant_user'     => $LibPay->getMerchantUser(),
                    'merchant_password' => $LibPay->getMerchantPassword(),
                    'transaction_id'    => $watingPayments['CardManual']['trans_gate']
                );
                $result = $LibPay->getTransaction($data_pay);
                CakeLog::info('Shopcard - cronjob get transaction:' . print_r(
                        array( $watingPayments['WaitingPayment']['order_id'],
                            $watingPayments['CardManual']['trans_gate'],
                            $result
                        ), true), 'payment');

                if ( !empty($result['amount']) && !empty($result['status']) && $result['status'] == 1 ) {
                    $rate = 1.2;
                    if( $watingPayments['WaitingPayment']['type'] == Payment::TYPE_NETWORK_VIETTEL ){
                        $rate = 1.2;
                    }

                    App::uses('PaymentLib', 'Payment');
                    $paymentLib = new PaymentLib();
                    $data_payment = array(
                        'waiting_id' => $watingPayments['WaitingPayment']['id'],
                        'time' => $watingPayments['WaitingPayment']['time'],
                        'chanel' => $watingPayments['WaitingPayment']['chanel'],
                        'type' => $watingPayments['WaitingPayment']['type'],

                        'order_id' => $watingPayments['WaitingPayment']['order_id'],
                        'user_id' => $watingPayments['WaitingPayment']['user_id'],
                        'game_id' => $watingPayments['WaitingPayment']['game_id'],

                        'price'     => ($result['amount']) * ($rate),
                        'price_org' => $result['amount'],
                        'price_end' => ($result['amount']) * (0.65),
                    );

                    $this->WaitingPayment->CardManual->save(array(
                        'CardManual' => array(
                            'id' => $watingPayments['CardManual']['id'],
                            'status' => WaitingPayment::STATUS_COMPLETED,
                            'price' => $result['amount']
                        )
                    ));

                    $paymentLib->setResolvedPayment($watingPayments['WaitingPayment']['id'], WaitingPayment::STATUS_COMPLETED);
                    $paymentLib->add($data_payment);

                    unset($paymentLib); unset($data_payment);
                    goto end;
                }elseif ( $result['status'] == -69 || $result['status'] == 999 ){
                    continue;
                } else {
                    $this->WaitingPayment->id = $watingPayments['WaitingPayment']['id'];
                    $this->WaitingPayment->saveField('status', WaitingPayment::STATUS_ERROR, array('callbacks' => false));

                    $this->WaitingPayment->CardManual->id = $watingPayments['CardManual']['id'];
                    $this->WaitingPayment->CardManual->saveField('status', WaitingPayment::STATUS_ERROR, array('callbacks' => false));
                }

                unset($conditions); unset($watingPayments); unset($watingCheck);
                unset($LibPay); unset($data_pay); unset($result);
            }
        }catch (Exception $e){
            CakeLog::error('error run shopcard:' . $e->getMessage());
        }

        end:
        $this->out('End..');
    }
}
