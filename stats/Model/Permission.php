<?php

App::uses('AppModel', 'Model');

class Permission extends AppModel {
	
    const ALL = 999999;
	const Distributor = 'distributor';
	const TYPE_Distributor = 'stats';
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
                $conditions = array(
                    'alias' => $aliases,
                );
                $ids = $Model->find('list', array(
                    'conditions' => $conditions,
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
}