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
		$unresolvedPayment = $paymentLib->checkUnsolvedPayment($user['id'], $game['id']);

		if( !empty($unresolvedPayment) ){
			$this->set('result', $unresolvedPayment);
			$this->render('/Payments/order');
		}else {
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

					$dataSource = $this->CompenseOrder->getDataSource();
					$dataSource->begin();

					# gọi đến api cổng thanh toán và check thẻ (ghi log khi gọi api)
					$result = $paymentLib->callPayApi($data);
					if( !empty($result['status']) && $result['status'] == 0 && $data['order_id'] == $result['data']['order_id']){
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

						# gửi api tới game cộng xu
						
					}elseif (!empty($result['status']) && $result['status'] == 1){
						# trạng thái lỗi, thẻ đã sử dụng, hoặc thẻ không đúng
						$paymentLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_ERROR);
					}else{
						# chờ hệ thống cổng thanh toán
						$paymentLib->setResolvedPayment($unresolvedPayment['WaitingPayment']['id'], WaitingPayment::STATUS_QUEUEING);
					}

					$dataSource->begin();

					$this->render('/Payments/result');
				} catch (Exception $e) {
					CakeLog::error($e->getMessage());
					$dataSource->rollback();
				}
			}
		}
	}
}
