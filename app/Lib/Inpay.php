<?php
class Inpay {
    private $subcpId    = '0455';
    private $aeskey     = 'XPCQW6L28SHN0HSV';
    private $secret     = '0VIPEZ3KOW4B5L88W1PW';
    private $gameCode   = 'R01';

    private $code;
    private $serial;
    private $type;

    private $user_id;
    private $game_id;
    private $order_id;

    private $note = '';

    function __construct($subcpId, $aeskey, $secret)
    {
        $this->subcpId  = $subcpId ;
        $this->aeskey  = $aeskey ;
        $this->secret   = $secret ;
    }

    /**
     * @return string
     */
    public function getSubcpId()
    {
        return $this->subcpId;
    }

    /**
     * @return string
     */
    public function getAeskey()
    {
        return $this->aeskey;
    }

    /**
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * @return string
     */
    public function getGameCode()
    {
        return $this->gameCode;
    }

    /**
     * @param string $gameCode
     */
    public function setGameCode($gameCode)
    {
        $this->gameCode = $gameCode;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return mixed
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * @param mixed $serial
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getGameId()
    {
        return $this->game_id;
    }

    /**
     * @param mixed $game_id
     */
    public function setGameId($game_id)
    {
        $this->game_id = $game_id;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->order_id;
    }

    /**
     * @param mixed $order_id
     */
    public function setOrderId($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }

    public function pad($text, $block_size){
        $padding = $block_size - (strlen($text) % $block_size);
        $pattern = chr($padding);
        return $text . str_repeat($pattern, $padding);
    }

    public function unpad($data){
        $pattern = substr($data, -1);
        $length = ord($pattern);
        $padding = str_repeat($pattern, $length);
        $pattern_pos = strlen($data) - $length;

        if(substr($data, $pattern_pos) == $padding)
        {
            return substr($data, 0, $pattern_pos);
        }

        return $data;
    }

    public function encrypt($text){
        $enc = MCRYPT_RIJNDAEL_128;
        $mode = strtolower(MCRYPT_MODE_CBC);
        $key = $initVector = $this->getAeskey();
        $cipher = mcrypt_module_open($enc, '', $mode, '');
        $block_size = mcrypt_get_block_size($enc, $mode);

        mcrypt_generic_init($cipher, $key, $initVector);
        $encrypted_text = mcrypt_generic($cipher, $this->pad($text, $block_size));
        mcrypt_generic_deinit($cipher);
        return base64_encode($encrypted_text);
    }

    public function decrypt($text)
    {
        $enc = MCRYPT_RIJNDAEL_128;
        $mode = strtolower(MCRYPT_MODE_CBC);
        $key = $initVector = $this->getAeskey();
        $cipher = mcrypt_module_open($enc, '', $mode, '');

        mcrypt_generic_init($cipher, $key, $initVector);
        $decrypted_text = mdecrypt_generic($cipher, $text);
        mcrypt_generic_deinit($cipher);
        return $this->unpad($decrypted_text);
    }

    public function cardCharging() {
        try {
            $this->saveVippay();

            $fields = array(
                'subcpId'   => $this->getSubcpId(),
                'gameCode'  => $this->getGameCode(),
                'password'  => md5($this->getSerial() . $this->getCode() . $this->getSecret()),
                'serial'    => $this->encrypt($this->getSerial()),
                'code'      => $this->encrypt($this->getCode()),
                'telco'     => $this->getType(),
                'action'    => 'CHARGE',
                'extInfo'   => $this->getNote(),
                'transId'   => $this->getOrderId()
            );
            $fields = json_encode($fields);

            $ch = curl_init('http://api.inpay.vn/cardcharging');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $result = curl_exec($ch);
            $result = json_decode($result, true);
            curl_close($ch);
            return $result;
        }catch (Exception $e){
            CakeLog::error('error call api inpay - ' . $e->getMessage() , 'payment');
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
                'type'      => Payment::CHANEL_INPAY,
                'card_code' => $this->getCode(),
                'card_serial'   => $this->getSerial()
            )
        ));
    }

    public function call($data){
        App::import('Model', 'Payment');
        ClassRegistry::init('Payment');

        $type = '';
        if( $data['type'] == Payment::TYPE_NETWORK_VIETTEL ) $type = "VIETEL";
        if( $data['type'] == Payment::TYPE_NETWORK_MOBIFONE ) $type = "VMS";
        if( $data['type'] == Payment::TYPE_NETWORK_VINAPHONE ) $type = "GPC";
        #if( $data['type'] == Payment::TYPE_NETWORK_GATE ) $type_hanoipay = "GATE";
        $this->setType($type);

        $order_id = $this->getSubcpId() . $data['order_id'] ;
        $this->setOrderId($order_id);

        $this->setCode($data['card_code']);
        $this->setSerial($data['card_serial']);

        $this->setUserId($data['user_id']);
        $this->setGameId($data['game_id']);

        $resultInpay = $this->cardCharging();
        if( !empty($resultInpay) && $resultInpay['errorCode'] == 0){
            #restart redis
            $keyRedis = 'error-payment-inpay-' . $data['chanel'];
            App::import('Lib', 'RedisCake');
            $Redis = new RedisCake('action_count');
            $Redis->incr($keyRedis);
            $Redis->delete();

            # update trạng thái thành công
            $result = array(
                'status'    => 0,
                'messsage'  => 'success',
                'data'      => array(
                    'obj'   => $resultInpay,

                    'time'  => time(),
                    'type'  => $data['type'],
                    'chanel'    => $data['chanel'],

                    'order_id'  => $this->getOrderId(),
                    'user_id'   => $this->getUserId(),
                    'game_id'   => $this->getGameId(),

                    'card_code' => $this->getCode(),
                    'price'     => $resultInpay['amount'],
                    'card_serial'   => $this->getSerial()
                )
            );
        } elseif ( in_array( $resultInpay['errorCode'], array( 2, 10, 11, 12, 14, 49, 55) )
        ){
            #count redis
            $keyRedis = 'error-payment-inpay-' . $data['chanel'];
            App::import('Lib', 'RedisCake');
            $Redis = new RedisCake('action_count');
            $Redis->incr($keyRedis);
            $Redis->expire($keyRedis, 5*60);

            # trạng thái chưa xác định chờ hệ thống thanh toán trả về
            $result = array(
                'status'    => 2,
                'messsage'  => 'chưa xác định chờ hệ thống thanh toán trả về',
                'data'      => array(
                    'obj'   => $resultInpay,
                    'time'  => time(),
                    'type'  => $data['type'],
                    'chanel'    => $data['chanel'],
                    'order_id'  => $this->getOrderId(),
                    'card_code' => $this->getCode(),
                    'card_serial'   => $this->getSerial()
                )
            );
        }else{
            #count redis
            $keyRedis = 'error-payment-inpay-' . $data['chanel'];
            App::import('Lib', 'RedisCake');
            $Redis = new RedisCake('action_count');
            $Redis->incr($keyRedis);
            $Redis->expire($keyRedis, 5*60);

            # update trạng thái thẻ lỗi hoặc đã được sử dụng
            $result = array(
                'status'    => 1,
                'messsage'  => 'thẻ lỗi hoặc đã được sử dụng',
                'data'      => array(
                    'obj'   => $resultInpay,
                    'time'  => time(),
                    'type'  => $data['type'],
                    'chanel'    => $data['chanel'],
                    'order_id'  => $this->getOrderId(),
                    'card_code' => $this->getCode(),
                    'card_serial'   => $this->getSerial()
                )
            );
        }

        CakeLog::info('check paygate inpay:' . print_r($resultInpay, true), 'payment');

        return $result;
    }
}