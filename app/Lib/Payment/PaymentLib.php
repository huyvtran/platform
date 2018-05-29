<?php
App::import('Model', 'WaitingPayment');
App::import('Model', 'Payment');
App::import('Lib', 'Vippay');

class PaymentLib {
    public function checkUnsolvedPayment( $user_id, $game_id ){
        $this->WaitingPayment = ClassRegistry::init('WaitingPayment');
        $waiting = $this->WaitingPayment->find('first', array(
            'conditions' => array(
                'game_id'   => $game_id,
                'user_id'   => $user_id,
                'status'    => WaitingPayment::STATUS_WAIT
            )
        ));

        if (!empty($waiting['WaitingPayment'])) {
            return $waiting;
        } else {
            return false;
        }
    }

    # default status is error
    public function setResolvedPayment($id, $status = 3) {
        CakeLog::info('before save - waiting id: ' . $id .' - status:' . $status, 'payment');
        $this->WaitingPayment = ClassRegistry::init('WaitingPayment');
        $newData = array(
            'id'        => $id,
            'status'    => $status,
            'time'      => time()
        );
        $this->WaitingPayment->save($newData);
    }

    /*
     * data :   card_code, card_serial, type,
     *          order_id, user_id, game_id, chanel
     */
    public function callPayApi($data){
        ClassRegistry::init('Payment');

        $result = false;
        if( !empty($data['chanel']) ){
            switch ($data['chanel']){
                case Payment::CHANEL_VIPPAY:
                    $vippay = new Vippay();
                    $vippay->setMerchantId(12320); # quanvuhong.riotgame@gmail.com
                    $vippay->setMerchantUser('5f953767557e4f4cbd4d62b088bbaf0c');
                    $vippay->setMerchantPassword('086593e5a4464a32b726c90785af320a');
                    $result = $vippay->call($data);
                    break;
                case Payment::CHANEL_VIPPAY_2:
                    $vippay = new Vippay();
                    $vippay->setMerchantId(10367); # quanglevinh1977@gmail.com
                    $vippay->setMerchantUser('eb3b0fe3e28940799f624398a9ef6b77');
                    $vippay->setMerchantPassword('5834af9820294e9285b66d09f7dde138');
                    $result = $vippay->call($data);
                    break;
                case Payment::CHANEL_VIPPAY_3:
                    $vippay = new Vippay();
                    $vippay->setMerchantId(9142); # nguyenphuongduy1989.vn@gmail.com
                    $vippay->setMerchantUser('165cb7d6da73452aa269129b2d79235f');
                    $vippay->setMerchantPassword('f73ead2b14194e4999bbaee3fe84421d');
                    $result = $vippay->call($data);
                    break;
                case Payment::CHANEL_HANOIPAY:
                    App::import('Lib', 'Hanoipay');
                    $hanoipay = new Hanoipay();
                    $result = $hanoipay->call($data);
                    break;
                case Payment::CHANEL_INPAY:
                    App::import('Lib', 'Inpay'); #quanvuhong.riotgame@gmail.com
                    $vippay = new Inpay('0455', 'XPCQW6L28SHN0HSV', '0VIPEZ3KOW4B5L88W1PW');
                    $result = $vippay->call($data);
                    break;
            }
        }

        return $result;
    }

    /*
     * data :   order_id, user_id, game_id, card_code, card_serial, price,
     *          time, type, chanel,
     *          test = 0 // default
     *          note = '' // default
     *          waiting_id
     */
    public function add($data){
        $this->__getPriceEnd($data);
        CakeLog::info('data add :' .print_r($data,true));
        try {
            $this->Payment = ClassRegistry::init('Payment');
            
            $dataSource = $this->Payment->getDataSource();
            $dataSource->begin();

            $this->Payment->save($data);

            App::import('Lib', 'Transaction');
            $this->Transaction = ClassRegistry::init('Transaction');
            $data['type'] = Transaction::TYPE_PAY;
            $this->Transaction->save($data);

            $this->Payment->User->recursive = -1;
            $user = $this->Payment->User->findById($data['user_id']);
            $updatePay = $user['User']['payment'] + $data['price'];
            $this->Payment->User->id = $data['user_id'];
            $this->Payment->User->saveField('payment', $updatePay, array('callbacks' => false));
            
            $dataSource->commit();
            return true;
        }catch (Exception $e){
            CakeLog::error('error save payment - ' . $e->getMessage(), 'payment');
            $dataSource->rollback();
        }
        
        return false;
    }

    /*
     * data :   order_id, user_id, game_id, price,
     *          time, note,
     *          test = 0 // default
     */
    public function sub($data){
        CakeLog::info('log charge data:' . print_r($data,true), 'payment');
        try {
            $this->Charge = ClassRegistry::init('Charge');

            $dataSource = $this->Charge->getDataSource();
            $dataSource->begin();

            $this->Charge->save($data);

            App::import('Lib', 'Transaction');
            $this->Transaction = ClassRegistry::init('Transaction');
            $data['type'] = Transaction::TYPE_SPEND;
            $this->Transaction->save($data);

            $this->Charge->User->recursive = -1;
            $user = $this->Charge->User->findById($data['user_id']);
            $updatePay = $user['User']['payment'] - $data['price'];

            CakeLog::info('log charge user_id:' . $data['user_id']
                . '- before update pay:' . $user['User']['payment']
                . ' - update pay:' . $updatePay, 'payment');

            if($updatePay >= 0) {
                $this->Charge->User->id = $data['user_id'];
                $this->Charge->User->saveField('payment', $updatePay);
                $dataSource->commit();
                return true;
            }else{
                CakeLog::error('Not enough', 'payment');
            }
        }catch (Exception $e){
            CakeLog::error('error save charge', 'payment');
        }
        $dataSource->rollback();
        return false;
    }

    private function __getPriceEnd(&$data){
        if( !empty($data) ){
            ClassRegistry::init('Payment');
            $price_end = $data['price'];

            if( $data['chanel'] == Payment::CHANEL_VIPPAY
                || $data['chanel'] == Payment::CHANEL_VIPPAY_2
                || $data['chanel'] == Payment::CHANEL_VIPPAY_3
            ){
                switch ( $data['type'] ){
                    case Payment::TYPE_NETWORK_VIETTEL:
                    case Payment::TYPE_NETWORK_MOBIFONE:
                    case Payment::TYPE_NETWORK_VINAPHONE:
                        $price_end = 0.74 * $data['price'];
                        break;
                    case Payment::TYPE_NETWORK_GATE:
                        $price_end = 0.79 * $data['price'];
                        break;
                }
            }elseif ( $data['chanel'] == Payment::CHANEL_HANOIPAY ){
                switch ( $data['type'] ){
                    case Payment::TYPE_NETWORK_VIETTEL:
                        $price_end = 0.8 * $data['price'];
                        break;
                    case Payment::TYPE_NETWORK_MOBIFONE:
                        $price_end = 0.815 * $data['price'];
                        break;
                    case Payment::TYPE_NETWORK_VINAPHONE:
                        $price_end = 0.81 * $data['price'];
                        break;
                    case Payment::TYPE_NETWORK_GATE:
                        $price_end = 0.82 * $data['price'];
                        break;
                }

            }elseif ( $data['chanel'] == Payment::CHANEL_ONEPAY || $data['chanel'] == Payment::CHANEL_ONEPAY_2 ){
                return ;
            }elseif ( $data['chanel'] == Payment::CHANEL_PAYMENTWALL ){
                return ;
            }elseif ( $data['chanel'] == Payment::CHANEL_APPOTA ){
                $price_end = $data['price'] * 0.94 - 7150;
            }elseif ( $data['chanel'] == Payment::CHANEL_PAYPAL ){
                $price_end = $data['price'] - (6801 + ($data['price']*0.045) ) ;
            }elseif ( $data['chanel'] == Payment::CHANEL_NL_ALE ){
                $price_end = $data['price'] - (7700 + ($data['price']*0.035) ) ;
            }elseif ( $data['chanel'] == Payment::CHANEL_MANUAL ){
                $price_end = 0.65 * $data['price'];
            }

            $data['price_end'] = $price_end;
        }
    }
}