<?php

/**
 * Wrap for Redis libary: https://core.telegram.org/
 **/
class BotTelegram {

    protected $token = "612122610:AAGf477qu8IX0erRw6Ci3D2qFenRGfoNTV8";
    protected $chat_id = '-302159231';

    public function __construct($chat_id)
    {
        $this->chat_id  = $chat_id;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getChatId()
    {
        return $this->chat_id;
    }

    public function pushNotify($text){
        $data = [
            'chat_id' => $this->getChatId(),
            'text' => $text
        ];

        $url = "https://api.telegram.org/bot" . $this->getToken() . "/sendMessage?" . http_build_query($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 1s

        curl_exec($ch);

        if (curl_error($ch)) {
            return false;
        }

        curl_close($ch);
        return true;
    }
}