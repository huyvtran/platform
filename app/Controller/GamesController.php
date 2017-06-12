<?php

App::uses('AppController', 'Controller');

class GamesController extends AppController {

    public $components = array(
        'Security' => array(
            'csrfExpires' => '+180 minutes'
        ),
        'Search.Prg'
    );

    public $cacheAction = array(
    );

    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->layout = 'default_bootstrap';
        $this->Security->unlockedActions = array('admin_game_load', 'admin_index');
        $this->Auth->allow(array(
            'dashboard'
        ));
    }

    public function admin_add()
    {
        if ($this->request->is('post')) {
            $this->Game->create();
            $this->request->data['Game']['app'] = md5(uniqid());
            $this->request->data['Game']['secret_key'] = md5(uniqid());

            if ($this->Auth->user('username')) {
                $this->request->data['Game']['last_username'] = $this->Auth->user('username');
            }

            if ($this->Game->save($this->request->data)) {
                $this->Session->setFlash('The game has been saved');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash('The game could not be saved. Please, try again.');
            }
        }
    }
    
    public function admin_index()
    {
        $this->Prg->commonProcess();

        $parsedConditions = array();
        if (!empty($this->passedArgs)) {
            $parsedConditions = $this->Game->parseCriteria($this->passedArgs);
        }

        $parsedConditions = array_merge(array(
            'Game.id' => $this->Session->read('Auth.User.permission_game_default')
        ), $parsedConditions);

        $this->Game->contain(array('Website'));
        
        $paginateCriteria = $this->Paginator->settings['Game'] = array(
            'limit' => 100,
            'order' => array('Game.id' => 'desc'),
            'conditions' => $parsedConditions
        );


        $newData = $this->paginate();
        $page = empty($this->request->params['named']['page']) ? '' : $this->request->params['named']['page'];
        $games = Cache::read('admin_games_index_list_games' . md5(serialize($paginateCriteria)) . $page, 'info');
        if ($games === false) {
            $games = $newData;

            foreach ($games as $key => $game) {

                $games[$key]['Game'] = $this->__checkMissingDataForGame($game);
            }
            Cache::write('admin_games_index_list_games' . md5(serialize($paginateCriteria)) . $page, $games, 'info');
        }

        $this->set('games', $games);
    }

    public function admin_edit($id = null)
    {

        if (!$this->Game->exists($id)) {
            throw new NotFoundException('Invalid game');
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $games = $this->Game->findById($id);
            if (isset($this->request->data['Game']['data'])) {
                $this->request->data['Game']['data'] = Hash::merge($games['Game']['data'],$this->request->data['Game']['data']);
            }
            if ($this->Auth->user('username')) {
                $this->request->data['Game']['last_username'] = $this->Auth->user('username');
            }
            if ($this->Game->save($this->request->data)) {
                $this->Session->setFlash('The game has been saved', 'success');
                $this->redirect(array('action' => 'admin_edit', $this->Game->id));
            } else {
                $this->Session->setFlash($this->Game->validationErrors, 'error');
            }
        } else {
            $options = array('conditions' => array(
                'Game.' . $this->Game->primaryKey => $id,
            ));
            $this->Game->contain(array('Genre','Website'));

            $this->request->data = $this->Game->find('first', $options);
        }
        $this->request->data['Game'] = $this->__checkMissingDataForGame($this->request->data);
        if (!empty($this->request->data['Game']['errors']['tech'])) {
            $this->Session->setFlash($this->request->data['Game']['errors']['tech'], 'error');
        }
        # Find all websites but locked
        $websites = $this->Game->Website->find('all');
        $websites = Hash::combine($websites, '{n}.Website.id', array('%s : %s', '{n}.Website.title', '{n}.Website.url'));

        $genres = $this->Game->Genre->find('list');
        $this->set(compact('genres', 'websites'));
    }

    public function admin_editDescription($id = null)
    {

        if (!$this->Game->exists($id)) {
            throw new NotFoundException('Invalid game');
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            $dataSource = $this->Game->getDatasource();
            $dataSource->begin();

            $games = $this->Game->findById($id);

            if ($this->request->data['Game']['data']) {
                $this->request->data['Game'] = Hash::merge($games['Game'], $this->request->data['Game']);
            }
            if ($this->Auth->user('username')) {
                $this->request->data['Game']['last_username'] = $this->Auth->user('username');
            }

            if(isset($this->request->data['Game']['data']['game_update_date']) && $this->request->data['Game']['data']['game_update_date'] !=''
            && isset($this->request->data['Game']['data']['game_update_hour']) && $this->request->data['Game']['data']['game_update_hour'] !='' ){
                $this->request->data['Game']['data']['game_updated_at'] = $this->request->data['Game']['data']['game_update_date']." ". $this->request->data['Game']['data']['game_update_hour'].':00';;
            }
            if($this->request->data['Game']['status'] == '1' && $this->request->data['Game']['published_date']==''){
                $this->request->data['Game']['published_date'] = date('Y-m-d h:i:s');
            }

            if ($this->Game->save($this->request->data)) {
                $dataSource->commit();
            } else {
                $this->Session->setFlash($this->Game->validationErrors, 'error');
            }
        }

        $options = array('conditions' => array(
            'Game.' . $this->Game->primaryKey => $id,
        ));
        $this->Game->contain(array('Genre'));
        $game = $this->Game->find('first', $options);
        if(isset($game['Game']['data']['game_updated_at'])) {
            $public_date_int = strtotime($game['Game']['data']['game_updated_at']);
            $game['Game']['data']['game_update_date'] = date('Y-m-d', $public_date_int);
            $game['Game']['data']['game_update_hour'] = date('H:i', $public_date_int);
        }
        if(isset($game['Game']['published_date'])) {
            $public_date_int = strtotime($game['Game']['published_date']);
            $game['Game']['publish_date'] = date('Y-m-d', $public_date_int);
            $game['Game']['publish_hour'] = date('H:i', $public_date_int);
        }
        $this->request->data = $game;

        $this->request->data['Game'] = $this->__checkMissingDataForGame($game);
        if (!empty($this->request->data['Game']['errors']['content'])) {
            $this->Session->setFlash($this->request->data['Game']['errors']['content'], 'error');
        }
        $genres = $this->Game->Genre->find('list');
        $this->set(compact('genres'));
    }




    private function __checkMissingDataForGame($game, $sesNotify = array(), $dkims = array())
    {
        $gameData = $game['Game'];

        # ko check
        return $gameData;

        $errors = array();
        if (empty($gameData['fb_appid'])) {
            $errors['tech']['fb_appid'] = "<a href&#61;&quot;".Router::url(
                array(
                    'controller'=>'games',
                    'action'=>'admin_edit',
                    $gameData['id'],
                    '?' => array('error_field' => 'fb_appid')
                ))."&quot;>Facebook AppID is missing.</a>";
        }

        if (empty($gameData['app_gaid'])) {
            $errors['tech']['app_gaid'] = "<a href&#61;&quot;".Router::url(
                array(
                    'controller'=>'games',
                    'action'=>'admin_edit',
                    $gameData['id'],
                    '?' => array('error_field' => 'app_gaid')
                ))."&quot;>GA_ID is missing.</a>";
        }

        if (empty($gameData['dashboard_gaid'])) {
            $errors['tech']['dashboard_gaid'] = "<a href&#61;&quot;".Router::url(
                array(
                    'controller'=>'games',
                    'action'=>'admin_edit',
                    $gameData['id'],
                    '?' => array('error_field' => 'dashboard_gaid')
                ))."&quot;>dashboard GA_ID is missing.</a>";
        }
        if ($gameData['os'] == 'android' && empty($gameData['gcm_key'])) {
            $errors['tech']['gcm_key'] = "<a href&#61;&quot;".Router::url(
                array(
                    'controller'=>'games',
                    'action'=>'admin_edit',
                    $gameData['id'],
                    '?' => array('error_field' => 'gcm_key')
                ))."&quot;>Gcm key is missing.</a>";
        }
        if($gameData['os'] == 'ios' && !file_exists(VENDORS . 'apns-cert' . DS . $gameData['alias'] . DS . 'ck.pem') &&
            (empty($gameData['data']['pem_url']) || $gameData['data']['pem_url'] == '') ){
            $errors['sdk']['ck_pem'] = "<a href&#61;&quot;".Router::url(
                array(
                    'controller'=>'games',
                    'action'=>'admin_editofsdk',
                    $gameData['id'],
                    '?' => array('error_field' => 'ck_pem')
            ))."&quot;>file ck.pem  is missing.</a>";
        }

        if(!isset($gameData['fbpage_id'])
            || empty($gameData['fbpage_id'])
            || $gameData['fbpage_id'] == ""){
            $errors['content']['fbpage_id'] = "<a href&#61;&quot;".Router::url(
                array(
                    'controller'=>'games',
                    'action'=>'admin_editDescription',
                    $gameData['id'],
                    '?' => array('error_field' => 'fbpage_id')
                ))."&quot;>Fanpage Id is missing.</a>";
        }

        if(!isset($gameData['data']['fbpage_url'])
            || empty($gameData['data']['fbpage_url'])
            || $gameData['data']['fbpage_url'] == ""){
            $errors['content']['fbpage_url'] = "<a href&#61;&quot;".Router::url(
                array(
                    'controller'=>'games',
                    'action'=>'admin_editDescription',
                    $gameData['id'],
                    '?' => array('error_field' => 'fbpage_url')
                ))."&quot;>Fanpage URL is missing.</a>";
        }

        if(!isset($gameData['data']['group_fb_id'])
            || empty($gameData['data']['group_fb_id'])
            || $gameData['data']['group_fb_id'] == ""){
            $errors['content']['group_fb_id'] = "<a href&#61;&quot;".Router::url(
                array(
                    'controller'=>'games',
                    'action'=>'admin_editDescription',
                    $gameData['id'],
                    '?' => array('error_field' => 'group_fb_id')
                ))."&quot;>Group Facebook ID is missing.</a>";
        }

        if(!isset($gameData['support_email'])
            || empty($gameData['support_email'])
            || $gameData['support_email'] == ""){
            $errors['content']['support_email'] = "<a href&#61;&quot;".Router::url(
                array(
                    'controller'=>'games',
                    'action'=>'admin_editDescription',
                    $gameData['id'],
                    '?' => array('error_field' => 'support_email')
                ))."&quot;>Support Email is missing.</a>";
        }

        if(!isset($gameData['appstore_link'])
            || empty($gameData['appstore_link'])
            || $gameData['appstore_link'] == ""){

            if($gameData['os'] == 'android')
                $errors['content']['appstore_link'] = "<a href&#61;&quot;".Router::url(
                    array(
                        'controller'=>'games',
                        'action'=>'admin_editDescription',
                        $gameData['id'],
                        '?' => array('error_field' => 'appstore_link')
                    ))."&quot;>Google Play Link is missing.</a>";

            if($gameData['os'] == 'ios')
                $errors['content']['appstore_link'] = "<a href&#61;&quot;".Router::url(
                    array(
                        'controller'=>'games',
                        'action'=>'admin_editDescription',
                        $gameData['id'],
                        '?' => array('error_field' => 'appstore_link')
                    ))."&quot;>Appstore Link is missing.</a>";
        }

        if(!isset($gameData['parsed_description'])
            || empty($gameData['parsed_description'])
            || $gameData['parsed_description'] == ""){
            $errors['content']['description'] = "<a href&#61;&quot;" . Router::url(
                    array(
                        'controller' => 'games',
                        'action' => 'admin_editDescription',
                        $gameData['id'],
                        '?' => array('error_field' => 'description')
                    )) . "&quot;>Description are missing.</a>";
        }

        $gameData['errors'] = $errors;

        return $gameData;
    }

    public function admin_permission($type = '')
    {
        $this->loadModel('Game');
        $this->loadModel('Permission');
        $this->loadModel('User');
	    $conditions = array(
		    'Game.alias !=' => '',
		    'Game.id' => $this->Session->read('Auth.User.permission_game_default'),
		    'Game.status' => 1,
	    );
        if (!empty($type)) {
        	$conditions = array(
		        'Game.alias !=' => '',
		        'Game.id' => $this->Session->read('Auth.User.permission_game_default'),
	        );
        }
        $games = $this->Game->find('all',array(
            'conditions' => $conditions,
	        'recursive'  => -1,
            'group'=>'alias',
            'order'=>'Game.id DESC'
        ));
        $users = $this->Permission->find('all',array(
            'conditions' => array(
                'Permission.access' => 1,
                'Permission.model'  => 'Game',
                'User.active' => 1,
            ),
            'joins' => array(
                array(
                    'table' => 'games',
                    'alias' => 'Game',
                    'conditions' => array('Game.id = Permission.foreign_key'),
                ),
                array(
                    'table' => 'users',
                    'alias' => 'User',
                    'conditions' => array('User.id = Permission.user_id'),
                ),
            ),
            'fields' => array(
                'User.username',
                'Permission.foreign_key',
                'User.role',
            ),
            'group' => array('User.id','Game.alias'),
            'order' => array('User.username' => 'ASC'),
        ));
        $this->set(compact('games','users'));
        unset($users);
        unset($games);
    }
    
    public function admin_game_load()
    {
        if ($this->request->is('Post')) {
            $title = $this->request->data['title'];
            $alias = $this->request->data['alias'];
            if (isset($this->request->data['game_id'])) {
                if ($this->request->data['total'] != 0) {
                    $users = $this->Permission->find('all', array(
                        'conditions' => array(
                            'foreign_key' => $this->request->data['game_id'],
                            'access =' => 1,
                            'model ' => "Game",
                        ),
                        'joins' => array(
                            array(
                                "table" => "users",
                                "alias" => "User",
                                "conditions" => array("User.id = Permission.user_id"),
                            ),
                        ),
                        'fields' => array(
                            'DISTINCT User.id',
                            'User.username',
                            'User.email',
                            'User.role',
                        ),
                        'order' => array('User.username'=>'ASC'),
                    ));
                } else {
                    $users = 1;
                }
            }
        }
        $this->set(compact('users','title','alias'));
        $this->layout = 'blank';
    }

    public function dashboard()
    {
        if ($this->request->query('app_key')) {
            $appKey = $this->request->query('app_key');
        } elseif ($this->request->query('appkey')) {
            $appKey = $this->request->query('appkey');
        } elseif ($this->request->query('app')) {
            $appKey = $this->request->query('app');
        }
        if (!isset($appKey)) {
            throw new BadRequestException();
        }
        
        $game = $this->Game->find('first', array(
            'conditions' => array(
                'app' => $appKey
            ),
            'contain' => array(
                'Website'
            )
        ));
        
        if (empty($game)) {
            throw new BadRequestException('Không tìm thấy game này');
        }
        $website = $this->Common->currentWebsite();

        $this->set(compact('game', 'website'));

        $this->Common->setTheme();
        $this->layout = 'default';
        # Set variable currentGame in View
        $this->Common->currentGame();
        $this->set('title_for_layout', 'Dashboard');
    }

    public function admin_editofsdk($id = null)
    {
        if (!$this->Game->exists($id)) {
            throw new NotFoundException('Invalid game');
        }
        if ($this->request->is('post') || $this->request->is('put')) {

            $dataSource = $this->Game->getDatasource();
            $dataSource->begin();

            $games = $this->Game->findById($id);

            if (!empty($this->request->data['Game'])) {
                $this->request->data['Game'] = Hash::merge($games['Game'],$this->request->data['Game']);
            }else{
                $this->request->data['Game'] = $games['Game'];
            }

            if ($this->Auth->user('username')) {
                $this->request->data['Game']['last_username'] = $this->Auth->user('username');
            }
            if ($game_save = $this->Game->save($this->request->data)) {
                $dataSource->commit();
            } else {
                $this->Session->setFlash($this->Game->validationErrors, 'error');
            }

            if(!empty($game_save)) {
                $this->Session->setFlash('The game has been saved', 'success');
                $this->redirect(array('action' => 'admin_index'));
            }else{
                $this->Session->setFlash($this->Game->validationErrors, 'error');
            }
        } else {
            $options = array('conditions' => array(
                'Game.' . $this->Game->primaryKey => $id,
            ));
            $this->Game->contain(array('Genre','Website'));
            $this->request->data = $this->Game->find('first', $options);

            $this->request->data['Game'] = $this->__checkMissingDataForGame($this->request->data);
            if (!empty($this->request->data['Game']['errors']['sdk'])) {
                $this->Session->setFlash($this->request->data['Game']['errors']['sdk'], 'error');
            }
        }
    }
}
