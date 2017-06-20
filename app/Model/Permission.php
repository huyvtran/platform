<?php

App::uses('AppModel', 'Model');

class Permission extends AppModel {

    public $virtualFields = array(
        'options' => 'CONCAT(Permission.model,"-", Permission.type)'
    );
	public static $permission = array(
        'default'    => 'Game & CMS',
        'stats'      => 'Stats'
    );
	const Distributor = 'distributor';
	const TYPE_Distributor = 'stats';
	public static $role  = array('Admin', 'Developer', 'Content', 'Marketing','Distributor');
    public $allGameRoles = array('MarketingAdmin', 'Admin', 'Developer');

    const ALL = 999999;
	
	public function add_permisstion($userid,$gameid,$access=array())
    {
        for ($i=0;$i<=3;$i++) {
            if ( $i==0 ) {
                $dat['Permission']['model'] = 'Website';
                $dat['Permission']['type'] ='Default';  
                $dat['Permission']['access'] = $access[$i];             
            } elseif ($i==1) {
                $dat['Permission']['model'] = 'Game';
                $dat['Permission']['type'] ='Default';
                $dat['Permission']['access'] = $access[$i];
            } elseif ($i==2) {
                $dat['Permission']['model'] = 'Game';
                $dat['Permission']['type'] ='Support';
                 $dat['Permission']['access'] = $access[$i];
            } else {
                $dat['Permission']['model'] = 'Game';
                $dat['Permission']['type'] ='Stats';
                $dat['Permission']['access'] = $access[$i];
            }
            $dat['Permission']['foreign_key'] = $gameid;
            $dat['Permission']['user_id'] = $userid;
            $this->create();
            $this->save($dat);
        }
    }
	
	public function getRightIds($model, $user_id, $type = 'Default')
	{
		$permissions = $this->find('all', array(
			'conditions' => array(
				'type' => $type,
				'user_id' => $user_id,
				'model' => $model,
				'access' => 1
			),
			'recursive' => -1
		));

		if (!empty($permissions)) {
            $Model = ClassRegistry::init(ucfirst($model));
            $ids = Hash::extract($permissions, '{n}.Permission.foreign_key');

            if (in_array(self::ALL, $ids)) {
                $ids = $Model->find('list', array('fields' => array('id', 'id'), 'recursive' => -1));
            }
            if ($model == 'Game') {
                $aliases = $Model->find('list', array(
                    'conditions' => array('id' => $ids),
                    'recursive' => -1,
                    'fields' => array('id', 'alias')
                ));
                $ids = $Model->find('list', array(
                    'conditions' => array('alias' => $aliases),
                    'recursive' => -1,
                    'fields' => array('id', 'id')               
                ));
            }
			return $ids;
		}
		return array();
	}

	public function check($model, $user_id, $foreign_key, $type = 'Default')
	{
		return $this->find('first', array(
			'conditions' => array(
				'foreign_key' => $foreign_key,
				'type' => $type,
				'user_id' => $user_id,
				'model' => $model,
				'access' => 1
			),
			'recursive' => -1
		));
	}
	
	public function findPermission($user_id, $ids, $typeFind, $type)
    {
        if ($typeFind == 'list') {
            $data = $this->find('list', array(
                'conditions' => array(
                    'user_id' => $user_id,
                    'model' => 'Game',
                    'type' => $type,
                    'access' => 1,
                    'foreign_key' => $ids,
                ),
                'fields'=>array('foreign_key')
            ));
        } elseif ($typeFind == 'first') {
            $data = $this->find('first', array(
                'conditions' => array(
                    'user_id' => $user_id,
                    'model' => 'Game',
                    'type' => $type,
                    'access' => 1,
                    'foreign_key' => $ids,
                ),
            ));
        } elseif ($typeFind == 'all') {
            $data = $this->find('all', array(
                'conditions' => array(
                    'user_id' => $user_id,
                    'model' => 'Game',
                    'type' => $type,
                    'access' => 1,
                    'foreign_key' => $ids,
                ),
            ));
        }
        return $data;
    }

	public function getConditions($data1, $data2, $user_id)
    {
        $conditions = array('User.id !=' => $user_id);
        if (!empty($data1) && empty($data2)) {
            $conditions = array_merge($conditions, array('User.username LIKE' => $data1));
        } elseif (empty($data1) && !empty($data2)) {
            $conditions = array_merge($conditions, array('User.email LIKE' => $data2));
        } elseif (!empty($data1) && !empty($data2)) {
            $conditions = array_merge($conditions, array('User.username LIKE' => $data1, 'User.email LIKE' => $data2));
        }
        return $conditions;
    }
}