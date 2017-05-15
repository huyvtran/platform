<?php
class ArticlesTheme {
    public function after_view($Controller){
        $relateArticles  = $Controller->getRelate($Controller->viewVars['article']['Article']['id'], 4);
        $Controller->loadModel('Game');
        $gameConfig = $Controller->Game->getLinkOs($Controller->Common->currentGame());
        $Controller->set('gameConfig',$gameConfig);
        $gameInfo   =   $Controller->Common->currentGame();
        if($Controller->request->params['category'] == 'guides'){
            $Controller->layout = 'blank';
            $Controller->view = 'guide_view';
        }
        if(isset($Controller->viewVars['article']['Article']['summary']) && $Controller->viewVars['article']['Article']['summary'] != ''){
            $description_for_layout = $Controller->viewVars['article']['Article']['summary'];
            $Controller->set('description_for_layout', $description_for_layout . ' | Vua Chiến Hạm ');
        }
        if(isset($Controller->viewVars['article']['AvatarShare']['data']) && count($Controller->viewVars['article']['AvatarShare']['data']) > 0){
            if(isset($Controller->viewVars['article']['AvatarShare']['data'][0]['aws']['ObjectURL'])){
                $image_title = $Controller->viewVars['article']['AvatarShare']['data'][0]['aws']['ObjectURL'];
                $Controller->set('image_title', $image_title);
            }else{
                if(isset($Controller->viewVars['article']['Avatar']['data'][0]['aws']['ObjectURL'])){
                    $image_title = $Controller->viewVars['article']['Avatar']['data'][0]['aws']['ObjectURL'];
                    $Controller->set('image_title', $image_title);
                }
            }
        }
        $Controller->set('gameInfo',$gameInfo);
        $Controller->set('relateArticles',$relateArticles);
    }
}