<?php

App::uses('AppController', 'Controller');

class PaymentsController extends AppController {

	public $helpers = array('Number');

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow();
	}

	public function charge()
	{
		// echo 'Hệ thống thanh toán đang được bảo trì, và sẽ online trong thời gian sớm nhất. Chúng tôi xin lỗi vì sự bất tiện này.';
		// die();

        $this->layout = 'payment';
		$this->Common->currentGame();
		$currentGame = $this->Common->currentGame();

		$user_id = $this->Auth->user('id');

		if (!$this->request->header('app')) {
			//throw new BadRequestException('Không tìm thấy game này');
			$app_key = 'app1';
		} else {
			$app_key = $this->request->header('app');
		}

		//check to see if there is unresolved payment
		$unresolvedPayment = $this->_checkUnsolvedPayment($app_key, $user_id);
		if ($unresolvedPayment) {
				$this->set('card_amount', $unresolvedPayment['game_price']);
				$this->set('result', $unresolvedPayment);
				$this->render('/Payments/result');
		} else {
            $this->loadModel('Product');
            $this->Product->contain();
            $products = $this->Product->find('all', array(
                'conditions' => array(
                    'Product.game_id' => $this->Product->Game->getCurrentId($app_key),
                    'payment_type' => 'apple'),
                'order'=>array('Product.platform_price' => 'desc')));
            $vCurrencyType2 = 'gold';

			$this->set('vcurrency_type2', $vCurrencyType2);

			$game = $this->Common->currentGame();
			$now = time();

			//prevent Apple refund
			$last_paid = $this->Cookie->read('paid.time');
			$last_paid = intval($last_paid);
			if (($now - $last_paid) < 600) {
				$products_temp = array();
				foreach($products as $product) {
					if ($product['Product']['platform_price'] < 5000)
						$products_temp[] = $product;
				}
				$products = $products_temp;
			}

			$this->set(compact('products', 'game'));
			$this->set('mobgame_appkey', $app_key);
			$this->set('user_id', $user_id);
			$this->set('mobgame_paid_state', $paid_state);
			$gameData = $this->Common->currentGame('data');

			if (!isset($gameData['vcurrency']['type']) || empty($gameData['vcurrency']['type']))
			  $vcurrencyType = "diamond";
			else
			  $vcurrencyType = $gameData['vcurrency']['type'];
			$this->set('vcurrency_type', $vcurrencyType);

			$this->set('now', $now);

			$country_code = $this->Auth->user('country_code');
			$this->set('country_code', $country_code);

			$back_date = time() - 60;
			$this->loadModel('MobOrder');

			$mobOrder = $this->MobOrder->find('all', array(
			            'conditions' => array('MobOrder.user_id' => $user_id,
			                              'MobOrder.type' => 'applestore',
			                              'MobOrder.time >=' => $back_date)));
			$iapCount = count($mobOrder);

			$mobOrder = $this->MobOrder->find('all', array(
			            'conditions' => array('MobOrder.user_id' => $user_id,
			                              'MobOrder.type' => 'google',
			                              'MobOrder.time >=' => $back_date)));

			$iapCount = $iapCount + count($mobOrder);

			$this->set('iap_count', $iapCount);

			$back_date = time() - 86400;

			$mobOrder = $this->MobOrder->find('all', array(
						            'conditions' => array('MobOrder.user_id' => $user_id,
						                              'MobOrder.type' => 'applestore',
						                              'MobOrder.time >=' => $back_date)));
			$iapCount = count($mobOrder);

			$mobOrder = $this->MobOrder->find('all', array(
			            'conditions' => array('MobOrder.user_id' => $user_id,
			                              'MobOrder.type' => 'google',
			                              'MobOrder.time >=' => $back_date)));

			$iapCount = $iapCount + count($mobOrder);

			$this->set('iap_count_day', $iapCount);

			if (!empty($pay_current_event)) {
				$promotion_text = $pay_current_event[0]['PromotionNotification']['title'];
				$this->set('promotion_text', $promotion_text);

			}

            $this->render('/Payments/pay_submit_default');
		}
	}

    private function _checkUnsolvedPayment($app_key, $user_id) {
        $this->loadModel('WaitingOrder');
        $mobOrder = $this->WaitingOrder->find('first', array(
            'conditions' => array('app_key'=>$app_key,
                'user_id' => $user_id,
                'confirm_status' => 0,
                'time >=' => 1383200248)));

        if (!empty($mobOrder)) {
            $result = array (
                'id' => $mobOrder['WaitingOrder']['id'],
                'order_id' => $mobOrder['WaitingOrder']['order_id'],
                'user_id' => $mobOrder['WaitingOrder']['user_id'],
                'status' => $mobOrder['WaitingOrder']['status'],
                'order_product' => $mobOrder['WaitingOrder']['order_product'],
                'platform_price' =>$mobOrder['WaitingOrder']['platform_price'],
                'game_price' => $mobOrder['WaitingOrder']['game_price'],
                'details' => $mobOrder['WaitingOrder']['note'],
                'time' => $mobOrder['WaitingOrder']['time'],
                'game_role_id' => $mobOrder['WaitingOrder']['game_role_id'],
                'game_area_id' => $mobOrder['WaitingOrder']['game_area_id']
            );
            $this->_setResolvedPayment($mobOrder['WaitingOrder']['id']);
            return $result;
        } else {
            return false;
        }
    }

    private function _setResolvedPayment($id, $confirm_status = 3) {
        $this->loadModel('WaitingOrder');
        $newData = array('id'=>$id,
            'confirm_status'=>$confirm_status,
            'time'=>time());
        $this->WaitingOrder->save($newData);
    }
}
