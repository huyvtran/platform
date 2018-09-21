<?php
/**
 * Created by PhpStorm.
 * User: HONG QUAN
 * Date: 6/15/2018
 * Time: 2:12 PM
 */

# trang chủ https://shopdoithe.vn/api
class ShopCard
{
    protected $merchant_id ;
    protected $merchant_user ;
    protected $merchant_password ;

    public function __construct()
    {
        $this->merchant_id = 2679918349;
        $this->merchant_user = 'fehhx59vk1s9d1l';
        $this->merchant_password = 'd98a4c70b688c2d3e04f07deca00dacb993e51059a7384b27ba4abb678dd9de0';
    }

    /**
     * @return string
     */
    public function getMerchantId()
    {
        return $this->merchant_id;
    }

    /**
     * @return string
     */
    public function getMerchantUser()
    {
        return $this->merchant_user;
    }

    /**
     * @return string
     */
    public function getMerchantPassword()
    {
        return $this->merchant_password;
    }

    public function sign($data){
        if( !empty($data['card_type']) && in_array($data['card_type'], array(2, 3, 4) ) ) $data['card_amount'] = '';
        unset($data['note']);
        $sign_data = implode("|", $data);
        $sign = strtoupper(hash('sha256', $sign_data));
        return $sign;
    }

    public function Execute($url, $data = false){
        if( $data ) {
            $url .= '?' . http_build_query($data);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        #curl_setopt($ch, CURLOPT_TIMEOUT, 5);

        $result = curl_exec($ch);
        $result = str_replace("\xEF\xBB\xBF",'',$result);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        CakeLog::info('result:' . print_r($result, true), 'payment');
        if ( !empty($result) && $status==200){
            return json_decode($result, true);
        }
        return false;
    }

    public function checkout($data){
        $url = "https://shopdoithe.vn/api/card";
        $sign = $this->sign($data);
        $data = array_merge($data, array('sign' => $sign));
        $result = $this->Execute($url, $data);
        return $result;
    }

    public function getTransaction($data){
        $url = "https://shopdoithe.vn/api/cardresult";
        $sign = $this->sign($data);
        $data = array_merge($data, array('sign' => $sign));
        $result = $this->Execute($url, $data);
        return $result;
    }
}