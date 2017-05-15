<?php

App::uses('String', 'Utility');

class CategoriesTheme {
    public function before_index($Controller){
        if(isset($Controller->request->params['slug']) && $Controller->request->params['slug'] == 'guides'){
            $Controller->request->data['limit'] = 100;
        }
    }
    public function after_index($Controller){
        $slug_type   =   'events';
        if(isset($Controller->request->params['slug'])){
            $slug_type   =   $Controller->request->params['slug'];
        }
        $tite_game = "Vua Chiến Hạm";
        # set meta page title
        switch ($slug_type) {
            case 'news+events':
                $title_for_layout = 'Tin tức - Sự kiện | '.$tite_game;
                break;
            case 'news':
                $title_for_layout = 'Tin tức | '.$tite_game;
                break;
            case 'events':
                $title_for_layout = 'Sự kiện | '.$tite_game;
                break;
            case 'features':
                $title_for_layout = 'Đặc sắc | '.$tite_game;
                break;
            case 'khuyen-mai':
                $title_for_layout = 'Khuyến mại | '.$tite_game;
                break;
            case 'guides':
                $Controller->layout = 'blank' ;
                $Controller->view = 'guides' ;
                $title_for_layout = 'Hướng dẫn | '.$tite_game;
                break;
            case 'faq':
                $title_for_layout = 'FAQ | '.$tite_game;
                break;
            case 'heroes':
                $Controller->view = 'list_heroes' ;
                $title_for_layout = 'Danh sách tướng | '.$tite_game;
                break;
            default:
                $title_for_layout = ' '.$tite_game;
        }
        $gameInfo   =   $Controller->Common->currentGame();
        $Controller->set('gameInfo',$gameInfo);
        if(isset($Controller->viewVars['articles'][0]['Category']['description'])){
            $description = $Controller->viewVars['articles'][0]['Category']['description'];
        }

        if (!empty($Controller->viewVars['articles'][0]['Category']['description'])) {
            $Controller->set('description_for_layout', $description . ' | Vua Chiến Hạm');
        }

        // xử lý tiêu đề khi là Tag
        if( !empty($Controller->viewVars['obj_tag'][0]['Tag']['name']) ) {
            $title_for_layout = $Controller->viewVars['obj_tag'][0]['Tag']['name'] . ' | Vua Chiến Hạm';
            $Controller->set('description_for_layout', $Controller->viewVars['obj_tag'][0]['Tag']['name'] . ' | Vua Chiến Hạm');
        }
        $Controller->set(compact('title_for_layout'));
    }
    public function after_listHeroes($Controller){
        $tite_game = "Vua Chiến Hạm.";
        if(isset($Controller->viewVars['articles'][0]['Category']['description'])){
            $description = $Controller->viewVars['articles'][0]['Category']['description'];
        }
        if (!empty($Controller->viewVars['articles'][0]['Category']['description'])) {
            $Controller->set('description_for_layout', $description . ' | Vua Chiến Hạm');
        }
        $Controller->set('title_for_layout','Danh sách tướng | ' . $tite_game);
        $Controller->set('description_for_layout', 'Vua Chiến Hạm | Danh Sách Tướng');
        $Controller->Game->recursive = -1;
        $Controller->Game->contain('Avatar','Website');
        $gameInfo   =   $Controller->Common->currentGame();
        $Controller->set('gameInfo',$gameInfo);
    }

}