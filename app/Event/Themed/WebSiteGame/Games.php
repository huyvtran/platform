<?php

class GamesTheme {

	public function after_index($Controller)
	{
        $os = isset($Controller->request->pass[0]) ? $Controller->request->pass[0] : '%';
        $alias = $Controller->Game->find('list', array(
            'contain' => false,
            'conditions' => array(
                'Game.status' => 1,
                'Game.show_on_funtap' => 1,
                'Game.os LIKE' => '%' . $os . '%',
            ),
            'fields' => 'alias'
        ));
        $Controller->loadModel('Website');
        $games = $Controller->Game->find('all', array(
            'contain' => false,
            'conditions' => array(
                'Game.alias' => $alias,
                'Game.show_on_funtap' => 1,
            ),
            'order' => array(
                'Game.published_date' => 'DESC',
                //'Game.os' => 'DESC'
            )
        ));
        $tmp = array();
//        $count_list = 0;
//        foreach($games as $game) {
//            if (empty($game['Game']['alias'])) {
//                $key = $game['Game']['title'];
//            } else {
//                $key = $game['Game']['alias'];
//            }
//            if(isset($game['Game']['data']['is_close']) && $game['Game']['data']['is_close'] == 1){
//                unset($games[$count_list]);
////                $game_closes[$key][] = $game;
//            }else{
//                $tmp[$key][] = $game;
//            }
//
//        }
        $count_list=0;
        foreach($games as $game) {
            if(isset($game['Game']['data']['is_close']) && $game['Game']['data']['is_close'] == 1){
                unset($games[$count_list]);
            }else{
                if (empty($game['Game']['alias'])) {
                    $key = $game['Game']['title'];
                } else {
                    $key = $game['Game']['alias'];
                }
                $tmp[$key][] = $game;
            }
            $count_list++;
        }
        $games = $tmp;
//        $title_for_layout = 'Danh Sách Games | FunTap - Cổng GAME MOBILE, GMO hàng đầu Việt Nam.';
        $listGames = $this->getListGame($Controller);
		$Controller->set(compact('title_for_layout','games','listGames'));

	}

//	public function after_view($Controller)
//	{
//		$title_for_layout = $description_for_layout = $Controller->viewVars['game']['Game']['title'] . ' | FunTap - Cổng GAME MOBILE, GMO hàng đầu Việt Nam.';
//		foreach ($Controller->viewVars['games'] as $k => &$v) {
//			if (!$v['Game']['show_on_funtap']) {
//				unset($Controller->viewVars['games'][$k]);
//			}
//           if($v['Game']['os'] == 'ios'){
//               $v['Game'] = array('position'=>1) + $v['Game'];
//           }elseif($v['Game']['os'] == 'android'){
//               $v['Game'] = array('position'=>2) + $v['Game'];
//           }elseif($v['Game']['os'] == 'wp'){
//               $v['Game'] = array('position'=>3) + $v['Game'];
//           }
//		}
//        $listGames = $this->getListGame($Controller);
//        $gallery = $Controller->Game->Website->Media->find('item', array('conditions' => array(
//            'website_id' => $Controller->viewVars['game']['Website']['id'],
//            'type' => 'Galleries'
//        )));
//        $Controller->loadModel('Media');
//        $Controller->loadModel('Image');
////        $Controller->Media->contain(array('Image'));
//        $media_ids = $Controller->Media->find('list', array(
//            'fields'=>'id,id',
//            'conditions' =>array(
//                'website_id' => $Controller->viewVars['game']['Website']['id'],
//                'type' => 'Screenshot_funtap'
//            )));
//        $slides = $Controller->Image->find('all', array(
//            'conditions' =>array(
//                'foreign_key' => $media_ids,
//                'model'       => 'Media'
//            ),
//        'order'=>array('position'=>'DESC')
//    ));
//		$Controller->set(compact('title_for_layout','description_for_layout','listGames','gallery','slides'));
//	}
    public function getListGame($Controller){
        $listGames = $Controller->Game->find('all', array(
            'contain' => array('Avatar'),
            'conditions' => array(
                'Game.status' => 1,
                'Game.show_on_funtap' => 1,
            ),
            'order' => array(
                'Game.published_date' => 'DESC',
//                'Game.os' => 'DESC',
            ),
            'group'=>'alias',
            'limit'=>5
        ));
        return $listGames;
    }
}