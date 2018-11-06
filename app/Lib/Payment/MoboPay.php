<?php
/**
 * Created by PhpStorm.
 * User: HONG QUAN
 * Date: 6/15/2018
 * Time: 2:12 PM
 */

# trang chủ https://nap.mobo.vn/
class MoboPay
{
    protected $client_id ;
    protected $secret_key ;

    public function __construct()
    {
        $this->client_id = '4be9kf99slavxguo72hy9c5sig2dvphp';
        $this->secret_key = 'ffb9a193d6b00d1fd03af671f001304f';
    }

    /**
     * @return string
     */
    public function getClientId()
    {
        return $this->client_id;
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
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $result = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ( !empty($result) && $status==200){
            return json_decode($result, true);
        }
        return false;
    }

    public function checkout($input){
        $data = 'clientid=' . $this->getClientId();
        $data .= '&serial=' . $input['card_seri'];
        $data .= '&pin=' . $input['card_code'];
        $data .= '&transid=' . $input['transid'];

        $url = "https://nap.mobo.vn/v3.0/recharge";
        $result = $this->Execute($url, $data);
        return $result;
    }
}