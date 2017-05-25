<?php

App::uses('AppController', 'Controller');
App::uses('PaymentLib', 'Payment');

class PaymentsController extends AppController {
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow();
	}

	public function charge()
	{
//		 echo 'Hệ thống thanh toán đang được bảo trì, và sẽ online trong thời gian sớm nhất. Chúng tôi xin lỗi vì sự bất tiện này.';
//		 die();

        $this->layout = 'payment';

		# load for view
		$this->loadModel('Payment');
		
		$game = $this->Common->currentGame();
		if( empty($game) || !$this->Auth->loggedIn() ){
			throw new NotFoundException('Vui lòng login');
		}
		$user = $this->Auth->user();

		$paymentLib = new PaymentLib();
		# check to see if there is unresolved payment

        if ($this->request->is('post')) {
            $chanel = Payment::CHANEL_VIPPAY; // default
            $data = $this->request->data;
            $data = array_merge($data, array(
                'user_id' => $user['id'],
                'game_id' => $game['id'],
                'chanel' => $chanel,
                'status' => WaitingPayment::STATUS_WAIT,
                'time' => time(),
                'order_id' => microtime(true) * 10000
            ));

            $this->loadModel('Payment');
            try {
                $unresolvedPayment = $this->Payment->WaitingPayment->save($data);

                $dataSource = $this->Payment->getDataSource();
                $dataSource->begin();

                # gọi đến api cổng thanh toán và check thẻ (ghi log khi gọi api)
                $result = $paymentLib->callPayApi($data);
                if( isset($result['status']) && $result['status'] == 0 && $data['order_id'] == $result['data']['order_id']){
                    $this->render('/Payments/result');

                    # trạng thái thành công, lưu dữ liệu payment
                    $user_test = 0; // default
                    $data_payment = array(
                        'waiting_id'	=> $unresolvedPayment['WaitingPayment']['id'],

                        'time'  => $data['time'],
                        'type'  => $data['type'],
                        'test'	=> $user_test,
                        'chanel'    => $data['chanel'],

                        'order_id'  => $result['data']['order_id'],
                        'user_id' 	=> $user['id'],
                        'game_id' 	=> $game['id'],

                        'card_code' => $result['data']['card_code'],
                        'price'     => $result['data']['price'],
                        'card_serial'   => $result['data']['card_serial']
                    );
                    $paymentLib->add($data_payment);

                }elseif (!empty($result['status']) && $result['status'] == 1){
                    # trạng thái lỗi, thẻ đã sử dụng, hoặc thẻ không đúng
                    $paymentLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_ERROR);
                    $this->render('/Payments/error');
                }else{
                    # chờ hệ thống cổng thanh toán
                    $paymentLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_QUEUEING);
                    $this->render('/Payments/order');
                }
                $dataSource->commit();
            } catch (Exception $e) {
                CakeLog::error($e->getMessage());
                $dataSource->rollback();
            }
        }
	}

	private function _getAccount($userId, $gameId){
		# check switch account exist or not
		$this->loadModel('Account');
		$this->Account->contain();
		$account = $this->Account->findAllByGameIdAndUserId($gameId, $userId);

		if (empty($account)) {
			throw new BadRequestException('Can not found account');
		}
		$accountId = $account[0]['Account']['id'];
		if (!empty($account[0]['Account']['account_id'])) {
			$accountId = $account[0]['Account']['account_id'];
		}
		return $accountId;
	}

	public function feedback(){
        $app = 'app';
        $token  = 'token';

        if($this->request->header($app)){
            $appKey = $this->request->header($app);
        }

        if($this->request->header($token)){
            $accessToken = $this->request->header($token);
        }

        if ($this->request->query('app_key')) {
            $appKey = $this->request->query('app_key');
        } elseif ($this->request->query('appkey')) {
            $appKey = $this->request->query('appkey');
        } elseif ($this->request->query('app')) {
            $appKey = $this->request->query('app');
        }

        if ($this->request->query('access_token'))
            $accessToken = $this->request->query('access_token');

        if (!isset($appKey, $accessToken)) {
            throw new BadRequestException();
        }

        # update payment khi ingame trả về
    }
}
