<?php

App::uses('AppModel', 'Model');

class Game extends AppModel {

    const GROUP_R01 = 1;
    const GROUP_R02 = 2;
    const GROUP_R03 = 3;

	public $validationDomain = 'not_translate';

	public $virtualFields = array(
	    'title_os' => 'CONCAT(Game.title, " - ", Game.os)'
	);

	public $actsAs = array(
		'Utils.Sluggable' => array(
			'separator' => '-',
			'update'	=> false,
			'unique' => false
		),
		'TextParser' => array(
			'fields' => array('description', 'changelog')
		),
		'Utils.Serializable' => array(
			'field' => array('paypal', 'data')
		),
        'Search.Searchable'
	);

	public static $usedLanguages = array('eng', 'zh_cn', 'zh_tw', 'tha', 'vie', 'es_es','ind');

	/**
	 * hasOne Website based on language field
	 */
	public $hasAndBelongsToMany = array(
		'Genre'
	);

	public $belongsTo = array('Website');

	public $validate = array(
		'os' => array(
			'rule' => array('inList', array('android', 'ios', 'pc', 'wp')),
			'required' => false,
			'allowEmpty' => true
		)
	);

    public $filterArgs = array(
        'title' => array('type' => 'like', 'field' => 'title')
    );

	/**
	 * return the game's ID and all similar game
	 * @param Array|Int $game array get from Common->currentGame() or game's id
	 */
	public function getSimilarGameId($game)
	{
		if (is_numeric($game)) {
			$this->recursive = -1;
			$game = $this->findById($game);
			$game = $game['Game'];
		}
		if (empty($game['alias']))
			return $game['id'];
		$games = $this->find('all', array(
			'conditions' => array(
				'alias' => $game['alias'],
				'id !=' => $game['id']
			),
			'recursive' => -1
		));
		if (empty($games)) {
			return $game['id'];
		}
		$ids = Hash::extract($games, '{n}.Game.id');
		array_unshift($ids, $game['id']);
		return $ids;
	}

	public function afterSave($created)
	{
        App::import('Lib', 'RedisCake');
        $Redis = new RedisCake('action_count');
        $Redis->set('list_game_last_update', date('Y-m-d H:i:s'));

		# Clear detect request to which game, or website in CommonComponent
		Cache::clear(false, 'info');
		clearCachefile('config_language_games', '', '');
	}

	/**
	 * Lấy game id của app hiện thời 
	 */
	public function getCurrentId($appKey)
	{
		$this->contain();
		$game = $this->findByAppKey($appKey);
		if (empty($game)) {
			throw new NotFoundException('Không tìm thấy game này');
		}
		return $game['Game']['id'];
	}
	
	/**
	 * Get number of unread Articles of a Game from a specific point of time.
	 * 
	 * @param int $game_id		The id of the Game.
	 * @param DateTime $fromTime	Should be retrieve from cookie or session.
	 * @return int		The number of unread Articles.
	 */
	public function getCountUnreadArticles($website_id, $fromTime = null) {
		if ($fromTime) {
			$count = $this->Website->Category->Article->find('count',
				array('fields' => 'DISTINCT Article.id', 'conditions' => array(
					'Article.website_id' => $website_id,
					'Article.modified > ' => $fromTime->format('Y-m-d H:i:s')
				)
				));
		} else {
			$count = $this->Website->Category->Article->find('count',
				array('fields' => 'DISTINCT Article.id', 'conditions' => array(
					'Article.website_id' => $website_id
				)
				));
		}		
		
		return $count;
	}

    # Function dùng để get All link Download ,skype, Email của từng Game
    public function getLinkOs($currentGame)
    {
        $data  = array();

        if(isset($currentGame['alias']) && $currentGame['alias'] !='')
        {
            $games = $this->find('all',array(
                'conditions' => array('Game.alias' => $currentGame['alias']),
                'order' => array('Game.id DESC'),
            ));

            foreach ($games as $key => $val)
            {
                switch ($val['Game']['os'])
                {
                    case 'ios':
                        $data['appstore_link'] = $val['Game']['appstore_link'];
                        break;

                    case 'android':
                        $data['google_play_link'] = $val['Game']['appstore_link'];
                        $data['apk_link']      = $val['Game']['jailbreak_link'];

                        break;

                    case 'pc':
                        $data['pc_link'] = $val['Game']['appstore_link'];
                        break;

                    case 'wp':
                        $data['windown_link'] = $val['Game']['appstore_link'];
                        break;
                }

               
                $data['support_email'] = $val['Game']['support_email'];
                if(isset($val['Game']['data']['support_skype']) && $val['Game']['data']['support_skype']!="" ) {
                    $data['support_skype'] = $val['Game']['data']['support_skype'];
                }
                if(isset($val['Game']['data']['fbpage_url'])&& $val['Game']['data']['fbpage_url'] != "")
                    $data['fbpage_url'] = $val['Game']['data']['fbpage_url'];

                if(isset($val['Game']['data']['group_fb_id']) && $val['Game']['data']['group_fb_id'] !="")
                    $data['group_fb_id'] = $val['Game']['data']['group_fb_id'];

                if(isset($val['Game']['fbpage_id']) && $val['Game']['fbpage_id'] !="" )
                    $data['fbpage_id'] = $val['Game']['fbpage_id'];

                if(isset($val['Game']['parsed_description']) && $val['Game']['parsed_description'] !="" )
                    $data['parsed_description'] = $val['Game']['parsed_description'];

                if(isset($val['Game']['data']['time_zone']) && $val['Game']['data']['time_zone'] !="")
                    $data['time_zone'] = $val['Game']['data']['time_zone'];
            }
        }

        return $data;

    }
}
