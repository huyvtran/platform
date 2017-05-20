<?php
App::import('Model', 'WaitingPayment');
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
            $result = $waiting['WaitingPayment'] ;
            return $result;
        } else {
            return false;
        }
    }

    # default status is error
    public function _setResolvedPayment($id, $status = 3) {
        $this->WaitingPayment = ClassRegistry::init('WaitingPayment');
        $newData = array(
            'id'        => $id,
            'status'    => $status,
            'time'      => time()
        );
        $this->WaitingPayment->save($newData);
    }
}