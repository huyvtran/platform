<?php

/**
 * Wrap for Redis libary: https://core.telegram.org/
 **/
class BotTelegram {

    protected $token = "612122610:AAGf477qu8IX0erRw6Ci3D2qFenRGfoNTV8";
    protected $chat_id = '-302159231';

	public function __construct($token, $chat_id)
	{
	    $this->token    = $token;
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
        file_get_contents("https://api.telegram.org/bot" . $this->getToken() . "/sendMessage?" . http_build_query($data) );
    }
}