<?php

App::uses('AppModel', 'Model');
//App::uses('SessionComponent', 'Controller/Component');

class Game extends AppModel {

	public $displayField = 'title_os';

    public $virtualFields = array(
        'title_os' => "IF(Game.short_words IS NULL OR Game.short_words = '', CONCAT(Game.title, ' - ',Game.os), CONCAT(Game.short_words, ' - ',Game.os))"
    );

    public function beforeFind($queryData)
	{
		$username = CakeSession::read('Auth.User.username');

		if ($username == 'mobgameyouseeme' && !empty($queryData['conditions']['Game.status'])) {
		     unset($queryData['conditions']['Game.status']);
		}
		return $queryData;
	}

	/**
	 * return the game's ID and all similar game
	 */
	public function getSimilarById($id)
	{
		$game = $this->find('first', array(
			'conditions' => array('Game.id' => $id),
			'recursive' => -1
		));


		$games = $this->find('all', array(
			'conditions' => array('alias' => $game['Game']['alias']),
			'recursive' => -1
		));
		if (empty($games)) {
			return $game['id'];
		}
		$ids = Hash::extract($games, '{n}.Game.id');
		return $ids;
	}
}