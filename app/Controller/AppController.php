<?php

App::uses('Controller', 'Controller');

if (Configure::read('debug') != 0) {
	App::uses('FireCake', 'DebugKit.Lib');
}

class AppController extends Controller {

	public $helpers = array(
		'Session',
		'Html',
		'Form',
		'Js',
		'Time',
		'Paginator',
		'Cache',
		'Nav',
		'Cms'
	);

	public $components = array(
		'Auth',
		'Cookie',
		'Session',
		'Common',
		'RequestHandler',
		'Acl',
		'Paginator',
		'Log',
//		'DebugKit.Toolbar'
	);

	public $menu = array(
		'Users' => array(
			'categories' => array(
				'User - Index' 		=> '/admin/users/index',
				'User - Staff' 		=> '/admin/users/index/staff:1',
				'User - Add' 		=> '/admin/users/add',
			),
			'activeMenu' => array('users')
		),
        'CMS' => array(
            'categories' => array(
                'Categories - Index'	=> '/admin/categories',
                'Categories - Add'		=> '/admin/categories/add',
                'Websites - Index'	=> '/admin/websites',
                'Websites - Add'	=> '/admin/websites/add',
            ),
            'activeMenu' => array('users')
        ),
		'Games' => array(
			'categories' => array(
				'Games - Add'	=> '/admin/games/add',
				'Games - Index'	=> '/admin/games',
				'Games - Permissions' 	=> '/admin/games/permission',
			),
			'activeMenu' => array('games')
		),
		'Debug' => array(
			'categories' => array(
				'Logs - Local'			=> '/admin/administrators/readLog',
				'Cmd'					=> '/admin/administrators/cmd',
				'Redis'					=> '/admin/administrators/redis',
			),
			'activeMenu' => array('debug')
		)
	);

    public function __construct($request = null, $response = null)
    {
    	if (!empty($_GET['debugkit'])) {
        	$this->components[] = 'DebugKit.Toolbar';
        }
        parent::__construct($request, $response); 
    }
    
	public function beforeFilter()
	{
		if (
			# Nếu request từ app có token, mà ko có app_key thì báo lỗi , để tránh trường hợp app quên send header đúng chuẩn
			!$this->request->header('app') && $this->request->header('token')
		) {
			CakeLog::info(print_r($_SERVER, true));
			throw new BadRequestException("Hello, header don't have appkey");
		}

		$this->__setCookie();
		$this->__configAuth();

		if (empty($this->request->params['requested'])){
			$this->__cookieAuth();
			$this->__tokenAuth();
			$this->__lastActivity();
		}

		if (!empty($this->request->data)) {
			$this->request->data = $this->__trimData($this->request->data);
		}

		# Dispatch event before action happen
		$this->getEventManager()->dispatch(new CakeEvent('Controller.beforeTheme', $this));

		if ($this->request->header('app')) {
			$this->getEventManager()->dispatch(new CakeEvent('Controller.beforeGame', $this));
		}

		$this->__setPermissionGame();

		# Check permission user to show menu that user has permission to access
		if($this->Auth->loggedIn() && !in_array($this->Auth->user('role'), array('User', 'Guest'))) {

			if (in_array($this->Auth->user('role'), array('Admin', 'Developer'))) {
				$menu = $this->menu;
			} else {
				$menu = Cache::read("menu_for_staff_id_" . $this->Auth->user('id'), 'long');
			}
			if ($menu === false) {
				foreach ($this->menu as $name1 => $categories) {
					$categories = $categories['categories'];

					ksort($categories);
					$this->menu[$name1]['categories'] = $categories;
					foreach ($categories as $name2 => $category) {
						if (is_bool($category)) continue;

						if (is_string($category)) {
							if( strpos($category, 'http:') === 0
								|| strpos($category, 'https://') === 0
							){
								if( !in_array($this->Auth->user('role'), array('Admin', 'Developer'))){
									unset($this->menu[$name1]['categories'][$name2]);
								}
								continue;
							}

							$parses = Router::parse($category);
							if (!$this->Acl->check($this->Auth->user(), $parses['controller'] . '/' . $parses['action'])) {
								unset($this->menu[$name1]['categories'][$name2]);
							}
						} else { # is array
							foreach ($category as $name3 => $childCategory) {
								$parses = Router::parse($childCategory);
								if (!$this->Acl->check($this->Auth->user(), $parses['controller'] . '/' . $parses['action'])) {
									unset($this->menu[$name1]['categories'][$name2][$name3]);
								}
							}
						}
					}
				}
				$menu = $this->menu;
				Cache::write("menu_for_staff_id_" . $this->Auth->user('id'), $menu, 'long');
			}
			$this->set('menu', $menu);
		}

	}

	public function beforeRender()
	{
		# Dispatch event after action happen
		$this->getEventManager()->dispatch(new CakeEvent('Controller.afterTheme', $this));
		if ($this->request->header('app')) {
			$this->getEventManager()->dispatch(new CakeEvent('Controller.afterGame', $this));
		}		
		if (!isset($this->viewVars['title_for_layout'])){
			$this->set('title_for_layout', implode('-', array_merge(array(ucfirst($this->request->params['controller'])), array_reverse(explode('_', $this->request->params['action'])))));
		}
	}

	public function afterFilter()
	{
	}

	public function __setPermissionGame()
	{
		if ($this->Auth->loggedIn()) {
			if (!in_array($this->Auth->user('role'), array('Guest', 'User'))) {
				$this->loadModel('Permission');
				if (in_array($this->Auth->user('role'), $this->Permission->allGameRoles)) {
					$this->loadModel('Game');
					$this->Session->write('Auth.User.permission_game_default', $this->Game->find('list', array('fields' => array('id', 'id'))));
				} else {
					$permissions = $this->Permission->getRightIds('Game', $this->Auth->user('id'), 'Default');
					$this->Session->write('Auth.User.permission_game_default', $permissions);
				}
			}
		}
	}
	/**
	 *  Set Cookies default configs
	 */
	protected function __setCookie()
	{
		$this->Cookie->type('rijndael');
		$this->Cookie->name = 'App';
		$this->Cookie->time = '90 days';
		$this->Cookie->path = '/';

		# Dev or production server
		if (empty($_SERVER['APPLICATION_ENV'])) {
			$domain = get_domain(env('HTTP_HOST'));
		} else {
			$this->Cookie->name = 'Dev';
			$domain = env('HTTP_HOST');
		}
		$this->Cookie->domain = $domain;
//		if (strpos($domain, 'localhost') !== false) {
//			$this->Cookie->domain = '';
//		}

		$this->Cookie->key = 'qSdd%ddId232qADYhG93b0qyJfIxfs1232guVoUubWwvaniR2G0FgaC9mis*&saX6Owsd121!';
	}
	/**
	 * Set Authen default configs 
	 */
	protected function __configAuth()
	{
		$this->Auth->userModel = 'User';
		AuthComponent::$sessionKey = 'Auth.User';
		$this->Auth->authorize = array(
			'Actions'
		);

		if (env('http_app') || env('http_token')) {
			$this->Auth->loginAction = array(
				'admin' => false,
				'controller' => 'users',
				'action' => 'index'
			);
		} else {
			$this->Auth->loginAction = array(
				'admin' => false,
				'controller' => 'users',
				'action' => 'login'
			);
		}

		$this->Auth->authError = 'Bạn cần phải đăng nhập tài khoản để truy cập trang này';
		$this->Auth->loginError = 'Sai mật mã hoặc tên tài khoản, xin hãy thử lại';
		$this->Auth->authenticate = array('Form' => array(
				'fields' => array('username' => 'email'),
				'userModel' => 'User',
				'scope' => array(
					// 'User.email_verified' => 1,
					'User.active' => 1
				)
			));

		$this->Auth->loginRedirect = '/';
		$this->Auth->logoutRedirect = $this->referer('/');
	}

	/**
	 * Save user's last activity.
	 */
	protected function __lastActivity()
	{
		if ($this->Auth->loggedIn())
		{
			if (   !$this->Session->read('Auth.User.last_action')
				|| 	(	$this->Session->read('Auth.User.last_action') 
					&& 	strtotime($this->Session->read('Auth.User.last_action')) + 3600 * 2 < time()
					)
			) {
				# push users's last action to redis queue
				if (extension_loaded('redis')) {
					App::import('Lib', 'RedisQueue');
					$Redis = new RedisQueue();
					$Redis->rPush(array(
						'model' => 'User',
						'data' => array(
							'id' => $this->Auth->user('id'),
							'last_action' => date('Y-m-d H:i:s')
						)
					));
				}
				$this->Session->write('Auth.User.last_action', date('Y-m-d H:i:s', time()));
			}
		}
	}

	/**
	 * Login bằng cookies
	 */
	protected function __cookieAuth()
	{
		if (empty($this->request->data['User'])) {
			if (!$this->Auth->loggedIn()) {
				$cookie = $this->Cookie->read('User');
				if (!empty($cookie['username']) || !empty($cookie['email'])) {
					$this->request->data['User'] = $cookie;
					if ($this->Auth->login()) {
						$this->loadModel('User', $this->Auth->user('id'));
						$this->Session->delete('Message.auth');
					}
					unset($this->request->data['User']);
				}
			}
		}
	}
	
	/**
	 * Login bằng token
	 */
	protected function __tokenAuth()
	{
		if (empty($this->request->data['User'])) {
			$token = $this->request->header('token');
			if ( !$this->Auth->loggedIn()
				&& !empty($token)
				&& !($this->request->controller == 'users' 
					&& in_array($this->request->action, array('index'))
                )
			) {
				$this->loadModel('AccessToken');
				$this->AccessToken->contain(array('User'));
				$accessToken = $this->AccessToken->findByToken($token);

				if (!empty($accessToken['User'])) {

					if ($accessToken['User']['active'] == 1) {
						$this->Auth->login($accessToken['User']);
					} else {
						$this->Session->setFlash('Tài khoản này không thể tiếp tục sử dụng hiện thời');
						$this->redirect(array('controller' => 'users', 'action' => 'index'));
					}
				} else {
					$this->redirect(array('controller' => 'users', 'action' => 'index'));
				}
			}
		}
	}

	protected function __trimData($data)
	{
		if (is_array($data)) {
			foreach($data as $key => $val) {
				$data[$key] = $this->__trimData($val);
			}
		} else {
			$data = trim($data);
		}
		return $data;
	}


	/**
	* Function to send json response. This function is generally used when an ajax request is made
	*
	* @param array   $response Data to be sent in json response
	*
	* @return void
	*/
	public function sendJson($response)
	{
		$this->autoRender = false;
		$this->response->type('json');
		// Make sure no debug info is printed
		//Configure::write('debug', 0); // Turn this to 2 for debugging
		$response['data'] = $this->Common->api_encryptBlowfish(json_encode($response['data']));
		$this->response->body(json_encode($response));
	}//end sendJson()

}
