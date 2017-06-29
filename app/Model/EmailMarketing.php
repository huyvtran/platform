<?php

App::uses('AppModel', 'Model');
App::uses('AppEmail', 'Network/Email');
App::import('Lib', 'RedisCake');

class EmailMarketing extends AppModel {

	public $recursive = -1;

	public $validationDomain = 'not_translate';
	
	public $actsAs = array(
		'Utils.Serializable' => array(
			'field' => 'data'
		),
		'Search.Searchable'
	);
	
	public $validate = array(
		'type' => array(
			'rule' => 'notEmpty',
			'required' => true,
			'allowEmpty' => false
		),
		'title' => array(
			'rule' => 'notEmpty',
			'required' => true,
			'allowEmpty' => false,
			'message' => 'The title is empty'
		),
		'game_id' => array(
			'rule'=> 'notEmpty',
			'required'=> true,
			'allowEmpty' => false,
			'message' => 'Please choose a game.'
		)
	);

	public $belongsTo = array('User', 'Game');

	public $filterArgs = array(
		'game_id' => array('type' => 'value', 'field' => 'game_id'),
	);

	const SEND_COMPLETED = 4; # all notifications was pushed
	const SEND_QUEUED = 3; # all notifications was queued
	const SEND_QUEUEING = 2; # pushing to amazon sqs
	const SEND_PUSHLISHED = 1; # published
	const SEND_WAIT = 0; # wait for pushlish

	const TYPE_ALL = 0;
	const TYPE_GIFTCODE = 1;

	public function afterValidate()
	{
		if (	!empty($this->data['EmailMarketing']['data']['addresses'])
			&& 	!empty($this->data['EmailMarketing']['data']['giftcodes'])
		) {
			$addresses = explode("\n", $this->data['EmailMarketing']['data']['addresses']);
			$giftcodes = explode("\n", $this->data['EmailMarketing']['data']['giftcodes']);
			$addresses = array_filter($addresses);
			$giftcodes = array_filter($giftcodes);
			foreach($addresses as $k => $v) {
				$addresses[$k] = trim($v, " \t\n\r\0\x0B.");
			}

			foreach($giftcodes as $k => $v) {
				$giftcodes[$k] = trim($v, " \t\n\r\0\x0B.");
			}

			$this->data['EmailMarketing']['data']['addresses'] = implode("\n", $addresses);
			$this->data['EmailMarketing']['data']['giftcodes'] = implode("\n", $giftcodes);
		}
		return true;
	}

    public function beforeSave($options = array()) {
        if (!empty($this->data['EmailMarketing']['body'])) {
            $LinkTracking = ClassRegistry::init('LinkTracking');
            $this->contain('Game', 'Game.Website');
            $email = $this->findById($this->id);

            $this->data['EmailMarketing']['parsed_body'] = $this->data['EmailMarketing']['body'];

            $doc = new DOMDocument();
            @$doc->loadHTML('<meta http-equiv="content-type" content="text/html; charset=utf-8">'.$this->data['EmailMarketing']['body']);

            $links = $doc->getElementsByTagName('a');
            foreach ($links as $a) {
                $href = $a->getAttribute('href');

                if ( substr($href, 0, 7) == 'mailto:'
                    || $href == '@unsubscribeLink'
                    || $LinkTracking->hasAny(array('convert_link' => $href))
                ) {
                    continue;
                }

                $conditions = array(
                    'model' => 'EmailMarketing',
                    'foreign_key' => $this->id,
                    'original_link' => $href,
                    'type' => 1
                );
                if (!$LinkTracking->hasAny($conditions)) {
                    $data = array('LinkTracking' => $conditions);

                    $LinkTracking->create();
                    $LinkTracking->save($data);

                    $data['LinkTracking']['convert_link']
                        = Router::url(array(
                        'controller' => 'LinkTrackings',
                        'action' => 'track',
                        'admin' => false,
                        $this->hashStr($LinkTracking->id)
                    ), true);

                    $LinkTracking->save($data);
                } else {
                    $data = $LinkTracking->find('first', array('conditions' => $conditions));
                }

                $a->setAttribute('href', $data['LinkTracking']['convert_link']);
            }

            $body = $doc->getElementsByTagName('body')->item(0);
            $gif = $doc->createElement('img');

            $src = Router::url(array(
                'controller' => 'LinkTrackings',
                'action' => 'open',
                'admin' => false,
                $this->hashStr($this->id)
            ), true);
            $gif->setAttribute('src', $src);
            $body->appendChild($gif);

            $this->data['EmailMarketing']['parsed_body'] = $doc->saveHTML();
        }
        return true;
    }

	public function afterSave($created)
	{
		Cache::clear(false, 'email');
	}

	public function afterDelete()
	{
		Cache::clear(false, 'email');	
	}

    function paginateCount($conditions = array(), $recursive = 0, $extra = array())
    {
        $parameters = compact('conditions');
        if ($recursive != $this->recursive) {
            $parameters['recursive'] = $recursive;
        }

        $extra['recursive'] = -1;
        $extra['contain'] = array();
        if(isset($conditions['Game.id'])){
            $extra['contain'] = array_merge( $extra['contain'], array('Game') );
        }

        return $this->find('count', array_merge($parameters, $extra));
    }

    /**
     * https://support.google.com/mail/answer/81126?topic=12838
     **/
    public function send($id, $address, $params = array(), $test = false)
    {
        $email = Cache::read('info_email_game_' . $id, 'email');
        if (empty($email)) {
            $this->contain(array('Game' => array('Website')));
            $email = $this->findById($id);
            Cache::write('info_email_game_' . $id, $email, 'email');
        }

        if (!empty($email['EmailMarketing']['parsed_body'])) {
            $emailBody = strtr($email['EmailMarketing']['parsed_body'], $params);
        } else {
            $emailBody = strtr($email['EmailMarketing']['body'], $params);
        }

        $emailBody = $this->parseUnsubcribe($emailBody, $address, $email['Game']['id'], $email['Game']['Website']['url']);

        # Gắn email gửi vào từng link track trong email
        $LinkTracking = ClassRegistry::init('LinkTracking');
        $links = $LinkTracking->find('list', array(
            'conditions' => array(
                'foreign_key' => $email['EmailMarketing']['id'],
                'model' => 'EmailMarketing',
            ),
            'fields' => array('id', 'convert_link'),
        ));

        foreach ($links as $value) {
            $new_link = $value . '/' . $this->hashStr($address);
            $emailBody = str_replace($value, $new_link, $emailBody);
        }
        # Gắn email gửi vào link ảnh để track view trong email
        if (!isset($img) && empty($img)) {
            $doc = new DOMDocument();
            @$doc->loadHTML($emailBody);
            $img = $doc->getElementsByTagName('img');
        }
        foreach ($img as $i) {
            $src = $i->getAttribute('src');
            if (strpos($src, '/LinkTrackings/open/') != false) {
                $new_src = $src . '/' . $this->hashStr($address);
                $emailBody = str_replace($src, $new_src, $emailBody);
            }
        }

        try {
            $config = "amazonses";
            $Redis = new RedisCake('action_count');
            $count_redis = $Redis->get('count_email_marketing_all_game');
            if( is_numeric($count_redis) ){
                $check_config_email = $count_redis%2 ;
                switch ($check_config_email){
                    case 1:
                        $config = "amazonses1";
                        break;
                }
            }

            $Email = new AppEmail($config);
            $from = array( key($Email->getConfig()['from']) => $email['Game']['title']);

            if ($email['EmailMarketing']['total'] > 10000) {
                $Email->addHeaders(array('Precedence' => 'bulk'));
            }

            $Email->addHeaders(array('emailid' => $id));
            $title = strtr($email['EmailMarketing']['title'], $params);
            $Email->to($address)
                ->subject($title)
                ->from($from)
                ->emailFormat('html');

            if ($test) {
                $Email->viewVars(array('forceSend' => true));
            }
            $sendMail = $Email->send($emailBody);
        }catch (Exception $e){
            CakeLog::error('error send mail mkt - ' . $e->getMessage());
        }
        return true;
    }

    public function parseUnsubcribe($content, $address, $gameId, $websiteUrl = null)
    {
        if (empty($websiteUrl)) {
            $websiteUrl = 'muriot.com';
        }
        # replace @unsubscribe[wordforunsubscribe]
        preg_match_all('/\@unsubscribe\[([^\]]*)\]/i', $content, $matches);
        if (!empty($matches[0])) {

            $token = $this->unsubscribleToken($address);

            foreach($matches[0] as $k => $match) {
                $replaces[$match] = "<a href='http://" . $websiteUrl . "/emailFeedbacks/unsubscribe?e="
                    . $this->hashStr($address) . "&t=" . $token . "&g=" . $this->hashStr($gameId) . "'>" . $matches[1][$k] . "</a>";
            }

            $content = strtr($content, $replaces);
        }

        preg_match_all('/\@unsubscribeLink/i', $content, $matches);
        if (!empty($matches[0])) {

            $token = $this->unsubscribleToken($address);

            foreach($matches[0] as $k => $match) {
                $replaces[$match] = "http://" . $websiteUrl . "/emailFeedbacks/unsubscribe?e="
                    . $this->hashStr($address) . "&t=" . $token . "&g=" . $this->hashStr($gameId);
            }

            $content = strtr($content, $replaces);
        }
        return $content;
    }

    public function unsubscribleToken($email)
    {
        return md5(Configure::read('Security.salt') + Security::hash($email, null, true));
    }

    public function hashStr($str)
    {
        $base64 = base64_encode(Security::rijndael($str, 'muriot@quan#123muriot@quan#123muriot@quan#123', 'encrypt'));
        return rtrim(strtr($base64, '+/', '-_'), '=');
    }

    public function unhashStr($str)
    {
        $base64decode = base64_decode(str_pad(strtr($str, '-_', '+/'), strlen($str) % 4, '=', STR_PAD_RIGHT));
        return Security::rijndael($base64decode, 'muriot@quan#123muriot@quan#123muriot@quan#123', 'decrypt');
    }
}
