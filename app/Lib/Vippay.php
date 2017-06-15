<?php

class Vippay {
    
    private $merchant_id = 8945;
	private $api_user = '6433e60201c2412ca9d211ed2d9a8caa';
	private $api_password = 'f3197fbb40b748e9b6123cf2739bbdf2';

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

            $fields = array(
                'merchant_id' => $this->merchant_id,
                'pin' => $this->getPin(),
                'seri' => $this->getSeri(),
                'card_type' => $this->getCardType(),
                'note' => $this->getNote()
            );

            $ch = curl_init("https://vippay.vn/api/api/card");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_USERPWD, $this->api_user . ":" . $this->api_password);
            $result = curl_exec($ch);
            $result = json_decode($result);
            return $result;
        }catch (Exception $e){
            CakeLog::error('error call api vippay - ' . $e->getMessage());
        }

        return false;
    }

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
                'type'      => Payment::CHANEL_VIPPAY,
                'card_code' => $this->getPin(),
                'card_serial'   => $this->getSeri()
            )
        ));
    }
    
    public function call($data){
        App::import('Model', 'Payment');
        ClassRegistry::init('Payment');
        
        $this->setPin($data['card_code']);
        $this->setSeri($data['card_serial']);

        $type_vippay = '';
        if( $data['type'] == Payment::TYPE_NETWORK_VIETTEL ) $type_vippay = 1;
        if( $data['type'] == Payment::TYPE_NETWORK_MOBIFONE ) $type_vippay = 2;
        if( $data['type'] == Payment::TYPE_NETWORK_VINAPHONE ) $type_vippay = 3;
        $this->setCardType($type_vippay);

        $this->setUserId($data['user_id']);
        $this->setGameId($data['game_id']);
        $this->setOrderId($data['order_id']);

        $resultVippay = $this->cardCharging();
        if(isset($resultVippay->code) && $resultVippay->code == 0 && $resultVippay->info_card >= 10000){
            # update trạng thái thành công
            $result = array(
                'status'    => 0,
                'messsage'  => 'success',
                'data'      => array(
                    'obj'   => $resultVippay,

                    'time'  => time(),
                    'type'  => $data['type'],
                    'chanel'    => $data['chanel'],
                    'note'      => $this->getNote(),

                    'order_id'  => $this->getOrderId(),
                    'user_id'   => $this->getUserId(),
                    'game_id'   => $this->getGameId(),

                    'card_code' => $this->getPin(),
                    'price'     => $resultVippay->info_card,
                    'card_serial'   => $this->getSeri()
                )
            );
        } elseif (isset($resultVippay->code)
            && in_array( $resultVippay->code, array(1007, 1008, 1009) )
        ){
            # update trạng thái thẻ lỗi hoặc đã được sử dụng
            $result = array(
                'status'    => 1,
                'messsage'  => 'thẻ lỗi hoặc đã được sử dụng',
                'data'      => array(
                    'obj'   => $resultVippay,
                    'price' => $resultVippay->info_card,
                    'time'  => time(),
                    'type'  => $data['type'],
                    'chanel'    => $data['chanel'],
                    'order_id'  => $this->getOrderId(),
                    'card_code' => $this->getPin(),
                    'card_serial'   => $this->getSeri()
                )
            );
        }else{
            # trạng thái chưa xác định chờ hệ thống thanh toán trả về
            $result = array(
                'status'    => 2,
                'messsage'  => 'chưa xác định chờ hệ thống thanh toán trả về',
                'data'      => array(
                    'obj'   => $resultVippay,
                    'price' => $resultVippay->info_card,
                    'time'  => time(),
                    'type'  => $data['type'],
                    'chanel'    => $data['chanel'],
                    'order_id'  => $this->getOrderId(),
                    'card_code' => $this->getPin(),
                    'card_serial'   => $this->getSeri()
                )
            );
        }

        CakeLog::info('check paygate vippay:' . print_r($resultVippay, true), 'payment');
        
        return $result;
    }
}