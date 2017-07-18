<?php

App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('HttpSocket', 'Network/Http');
App::uses('Security', 'Utility');
App::uses('Validation', 'Ulitity');
App::import('Lib', 'RedisQueue');

class UsersController extends AppController {

	public $name = 'Users';

	public $cacheAction = array(
	);

	public $components = array(
		'Auth',
		'Session',
		'Cookie',
		'Paginator',
		'Command',
		'Search.Prg',
		'Utils.Referer'
	);

	public $presetVars = array(
		array('field' => 'search', 'type' => 'value'),
		array('field' => 'username', 'type' => 'value'),
		array('field' => 'email', 'type' => 'value'),
		array('field' => 'vip', 'type' => 'value'),
		array('field' => 'facebook_uid', 'type' => 'value'),
		array('field' => 'id', 'type' => 'value'),
		array('field' => 'account_id', 'type' => 'value')
	);

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow( 
			'logout', 'api_register', 'api_login', 'api_change_password',
			'api_login_takan', 'api_register_takan',
			'api_login_ldr', 'api_register_ldr', 'api_change_password_ldr',
			'api_register_v26', 'api_login_v26'
		);
	}

	#########################
	# Login cms
	public function login()
	{
		$this->set('title_for_layout', 'Login');

		if ($this->request->is('post')) {
			$this->Auth->login();
			# Nếu user không thể login bằng email , check username
			if (!$this->Auth->user() && !empty($this->request->data['User']['email'])) {
				$tempEmail = $this->request->data['User']['email'];
				$this->User->contain();
				
				#login by username
				if ($user = $this->User->findByUsername($this->request->data['User']['email'])) {
					$this->request->data['User']['email'] = $user['User']['email'];
					$this->Auth->login();
				}
				
				#login by phone
				if (!$this->Auth->user()) {
					if ($user = $this->User->findByPhone($this->request->data['User']['email'])) {
						$this->request->data['User']['email'] = $user['User']['email'];
						$this->Auth->login();
						if (!$this->Auth->user()) {
							$this->request->data['User']['email'] = $tempEmail;
						}
					}
				}
			}
			
			if ($this->Auth->user()) {
				$this->User->id = $this->Auth->user('id');
				if ($this->here == $this->Auth->loginRedirect) {
					$this->Auth->loginRedirect = '/';
				}

				$this->Session->setFlash(sprintf(__('%s, bạn đã login thành công'), $this->Auth->user('username')), 'success');
				if (!empty($this->request->data) && $this->action != 'login_email') {
					$data = $this->request->data['User'];
					$this->request->data['User']['remember_me'] = true;
					$this->_setCookie();
				}

				if (empty($data['return_to'])) {
					$data['return_to'] = null;
				}

				$this->Session->delete('Message.auth');
				$this->Session->setFlash('You has been logged in successfully', 'success');
				$this->redirect(array('controller' => 'admin'));
			} else {
				$this->User->validationErrors['email'][] = '';
				$this->User->validationErrors['password'][] = '';

				$this->Session->setFlash(__('Email/Tên đăng nhập và/hoặc mật khẩu không đúng!'), 'error');
				unset($this->request->data['User']['password']);
			}

			if (isset($this->request->params['named']['return_to'])) {
				$this->set('return_to', urldecode($this->request->params['named']['return_to']));
			} else {
				$this->set('return_to', false);
			}
		}

		$this->layout = 'default_bootstrap';
	}

	public function logout()
	{
		if ($this->Auth->loggedIn()) {
			$message = sprintf('%s you have successfully logged out', $this->Auth->user('username'));
			$this->Auth->logout();
			$this->Cookie->destroy();
			$this->Session->destroy();
			$this->Session->setFlash($message);
			if ($this->request->query('app')){
				$website_url = $this->Common->currentWebsite('url');
				$this->redirect('http://' . $website_url);
			}
			if($this->request->query('reditectTo')){
				$website_url = $this->request->query('reditectTo');
				$this->redirect('http://' . $website_url);
			}
		}
		if ($this->request->query('next')) {
			$this->redirect($this->request->query('next'));
		}
		$this->redirect($this->referer('/', true));
	}

	protected function _setCookie($options = array(), $cookieKey = 'User')
	{
		$options = array('name' => $this->Cookie->name);
		if (empty($this->request->data['User']['remember_me'])) {
			$this->Cookie->delete($cookieKey);
		} else {
			$validProperties = array('domain', 'key', 'name', 'path', 'secure', 'time');
			$defaults = array(
				'name' => 'rememberMe');

			$options = array_merge($defaults, $options);
			foreach ($options as $key => $value) {
				if (in_array($key, $validProperties)) {
					$this->Cookie->{$key} = $value;
				}
			}

			$cookieData = array(
				'email' => $this->request->data['User']['email'],
				'password' => $this->request->data['User']['password']);
			$this->Cookie->write($cookieKey, $cookieData, true, '1 Month');
		}
		unset($this->request->data['User']['remember_me']);
	}

	public function admin_add()
	{
		$this->User->validator()->remove('phone');
		if (!empty($this->request->data)) {
			$this->request->data['User']['active'] = 1;
			$this->User->validator()->remove('password')->remove('phone');

			if ($this->User->add($this->request->data)) {
				$this->Session->setFlash('The User has been saved', 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(current(current($this->User->validationErrors)), 'error');
				$this->redirect(array('action' => 'index'));
			}
			$this->redirect(array('action' => 'index', 'admin' => true));
		}
		$this->layout = 'default_bootstrap';
	}

	function admin_index()
	{

		$this->loadModel('Game');
		if (!empty($this->request->data['User']['role_id']) && (empty($this->request->data['User']['game_id']) || empty($this->request->data['User']['area_id']))) {
			$this->Session->setFlash('You must choose a game if you want to search by role ID ', 'error');
			$this->redirect(array('action' => 'index', 'admin' => true));
		}
		$this->Prg->commonProcess();
		$this->User->validator()->remove('username');
		$this->User->validator()->remove('email');
		$this->request->data['User'] = $this->passedArgs;
		if ($this->User->Behaviors->loaded('Searchable')) {
			$parsedConditions = $this->User->parseCriteria($this->passedArgs);
		} else {
			$parsedConditions = array();
		}
		if (isset($this->request->data['User']['username']) && $this->request->data['User']['username'] != '' ) {
			$conditions = array_merge($parsedConditions,
				array("username LIKE '%" . $this->request->data['User']['username'] . "%'"));
		} elseif (isset($this->request->data['User']['email']) && $this->request->data['User']['email'] != '') {
			$conditions = array_merge($parsedConditions,
				array("email LIKE '" . $this->request->data['User']['email'] . "'"));
		} else {
			$conditions = $parsedConditions;
		}
		if (!empty($this->passedArgs) && empty($conditions)) {
			if ((count($this->passedArgs) == 1 && empty($this->passedArgs['page'])) || count($this->passedArgs) > 1) {
				$this->Session->setFlash("Can not find anyone match this conditions", "error");
			}
		}
		$this->Paginator->settings['User']['conditions'] = $conditions;
		$this->Paginator->settings['User']['order'] = array('User.id' => 'desc');
		if (!empty($this->request->params['named']['page']) && $this->request->params['named']['page'] > 20) {
			throw new Exception('NotFoundException');
		}
		if (!empty($this->request->params['named']['staff'])) {
			$this->Paginator->settings['User']['limit'] = 100;
		} else {
			$this->User->bindModel(array(
				'hasAndBelongsToMany' => array(
					'Game' => array(
						'joinTable' => 'Account',
						'fields' => array(
							'Game.title',
							'Game.os',
						),
					),
				),
				'hasOne' => array(
					'LogUpdatedAccount'
				)
			));
			$this->User->contain(array('Game', 'LogUpdatedAccount'));
		}
		$this->User->recursive = -1;
		$users = $this->Paginator->paginate();
		$games = $this->Game->find('list', array('fields' => array('id', 'title_os')));
		$this->set(compact('games','users'));
		$this->layout = 'default_bootstrap';
	}

	public function api_register()
	{
		$result = array(
			'status' => 1,
			'messsage' => 'error'
		);

		CakeLog::info('input api register:' . print_r($this->request->data,true));

		if (!isset(
			$this->request->data['phone'],
			$this->request->data['username'],
			$this->request->data['password'],
			$this->request->data['sign']
		)) {
			$result = array(
				'status' => 2,
				'message' => 'Necessary data is missing'
			);
			goto end;
		}

		$sign = md5(
			$this->request->data['username'] 
			. $this->request->data['password']
			. $this->request->data['phone'] 
			. $this->Common->currentGame('secret_key') 
			. $this->Common->currentGame('app')
		);

		if( $this->request->data['sign'] !== $sign ){
			CakeLog::info('sign register:' . print_r($sign,true));
			$result = array(
				'status' => 3,
				'message' => 'The sign is incorrect'
			);
			goto end;
		}

		unset($this->request->data['sign']);
		$this->request->data['User'] = $this->request->data;
		$this->request->data['User']['email'] = time().'@myapp.com';
		$this->request->data['User']['role'] = 'User';
		$this->request->data['User']['active'] = true;
		$this->request->data['User']['username'] = $this->request->data['username'];

		$userCheck = $this->User->findByUsername($this->request->data['User']['username']);
		if( !empty($userCheck['User']) ){
			$result = array(
				'status' => 4,
				'message' => 'The username is in use'
			);
			goto end;
		}

		$this->User->validator()->remove('email')->remove('password', 'confirmPassword');
		$this->User->validator()->remove('phone');

		if ($this->Auth->user()) {
			$data = $this->Command->authen('login', true);
			$this->Log->logLogin();
			$result = array(
				'status' => 5,
				'messsage' => 'Register successfully',
				'data' => $data
			);
			goto end;
		}

		if ($this->request->is('post')) {
			if ($this->Auth->user()) {
				$data = $this->Command->authen('login', true);
				$this->Log->logLogin();
				$result = array(
					'status' => 0,
					'messsage' => 'Register successfully',
					'data' => $data
				);
				goto end;
			}

			$dataSource = $this->User->getDataSource();
			$dataSource->begin();
			# lock users
			$this->User->query("SELECT * FROM users LIMIT 1 FOR UPDATE");
			# lock accounts
			$this->User->query("SELECT * FROM accounts LIMIT 1 FOR UPDATE");
			
			$user = $this->User->register($this->request->data);
			if ($user !== false) {
				$this->User->createAccount($this->Common->currentGame());
				$dataSource->commit();

				$this->User->read();
				$this->Auth->login($this->User->data['User']);

				$data = $this->Command->authen('login', true);
				$this->Log->logLogin();

				$result = array(
					'status' => 0,
					'messsage' => 'Register successfully',
					'data' => $data
				);
			} else {
				$dataSource->rollback();
				unset($this->request->data[$this->modelClass]['password']);

				$messageError = 'Validation errors';
				if( !empty($this->User->validationErrors['password'][0])
					&& is_string($this->User->validationErrors['password'][0])
				){
					$messageError = $this->User->validationErrors['password'][0] ;
				}
				$result = array(
					'status' => 6,
					'message' => $messageError,
					'data' => $this->User->validationErrors
				);

				CakeLog::info('check validate register: '. print_r($result,true));
			}
		}

		end:
		CakeLog::info('output api register:' . print_r($result,true));
		$this->set('result', $result);
		$this->set('_serialize', 'result');
	}

	public function api_login(){
		$result = array(
			'status' => 1,
			'messsage' => 'error'
		);

		CakeLog::info('input api login:' . print_r($this->request->data,true));

		if (!isset(
			$this->request->data['username'],
			$this->request->data['password'],
			$this->request->data['sign']
		)) {
			$result = array(
				'status' => 2,
				'message' => 'Necessary data is missing'
			);
			goto end;
		}

		$sign = md5(
			$this->request->data['username']
			. $this->request->data['password']
			. $this->Common->currentGame('secret_key')
			. $this->Common->currentGame('app')
		);

		if( $this->request->data['sign'] !== $sign ){
			CakeLog::info('sign login:' . print_r($sign,true));
			$result = array(
				'status' => 3,
				'message' => 'The sign is incorrect'
			);
			goto end;
		}

		# Nếu user không thể login bằng email , check username
		$this->request->data['User']['email'] = $this->request->data['username'];
		$this->request->data['User']['password'] = $this->request->data['password'];
		if (!$this->Auth->user() && !empty($this->request->data['User']['email'])) {
			$tempEmail = $this->request->data['User']['email'];
			$this->User->contain();
			#login by username
			if ($user = $this->User->findByUsername($this->request->data['User']['email'])) {
				$this->request->data['User']['email'] = $user['User']['email'];
				$this->Auth->login();
			}

			if (!$this->Auth->user()) {
				if ($user = $this->User->findByPhone($this->request->data['User']['email'])) {
					$this->request->data['User']['email'] = $user['User']['email'];
					$this->Auth->login();
					if (!$this->Auth->user()) {
						$this->request->data['User']['email'] = $tempEmail;
					}
				}
			}
		}

		if ($this->Auth->user()) {
			# if user login in the game
			if ($this->Common->currentGame()) {
				$dataSource = $this->User->getDataSource();
				$dataSource->begin();
				$this->User->Account->query("SELECT * FROM accounts LIMIT 1 FOR UPDATE");
				$accountExist = $this->User->Account->find('first', array(
					'conditions' => array(
						'user_id' => $this->Auth->user('id'),
						'game_id' => $this->Common->currentGame('id')
					),
					'recursive' => -1
				));

				if (empty($accountExist)) {
					$this->Session->write(AuthComponent::$sessionKey . '.new_account', 1);

					$this->User->Account->recursive = -1;
					$this->User->createAccount(
						$this->Common->currentGame(),
						$this->Auth->user('id')
					);
				}
				$dataSource->commit();
				$data = $this->Command->authen('login', true);
				$this->Log->logLogin();

				$result = array(
					'status' => 0,
					'messsage' => 'login successfully',
					'data' => $data
				);
			} else {
				$this->Session->setFlash('You has been logged in successfully', 'success');
				$this->redirect(array('action' => 'login_successful'));
			}
		} else {
			$result = array(
				'status' => 4,
				'message' => __('Tên đăng nhập và/hoặc mật khẩu không đúng!')
			);
			goto end;
		}

		end:
		CakeLog::info('output api login:' . print_r($result,true));
		$this->set('result', $result);
		$this->set('_serialize', 'result');
	}

	public function api_change_password(){
		try{
			$result = array(
				'status' => 1,
				'messsage' => __('lỗi')
			);
			CakeLog::info('input api chang password:' . print_r($this->request->data,true));

			if (!isset(
				$this->request->data['username'],
				$this->request->data['old_pass'],
				$this->request->data['new_pass'],
				$this->request->data['sign']
			)) {
				$result = array(
					'status' => 2,
					'message' => __('Thiếu thông tin đổi mật khẩu')
				);
				goto end;
			}

			$sign = md5(
				$this->request->data['username']
				. $this->request->data['old_pass']
				. $this->request->data['new_pass']
				. $this->Common->currentGame('secret_key')
				. $this->Common->currentGame('app')
			);

			if( $this->request->data['sign'] !== $sign ){
				CakeLog::info('sign login:' . print_r($sign,true));
				$result = array(
					'status' => 3,
					'message' => 'The sign is incorrect'
				);
				goto end;
			}

			$old_password = $this->request->data['old_pass'];
			$new_pass = $this->request->data['new_pass'];
			$username = $this->request->data['username'];
			$user = $this->User->findByUsername($username);
			if (!empty($user)) {
				$this->User->data['User']['password'] = $new_pass;
				$this->User->set($this->User->data);
				if ($user['User']['password'] == Security::hash($old_password, 'sha1', true)) {
					$this->User->validator()->remove('password', 'confirmPassword');
					if ($this->User->validates(array('fieldList' => array('password')))) {
						$this->User->id = $user['User']['id'];
						$this->User->data['User']['password'] = Security::hash($new_pass, 'sha1', true);
						if ($this->User->save($this->User->data, false, array('password'))) {
							if (isset($user['User']['id']) && !empty($user['User']['id'])) {
								$this->loadModel('AccessToken');
								$token = $this->AccessToken->generateToken($this->Common->currentGame('app'), $user['User']['id']);
								$this->loadModel('Account');
								$this->Account->contain();
								$account = $this->Account->findByUserIdAndGameId(
									$user['User']['id'],
									$this->Common->currentGame('id')
								);
								$data = array_merge(
									array(
										'User' => array(
											'username' => $user['User']['username'],
											'account_id' => $account['Account']['account_id']
										)),
									array(
										'access_token' => $token['AccessToken']['token'],
										'token_expire' => $token['AccessToken']['expired'])
								);

								$result = array(
									'status' => 0,
									'data' => $data,
									'message' => __('Đổi mật khẩu thành công')
								);
								goto end;
							}
						} else {
							$result = array(
								'status' => 6,
								'message' => __('Đổi mật khẩu không thành công, không lưu được dữ liệu')
							);
							goto end;
						}
					} else {
						if (!empty($this->User->validationErrors)) {
							$result = array(
								'status' => 7,
								'message' => $this->User->validationErrors['password'][0]
							);
							goto end;
						}
					}
				} else {
					$result = array(
						'status' => 5,
						'message' => __('Mật khẩu cũ không chính xác')
					);
					goto end;
				}
			} else {
				$result = array(
					'status' => 4,
					'message' => __('Không tìm thấy người chơi')
				);
				goto end;
			}
		}catch (Exception $e){
			$result = array(
				'status' => 500,
				'message' => __('Lỗi không xác định')
			);
			goto end;
		}

		end:
		CakeLog::info('output api change password:' . print_r($result,true));
		$this->set('result', $result);
		$this->set('_serialize', 'result');
	}

	public function admin_edit($id = null)
	{
		if (!$id || !$user = $this->User->findById($id)) {
			throw new NotFoundException("Can not find this user");
		}

		if (!empty($this->request->data)) {
			$this->request->data['User']['active'] = 1;
			$this->User->validator()->remove('password');
			$this->User->validator()->remove('phone');

			if ($this->User->add($this->request->data, false)) {
				$this->Session->setFlash('The User has been saved');
				$this->redirect(array('action' => 'index'));
			}
			$this->redirect(array('action' => 'index', 'admin' => true));
		}
		$this->request->data = $user;
		$this->layout = 'default_bootstrap';
	}

	public function admin_editContent($id = null){
		$this->admin_edit($id);
	}

    public function admin_view($id)
    {
        $id_game = $this->Auth->user('permission_game_default');

        $this->loadModel('Game');
        $list_games = $this->Game->find('list', array(
            'fields' => array('id', 'title_os'),
            'conditions' => array('id' => $id_game)
        ));

        $this->loadModel('Payment');
        $payments = $this->Payment->find('all', array(
            'fields' => array('SUM(price) as total, game_id'),
            'conditions' => array('Payment.user_id' => $id, 'Payment.game_id' => $id_game),
            'group' => array('game_id'),
            'recursive' => -1
        ));

        $this->User->bindModel(array(
            'hasOne' => array(
                'LogUpdatedAccount'
            )
        ));
        $this->User->contain( array(
            'Account' => array(
                'Game', 'conditions' => array(
                    'Account.game_id' => $id_game
                )
            )
        ));
        $user = $this->User->find('first', array(
            'conditions' => array('User.id' => $id, 'Account.game_id' => $id_game),
            'joins' => array(
                array(
                    'type' => 'LEFT',
                    "table" => "accounts",
                    "alias" => "Account",
                    "conditions" => array("User.id = Account.user_id"),
                ),
            ),
            'contain' => array('Profile'),
        ));
        if (empty($user)) {
            throw new NotFoundException("Can not find this user");
        }

        $this->loadModel('LogEntergame');
        $this->LogEntergame->bindModel(array('belongsTo' => array('Game')));
        $areaRoles = $this->LogEntergame->find('all', array(
            'fields' => array('DISTINCT area_id', 'role_id', 'Game.id', 'Game.title', 'Game.os'),
            'conditions' => array(
                'user_id' => $id,
                'Game.id' => $id_game,
            ),
            'contain' => array('Game'),
            'limit' => 1000
        ));

        $this->set(compact('list_games', 'user', 'areaRoles', 'payments'));
        $this->layout = 'default_bootstrap';
    }

    public function admin_deactive($userId = null, $active = 0)
    {
        if (!$userId) {
            throw new BadRequestException();
        }
        $this->User->id = $userId;
        if ($this->User->saveField('active', $active)) {
            $this->Session->setFlash('Deactived the user.');
        } else {
            $this->Session->setFlash('Error happen');
        }

        $this->redirect($this->referer());
    }

	public function api_register_takan()
	{
		$result = array(
			'ret' => 903,
			'msg' => 'error'
		);

		if (!isset(
			$this->request->data['username'],
			$this->request->data['pwd']
		)) {
			$result = array(
				'ret' => 903,
				'msg' => 'Necessary data is missing'
			);
			goto end;
		}

		CakeLog::info('api_register_takan:' . print_r($this->request->data,true), 'user');

		$this->request->data['User'] = $this->request->data;
		$this->request->data['User']['email'] = time().'@myapp.com';
		$this->request->data['User']['role'] = 'User';
		$this->request->data['User']['active'] = true;
		$this->request->data['User']['password'] = $this->request->data['pwd'];
		$this->request->data['User']['username'] = 'takan_' . $this->request->data['username'];

		$userCheck = $this->User->findByUsername($this->request->data['User']['username']);
		if( !empty($userCheck['User']) ){
			$result = array(
				'ret' => 903,
				'msg' => 'The username is in use'
			);
			goto end;
		}

		$this->User->validator()->remove('email')->remove('password', 'confirmPassword');
		$this->User->validator()->remove('phone');

		if ($this->Auth->user()) {
			$data = $this->Command->authen_takan('login', true);
			$this->Log->logLogin();
			$result = array(
				'ret' => 0,
				'msg' => 'Register successfully',
				'data' => $data
			);
			goto end;
		}

		if ($this->request->is('post')) {
			if ($this->Auth->user()) {
				$data = $this->Command->authen_takan('login', true);
				$this->Log->logLogin_takan();
				$result = array(
					'ret' => 0,
					'msg' => 'Register successfully',
					'data' => $data
				);
				goto end;
			}

			$dataSource = $this->User->getDataSource();
			$dataSource->begin();
			# lock users
			$this->User->query("SELECT * FROM users LIMIT 1 FOR UPDATE");
			# lock accounts
			$this->User->query("SELECT * FROM accounts LIMIT 1 FOR UPDATE");

			$user = $this->User->register($this->request->data);
			if ($user !== false) {
				$this->User->createAccount($this->Common->currentGame());
				$dataSource->commit();

				$this->User->read();
				$this->Auth->login($this->User->data['User']);

				$data = $this->Command->authen_takan('login', true);
				$this->Log->logLogin();

				$result = array(
					'ret' => 0,
					'msg' => 'Register successfully',
					'data' => $data
				);
				goto end;
			} else {
				$dataSource->rollback();
				unset($this->request->data[$this->modelClass]['password']);

				$messageError = 'Validation errors';
				if( !empty($this->User->validationErrors['password'][0])
					&& is_string($this->User->validationErrors['password'][0])
				){
					$messageError = $this->User->validationErrors['password'][0] ;
				}

                if( !empty($this->User->validationErrors['username'][0])
                    && is_string($this->User->validationErrors['username'][0])
                ){
                    $messageError = $this->User->validationErrors['username'][0] ;
                }

				$result = array(
					'ret' => 903,
					'msg' => $messageError,
					'data' => $this->User->validationErrors
				);
				goto end;
			}
		}

		end:
		$this->set('result', $result);
		$this->set('_serialize', 'result');
	}

	public function api_login_takan(){
		$result = array(
			'ret' => 903,
			'msg' => 'error'
		);

		if (!isset(
			$this->request->data['account'],
			$this->request->data['pwd']
		)) {
			$result = array(
				'ret' => 903,
				'msg' => 'Necessary data is missing'
			);
			goto end;
		}
		$this->request->data['username'] = 'takan_' . $this->request->data['account'] ;
		$this->request->data['password'] = $this->request->data['pwd'];

		# Nếu user không thể login bằng email , check username
		$this->request->data['User']['email'] = $this->request->data['username'];
		$this->request->data['User']['password'] = $this->request->data['password'];
		if (!$this->Auth->user() && !empty($this->request->data['User']['email'])) {
			$tempEmail = $this->request->data['User']['email'];
			$this->User->contain();
			#login by username
			if ($user = $this->User->findByUsername($this->request->data['User']['email'])) {
				$this->request->data['User']['email'] = $user['User']['email'];
				$this->Auth->login();
			}

			if (!$this->Auth->user()) {
				if ($user = $this->User->findByPhone($this->request->data['User']['email'])) {
					$this->request->data['User']['email'] = $user['User']['email'];
					$this->Auth->login();
					if (!$this->Auth->user()) {
						$this->request->data['User']['email'] = $tempEmail;
					}
				}
			}
		}

		if ($this->Auth->user()) {
			# if user login in the game
			if ($this->Common->currentGame()) {
				$dataSource = $this->User->getDataSource();
				$dataSource->begin();
				$this->User->Account->query("SELECT * FROM accounts LIMIT 1 FOR UPDATE");
				$accountExist = $this->User->Account->find('first', array(
					'conditions' => array(
						'user_id' => $this->Auth->user('id'),
						'game_id' => $this->Common->currentGame('id')
					),
					'recursive' => -1
				));

				if (empty($accountExist)) {
					$this->Session->write(AuthComponent::$sessionKey . '.new_account', 1);

					$this->User->Account->recursive = -1;
					$this->User->createAccount(
						$this->Common->currentGame(),
						$this->Auth->user('id')
					);
				}
				$dataSource->commit();
				$data = $this->Command->authen_takan('login', true);
				$this->Log->logLogin();

				$result = array(
					'data' => $data,
					'ret' => 0,
					'msg' => 'login successfully'
				);
			} else {
				$this->Session->setFlash('You has been logged in successfully', 'success');
				$this->redirect(array('action' => 'login_successful'));
			}
		} else {
			$result = array(
				'ret' => 903,
				'msg' => __('Tên đăng nhập và/hoặc mật khẩu không đúng!')
			);
			goto end;
		}

		end:
		$this->set('result', $result);
		$this->set('_serialize', 'result');
	}

	public function api_register_ldr()
	{
		$result = array(
			'retcode' => 900,
			'retmsg' => 'error'
		);

		/*
		try {
			if ($this->request->is('post')) {
				$this->Common->bruteForce(array(
					'ip' => $this->Common->publicClientIp(),
					'action' => 'api_register_ldr',
					$this->Common->currentGame('id'),
				), 60*60, 100);
			}
		} catch (Exception $e) {
			$result = array(
				'retcode' => 900,
				'retmsg' => 'vui lòng thử lại sau ít phút'
			);
			goto end;
		}
        */

        CakeLog::info('api_register_ldr:' . print_r($this->request->data,true), 'user');

		if (!isset(
			$this->request->data['user_name'],
			$this->request->data['password'],
			$this->request->data['email']
		)) {
			$result = array(
				'retcode' => 900,
				'retmsg' => __('Thiếu thông tin đăng ký')
			);
			goto end;
		}

		$prefix_user = 'ldr_';
		$game = $this->Common->currentGame();
		if( !empty($game['app']) && in_array($game['app'], array('d316d77ea8430f82b1df322793e56f48', 'b41ec1c5766d423b73123cf637a8c5e3')) ){
            $prefix_user = 'vnz_';
        }

		$this->request->data['User'] = $this->request->data;
		$this->request->data['User']['email'] 	= time().'@myapp.com';
		$this->request->data['User']['role'] 	= 'User';
		$this->request->data['User']['active'] 	= true;
		$this->request->data['User']['phone'] 	= $this->request->data['email'];
		$this->request->data['User']['password'] = $this->request->data['password'];
		$this->request->data['User']['username'] = $prefix_user . $this->request->data['user_name'];

		$userCheck = $this->User->findByUsername($this->request->data['User']['username']);
		if( !empty($userCheck['User']) ){
			$result = array(
				'retcode' => 900,
				'retmsg' => __('tài khoản đã tồn tại')
			);
			goto end;
		}

		$this->User->validator()->remove('email')->remove('password', 'confirmPassword');
		$this->User->validator()->remove('phone', 'unique_phone');

		if ($this->Auth->user()) {
			$data = $this->Command->authen_vcc('login', true);
			$this->Log->logLogin();
			$result = array(
				'retcode' => 0,
				'retmsg' => __('đăng kí thành công'),
				'data' => $data
			);
			goto end;
		}

		if ($this->request->is('post')) {
			if ($this->Auth->user()) {
				$data = $this->Command->authen_vcc('login', true);
				$this->Log->logLogin();
				$result = array(
					'retcode' => 0,
					'retmsg' => __('đăng kí thành công'),
					'data' => $data
				);
				goto end;
			}

			$dataSource = $this->User->getDataSource();
			$dataSource->begin();
			# lock users
			$this->User->query("SELECT * FROM users LIMIT 1 FOR UPDATE");
			# lock accounts
			$this->User->query("SELECT * FROM accounts LIMIT 1 FOR UPDATE");

			$user = $this->User->register($this->request->data);
			if ($user !== false) {
				$this->User->createAccount($this->Common->currentGame());
				$dataSource->commit();

				$this->User->read();
				$this->Auth->login($this->User->data['User']);

				$data = $this->Command->authen_vcc('login', true);
				$this->Log->logLogin();

				$result = array(
					'retcode' => 0,
					'retmsg' => __('đăng kí thành công'),
					'data' => $data
				);
				goto end;
			} else {
				$dataSource->rollback();
				unset($this->request->data[$this->modelClass]['password']);

				$messageError = __('lỗi đăng ký');

				if( !empty($this->User->validationErrors['phone'][0])
					&& is_string($this->User->validationErrors['phone'][0])
				){
					$messageError = $this->User->validationErrors['phone'][0] ;
				}

				if( !empty($this->User->validationErrors['password'][0])
					&& is_string($this->User->validationErrors['password'][0])
				){
					$messageError = $this->User->validationErrors['password'][0] ;
				}

				if( !empty($this->User->validationErrors['username'][0])
					&& is_string($this->User->validationErrors['username'][0])
				){
					$messageError = $this->User->validationErrors['username'][0] ;
				}

				CakeLog::info('check validate register: '. print_r($this->User->validationErrors,true));
				$result = array(
					'retcode' => 5,
					'retmsg' => $messageError
				);
				goto end;
			}
		}

		end:
		$this->set('result', $result);
		$this->set('_serialize', 'result');
	}

	public function api_login_ldr(){
		$result = array(
			'retcode' => 5,
			'retmsg' => 'error'
		);

		if (!isset(
			$this->request->data['username'],
			$this->request->data['userpass']
		)) {
			$result = array(
				'retcode' 	=> 5,
				'retmsg' 	=> __('Thiếu thông tin đăng ký')
			);
			goto end;
		}

        $prefix_user = 'ldr_';
        $game = $this->Common->currentGame();
        if( !empty($game['app']) && in_array($game['app'], array('d316d77ea8430f82b1df322793e56f48', 'b41ec1c5766d423b73123cf637a8c5e3')) ){
            $prefix_user = 'vnz_';
        }

		$this->request->data['username'] = $prefix_user . $this->request->data['username'] ;
		$this->request->data['password'] = $this->request->data['userpass'];

		# Nếu user không thể login bằng email , check username
		$this->request->data['User']['email'] = $this->request->data['username'];
		$this->request->data['User']['password'] = $this->request->data['password'];

		if (!$this->Auth->user() && !empty($this->request->data['User']['email'])) {
			$tempEmail = $this->request->data['User']['email'];
			$this->User->contain();
			#login by username
			if ($user = $this->User->findByUsername($this->request->data['User']['email'])) {
				$this->request->data['User']['email'] = $user['User']['email'];
				$this->Auth->login();
			}

			if (!$this->Auth->user()) {
				if ($user = $this->User->findByPhone($this->request->data['User']['email'])) {
					$this->request->data['User']['email'] = $user['User']['email'];
					$this->Auth->login();
					if (!$this->Auth->user()) {
						$this->request->data['User']['email'] = $tempEmail;
					}
				}
			}
		}

		if ($this->Auth->user()) {
			# if user login in the game
			if ($this->Common->currentGame()) {
				$dataSource = $this->User->getDataSource();
				$dataSource->begin();
				$this->User->Account->query("SELECT * FROM accounts LIMIT 1 FOR UPDATE");
				$accountExist = $this->User->Account->find('first', array(
					'conditions' => array(
						'user_id' => $this->Auth->user('id'),
						'game_id' => $this->Common->currentGame('id')
					),
					'recursive' => -1
				));

				if (empty($accountExist)) {
					$this->Session->write(AuthComponent::$sessionKey . '.new_account', 1);

					$this->User->Account->recursive = -1;
					$this->User->createAccount(
						$this->Common->currentGame(),
						$this->Auth->user('id')
					);
				}
				$dataSource->commit();
				$data = $this->Command->authen_vcc('login', true);
				$this->Log->logLogin();

				$result = array(
					'data' => $data,
					'retcode' => 0,
					'retmsg' => __('đăng nhập thành công')
				);
			} else {
				$this->Session->setFlash('You has been logged in successfully', 'success');
				$this->redirect(array('action' => 'login_successful'));
			}
		} else {
			$result = array(
				'retcode' => 5,
				'retmsg' => __('Tên đăng nhập và/hoặc mật khẩu không đúng!')
			);
			goto end;
		}

		end:
		$this->set('result', $result);
		$this->set('_serialize', 'result');
	}

	public function api_change_password_ldr(){
		try{
			$result = array(
				'status' => 1,
				'messsage' => __('lỗi')
			);

			if (!isset(
				$this->request->data['user_name'],
				$this->request->data['password'],
				$this->request->data['new_password']
			)) {
				$result = array(
					'status' => 2,
					'message' => __('Thiếu thông tin đổi mật khẩu')
				);
				goto end;
			}

            $this->request->data['password'] = md5($this->request->data['password']);
            $this->request->data['new_password'] = md5($this->request->data['new_password']);

            $prefix_user = 'ldr_';
            $game = $this->Common->currentGame();
            if( !empty($game['app']) && in_array($game['app'], array('d316d77ea8430f82b1df322793e56f48', 'b41ec1c5766d423b73123cf637a8c5e3')) ){
                $prefix_user = 'vnz_';
            }

            $prefix_user = ''; // client gọi sang vẫn có tiền tố, ko check

			$old_password = $this->request->data['password'];

			$this->request->data['User']['password'] = $this->request->data['new_password'];
			$this->request->data['User']['username'] = $prefix_user . $this->request->data['user_name'];

			$user = $this->User->findByUsername($this->request->data['User']['username']);
			if (!empty($user)) {
				$this->User->data['User']['password'] = $this->request->data['User']['password'];
				$this->User->set($this->User->data);
				if ($user['User']['password'] == Security::hash($old_password, 'sha1', true)) {
					$this->User->validator()->remove('password', 'confirmPassword');
					if ($this->User->validates(array('fieldList' => array('password')))) {
						$this->User->id = $user['User']['id'];
						$this->User->data['User']['password'] = Security::hash($this->request->data['User']['password'], 'sha1', true);
						if ($this->User->save($this->User->data, false, array('password'))) {
							if (isset($user['User']['id']) && !empty($user['User']['id'])) {
								$this->loadModel('AccessToken');
								$token = $this->AccessToken->generateToken($this->Common->currentGame('app'), $user['User']['id']);
								$this->loadModel('Account');
								$this->Account->contain();
								$account = $this->Account->findByUserIdAndGameId(
									$user['User']['id'],
									$this->Common->currentGame('id')
								);
								$data = array_merge(
									array(
										'User' => array(
											'username' => substr($user['User']['username'], 4),
											'account_id' => $account['Account']['account_id']
										)),
									array(
										'access_token' 	=> $token['AccessToken']['token'],
										'token_expire' 	=> $token['AccessToken']['expired'],
										'indulge'		=> 1,
										'uid'	=> $account['Account']['account_id'],
										'ipv4'	=> $this->Common->publicClientIp(),
										'uname'	=> substr($user['User']['username'], 4),
										'KL_SSO'=> $account['Account']['account_id'],
										"KL_PERSON"	=> "HbzJXvrN14tizpsCilhL9zt-iNCtGzETlRdCLrEcfALa8k679L4vwQHMJN-5m-cOJm3Wqhg3-YE7EdI9-WX.SsCU49NIYeHUsLvq8anfi2GFO_AogqNkS6Uv4jQp.qxfgRdQnxpOzEeH_tpPLqWPlX_9kS1F5lb_c258dKhzKVG9.GJIlu-9l9_aqsvkGAK.pqkkDdvI6fP3uetKI7nJhSzSOyjpWuZSoGGVzlEvAG9R4gS3c3rlAQCZd58G5fxjC8sE9mSh.uGnOzOxuWAnx7QAdc_6d6Iva7Zou5YfpqM0",
										"isnew"		=> "true"
									)
								);

								$result = array(
									'retcode' 	=> 0,
									'data' 		=> $data,
									'message' 	=> __('Đổi mật khẩu thành công')
								);
								goto end;
							}
						} else {
							$result = array(
								'retcode' 	=> 5,
								'retmsg' 	=> __('Đổi mật khẩu không thành công, không lưu được dữ liệu')
							);
							goto end;
						}
					} else {
						if (!empty($this->User->validationErrors)) {
							$result = array(
								'retcode' 	=> 5,
								'retmsg' 	=> $this->User->validationErrors['password'][0]
							);
							goto end;
						}
					}
				} else {
					$result = array(
						'retcode' 	=> 5,
						'retmsg' 	=> __('Mật khẩu cũ không chính xác')
					);
					goto end;
				}
			} else {
				$result = array(
					'retcode' 	=> 4,
					'retmsg' 	=> __('Không tìm thấy người chơi')
				);
				goto end;
			}
		}catch (Exception $e){
			$result = array(
				'retcode' 	=> 500,
				'retmsg' 	=> __('Lỗi không xác định')
			);
			goto end;
		}

		end:
		$this->set('result', $result);
		$this->set('_serialize', 'result');
	}

	public function api_register_v26()
	{
		$result = array(
			'retcode' => 900,
			'retmsg' => 'error'
		);

		if (!isset(
			$this->request->data['user_name'],
			$this->request->data['password'],
			$this->request->data['email']
		)) {
			$result = array(
				'retcode' => 900,
				'retmsg' => __('Thiếu thông tin đăng ký')
			);
			goto end;
		}

		$prefix_user = 'p03_';
		$game = $this->Common->currentGame();

        CakeLog::info('api_register_v26 - game id:' . $game['id'] . '\n data:' . print_r($this->request->data,true), 'user');

        if( !empty( $game['data']['prefix'] ) ){
            $prefix_user = $game['data']['prefix'] ;
        }

		$this->request->data['User'] = $this->request->data;
		$this->request->data['User']['email'] 	= time().'@myapp.com';
		$this->request->data['User']['role'] 	= 'User';
		$this->request->data['User']['active'] 	= true;
		$this->request->data['User']['phone'] 	= $this->request->data['email'];
		$this->request->data['User']['password'] = $this->request->data['password'];
		$this->request->data['User']['username'] = $prefix_user . $this->request->data['user_name'];

		$userCheck = $this->User->findByUsername($this->request->data['User']['username']);
		if( !empty($userCheck['User']) ){
			$result = array(
				'retcode' => 900,
				'retmsg' => __('tài khoản đã tồn tại')
			);
			goto end;
		}

		$this->User->validator()->remove('email')->remove('password', 'confirmPassword');
		$this->User->validator()->remove('phone', 'unique_phone');

		if ($this->Auth->user()) {
			$data = $this->Command->authen_v26('login', true);
			$this->Log->logLogin();
			$result = array(
				'retcode' => 0,
				'retmsg' => __('đăng kí thành công'),
				'data' => $data
			);
			goto end;
		}

		if ($this->request->is('post')) {
			if ($this->Auth->user()) {
				$data = $this->Command->authen_v26('login', true);
				$this->Log->logLogin();
				$result = array(
					'retcode' => 0,
					'retmsg' => __('đăng kí thành công'),
					'data' => $data
				);
				goto end;
			}

			$dataSource = $this->User->getDataSource();
			$dataSource->begin();
			# lock users
			$this->User->query("SELECT * FROM users LIMIT 1 FOR UPDATE");
			# lock accounts
			$this->User->query("SELECT * FROM accounts LIMIT 1 FOR UPDATE");

			$user = $this->User->register($this->request->data);
			if ($user !== false) {
				$this->User->createAccount($this->Common->currentGame());
				$dataSource->commit();

				$this->User->read();
				$this->Auth->login($this->User->data['User']);

				$data = $this->Command->authen_v26('login', true);
				$this->Log->logLogin();

				$result = array(
					'retcode' => 0,
					'retmsg' => __('đăng kí thành công'),
					'data' => $data
				);
				goto end;
			} else {
				$dataSource->rollback();
				unset($this->request->data[$this->modelClass]['password']);

				$messageError = __('lỗi đăng ký');

				if( !empty($this->User->validationErrors['phone'][0])
					&& is_string($this->User->validationErrors['phone'][0])
				){
					$messageError = $this->User->validationErrors['phone'][0] ;
				}

				if( !empty($this->User->validationErrors['password'][0])
					&& is_string($this->User->validationErrors['password'][0])
				){
					$messageError = $this->User->validationErrors['password'][0] ;
				}

				if( !empty($this->User->validationErrors['username'][0])
					&& is_string($this->User->validationErrors['username'][0])
				){
					$messageError = $this->User->validationErrors['username'][0] ;
				}

				CakeLog::info('check validate register: '. print_r($this->User->validationErrors,true));
				$result = array(
					'retcode' => 5,
					'retmsg' => $messageError
				);
				goto end;
			}
		}

		end:
		$this->set('result', $result);
		$this->set('_serialize', 'result');
	}

	public function api_login_v26(){
		$result = array(
			'retcode' => 5,
			'retmsg' => 'error'
		);

		if (!isset(
			$this->request->data['username'],
			$this->request->data['userpass']
		)) {
			$result = array(
				'retcode' 	=> 5,
				'retmsg' 	=> __('Thiếu thông tin đăng ký')
			);
			goto end;
		}

		$prefix_user = 'p03_';
		$game = $this->Common->currentGame();
        if( !empty( $game['data']['prefix'] ) ){
            $prefix_user = $game['data']['prefix'] ;
        }
		
		CakeLog::info('api_login_v26 - game id:' . $game['id'] . '\n data:' . print_r($this->request->data,true), 'user');

		$this->request->data['username'] = $prefix_user . $this->request->data['username'] ;
		$this->request->data['password'] = $this->request->data['userpass'];

		# Nếu user không thể login bằng email , check username
		$this->request->data['User']['email'] = $this->request->data['username'];
		$this->request->data['User']['password'] = $this->request->data['password'];
		if (!$this->Auth->user() && !empty($this->request->data['User']['email'])) {
			$tempEmail = $this->request->data['User']['email'];
			$this->User->contain();
			#login by username
			if ($user = $this->User->findByUsername($this->request->data['User']['email'])) {
				$this->request->data['User']['email'] = $user['User']['email'];
				$this->Auth->login();
			}

			if (!$this->Auth->user()) {
				if ($user = $this->User->findByPhone($this->request->data['User']['email'])) {
					$this->request->data['User']['email'] = $user['User']['email'];
					$this->Auth->login();
					if (!$this->Auth->user()) {
						$this->request->data['User']['email'] = $tempEmail;
					}
				}
			}
		}

		if ($this->Auth->user()) {
			# if user login in the game
			if ($this->Common->currentGame()) {
				$dataSource = $this->User->getDataSource();
				$dataSource->begin();
				$this->User->Account->query("SELECT * FROM accounts LIMIT 1 FOR UPDATE");
				$accountExist = $this->User->Account->find('first', array(
					'conditions' => array(
						'user_id' => $this->Auth->user('id'),
						'game_id' => $this->Common->currentGame('id')
					),
					'recursive' => -1
				));

				if (empty($accountExist)) {
					$this->Session->write(AuthComponent::$sessionKey . '.new_account', 1);

					$this->User->Account->recursive = -1;
					$this->User->createAccount(
						$this->Common->currentGame(),
						$this->Auth->user('id')
					);
				}
				$dataSource->commit();
				$data = $this->Command->authen_v26('login', true);
				$this->Log->logLogin();

				$result = array(
					'data' => $data,
					'retcode' => 0,
					'retmsg' => __('đăng nhập thành công')
				);
			} else {
				$this->Session->setFlash('You has been logged in successfully', 'success');
				$this->redirect(array('action' => 'login_successful'));
			}
		} else {
			$result = array(
				'retcode' => 5,
				'retmsg' => __('Tên đăng nhập và/hoặc mật khẩu không đúng!')
			);
			goto end;
		}

		end:
		$this->set('result', $result);
		$this->set('_serialize', 'result');
	}

    public function api_change_password_v26(){
        try{
            $result = array(
                'status' => 1,
                'messsage' => __('lỗi')
            );

            if (!isset(
                $this->request->data['user_name'],
                $this->request->data['password'],
                $this->request->data['new_password']
            )) {
                $result = array(
                    'status' => 2,
                    'message' => __('Thiếu thông tin đổi mật khẩu')
                );
                goto end;
            }

            $this->request->data['password'] = md5($this->request->data['password']);
            $this->request->data['new_password'] = md5($this->request->data['new_password']);

            $prefix_user = ''; // client gọi sang vẫn có tiền tố, ko check

            $old_password = $this->request->data['password'];

            $this->request->data['User']['password'] = $this->request->data['new_password'];
            $this->request->data['User']['username'] = $prefix_user . $this->request->data['user_name'];

            $user = $this->User->findByUsername($this->request->data['User']['username']);
            if (!empty($user)) {
                $this->User->data['User']['password'] = $this->request->data['User']['password'];
                $this->User->set($this->User->data);
                if ($user['User']['password'] == Security::hash($old_password, 'sha1', true)) {
                    $this->User->validator()->remove('password', 'confirmPassword');
                    if ($this->User->validates(array('fieldList' => array('password')))) {
                        $this->User->id = $user['User']['id'];
                        $this->User->data['User']['password'] = Security::hash($this->request->data['User']['password'], 'sha1', true);
                        if ($this->User->save($this->User->data, false, array('password'))) {
                            if (isset($user['User']['id']) && !empty($user['User']['id'])) {
                                $this->loadModel('AccessToken');
                                $token = $this->AccessToken->generateToken($this->Common->currentGame('app'), $user['User']['id']);
                                $this->loadModel('Account');
                                $this->Account->contain();
                                $account = $this->Account->findByUserIdAndGameId(
                                    $user['User']['id'],
                                    $this->Common->currentGame('id')
                                );
                                $data = array_merge(
                                    array(
                                        'User' => array(
                                            'username' => substr($user['User']['username'], 4),
                                            'account_id' => $account['Account']['account_id']
                                        )),
                                    array(
                                        'access_token' 	=> $token['AccessToken']['token'],
                                        'token_expire' 	=> $token['AccessToken']['expired'],
                                        'indulge'		=> 1,
                                        'uid'	=> $account['Account']['account_id'],
                                        'ipv4'	=> $this->Common->publicClientIp(),
                                        'uname'	=> substr($user['User']['username'], 4),
                                        'KL_SSO'=> $account['Account']['account_id'],
                                        "KL_PERSON"	=> "HbzJXvrN14tizpsCilhL9zt-iNCtGzETlRdCLrEcfALa8k679L4vwQHMJN-5m-cOJm3Wqhg3-YE7EdI9-WX.SsCU49NIYeHUsLvq8anfi2GFO_AogqNkS6Uv4jQp.qxfgRdQnxpOzEeH_tpPLqWPlX_9kS1F5lb_c258dKhzKVG9.GJIlu-9l9_aqsvkGAK.pqkkDdvI6fP3uetKI7nJhSzSOyjpWuZSoGGVzlEvAG9R4gS3c3rlAQCZd58G5fxjC8sE9mSh.uGnOzOxuWAnx7QAdc_6d6Iva7Zou5YfpqM0",
                                        "isnew"		=> "true"
                                    )
                                );

                                $result = array(
                                    'retcode' 	=> 0,
                                    'data' 		=> $data,
                                    'message' 	=> __('Đổi mật khẩu thành công')
                                );
                                goto end;
                            }
                        } else {
                            $result = array(
                                'retcode' 	=> 5,
                                'retmsg' 	=> __('Đổi mật khẩu không thành công, không lưu được dữ liệu')
                            );
                            goto end;
                        }
                    } else {
                        if (!empty($this->User->validationErrors)) {
                            $result = array(
                                'retcode' 	=> 5,
                                'retmsg' 	=> $this->User->validationErrors['password'][0]
                            );
                            goto end;
                        }
                    }
                } else {
                    $result = array(
                        'retcode' 	=> 5,
                        'retmsg' 	=> __('Mật khẩu cũ không chính xác')
                    );
                    goto end;
                }
            } else {
                $result = array(
                    'retcode' 	=> 4,
                    'retmsg' 	=> __('Không tìm thấy người chơi')
                );
                goto end;
            }
        }catch (Exception $e){
            $result = array(
                'retcode' 	=> 500,
                'retmsg' 	=> __('Lỗi không xác định')
            );
            goto end;
        }

        end:
        $this->set('result', $result);
        $this->set('_serialize', 'result');
    }

	public function api_update_info()
	{
		$result = array(
			'status' => 1,
			'messsage' => __('lỗi')
		);

		if( !$this->Auth->loggedIn() ){
			CakeLog::error('Vui lòng login hoặc truyền access token tới api update info', 'user');
			$result = array(
				'status' => 2,
				'messsage' => __('Vui lòng login hoặc truyền access token')
			);
			goto end;
		}

		if (!isset(
			$this->request->data['email'],
			$this->request->data['phone']
		)) {
			CakeLog::error('Thiếu dữ liệu khi gọi api update info', 'user');
			$result = array(
				'status' => 3,
				'message' => 'Thiếu dữ liệu khi gọi api'
			);
			goto end;
		}

		$user = $this->Auth->user();

		if (!empty($this->request->data)) {
			$this->request->data['User'] = $this->request->data;
			$this->request->data['User']['id'] = $user['id'];
			$this->request->data['User']['active'] = 1;

			$this->User->validator()->remove('password')->remove('username')
				->remove('phone', 'unique_phone');
			if ($this->User->add($this->request->data, false)) {
				$result = array(
					'status' => 0,
					'message' => __('Cập nhật thông tin thành công')
				);
			}else{
				CakeLog::error('user info:' . print_r($this->User->validationErrors,true), 'user');
			}
		}

		end:
		$this->set('result', $result);
		$this->set('_serialize', 'result');
	}
	
	public function test_sendmail(){
        $options = array(
            'template' => 'default',
            'subject' => __('Thay đổi mật khẩu tài khoản'),
            'layout' => 'default'
        );
        try {
            $Email = new CakeEmail('amazonses');
            $email = $Email->to('quanhongvu@gmail.com')
                ->subject($options['subject'])
                ->viewVars(array(
                    'content' => 'Test nội dung',
                ))
                ->template($options['template'], $options['layout'])
                ->send();

            debug($email);
        }catch (Exception $e){
            CakeLog::error($e->getMessage());
            debug($e->getMessage());
        }
        die;
    }
}
