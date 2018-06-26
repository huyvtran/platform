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
			'api_play_now',
			'api_login_takan', 'api_register_takan',
			'api_login_ldr', 'api_register_ldr', 'api_change_password_ldr',
			'api_register_v26', 'api_login_v26', 'api_change_password_v26',
            'reset_password_web', 'reset_password_web_comfirm'
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
		}

        if (!empty($this->request->params['ext']) && $this->request->params['ext'] == 'json') {
            $this->set('result', array(
                'status' => 0,
                'message' => 'success'
            ));
            $this->set('_serialize', 'result');
        }else{
            $website_url = $this->referer('/', true);
            if ($this->request->query('app')){
                $website_url = $this->Common->currentWebsite('url');
                $website_url = 'http://' . $website_url ;
            }
            if($this->request->query('reditectTo')){
                $website_url = $this->request->query('reditectTo');
                $website_url = 'http://' . $website_url ;
            }
            $this->redirect($website_url);
        }
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
			));
			$this->User->contain(array('Game'));
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
			'status' => 900,
			'message' => 'error'
		);

		if (!isset(
			$this->request->data['username'],
			$this->request->data['password'],
			$this->request->data['email']
		)) {
			$result = array(
				'status' => 900,
				'message' => __('Thiếu thông tin đăng ký')
			);
			goto end;
		}

		$prefix_user = '';
		$game = $this->Common->currentGame();

		CakeLog::info('api_register - game id:' . $game['id'] . '\n data:' . print_r($this->request->data,true), 'user');

		if( !empty( $game['data']['prefix'] ) ){
			$prefix_user = $game['data']['prefix'] ;
		}

		$this->request->data['User'] = $this->request->data;
		$this->request->data['User']['email'] 	= $this->request->data['email'];
		$this->request->data['User']['role'] 	= 'User';
		$this->request->data['User']['active'] 	= true;
		$this->request->data['User']['password'] = $this->request->data['password'];
		$this->request->data['User']['username'] = $prefix_user . $this->request->data['username'];

		$userCheck = $this->User->findByUsername($this->request->data['User']['username']);
		if( !empty($userCheck['User']) ){
			$result = array(
				'status' => 900,
				'message' => __('tài khoản đã tồn tại')
			);
			goto end;
		}

		$this->User->validator()->remove('email')->remove('password', 'confirmPassword');
		$this->User->validator()->remove('phone');

		if ($this->Auth->user()) {
			$data = $this->Command->authen('login', true);
			$result = array(
				'status' => 0,
				'message' => __('đăng kí thành công'),
				'data' => $data
			);
			goto end;
		}

		if ($this->request->is('post')) {
			if ($this->Auth->user()) {
				$data = $this->Command->authen('login', true);
				$result = array(
					'status' => 0,
					'message' => __('đăng kí thành công'),
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
				$result = array(
					'status' => 0,
					'message' => __('đăng kí thành công'),
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
					'status' => 5,
					'message' => $messageError
				);
				goto end;
			}
		}

		end:
		$this->set('result', $result);
		$this->set('_serialize', 'result');
	}

	public function api_login(){
		$result = array(
			'status' 	=> 1,
			'message' 	=> 'error'
		);

		if (!isset(
			$this->request->data['username'],
			$this->request->data['password']
		)) {
			$result = array(
				'status' 	=> 2,
				'message' 	=> __('Thiếu thông tin đăng ký')
			);
			goto end;
		}

		$prefix_user = '';
		$game = $this->Common->currentGame();
		if( !empty( $game['data']['prefix'] ) ){
			$prefix_user = $game['data']['prefix'] ;
		}

		CakeLog::info('api_login - game id:' . $game['id'] . '\n data:' . print_r($this->request->data,true), 'user');

		$this->request->data['username'] = $prefix_user . $this->request->data['username'] ;
		$this->request->data['password'] = $this->request->data['password'];

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
				$result = array(
					'data' => $data,
					'status' 	=> 0,
					'message' 	=> __('đăng nhập thành công')
				);
			} else {
				$this->Session->setFlash('You has been logged in successfully', 'success');
				$this->redirect(array('action' => 'login_successful'));
			}
		} else {
			$result = array(
				'status' => 5,
				'message' => __('Tên đăng nhập và/hoặc mật khẩu không đúng!')
			);
			goto end;
		}

		end:
		$this->set('result', $result);
		$this->set('_serialize', 'result');
	}

	public function api_change_password(){
        try {
            if ($this->request->is('post')) {
                $this->Common->bruteForce(array(
                    'ip' => $this->Common->publicClientIp(),
                    'action' => 'api_change_password',
                    $this->Common->currentGame('id'),
                ), 60*60, 30);
            }
        } catch (Exception $e) {
            $result = array(
                'status' => 900,
                'messsage' => 'vui lòng thử lại sau ít phút'
            );
            goto end;
        }

        try{
            $result = array(
                'status' => 1,
                'messsage' => __('lỗi')
            );

            if (!isset(
                $this->request->data['username'],
                $this->request->data['password'],
                $this->request->data['new_password']
            )) {
                $result = array(
                    'status' => 2,
                    'message' => __('Thiếu thông tin đổi mật khẩu')
                );
                goto end;
            }

            $prefix_user = ''; // client gọi sang vẫn có tiền tố, ko check

            $old_password = $this->request->data['password'];

            $this->request->data['User']['password'] = $this->request->data['new_password'];
            $this->request->data['User']['username'] = $prefix_user . $this->request->data['username'];

            $game = $this->Common->currentGame();

            CakeLog::info('api_change_password - game id:' . $game['id'] . '\n data:' . print_r($this->request->data,true), 'user');
            $user = $this->User->findByUsername($this->request->data['User']['username']);
            if (!empty($user)) {
                $this->User->data['User']['password'] = Security::hash($this->request->data['User']['password'], 'sha1', true);
                $this->User->set($this->User->data);
                if ($user['User']['password'] == Security::hash($old_password, 'sha1', true)) {
                    $this->User->validator()->remove('password', 'confirmPassword');
                    if ($this->User->validates(array('fieldList' => array('password')))) {
                        $this->User->id = $user['User']['id'];
                        if ($this->User->save($this->User->data, false, array('password'))) {
                            if (isset($user['User']['id']) && !empty($user['User']['id'])) {
                                $this->loadModel('AccessToken');
                                $token = $this->AccessToken->generateToken($this->Common->currentGame('app'), $user['User']['id']);

                                $account_id = $this->Common->getAccount();

                                $data = array(
                                    'token'         => $token['AccessToken']['token'],
                                    'account_id'	=> $account_id,
                                    'user_id'		=> $user['User']['id'],
                                    'username' 	    => $user['User']['username']
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

	public function admin_edit($id = null)
	{
        $this->User->recursive = -1;
		if (!$id || !$user = $this->User->findById($id)) {
			throw new NotFoundException("Can not find this user");
		}

		if (!empty($this->request->data)) {
            # ghi log password cũ vào redis, exprite 24h
            if( $this->request->action == 'admin_editContent'){
                App::import('Lib', 'RedisCake');
                $Redis = new RedisCake('action_count');
                $key = 'reset_password_' . $id;
                $Redis->set($key,$user['User']['password']);
                $Redis->expire($key, 24*60*60 );
            }

			$this->request->data['User']['active'] = 1;
            $this->request->data['User']['payment'] = $user['User']['payment'];
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
        CakeLog::info('show pass id:' . $user['User']['id'] . ' - pass: '. $user['User']['password'] , 'user');

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

		$prefix_user = '';
		$game = $this->Common->currentGame();

        CakeLog::info('api_register_v26 - game id:' . $game['id'] . '\n data:' . print_r($this->request->data,true), 'user');

        if( !empty( $game['data']['prefix'] ) ){
            $prefix_user = $game['data']['prefix'] ;
        }

        # giá trị mặc định các game mu, ko đổi được, chả hiểu sao vậy
        $email = time().'@myapp.com';
        $phone = $this->request->data['email'];
        if( in_array($game['app'], array('17b05f0d4e311c83a62c0251165d23ef', '4297d1cee18d6b055e1f0e752bec90ab')) ){
            $email = $this->request->data['email'];
            $phone = '01234567891';
            $this->request->data['password'] = md5($this->request->data['password']);
        }

		$this->request->data['User'] = $this->request->data;
		$this->request->data['User']['email'] 	= $email;
		$this->request->data['User']['role'] 	= 'User';
		$this->request->data['User']['active'] 	= true;
		$this->request->data['User']['phone'] 	= $phone;
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

		$prefix_user = '';
		$game = $this->Common->currentGame();
        if( !empty( $game['data']['prefix'] ) ){
            $prefix_user = $game['data']['prefix'] ;
        }

        if( in_array($game['app'], array('17b05f0d4e311c83a62c0251165d23ef', '4297d1cee18d6b055e1f0e752bec90ab')) ){
            $this->request->data['userpass'] = md5($this->request->data['userpass']);
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
			    if( !empty($user['User']) && $user['User']['active'] === false){
                    $result = array(
                        'retcode' => 99,
                        'retmsg' => __('Your account has been locked, please contact admin!')
                    );
                    goto end;
                }
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

        if($this->request->data['username'] == 'r13_test123'){
            CakeLog::info('check login result:' . print_r($result,true), 'user');
        }
        
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

            $prefix_user = ''; // client gọi sang vẫn có tiền tố, ko check
            $game = $this->Common->currentGame();
            if( !empty( $game['data']['prefix'] ) ){
                $prefix_user = $game['data']['prefix'] ;
            }

            $old_password = $this->request->data['password'];

            $this->request->data['User']['password'] = $this->request->data['new_password'];
            $this->request->data['User']['username'] = $prefix_user . $this->request->data['user_name'];

            CakeLog::info('api_change_password_v26 - game id:' . $game['id'] . '\n data:' . print_r($this->request->data,true), 'user');
            $user = $this->User->findByUsername($this->request->data['User']['username']);
            if (!empty($user)) {
                $this->User->data['User']['password'] = Security::hash($this->request->data['User']['password'], 'sha1', true);
                $this->User->set($this->User->data);
                if ($user['User']['password'] == Security::hash($old_password, 'sha1', true)) {
                    $this->User->validator()->remove('password', 'confirmPassword');
                    if ($this->User->validates(array('fieldList' => array('password')))) {
                        $this->User->id = $user['User']['id'];
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
            $this->request->data['User']['payment'] = $user['payment'];
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

	public function admin_reset_password($id = null){
        $this->User->recursive = -1;
        if (!$id || !$user = $this->User->findById($id)) {
            throw new NotFoundException("Can not find this user");
        }

        App::import('Lib', 'RedisCake');
        $Redis = new RedisCake('action_count');
        $key = 'reset_password_' . $id;
        $password = $Redis->get($key);

        $Redis2 = new RedisQueue();
        $Redis2->rPush(array(
            'model' => 'User',
            'data' => array(
                'id'        => $id,
                'password'  => $password
            )
        ));
        $Redis->del($key);

        $this->Session->setFlash('The User has been updated', 'success');
        $this->redirect(array('action' => 'index'));
    }

    /**
     * Nhận mac_address từ app
     */
    public function api_play_now()
    {
        $result = array(
            'status' => 500,
            'message' => 'error'
        );

        if ($this->request->is('post')) {

            if (!isset(
                $this->request->data['key'],
                $this->request->data['mac_address']
            )) {
                $result = array(
                    'status' => 900,
                    'message' => __('Thiếu thông tin đăng ký')
                );
                goto end;
            }

            # Kiểm tra security key
            if (strtolower($this->request->data['key']) != strtolower(Security::hash($this->request->data['mac_address'] . $this->Common->currentGame('secret_key'), 'sha1'))) {
                throw new BadRequestException('Invalid Key');
            }

            # Login ngay lập tức nếu device_id (mac address, chưa update) đã có.
            $this->User->contain(array(
                'Account' => array(
                    'conditions' => array(
                        'game_id' => $this->Common->currentGame('id')
                    )
                )
            ));
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'device_id' => $this->request->data['mac_address'],
                    'email' => null
                )
            ));

            # lock users
            $this->User->query("SELECT * FROM users LIMIT 1 FOR UPDATE");
            # lock accounts
            $this->User->query("SELECT * FROM accounts LIMIT 1 FOR UPDATE");

            if (!empty($user)) {
                if (empty($user['Account'])) {
                    $dataSource = $this->User->getDataSource();
                    $dataSource->begin();
                    $this->User->createAccount($this->Common->currentGame(), $user['User']['id']);
                    $dataSource->commit();
                }
                $this->Auth->login($user['User']);
                $data = $this->Command->authen('login', true);
                $result = array(
                    'status' => 0,
                    'message' => __('đăng nhập thành công'),
                    'data' => $data
                );
                goto end;
            }

            $this->Session->write('mac_address', $this->request->data['mac_address']);
            $result = $this->_register_guest(true);
            goto end;
        }
        CakeLog::info('reveiceMacAddress do not have data POST , SERVER info: ' . print_r($_SERVER, true), 'user');
        throw new BadRequestException('Không có dữ liệu POST');

        end:
        $this->set('result', $result);
        $this->set('_serialize', 'result');
    }

    protected function _register_guest($return = false)
    {
        $this->layout = 'login';

        $result = array(
            'status' => 500,
            'message' => 'error'
        );

        if ($this->Auth->loggedIn()) {
            $data = $this->Command->authen('login', true);
            $result = array(
                'status' => 0,
                'message' => __('đăng nhập thành công'),
                'data' => $data
            );
            if($return) return $result;
        }

        if (!$this->Session->read('mac_address')) {
            throw new BadRequestException('Can not access directly');
        }

        $this->request->data['User']['device_id'] = $this->Session->read('mac_address');
        $this->request->data['User']['active'] = true;
        $this->request->data['User']['role'] = 'Guest';
        $this->request->data['User']['country_code'] = $this->User->getCountry();

        unset($this->User->validate['email']);
        unset($this->User->validate['password']);
        unset($this->User->validate['phone']);

        $this->User->contain();
        $lastUser = $this->User->find('first', array(
            'order' => array('id' => 'DESC')
        ));

        $this->request->data['User']['username'] = 'Q' .sprintf('%02d', $lastUser['User']['id']) . rand(1, 9);

        $dataSource = $this->User->getDataSource();
        $dataSource->begin();

        if ($this->User->save($this->request->data)) {
            $this->User->createAccount($this->Common->currentGame());

            $dataSource->commit();
            $this->User->read();
            $this->Auth->login($this->User->data['User']);

            $data = $this->Command->authen('login', true);
            $result = array(
                'status' => 0,
                'message' => __('đăng kí thành công'),
                'data' => $data
            );
            if($return) return $result;

        } else {
            $dataSource->rollback();
            if (!empty($this->User->validationErrors)) {
                $this->Session->setFlash($this->User->validationErrors, 'error');
            }
        }

        if($return) return $result;
    }

    public function reset_password_web()
    {
        $this->layout = 'default_bootstrap';
        $options = array(
            'from' => array('no-reply@muoriginfree.com' => 'Admin Riot'),
            'template' => 'password_reset_request',
            'subject' => __('Thay đổi mật khẩu tài khoản'),
            'layout' => 'default'
        );

        if (!empty($this->request->data)) {
            if ( $this->Common->verifyRecaptcha($this->request->data['User']['captcha']) ) {
                $user = $this->User->generatePasswordTokenByEmail($this->request->data);
                if (!empty($user)) {
                    try {
                    $Email = new CakeEmail('amazonses');
                    $Email->to($user['User']['email'])
                        ->from($options['from'])
                        ->subject($options['subject'])
                        ->viewVars(array(
                            'user' => $user['User'],
                            'websiteUrl' => 'truyenthuyetrong.com',
                            'emailAddress' => $user['User']['email']
                        ))
                        ->template($options['template'], $options['layout'])
                        ->emailFormat('html')
                        ->send();
                    }catch (Exception $e){
                        CakeLog::error($e->getMessage());
                    }
                    $this->redirect(array('action' => 'reset_password_web_comfirm'));
                } else {
                    unset($this->request->data['User']['captcha']);
                    $this->Session->setFlash(__('Địa chỉ email không tồn tại.'), 'error');
                }
            } else {
                unset($this->request->data['User']['captcha']);
                $this->Session->setFlash(__('Mã xác nhận không đúng.'), 'error');
            }
        }
    }

    /**
     * tool change passwword on admin
     */
    public function reset_password_web_comfirm()
    {
        if ($this->request->is('post')) {
            $user = $this->User->getUserByPasswordToken(strtolower($this->request->data['User']['token']));
            if (empty($user)) {
                $this->Session->setFlash('Invalid PIN code', 'error');
                $this->redirect(array('controller' => 'users', 'action' => 'reset_password_web_comfirm'));
            } else {
                if (!empty($this->request->data) && $this->User->resetPassword(Set::merge($user, $this->request->data))) {
                    $this->Session->setFlash('Changed password successfully', 'success');
                    $this->redirect(array('controller' => 'users', 'action' => 'login'));
                } else {
                    if (!empty($this->User->validationErrors)) {
                        $this->Session->setFlash($this->User->validationErrors, 'error');
                    }
                    $this->redirect(array('controller' => 'users', 'action' => 'reset_password_web_comfirm'));
                }
            }
        }
        $this->layout = 'default_bootstrap';
    }

    public function admin_searchip(){
        $this->layout = 'default_bootstrap';

        $this->loadModel('LogLogin');

        $this->LogLogin->Behaviors->load('Search.Searchable');
        $this->LogLogin->filterArgs = array(
            array('name' => 'ip', 'type' => 'value'),
            array('name' => 'username', 'type' => 'value', 'field' => 'User.username')
        );

        $this->Prg->commonProcess('LogLogin');
        $this->request->data['LogLogin'] = $this->passedArgs;

        $parsedConditions = array();
        if(!empty($this->passedArgs)) {
            $parsedConditions = $this->LogLogin->parseCriteria($this->passedArgs);
        }

        $parsedConditions = array_merge(array(
            'LogLogin.game_id' => $this->Session->read('Auth.User.permission_game_default')
        ), $parsedConditions);

        $this->LogLogin->bindModel(array(
            'belongsTo' => array('Game', 'User')
        ));

        $this->paginate = array(
            'LogLogin' => array(
                'fields' => array(
                    'LogLogin.*', 'Game.title', 'Game.os',
                    'User.username', 'User.id', 'User.active', 'User.payment', 'User.role'
                ),
                'conditions' => $parsedConditions,
                'contain' => array(
                    'Game', 'User'
                ),
                'order' => array('LogLogin.id' => 'DESC'),
                'recursive' => -1,
                'limit' => 20
            )
        );

        $users = $this->paginate('LogLogin');
        $this->set(compact('users'));
    }
}
