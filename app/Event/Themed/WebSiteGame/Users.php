<?php

    class UsersTheme
    {
        public function after_confirmChangePass($Controller)
        {
            $listGames = $this->getListGame($Controller);
            $Controller->set('listGames', $listGames);
            $Controller->set('title_for_layout', 'Quên mật khẩu | FunTap - Cổng GAME MOBILE, GMO hàng đầu Việt Nam.');
        }
        public function getListGame($Controller){
            $Controller->loadModel('Game');
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
            $count = 0;
            foreach($listGames as $g){
                if(isset($g['Game']['data']['is_close']) && $g['Game']['data']['is_close'] == 1){
                    unset($listGames[$count]);
                }
                $count++;
            }
            return $listGames;
        }
    }