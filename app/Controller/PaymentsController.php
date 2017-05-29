<?php

App::uses('AppController', 'Controller');
App::uses('PaymentLib', 'Payment');

class PaymentsController extends AppController {
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow();
	}

    public function api_pay(){
        $data = $this->pay(true);

        $result = array(
            'status' => 1,
            'message' => 'error'
        );
        if($data){
            $result = array(
                'status' => 0,
                'message' => 'success'
            );
        }

        $this->set('result', $result);
        $this->set('_serialize', 'result');
    }

	public function pay($return = false)
	{
//		 echo 'Hệ thống thanh toán đang được bảo trì, và sẽ online trong thời gian sớm nhất. Chúng tôi xin lỗi vì sự bất tiện này.';
//		 die();

        $this->layout = 'payment';

		# load for view
		$this->loadModel('Payment');
		
		$game = $this->Common->currentGame();
		if( empty($game) || !$this->Auth->loggedIn() ){
            CakeLog::error('Vui longf login', 'payment');
            if($return) return false;
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
            $this->loadModel('WaitingPayment');
            try {
                $unresolvedPayment = $this->WaitingPayment->save($data);

                $dataSource = $this->Payment->getDataSource();
                $dataSource->begin();

                $test_type = 0;
                if(!empty($game['data']['payment']['testallowed'])){
                    $testList = $game['data']['payment']['testallowed'];
                    if( in_array($user['email'], array_map('trim', explode("\n", $testList))) ){
                        $test_type = 1;
                    }
                }
                if($test_type){
                    $result = array(
                        'status'    => 0,
                        'messsage'  => 'success',
                        'data'      => array(
                            'time'  => $data['time'],
                            'type'  => $data['type'],
                            'chanel'    => $data['chanel'],

                            'order_id'  => $data['order_id'],
                            'user_id'   => $data['user_id'],
                            'game_id'   => $data['game_id'],

                            'card_code' => $data['card_code'],
                            'price'     => $data['card_code'],
                            'card_serial'   => $data['card_serial']
                        )
                    );
                }else{
                    # gọi đến api cổng thanh toán và check thẻ (ghi log khi gọi api)
                    $result = $paymentLib->callPayApi($data);
                    CakeLog::info('thẻ cào qua vippay:' . print_r($result,true), 'payment');
                }

                CakeLog::info('result payment 1:' . print_r($result,true), 'payment');
                if( isset($result['status']) && $result['status'] == 0 && $data['order_id'] == $result['data']['order_id']){
                    # trạng thái thành công, lưu dữ liệu payment
                    $data_payment = array(
                        'waiting_id'	=> $unresolvedPayment['WaitingPayment']['id'],

                        'time'  => $data['time'],
                        'type'  => $data['type'],
                        'test'	=> $test_type,
                        'chanel'    => $data['chanel'],

                        'order_id'  => $result['data']['order_id'],
                        'user_id' 	=> $user['id'],
                        'game_id' 	=> $game['id'],

                        'card_code' => $result['data']['card_code'],
                        'price'     => $result['data']['price'],
                        'card_serial'   => $result['data']['card_serial']
                    );
                    CakeLog::info('result payment:' . print_r($data_payment,true), 'payment');
                    $paymentLib->add($data_payment);

                    if($return) return true;
                    $this->render('/Payments/result');
                }elseif (!empty($result['status']) && $result['status'] == 1){
                    # trạng thái lỗi, thẻ đã sử dụng, hoặc thẻ không đúng
                    CakeLog::info('trạng thái lỗi, thẻ đã sử dụng, hoặc thẻ không đúng', 'payment');
                    $paymentLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_ERROR);
                    $this->render('/Payments/error');
                }else{
                    # chờ hệ thống cổng thanh toán
                    CakeLog::info('chờ hệ thống cổng thanh toán', 'payment');
                    $paymentLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_QUEUEING);
                    $this->render('/Payments/order');
                }
                $dataSource->commit();
            } catch (Exception $e) {
                CakeLog::error($e->getMessage());
                $dataSource->rollback();
            }
        }

        CakeLog::info('result payment game 2:' . print_r($game,true), 'payment');
        if($return) return false;
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

	public function api_charge(){
	    $result = array(
	        'status'    => 1,
            'mesage'    => 'empty'
        );

        $app = 'app';
        $token  = 'token';

        if( $this->request->header($app) ){
            $appKey = $this->request->header($app);
        }

        if ( $this->request->query('app_key') ) {
            $appKey = $this->request->query('app_key');
        } elseif ( $this->request->query('appkey') ) {
            $appKey = $this->request->query('appkey');
        } elseif ( $this->request->query('app') ) {
            $appKey = $this->request->query('app');
        }

        if( $this->request->header($token) ){
            $accessToken = $this->request->header($token);
        }

        if ( $this->request->query('access_token') ) {
            $accessToken = $this->request->query('access_token');
        }elseif ( $this->request->query('token') ){
            $accessToken = $this->request->query('token');
        }

        if (!isset($appKey, $accessToken)) {
            $result = array(
                'status'    => 2,
                'mesage'    => 'empty token or appkey'
            );
            goto end;
        }

        $game = $this->Common->currentGame();
        if( empty($game) || !$this->Auth->loggedIn() ){
            $result = array(
                'status'    => 3,
                'mesage'    => 'Invalid token or appkey'
            );
            goto end;
        }
        $user = $this->Auth->user();

        $price = $sign_input = false;
        if( !empty($this->request->data('price')) ){
            $price = $this->request->data('price');
        }elseif ( !empty($this->request->query('price')) ){
            $price = $this->request->query('price');
        }

        if( !is_numeric($price) || $price <= 0 ){
            $result = array(
                'status'    => 7,
                'mesage'    => 'Invalid price'
            );
            goto end;
        }

        if( !empty($this->request->data('sign')) ){
            $sign_input = $this->request->data('sign');
        }elseif ( !empty($this->request->query('sign')) ){
            $sign_input = $this->request->query('sign');
        }

        if( empty($price) || empty($sign_input) ){
            $result = array(
                'status' => 4,
                'message' => 'Necessary data is missing'
            );
            goto end;
        }

        $paymentLib = new PaymentLib();
        # update payment user khi ingame trả về
        # dữ liệu truyền sang `price`, `sign`
        $data = array(
            'user_id'   => $user['id'],
            'game_id'   => $game['id'],
            'time'      => time(),
            'order_id'  => microtime(true) * 10000,
            'price'     => $price,
            'sign'      => $sign_input
        );

        $sign = md5( $game['app'] . $game['secret_key'] . $accessToken . $data['price'] );
        if( empty($data['sign']) || $sign != $data['sign'] ){
            CakeLog::error('sign api charge:'. $sign, 'payment');
            $result = array(
                'status'    => 5,
                'message'   => 'The sign is incorrect'
            );
            goto end;
        }

        if( $paymentLib->sub($data) ){
            $result = array(
                'status'    => 0,
                'mesage'    => 'success'
            );
            goto end;
        }else{
            $result = array(
                'status'    => 6,
                'mesage'    => 'error'
            );
            goto end;
        }

        end:
        $this->set('result', $result);
        $this->set('_serialize', 'result');
    }
}
