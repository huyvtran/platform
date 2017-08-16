<?php

class Hanoipay {
    
//	private $accesskey  = '041d1d28a3e6195c60a517319feb04d2';
//	private $secret     = '5af8db65db50eac2e15faf557c532e3c';

    private $accesskey  = '15a22563cc3803db40dc47643e5571e3';
    private $secret     = '32685ea89936f4000f801b52a4d964c7';

    private $pin;
    private $seri;
    private $card_type;

    private $user_id;
    private $game_id;
    private $order_id;

    private $note = '';

    public function getPin() {
        return $this->pin;
    }

    public function getSeri() {
        return $this->seri;
    }

    public function getCardType() {
        return $this->card_type;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getGameId() {
        return $this->game_id;
    }

    public function getOrderId() {
        return $this->order_id;
    }

    public function getNote() {
        return $this->note;
    }

    public function setPin($pin) {
        $this->pin = $pin;
    }

    public function setSeri($seri) {
        $this->seri = $seri;
    }

    public function setCardType($card_type) {
        $this->card_type = $card_type;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setGameId($game_id) {
        $this->game_id = $game_id;
    }

    public function setOrderId($order_id) {
        $this->order_id = $order_id;
    }

    public function setNote($note) {
        $this->note = $note;
    }

	public function cardCharging() {
        try {
            $this->saveVippay();

            $sign = md5(
                $this->accesskey
                . $this->getPin()
                . $this->getSeri()
                . $this->getOrderId()
                . $this->getCardType()
                . $this->secret
            );
            $fields = array(
                'type'      => $this->getCardType(),
                'transid'   => $this->getOrderId(),
                'accesskey' => $this->accesskey,
                'pin'       => $this->getPin(),
                'serial'    => $this->getSeri(),
                'signature' => $sign
            );
            $fields = json_encode($fields);

            $ch = curl_init("http://card.hanoipay.com/api/card/v2/topup");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/json',
                    'Content-Length: ' . strlen($fields))
            );
            $result = curl_exec($ch);
            $result = json_decode($result);
            return $result;
        }catch (Exception $e){
            CakeLog::error('error call api hanoipay - ' . $e->getMessage() , 'payment');
        }

        return false;
    }

    # lưu bảng vippay với type 2
    private function saveVippay(){
        App::import('Model', 'Payment');
        ClassRegistry::init('Payment');

        App::import('Lib', 'RedisQueue');
        $Redis = new RedisQueue();
        $Redis->rPush(array(
            'model' => 'Vippay',
            'data' => array(
                'order_id'  => $this->getOrderId(),
                'user_id'   => $this->getUserId(),
                'game_id'   => $this->getGameId(),
                'type'      => Payment::CHANEL_HANOIPAY,
                'card_code' => $this->getPin(),
                'card_serial'   => $this->getSeri()
            )
        ));
    }
    
    public function call($data){
        App::import('Model', 'Payment');
        ClassRegistry::init('Payment');

        $type_hanoipay = '';
        if( $data['type'] == Payment::TYPE_NETWORK_VIETTEL ) $type_hanoipay = "VIETTEL";
        if( $data['type'] == Payment::TYPE_NETWORK_MOBIFONE ) $type_hanoipay = "MOBI";
        if( $data['type'] == Payment::TYPE_NETWORK_VINAPHONE ) $type_hanoipay = "VINA";
        if( $data['type'] == Payment::TYPE_NETWORK_GATE ) $type_hanoipay = "GATE";
        $this->setCardType($type_hanoipay);

        $this->setOrderId($data['order_id']);

        $this->setPin($data['card_code']);
        $this->setSeri($data['card_serial']);

        $this->setUserId($data['user_id']);
        $this->setGameId($data['game_id']);

        $resultHanoipay = $this->cardCharging();
        if( isset($resultHanoipay->Status) && $resultHanoipay->Status == 1 ){
            # update trạng thái thành công
            $result = array(
                'status'    => 0,
                'messsage'  => 'success',
                'data'      => array(
                    'obj'   => $resultHanoipay,

                    'time'  => time(),
                    'type'  => $data['type'],
                    'chanel'    => $data['chanel'],
                    'note'      => $resultHanoipay->Description,

                    'order_id'  => $this->getOrderId(),
                    'user_id'   => $this->getUserId(),
                    'game_id'   => $this->getGameId(),

                    'card_code' => $this->getPin(),
                    'price'     => $resultHanoipay->Amount,
                    'card_serial'   => $this->getSeri()
                )
            );
        } elseif (isset($resultHanoipay->Status)
            && in_array( $resultHanoipay->Status, array( -24, -96, -98, -999) )
        ){
            # trạng thái chưa xác định chờ hệ thống thanh toán trả về
            $result = array(
                'status'    => 2,
                'messsage'  => 'chưa xác định chờ hệ thống thanh toán trả về',
                'data'      => array(
                    'obj'   => $resultHanoipay,
                    'time'  => time(),
                    'type'  => $data['type'],
                    'chanel'    => $data['chanel'],
                    'order_id'  => $this->getOrderId(),
                    'card_code' => $this->getPin(),
                    'card_serial'   => $this->getSeri()
                )
            );
        }else{
            # update trạng thái thẻ lỗi hoặc đã được sử dụng
            $result = array(
                'status'    => 1,
                'messsage'  => 'thẻ lỗi hoặc đã được sử dụng',
                'data'      => array(
                    'obj'   => $resultHanoipay,
                    'time'  => time(),
                    'type'  => $data['type'],
                    'chanel'    => $data['chanel'],
                    'order_id'  => $this->getOrderId(),
                    'card_code' => $this->getPin(),
                    'card_serial'   => $this->getSeri()
                )
            );
        }

        CakeLog::info('check paygate hanoipay:' . print_r($resultHanoipay, true), 'payment');
        
        return $result;
    }
}