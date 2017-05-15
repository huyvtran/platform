<?php

App::uses('AppController', 'Controller');

class PermissionsController extends AppController
{
    public function admin_index(){
        $this->layout = 'default_bootstrap';
    }

    public function admin_add()
    {
        $user_id = $this->request->query('user_id');
        $this->loadModel('Game');
        $this->loadModel('Website');
        $this->loadModel('Permission');
        $this->loadModel('Distributor');
        $this->loadModel('User');
        $user = $this->User->findById($user_id);
        if (!$user_id && !$this->request->is('ajax')) {
            throw new Exception('Parameters is missing Oo.');
        }
        $options = Permission::$permission;
        $options = array_keys($options);
        $distributor = $this->Distributor->find('list', array(
            'fields' => array('id', 'title'),
        ));
        if (!empty($options)) {
            foreach ($options as $key => $val) {
                $dataPermission = $this->Permission->find('list', array(
                    'fields' => array('foreign_key', 'options'),
                    'conditions' => array(
                        'type' => $val,
                        'user_id' => $user_id,
                        'model' => 'game',
                    ),
                ));
                $foreign_key_checked[$val] = (!empty($dataPermission)) ? $dataPermission : null;
                $foreign_keys[$val] = $this->Game->find('list', array(
                	'conditions' => array('Game.status' => 1),
                    'fields' => array('id', 'title'),
                    'order'  => array('title' => 'ASC'),
                    'group'  => array('alias'),
                ));
            }
        }
        if (!empty($distributor)) {
            $datadistributor = $this->Permission->find('list', array(
                'fields' => array('foreign_key'),
                'conditions' => array(
                    'type' => Permission::TYPE_Distributor,
                    'user_id' => $user_id,
                    'model' => Permission::Distributor,
                ),
            ));
            $datadistributor_all = $this->Permission->find('list', array(
                'fields' => array('foreign_key'),
                'conditions' => array(
                    'type' => Permission::TYPE_Distributor,
                    'user_id' => $user_id,
                    'model' => Permission::Distributor,
                    'foreign_key' => Permission::ALL,
                ),
            ));
            $distributor_checked_all = (!empty($datadistributor_all)) ? $datadistributor_all : null;
            $distributor_checked = (!empty($datadistributor)) ? $datadistributor : null;
        }
        if ($this->request->is('ajax')) {
            if (in_array($this->Auth->user('role'), array('Admin', 'Developer'))) {
                if (isset($this->request->data['arr'])) $arr = $this->request->data['arr'];
                $alias = $this->Game->find('list', array(
                    'fields' => array('alias'),
                    'conditions' => array('id' => $arr['game_id']),
                ));
                $id = $this->Game->find('list', array(
                    'fields' => array('id'),
                    'conditions' => array('alias' => $alias),
                ));
                $id = (!in_array($arr['per'], array(Permission::Distributor, Permission::TYPE_Distributor))) ? $id : array($arr['game_id']);
                $result = $this->add_game_ajax($arr['game_id'], $arr['per'], $arr['value'], $arr['user_id'], $id, $alias);
            } else {
                $result = array(
                    'code'    => 7,
                    'message' => 'Sorry, You can not add permission',
                );
            }
            $this->autoRender = false;
            return json_encode($result);
        }
        $this->set(compact('foreign_key_checked', 'user', 'foreign_keys', 'distributor', 'distributor_checked', 'distributor_checked_all'));
        $this->layout = 'default_bootstrap';
    }

    private function add_game_ajax($game_id, $per, $value, $user_id, $id, $alias)
    {
		$this->loadModel('Permission');
        $result = 0;
        $data = array();
        if ($per == Permission::Distributor) {
            $per_mi = Permission::TYPE_Distributor;
        } else {
            $per_mi = $per;
        }
        $data['Permission']['type'] = $per_mi;
        $data['Permission']['access'] = $value;
        $data['Permission']['user_id'] = $user_id;
		$all_game = Permission::ALL;
        if ($game_id == $all_game) {
            if ($value == 1) {
                $game_id = $all_game;
                $data['Permission']['foreign_key'] = $game_id;
                if ($per == Permission::Distributor) {
                    $model = Permission::Distributor;
                } else {
                    $model = 'game';
                }
                $data['Permission']['model'] = $model;
                $per_game = $this->Permission->find('first', array(
                    'conditions' => array(
                        'foreign_key' => $game_id,
                        'model' => ($per != Permission::Distributor) ? 'game' : Permission::Distributor,
                        'user_id' => $user_id,
                        'type' => $per,
                    ),
                ));
                $this->Permission->create();
                if (!empty($per_game)) $this->Permission->id = $per_game['Permission']['id'];
                if ($this->Permission->save($data)) {
                    $result = array(
                        'code' => 1,
                        'message' => 'Permissions have been added successful',
                    );
                } else {
                    $result = array(
                        'code' => 2,
                        'message' => 'Ếch Oops! something went wrong Oo.',
                    );
                }
                if ($per == 'default') {
                    $data['Permission']['model'] = 'website';
                    $per_web = $this->Permission->find('first', array(
                        'conditions' => array(
                            'foreign_key' => $game_id,
                            'model' => 'website',
                            'user_id' => $user_id,
                            'type' => 'default',
                        ),
                    ));
                    $this->Permission->create();
                    if (!empty($per_web)) $this->Permission->id = $per_web['Permission']['id'];
                    if ($this->Permission->save($data)) {
                        $result = array(
                            'code' => 1,
                            'message' => 'Permissions have been added successful',
                        );
                    } else {
                        $result = array(
                            'code' => 2,
                            'message' => 'Ếch Oops! something went wrong Oo.',
                        );
                    }
                }
            } else if ($value == 0) {
                $condition_website = array();
                if ($per == Permission::Distributor) {
                    $condition = array('user_id' => $user_id, 'model' => Permission::Distributor, 'foreign_key' => Permission::ALL, 'type' => Permission::TYPE_Distributor);
                } else if ($per == 'default') {
                    $condition = array('user_id' => $user_id, 'model' => 'game', 'foreign_key' => Permission::ALL, 'type' => $per);
                    $condition_website = array('user_id' => $user_id, 'model' => 'website', 'foreign_key' => Permission::ALL, 'type' => $per);
                } else {
                    $condition = array('user_id' => $user_id, 'model' => 'game', 'foreign_key' => Permission::ALL, 'type' => $per);
                }
                if ($this->Permission->deleteAll($condition)) {
                    $result = array(
                        'code' => 0,
                        'message' => 'Permissions have been left successful',
                    );
                }
                if (!empty($condition_website)) {
                    if ($this->Permission->deleteAll($condition_website)) {
                        $result = array(
                            'code' => 0,
                            'message' => 'Permissions have been left successful',
                        );
                    }
                }
            }
        } else {
            if ($value == 1) {
                foreach ($id as $id_game) {
                    $data['Permission']['foreign_key'] = $id_game;
                    $data['Permission']['model'] = ($per != Permission::Distributor) ? 'game' : Permission::Distributor;
                    $per_game = $this->Permission->find('first', array(
                        'conditions' => array(
                            'foreign_key' => $id_game,
                            'model' => ($per != Permission::Distributor) ? 'game' : Permission::Distributor,
                            'user_id' => $user_id,
                            'type' => $per,
                        ),
                    ));
                    $this->Permission->create();
                    if (!empty($per_game)) $this->Permission->id = $per_game['Permission']['id'];
                    if ($this->Permission->save($data)) {
                        $result = array(
                            'code' => 1,
                            'message' => 'Permissions have been added successful',
                        );
                    } else {
                        $result = array(
                            'code' => 2,
                            'message' => 'Ếch Oops! something went wrong Oo.',
                        );
                    }
                }
                if ($per == 'default') {
                    $id_web = $this->Game->find('list', array(
                        'fields' => array('website_id'),
                        'conditions' => array('alias' => $alias),
                    ));
                    foreach ($id_web as $id_w) {
                        if ($id_w == null) $id_w = 0;
                        $data['Permission']['foreign_key'] = $id_w;
                        $data['Permission']['model'] = 'website';
                        $per_web = $this->Permission->find('first', array(
                            'conditions' => array(
                                'foreign_key' => $id_w,
                                'model' => 'website',
                                'user_id' => $user_id,
                                'type' => 'default',
                            ),
                        ));
                        $this->Permission->create();
                        if (!empty($per_web)) $this->Permission->id = $per_web['Permission']['id'];
                        if ($this->Permission->save($data)) {
                            $result = array(
                                'code' => 1,
                                'message' => 'Permissions have been added successful',
                            );
                        } else {
                            $result = array(
                                'code' => 2,
                                'message' => 'Ếch Oops! something went wrong Oo.',
                            );
                        }
                    }
                }
            } else if ($value == 0) {
                if ($per != Permission::Distributor) {
                    $game_id = $this->Game->find('list', array(
                        'fields' => array('id','id'),
                        'conditions' => array('alias' => $alias),
                    ));
                    $website_id = $this->Game->find('list', array(
                        'conditions' => array('alias' => $alias),
                        'fields' => array('website_id'),
                    ));
                    foreach ($website_id as $key => $val) {
                        if ($val == null) {
                            $website_id[$key] = 0;
                        }
                    }
                    if ($this->Permission->deleteAll(array('user_id' => $user_id, 'type' => $per, 'foreign_key' => $game_id, 'model' => 'game')) &&
                        $this->Permission->deleteAll(array('user_id' => $user_id, 'type' => $per, 'foreign_key' => $website_id, 'model' => 'website'))
                    ) {
                        $result = array(
                            'code' => 0,
                            'message' => 'Permissions have been left successful',
                        );
                    }
                } else if ($per == Permission::Distributor) {
                    if ($this->Permission->deleteAll(array('user_id' => $user_id, 'type' => Permission::TYPE_Distributor, 'foreign_key' => $id, 'model' => $per))) {
                        $result = array(
                            'code' => 0,
                            'message' => 'Permissions have been left successful',
                        );
                    }
                }
            }
        }
        return $result;
    }

    public function admin_delete()
    {
        $this->loadModel('Game');
        $this->loadModel('Permission');
        $this->loadModel('User');
        if (isset($this->passedArgs['user_id']) && isset($this->passedArgs['alias'])) {
            $user_delete = $this->passedArgs['user_id'];
            $alias = $this->passedArgs['alias'];
            if ($this->Session->read('Auth.User') && in_array($this->Session->read('Auth.User.role'), array('Developer', 'Admin'))) {
                $games_id = $this->Game->find('list', array(
                    'conditions' => array('Game.alias' => $alias),
                    'fields' => array('id'),
                ));
                $website_id = $this->Game->find('list', array(
                    'conditions' => array('Game.alias' => $alias),
                    'fields' => array('website_id'),
                ));
                $this->Permission->deleteAll(array(
                        "foreign_key" => $games_id,
                        "user_id" => $user_delete,
                        "model" => "Game",
                    )
                );
                $this->Permission->deleteAll(array(
                        "foreign_key" => $website_id,
                        "user_id" => $user_delete,
                        "model" => "Website",
                    )
                );
            } else {
                $this->Session->setFlash(__('Không có quyền xoá.'), 'error');
            }
            $this->redirect(array(
                'controller' => 'permissions',
                'action' => 'game',
                '?' => array('alias' => $alias),
            ));
        }
        $this->autoRender = false;
    }

	public function admin_game()
    {
        $this->loadModel('Permission');
        $this->loadModel('Game');
        $this->loadModel('User');
        if ($this->Session->read('Auth.User')) $user_id = $this->Session->read('Auth.User.id');
        $alias = $this->request->query('alias');
		if ((!isset($alias) || $alias == '') && !$this->request->is('ajax')) {
            throw new Exception('Can not find this game');
        }
        $game = $this->Game->find('all', array(
            'conditions' => array('alias' => $alias),
            'fields'     => array('id', 'title', 'alias'),
        ));
		$ids = array();
        foreach ($game as $key => $val) {
            $ids[] = $val['Game']['id'];
        }
        // save checkbox checked for user
        $users     = $this->save_checked_data($ids, $user_id);
        // get game for each user
        $game_user = $this->get_game_user();
        // when button search clicked
        if (isset($this->request->data['submit']) && $this->request->data['submit'] == 'Search') {
            if (empty($this->request->data['User']['username']) && empty($this->request->data['User']['email'])) {
                $this->Session->setFlash('<b>Please enter username or email</b>');
            } else {
                $conditions = $this->Permission->getConditions($this->request->data['User']['username'], $this->request->data['User']['email'], $user_id);
                $users      = $this->User->find('all', array('conditions' => $conditions, 'order' => array('username' => 'ASC')));
                (empty($users)) ? $this->Session->setFlash('<b>No result found</b>') : $this->set('users_id', $users[0]['User']['id']);
            }
        }
        // request ajax
        if ($this->request->is('ajax')) {
            $roles = array('Admin', 'Developer');
            if (in_array($this->Auth->user('role'), $roles)) {
                $check = "/^[a-zA-Z0-9]+@mobgame.vn$/";
                if (isset($this->request->data['arr'])) $arr = $this->request->data['arr'];
                $game_id = $this->Game->find('list', array(
                    'conditions' => array('alias' => $arr['alias']),
                    'fields'     => array('id'),
                ));
                $data_permission = $this->Permission->find('all', array(
                    'conditions' => array(
                        'foreign_key' => $game_id,
                        'user_id' => $user_id,
                    ),
                    'fields' => array('type'),
                ));
                $permission = array();
                foreach ($data_permission as $value) {
                    foreach($value as $v) {
                        foreach ($v as $k) {
                            $permission[] = $k;
                        }
                    }
                }
                if (empty($arr['email'])) {
                    $result = array (
                        'code'    => 4,
                        'message' => 'You can add permissions for this user because user do not have email',
                    );
                } else {
                    if (!in_array($this->Auth->user('role'), $roles) && !preg_match($check, $arr['email'])) {
                        $result = array(
                            'code'    => 3,
                            'message' => 'Sorry, Your role can add or remove permission for user have email with form "*@mobgame.vn"',
                        );
                    } else if (!in_array($this->Auth->user('role'), $roles) && empty($permission)) {
                        $result = array(
                            'code'    => 5,
                            'message' => 'Sorry, You do not have permission for this game',
                        );
                    } else if (!in_array($this->Auth->user('role'), $roles) && !in_array($arr['per'], $permission)) {
                        $tmp = implode(' and ', $permission);
                        $result = array(
                            'code'    => 6,
                            'message' => 'Sorry, You can add role ' . $tmp . ' for this user',
                        );
                    } else {
                        $this->User->id = $arr['user_id'];
                        if (in_array($this->Auth->user('role'), $roles)) {
                            $user_role = $arr['role'];
                        } else {
                            $user_role = ($this->Auth->user('role') == 'MarketingAdmin') ? 'Marketing' : $this->Auth->user('role');
                        }
                        if ($this->add_game_per($arr, 'alias', 'game') == 1 && $this->User->saveField('role', $user_role)) {
                            $result = array(
                                'code'    => 1,
                                'message' => 'Permissions have been added successful',
                            );
                        } else {
                            $result = array(
                                'code'    => 2,
                                'message' => 'Permissions have been left successful',
                            );
                        }
                    }
                }
            } else {
                $result = array(
                    'code'    => 7,
                    'message' => 'Sorry, You can not add permission',
                );
            }

            $this->autoRender = false;
            return json_encode($result);
        }
        $this->set(compact('game', 'users', 'game_user'));
        $this->layout = 'default_bootstrap';
    }

    private function save_checked_data($ids,$user_id){
        $permissions = Permission::$permission;
        $role        = array('Admin', 'Developer');
        $keys        = array_keys($permissions);
        $user_ids = $this->User->find('list',array(
            'conditions' => array(
                'User.role' => $role,
            ),
            'fields' => array('id'),
        ));
        $users_tmp = $this->Permission->find('all', array(
            'conditions' => array(
                'foreign_key' => $ids,
                'access ='    => 1,
                'model '      => "game",
                'NOT'         => array('user_id' => $user_ids),
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
                'User.active',
                'User.role',
            ),
            'order' => array('User.username' => 'ASC'),
        ));
        $users    = array();
        $user_per = $this->Permission->findPermission($user_id, $ids, 'list', $keys);
        foreach ($users_tmp as $val) {
            $users_per = $this->Permission->findPermission($val['User']['id'], $ids, 'all', $keys);
            foreach ($permissions as $k => $v)  {
                $per = strtolower($k);
                if (in_array($this->Auth->user('role'), $role) && empty($user_per)) {
                    $val['Disable']["$per"] = false;
                } else {
                    if (!empty($user_per)) $val['Disable']["$per"] = false;
                }
                foreach ($users_per as $user_p) {
                    $options = strtolower($user_p['Permission']['options']);
                    if ($options == 'game-default') {
                        $val['User']['game-default']    = 1;
                        $val['User']['website-default'] = 1;
                    } else if ($options == "game-$per") {
                        $val['User']["game-$per"] = 1;
                    }
                }
            }
            $users[] = $val;
        }
        return $users;
    }

    private function get_game_user(){
        $data =$this->Permission->find('all', array(
            'conditions' => array(
                'model'  => 'game',
                'access' => 1,
            ),
            'joins' => array(
                array(
                    "table" => "games",
                    "alias" => "Game",
                    "conditions" => array("Game.id = Permission.foreign_key"),
                ),
            ),
            'fields' => array(
                'Game.id',
                'Game.title',
                'Permission.user_id',
            ),
            'group' => array('Permission.user_id', 'Game.alias'),
            'order' => array('Game.id' => 'ASC'),
        ));
        return $data;
    }

	private function get_data($model,$field,$alias)
    {
        $big_data = array();
        $data = $this->$model->find('list', array(
            'conditions' => array("$model.$field" => $alias),
            'fields'     => array('id'),
        ));
        $big_data [$model] = $data;
        if ($model == 'Game') {
            $website_id = $this->$model->find('list', array(
                'conditions' => array("$model.$field" => $alias),
                'fields'     => array('website_id'),
            ));
            $big_data ['website'] = $website_id;
        }
        return $big_data;
    }
	
	private function add_game_per($arr, $field, $model)
    {
        $result = '';
        $model  = ucfirst($model);
        $field  = strtolower($field);
        $data   = $this->get_data($model,$field,$arr['alias']);
        if ($arr['value'] == 1) {
            $data_game = array();
            $data_game['Permission']['type']    = $arr['per'];
            $data_game['Permission']['user_id'] = $arr['user_id'];
            $data_check = $this->Permission->find('all', array(
                'conditions' => array(
                    'user_id'     => $this->Session->read('Auth.User.id'),
                    'model'       => 'game',
                    'type'        => $arr['per'],
                    'access'      => 1,
                    'foreign_key' => $data[$model],
                ),
            ));
            $tmp = (!empty($data_check)) ? $data_check : $data[$model];
            foreach ($tmp as $val) {
                $foreign_key = (isset($val['Permission']['foreign_key'])) ? $val['Permission']['foreign_key'] : $val;
                $access      = (isset($val['Permission']['access'])) ? $val['Permission']['access'] : 1;
                $permission_check = $this->Permission->find('first', array(
                    'conditions' => array(
                        'foreign_key' => $foreign_key,
                        'model'       => 'game',
                        'user_id'     => $arr['user_id'],
                        'type'        => $arr['per'],
                    ),
                ));
                $data_game['Permission']['model']       = 'game';
                $data_game['Permission']['foreign_key'] = $foreign_key;
                $data_game['Permission']['access']      = $access;
                $this->Permission->create();
                if (!empty($permission_check)) $this->Permission->id = $permission_check['Permission']['id'];
                if ($this->Permission->save($data_game)) {
                    $result = 1;
                }
            }
            if ($arr['per'] == 'default') {
                foreach ($data['website'] as $val) {
                    if ($val == null) $val = 0;
                    $permission_check = $this->Permission->find('first', array(
                        'conditions' => array(
                            'foreign_key' => $val,
                            'model'       => 'website',
                            'user_id'     => $arr['user_id'],
                            'type'        => $arr['per'],
                        ),
                    ));
                    $data_game['Permission']['model']       = 'website';
                    $data_game['Permission']['foreign_key'] = $val;
                    $data_game['Permission']['access']      = 1;
                    $this->Permission->create();
                    if (!empty($permission_check)) $this->Permission->id = $permission_check['Permission']['id'];
                    if ($this->Permission->save($data_game)) {
                        $result = 1;
                    }
                }
            }
        } elseif ($arr['value'] == 0) {
            $game_id = $this->Game->find('list', array(
                'fields' => array('id','id'),
                'conditions' => array('alias' => $arr['alias']),
            ));
            $website_id = $this->Game->find('list', array(
                'conditions' => array('alias' => $arr['alias']),
                'fields' => array('website_id'),
            ));
            foreach ($website_id as $key => $val) {
                if ($val == null) {
                    $website_id[$key] = 0;
                }
            }
            if ($this->Permission->deleteAll(array('user_id' => $arr['user_id'], 'type' => $arr['per'], 'foreign_key' => $game_id, 'model' => 'game')) &&
                $this->Permission->deleteAll(array('user_id' => $arr['user_id'], 'type' => $arr['per'], 'foreign_key' => $website_id, 'model' => 'website'))
            ) {
                $result = 0;
            }
        }
        return $result;
    }
}