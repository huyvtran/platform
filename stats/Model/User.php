<?php

class User extends AppModel {

    public $actsAs = array('Search.Searchable');
    public $filterArgs = array(
        'alias' => array('type' => 'value', 'field' => 'Game.alias')
    );

	const TYPE_3_DAYS_LOGIN       = '3days_login';
	const TYPE_7_DAYS_LOGIN       = '7days_login';
	const TYPE_30_DAYS_LOGIN      = '30days_login';

	public $hasOne  = 'Profile';
	public $belongsTo = array('Account');

	public function getSourceUsersTotalInRange($gameIds, $fromTime, $toTime)
	{
		$userIds = array("id IN (
			SELECT user_id
			FROM accounts
			WHERE game_id IN(" . implode(',', $gameIds) . ")
			AND created >= '" . date('Y-m-d H:i:s', $fromTime) . "'
			AND created < '" . date('Y-m-d H:i:s', $toTime) . "'
		)");

		# count guest users total
		$guest = $this->find('count', array(
			'conditions' => array_merge(
				$userIds,
				array('facebook_uid IS NULL')
			)
		));

		# count facebook userstotal	
		$fb = $this->find('count', array(
			'conditions' => array_merge(
				$userIds,
				array('facebook_uid IS NOT NULL')
			)
		));
		# count email users total
		$email = $this->find('count', array(
			'conditions' => array_merge(
				$userIds,
				array(
					'email IS NOT NULL',
					'facebook_uid IS NULL'
				)
			)
		));
		return array($guest, $fb, $email);
	}

	public function getSourceUsersTotal($gameIds = null)
	{
		if ($gameIds == null) {
			$guest = Cache::read('guest_total', 'stats');
		} else {
			$guest = 0;
			foreach ($gameIds as $key => $gameId) {
				$v = Cache::read('guest_total' . $gameId, 'stats');	
				if ($guest !== false) {
					$guest += $v;
				}
			}
		}

		if ($gameIds == null) {
			$fb = Cache::read('facebook_total', 'stats');
		} else {		
			$fb = 0;
			foreach ($gameIds as $key => $gameId) {
				$v = Cache::read('facebook_total' . $gameId, 'stats');	
				if ($fb !== false) {
					$fb += $v;
				}
			}
		}

		if ($gameIds == null) {
			$email = Cache::read('email_user_total', 'stats');
		} else {
			$email = 0;
			foreach ($gameIds as $key => $gameId) {
				$v = Cache::read('email_user_total' . $gameId, 'stats');	
				if ($email !== false) {
					$email += $v;
				}
			}
		}
		return array($guest, $fb, $email);
	}

}
