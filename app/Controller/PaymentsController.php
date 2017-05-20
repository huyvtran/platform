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
		//check to see if there is unresolved payment
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
				$this->loadModel('PrePayment');

				$dataSource = $this->Payment->getDataSource();
				$dataSource->begin();
				try {
					$this->PrePayment->save($data);
					$unresolvedPayment = $this->Payment->WaitingPayment->save($data);
					$dataSource->commit();

					# gọi đến api vippay và check thẻ 
					$this->set('result', $unresolvedPayment);
					$this->render('/Payments/order');
				} catch (Exception $e) {
					CakeLog::error($e->getMessage());
					$dataSource->rollback();
				}
				die;
			}
		}
	}
}
