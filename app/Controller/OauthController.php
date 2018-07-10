<?php

App::uses('AppController', 'Controller');

class OauthController extends AppController {

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow(array(
			'userInfo', 'api_userAuthen', 'token', 'getGame',
            'api_tracking_install', 'api_list_game', 'api_userList',
            'api_registerDeviceNotify'
		));
	}

	public function api_userAuthen()
	{
		$data = $this->userInfo(true);
		$data['User'] = $data['user'];
		unset($data['user']);
		$result = array(
			'status' => 0,
			'message' => 'OK',
			'data' => $data
		);
		$this->set('result', $result);
		$this->set('_serialize', 'result');			
	}

	public function userInfo($return = false)
	{
        $app = 'app';
        $token  = 'token';
		$this->loadModel('AccessToken');
		$this->loadModel('Game');
		$this->loadModel('Account');

        if($this->request->header($app)){
            $appKey = $this->request->header($app);
        }

        if($this->request->header($token)){
            $accessToken = $this->request->header($token);
        }

		if ($this->request->query('app_key')) {
			$appKey = $this->request->query('app_key');
		} elseif ($this->request->query('appkey')) {
			$appKey = $this->request->query('appkey');
		} elseif ($this->request->query('app')) {
			$appKey = $this->request->query('app');
		}

        if ($this->request->query('access_token'))
		    $accessToken = $this->request->query('access_token');

		if (!isset($appKey, $accessToken)) {
			throw new BadRequestException();
		}

		$this->AccessToken->contain(array('User'));
		$token = $this->AccessToken->findByToken($accessToken);
		if (empty($token) || empty($token['User'])) {
			throw new BadRequestException('Invalid Token');
		}

		$this->Game->contain();
		$game = $this->Game->find('first', array(
			'conditions' => array('app' => $token['AccessToken']['app'])
		));
		if (empty($game)) {
			throw new BadRequestException('Can not found this game');
		}

		$this->Account->contain();
		$account = $this->Account->findAllByGameIdAndUserId($this->Account->Game->getSimilarGameId($game['Game']['id']), $token['User']['id']);

		if (empty($account)) {
			throw new BadRequestException('Can not found account ');
		}

		$accountId = $account[0]['Account']['id'];
		if (!empty($account[0]['Account']['account_id'])) {
			$accountId = $account[0]['Account']['account_id'];
		}

        $username = $token['User']['username'];
        if( !empty($game['Game']['data']['prefix']) ) $username = substr($token['User']['username'], strlen($game['Game']['data']['prefix']));

		$result = array(
			'user' => array(
                'user_id' 		=> $token['User']['id'],
				'account_id' 	=> $accountId,
				'role' 			=> $token['User']['role'],
				'email' 		=> $token['User']['email'],
				'user_name' 	=> $username,
				'full_name' 	=> $token['User']['email'],
				'last_action'	=> $token['User']['last_action'],
                'coin'	        => $token['User']['payment'],
				'payment'	        => $token['User']['payment'],
				'phone'	    	=> $token['User']['phone'],
			)
		);

		if ( $return ) {
			return $result;
		}
		$this->set('result', $result);
		$this->set('_serialize', 'result');	
	}

	public function getLogin()
	{
		$result = $this->Log->logLogin();
        if ($result) {
            $result = array(
                'status' => 0,
                'message' => 'OK'
            );
        } else {
            $result = array(
                'status' => 1,
                'message' => 'Params is missing'
            );
        }
		$this->set('result', $result);
		$this->set('_serialize', 'result');
		return $result;
	}

	public function getCharacter()
	{
		$result = $this->Log->logEntergame();
		if ($result) {
			$result = array(
				'code' => 1,
				'message' => 'OK'
			);
		} else {
			$result = array(
				'code' => 2,
				'message' => 'Params is missing'
			);
		}
		$this->set('result', $result);
		$this->set('_serialize', 'result');
	}

    public function login()
    {
        $this->loadModel('User');
        $this->set('title_for_layout', 'Login');

        if ($this->request->is('post')) {
            $this->Auth->login();

            # Nếu user không thể login bằng email , check username
            if (!$this->Auth->user() && !empty($this->request->data['User']['email'])) {
                $tempEmail = $this->request->data['User']['email'];
                if ($user = $this->User->findByUsername($this->request->data['User']['email'])) {
                    $this->request->data['User']['email'] = $user['User']['email'];

                    $this->Auth->login();
                    if (!$this->Auth->user()) {
                        $this->request->data['User']['email'] = $tempEmail;
                    }
                }
            }
            if (!$this->Auth->user()) {
                $this->User->validationErrors['email'] = 'jsdfljslfkj';
                $this->User->validationErrors['password'] = 'jsdfljslfkj';
                $this->Session->setFlash(__('Email/Tên đăng nhập và/hoặc mật khẩu không đúng'), 'error');
                unset($this->request->data['User']['password']);
                //Tên hoặc password nhập không đúng. Xin hãy thử lại.
            }

            if (isset($this->request->params['named']['return_to'])) {
                $this->set('return_to', urldecode($this->request->params['named']['return_to']));
            } else {
                $this->set('return_to', false);
            }
        }

        if ($this->Auth->user()) {
            $this->loadModel('AuthorizationCode');
            $this->loadModel('Game');
            $this->Game->contain();
            $app = $this->Game->findAllByTitleOrAlias($this->request->query('app'), $this->request->query('app'));

            if (empty($app)) {
                throw new InternalErrorException('This app has been created yet');
            }

            return $this->Common->oauthRedirect($app[0]['Game']['id'], $this->Auth->user('id'), $this->request->query('continue'));
        }

        $this->layout = 'default_bootstrap';
    }

    /**
     * Request to this action to verify authorzation code and get info user
     * GET:
     * - code
     **/
    public function token()
    {
        if (!$this->request->is('get')) {
            throw new BadRequestException('Required a get request.');
        }

        $result = $this->Common->oauthReturnUserInfo($this->request->query('code'), $this->request->query('secret'));

        $this->set('result', $result);
        $this->set('_serialize', 'result');
    }

    /**
     * Lấy thong tin game
     * GET
     */
    public function getGame()
    {
        if (!$this->request->header('app')) {
            throw new BadRequestException();
        }

        $game = $this->Common->currentGame();

        if (empty($game)) {
            throw new BadRequestException();
        }

        $result = array(
            'app'       => $game['app'],
            'title'     => $game['title'],
            'language'  => $game['language_default'],
        );
        if (!empty($game['data']['appsflyer'])) {
            $result['appsflyer'] = $game['data']['appsflyer'];
            if (!empty($game['appsflyer']['is_use_http'])) {
                $result['appsflyer']['is_use_http'] = (bool) $game['appsflyer']['is_use_http'];
            }
        }

        if (!empty($game['data']['google_iab'])) {
            $result['google_iab'] = $game['data']['google_iab'];
        }

        $result['hide_login'] = $this->Common->hideFunction('hide_login');

        $this->set('result', $result);
        $this->set('_serialize', 'result');
        return $result;
    }

    /**
     * @header: app (*), token (*), device (*), network, game_version, sdk_version, resolution, os
     * @method POST
     * @param: token_notify
     */
    public function getInfoAuthen(){
        if (!$this->request->header('app') || !$this->request->header('token')) {
            throw new BadRequestException();
        }

        if ($this->Common->isMaintained()) {
            $this->response->statusCode('403');
            $this->set('result', new stdClass()); // return [], iphone can't detect value null
            $this->set('_serialize', false);
            return false;
        }

        $result = array();

        #logLogin
        $getLogin = $this->getLogin();
        $result['getLogin'] = $getLogin;

        #userInfo
        $userInfo = $this->userInfo(true);
        $result['userInfo'] = $userInfo;

        $this->set('result', $result);
        $this->set('_serialize', 'result');
        return $result;
    }

    public function api_tracking_install(){
        CakeLog::info('checking install header:' . print_r($this->request->query,true));
        CakeLog::info('checking install query:' . print_r($this->request->header('*'),true));

        $result = array();
        $this->set('result', $result);
        $this->set('_serialize', 'result');
        return $result;
    }

    public function api_list_game(){
        try {
            $this->loadModel('Game');
            $this->Game->recursive = -1;
            $games = $this->Game->find('all', array(
                'fields' => array('title', 'app', 'secret_key',
                    'slug', 'alias', 'short_words', 'status',
                    'os', 'language_default', 'modified'
                ),
                'conditions' => array(
                    'status' => true
                )
            ));
            $games = Hash::extract($games, '{n}.Game');

            App::import('Lib', 'RedisCake');
            $Redis = new RedisCake('action_count');

            $result = array(
                'error_code' => 0,
                'system' => 'mu_plf',
                'last_update' => $Redis->get('list_game_last_update'),
                'message' => 'success',
                'data' => $games
            );
        }catch (Exception $e){
            CakeLog::error('list game error:' . $e->getMessage());
            $result = array(
                'error_code' => $e->getCode(),
                'message' => $e->getMessage(),
            );
        }

        $this->set('result', $result);
        $this->set('_serialize', 'result');
        return $result;
    }

    public function api_userList(){
        $result = array(
            'error_code' => 1,
            'message' => 'error',
        );

        if ($this->request->data('tokens'))
            $tokens = $this->request->data('tokens');

        if (!isset($tokens)) {
            $result = array(
                'error_code' => 2,
                'message' => 'token is not empty',
            );
            goto end;
        }

        $this->loadModel('AccessToken');
        $this->AccessToken->contain(array('User'));
        $data = $this->AccessToken->find('all', array(
            'fields' => array('AccessToken.token', 'User.id', 'User.email', 'User.phone', 'User.username'),
            'conditions' => array(
                'token' => $tokens
            )
        ));

        if (empty($data) ) {
            $result = array(
                'error_code' => 3,
                'message' => 'token is false',
            );
            goto end;
        }

        $users = [];
        foreach ($data as $user) {
            $tmp = $user['User'];
            $tmp['token'] = $user['AccessToken']['token'];
            $users[] = $tmp;
        }

        $result = array(
            'error_code' => 0,
            'message' => 'success',
            'data' => $users
        );

        end:
        $this->set('result', $result);
        $this->set('_serialize', 'result');
    }

    public function api_registerDeviceNotify(){
        $result = array(
            'error_code' => 1,
            'message' => 'error',
        );

        $this->set('result', $result);
        $this->set('_serialize', 'result');
    }
}

