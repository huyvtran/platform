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
        if( !empty($data['chanel']) && $data['chanel'] == Payment::CHANEL_VIPPAY ){
            $vippay = new Vippay();
            $result = $vippay->call($data);
            return $result;
        }
        return false;
    }

    /*
     * data :   order_id, user_id, game_id, card_code, card_serial, price,
     *          time, type, chanel,
     *          test = 0 // default
     *          waiting_id
     */
    public function add($data){
        $this->Payment = ClassRegistry::init('Payment');
        $this->setResolvedPayment($data['waiting_id'], WaitingPayment::STATUS_COMPLETED);

        $this->Payment->save($data);

        App::import('Lib', 'Transaction');
        $this->Transaction = ClassRegistry::init('Transaction');
        $data['type'] = Transaction::TYPE_PAY;
        $this->Transaction->save($data);
    }
}