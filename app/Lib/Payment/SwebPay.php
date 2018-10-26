<?php
/**
 * Created by PhpStorm.
 * User: HONG QUAN
 * Date: 6/15/2018
 * Time: 2:12 PM
 */

# trang chủ https://swebvn.com/
class SwebPay
{
    protected $uid ;
    protected $secret_key ;

    public function __construct()
    {
        $this->uid = '18891';
        $this->secret_key = '209d4462b0d8daa1e49f81315e768df4';
    }

    /**
     * @return string
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * @return string
     */
    public function getSecretKey()
    {
        return $this->secret_key;
    }

    public function sign($data){
        unset($data['price']);
        unset($data['note']);
        $sign_data = implode("", $data) . $this->getSecretKey();
        $sign = hash("sha256", $sign_data );
        return $sign;
    }

    public function Execute($url, $data = false){
        if( $data ) {
            $url .= '?' . http_build_query($data);
        }
        CakeLog::info('sweb url:' . print_r($url, true), 'payment');

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);

        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ( !empty($result) && $status==200){
            return json_decode($result, true);
        }
        return false;
    }

    public function status(){
        $url = "http://35.234.61.169/status2.php";
        $result = $this->Execute($url);
        return $result;
    }

    public function checkout($data){
        $url = "http://35.234.61.169/api/cardcharging2";
        $sign = $this->sign($data);
        $data = array_merge($data, array('mac' => $sign));
        $result = $this->Execute($url, $data);
        return $result;
    }

    public function getTransaction($data){
        $url = "http://35.234.61.169/check_card.php";
        $result = $this->Execute($url, $data);
        return $result;
    }
}