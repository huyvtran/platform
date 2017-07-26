<?php
App::uses('Component', 'Controller');
App::uses('HttpSocket', 'Network/Http');
App::uses('Utility', 'Lib');

class CommonComponent extends Component {

	public $components = array(
		'Session', 'Auth'
	);

	function __construct(ComponentCollection $collection, $settings = array())
	{
		parent::__construct($collection, $settings);
	}
	
	public function initialize(Controller $controller)
	{
		$this->Controller = $controller;
	}


	/**
	 * Get current game info
	 * Need use cache in this function
	 * auto set variable "currentGame" in View
	 **/
	public function currentGame($field = null)
	{
		$appkey = $this->Controller->request->header('app');
		# get current game from SDK
		if ($appkey) {
			$game = Cache::read('current_game_app_' . $appkey, 'info');
			if ($game === false) {
				$this->Controller->loadModel('Game');
				$this->Controller->Game->contain();
				$game = $this->Controller->Game->findByApp($appkey);
				Cache::write('current_game_app_' . $appkey, $game, 'info');
			}

		} else {
			# get current game from website
			$websiteId = $this->currentWebsite('id');
			$game = Cache::read('current_game_game_from_website' . $websiteId, 'info');
			if ($game === false) {
				$this->Controller->loadModel('Game');
				$games = $this->Controller->Game->find('all', array(
					'conditions' => array(
						'website_id' => $websiteId
					),
					'contain' => array('Website')
				));
				if (!empty($games)) {
					if (count($games) > 1) {
						foreach($games as $v) {
							if ($v['Game']['os'] == 'ios') {
								$game = $v;
								break;
							}
						}
					}
					if (empty($game)) {
						$game = $games[0];
					}

					Cache::write('current_game_game_from_website' . $websiteId, $game, 'info');
				}
			}
		}

		if (empty($game)) {
			return false;
		} else {
			$this->Controller->set('currentGame', $game['Game']);
		}

		if ($field) {
			if (isset($game['Game'][$field])) {
				return $game['Game'][$field];
			}
			return false;
		}

		return $game['Game'];
	}

	/**
	 * Get current website info
	 * Need use cache in this function
	 **/
	public function currentWebsite($field = null)
	{
		$appkey = $this->Controller->request->header('app');

		# this request from SDK if headers contain mobgame_appkey
		if ($appkey) {
			$game = Cache::read('current_website_game_appkey_' . $appkey, 'info');

			if ($game === false) {
				$this->Controller->loadModel('Game');
				$this->Controller->Game->contain(array('Website'));
				$game = $this->Controller->Game->findByApp($appkey);
				Cache::write('current_website_game_appkey_' . $appkey, $game, 'info');
			}

			if ($game === false) {
				throw new BadRequestException('This appkey is not exist');
			}

			if (!empty($game['Website'])) {
				$website = $game['Website'];
			}
		} else {

			$website = Cache::read('current_website_website_servername_' . env('SERVER_NAME'), 'info');

			if ($website === false) {
				$this->Controller->loadModel('Website');
				$this->Controller->Website->contain();
				$website = $this->Controller->Website->findByUrlOrUrl2(env('SERVER_NAME'), env('SERVER_NAME'));

				if (!empty($website['Website'])) {
					$website = $website['Website'];
					Cache::write('current_website_website_servername_' . env('SERVER_NAME'), $website, 'info');
				}
			}
		}

		if (empty($website)) {
			return false;
		} else {
			# these variables always show on game website (CMS)
			$this->Controller->set('currentWebsite', $website);
		}

		if ($field) {
			if (isset($website[$field])) {
				return $website[$field];
			}
			return false;
		}
		return $website;
	}

	/**
	 * First priority is game's theme in database , and next to website theme
	 **/
	public function setTheme()
	{
		$this->Controller->theme = $this->getTheme();
	}

	public function getTheme()
	{
		if ($this->Controller->request->header('app')) {
			return $this->currentGame('app_theme');

		} elseif (!$this->Controller->request->header('app')) {

			$MobileDetect = new Mobile_Detect();

			# developer use param get mobile and desktop to force theme
			if ($this->Controller->request->query('mobile')) {
				$theme = $this->currentWebsite('theme_mobile');
			} elseif ($this->Controller->request->query('desktop')) {
				$theme = $this->currentWebsite('theme');
			} elseif ($this->Controller->request->query('theme')) {
				$theme = $this->Controller->request->query('theme');
			}

			# if no theme is set from above conditions
			if (empty($theme)) {
				if ($MobileDetect->isTablet() || $MobileDetect->isMobile()) {
					$theme = $this->currentWebsite('theme_mobile');
				} else {
					$theme = $this->currentWebsite('theme');
				}

			} else {
				# disable cache for develop
				Configure::write('Cache.disable', true);
			}

			if ($theme) {
				return $theme;
			}
		}
	}

	public function setLanguage()
	{
		$lang = $this->currentWebsite('lang');

		if (!empty($lang)) {
			Configure::write('Config.language', $lang);
		} else {

		}
	}

	public function setReferer($referer = null)
	{
		if (!$referer)
			$referer = $this->Controller->referer(null, true);
		if (Router::normalize($this->Auth->loginAction) != $referer)
			$this->Session->write('Referer',$referer);
	}

	public function publicClientIp()
	{
		$ip = $this->Controller->request->clientIp();
		if ($this->isPrivateIp($ip)) {
			$ip = $this->Controller->request->clientIp(false);
		}

		if( !empty($this->Controller->request->data['ip']) ){
		    $ip = $this->Controller->request->data['ip'];
        }
		return $ip;
	}

	public function isPrivateIp($ip)
	{
		$pri_addrs = array(
			'192.168.0.0|192.168.255.255',
			'127.0.0.0|127.255.255.255'
		);

		$long_ip = ip2long($ip);
		if($long_ip != -1) {
			foreach($pri_addrs AS $pri_addr)
			{
				list($start, $end) = explode('|', $pri_addr);

				// IF IS PRIVATE
				if($long_ip >= ip2long($start) && $long_ip <= ip2long($end))
					return true;
			}
		}

		return false;
	}

    /**
     * Generate code as token and redirect to location
     * @param int $continueUrl redirect to this url and append code token query
     **/
    public function oauthRedirect($appId, $userId, $continueUrl)
    {
        $code = $this->oauthGenerateCode($appId, $userId);
        if ($parsedUrl = parse_url($continueUrl)) {
            if ($parsedUrl['path'] == null) {
                $continueUrl .= '/';
            }
            $separator = empty($parsedUrl['query']) ? '?' : '&';
            $continueUrl .= $separator . "code=$code";
        }

        return $this->Controller->redirect($continueUrl);
    }

    /**
     * Generate code for Oauth flow
     * @param int $appId game ID , use for check app secret after
     * @param int $userId user currently login
     **/
    public function oauthGenerateCode($appId, $userId)
    {
        $this->Controller->loadModel('AuthorizationCode');
        $code = $this->Controller->AuthorizationCode->generateCode($appId, $userId);
        return $code;
    }

    /**
     * Check code and secret use for server <-> server to get userInfo
     * @param string $code generated at CommonComponent::oauthGenerateCode
     * @param string $secret get from table games secret_key field,
     * 						 app request userInfo, must provider this sercret
     **/
    public function oauthReturnUserInfo($code = null, $secret = null)
    {
        if (empty($code) || empty($secret)) {
            return array();
        }
        $this->Controller->loadModel('AuthorizationCode');

        $code = $this->Controller->AuthorizationCode->find('first', array(
            'conditions' => array(
                'code' => $this->Controller->request->query('code')
            ),
            'contain' => array(
                'Game' => array('conditions' => array('secret_key' => $secret)),
                'User' => array('Profile')
            )
        ));

        if (empty($code)) {
            throw new BadRequestException('Code is invalid');
        }

        if (!empty($code['Game']) && !empty($code['User'])) {
            if( !empty($code['Game']) ) unset($code['Game']);
            if( !empty($code['AuthorizationCode']) ) unset($code['AuthorizationCode']);
            if( isset($code['User']['Profile']) ) unset($code['User']['Profile']);
            
            # you can add additional info, but avoid to modify any field in here
            return $code;
        }

        return array();
    }

	public function bruteForce($fields, $rangeTime, $blockAfterTimes)
	{
		if (is_array($fields)) {
			$key = implode("_", $fields);
		} else {
			$key = $fields;
		}

		App::import('Lib', 'RedisCake');
		$Redis = new RedisCake('action_count');
		$Redis->incr($key);
		$ttl = $Redis->ttl($key);

		if ($ttl < 0 || $ttl > $rangeTime) {
			$Redis->expire($key, $rangeTime);
		}
		$count = $Redis->get($key);
		if ($count > $blockAfterTimes) {
			throw new Exception("Bạn không thể thực hiện hành động này");
			CakeLog::error("Bạn không thể thực hiện hành động này");
		}
	}

	public function getAccount(){
        $user = $this->Auth->user();
        $game = $this->currentGame();

        if (empty($user) || empty($game)) {
            throw new BadRequestException('Account is invalid');
        }

        $this->Controller->loadModel('Account');
        $this->Controller->Account->contain();
        $account = $this->Controller->Account->findAllByUserIdAndGameId( $user['id'], $game['id'] );

        if (empty($account)) {
            throw new BadRequestException('Can not found account');
        }
        $accountId = $account[0]['Account']['id'];
        if (!empty($account[0]['Account']['account_id'])) {
            $accountId = $account[0]['Account']['account_id'];
        }
        return $accountId;
    }

	public function encryptBlowfish($str){
		$key = 'dontchangethiswordsdontchangethiswords';

		$result = mcrypt_encrypt(MCRYPT_BLOWFISH, $key, $str, MCRYPT_MODE_ECB );
		$result = base64_encode($result);
		return $result ;
	}

	public function decryptBlowfish($str){
		$key = 'dontchangethiswordsdontchangethiswords';

		$result = base64_decode($str);
		$result = mcrypt_decrypt(MCRYPT_BLOWFISH, $key, $result, MCRYPT_MODE_ECB);
		$result = trim($result, " \x00..\x1F");
		return $result;
	}
}
