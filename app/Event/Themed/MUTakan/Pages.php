<?php

class PagesTheme {

	public function after_home($Controller)
	{
        $website_id = $Controller->Common->currentWebsite('id');
        $Controller->loadModel('Article');
        $Controller->loadModel('Website');
        $newAndEvents = $Controller->Article->find('all',array('Avatar',
            'conditions' => array(array(
                'Article.published' => true,
                'Category.type'=>'Article',
                'Article.website_id' => $website_id,
                'OR'=>
                    array(
                        array('Category.slug'=>'events'),
                        array('Category.slug'=>'news')
                    ))),
            'fields' => array(),
            'contain' => array('Avatar', 'Category'),
            'order' =>array( 'Article.published_date' => 'DESC'),
            'limit'=>5
        ));
        $events = $Controller->Article->find('all',array('Avatar',
            'conditions' => array(array(
                'Article.published' => true,
                'Category.type'=>'Article',
                'Article.website_id' => $website_id,
                'Category.slug'=>'events'
            )),
            'fields' => array(),
            'contain' => array('Avatar', 'Category'),
            'order' =>array( 'Article.published_date' => 'DESC'),
            'limit'=>5
        ));
        $news = $Controller->Article->find('all',array('Avatar',
            'conditions' => array(array(
                'Article.published' => true,
                'Category.type'=>'Article',
                'Article.website_id' => $website_id,
                'Category.slug'=>'news',
            )),
            'fields' => array(),
            'contain' => array('Avatar', 'Category'),
            'order' =>array( 'Article.published_date' => 'DESC'),
            'limit'=>5
        ));
        $sliders = $Controller->Website->Media->find('item', array(
            'conditions' => array(
                'website_id' => $Controller->Common->currentWebsite('id'),
                'type' => 'SlideCMS'
            ),
            'contain' => array(
                'Image' => array(
                    'order' => array('Image.position' => 'DESC'),
                    'limit' => 3
                ))
        ));
        $Controller->Common->currentGame();
        $Controller->set(compact('sliders'));
		$Controller->set('title_for_layout', 'Vua Chiến Hạm - Siêu phẩm Chiến thuật quân sự đến từ Hàn Quốc');
		$Controller->set('description_for_layout', "Vua Chiến Hạm - Siêu phẩm chiến thuật quân sự đến từ Hàn Quốc, nơi tái hiện chân thực những trận hải chiến kinh điển trong Thế chiến thứ 2.");
		$Controller->set('newAndEvents',array_slice($newAndEvents,0,5));
		$Controller->set('news',array_slice($news,0,5));
		$Controller->set('events',array_slice($events,0,5));
	}
    public function after_error($Controller){
        $gameInfo   =   $Controller->Common->currentGame();
        $Controller->set('gameInfo',$gameInfo);
        $Controller->set('title_for_layout', 'Lỗi | Vua Chiến Hạm - Siêu phẩm Chiến thuật quân sự đến từ Hàn Quốc');
    }
    public function after_customePage($Controller){
        $article = $Controller->viewVars['articles'];
        $title = 'Tin Tức';
        if( isset($article[0]['Category']['slug']) && $article[0]['Category']['slug'] == 'guides'){
          $title = 'Hướng Dẫn';
        }elseif( isset($article[0]['Category']['slug']) && $article[0]['Category']['slug'] == 'features'){
            $title = 'Đặc Sắc';
        }elseif( isset($article[0]['Category']['slug']) && $article[0]['Category']['slug'] == 'faq'){
            $title = 'Câu hỏi thường gặp';
        }
        $Controller->set('title_for_layout', $title .' | Vua Chiến Hạm - Siêu phẩm Chiến thuật quân sự đến từ Hàn Quốc');
        if(isset($Controller->viewVars['articles'][0]['Category']['description'])){
            $description = $Controller->viewVars['articles'][0]['Category']['description'];
        }
        if (!empty($Controller->viewVars['articles'][0]['Category']['description'])) {
            $Controller->set('description_for_layout', $description . ' | Vua Chiến Hạm - Siêu phẩm chiến thuật quân sự đến từ Hàn Quốc, nơi tái hiện chân thực những trận hải chiến kinh điển trong Thế chiến thứ 2.');
        }
        $Controller->Game->recursive = -1;
        $Controller->Game->contain('Avatar','Website');
        $listGame = $Controller->Game->find('all',array(
            'conditions'=>array(
                'language_default' => 'vie',
                'status'           => 1,
                'alias !='         => array('mobgamedemo','funtap')
            ),
            'group'=>'alias',
        ));
        //image for mkt
        $image_mkt_small = $Controller->Website->Media->find('item', array(
            'conditions' => array(
                'type' => 'image_mkt_small_vi'
            ),
            'contain' => array(
                'Image' => array(
                    'order' => array('Image.position' => 'DESC'),
                    'limit' => 1
                ))
        ));
        $image_mkt_big = $Controller->Website->Media->find('item', array(
            'conditions' => array(
                'type' => 'image_mkt_big_vi'
            ),
            'contain' => array(
                'Image' => array(
                    'order' => array('Image.position' => 'DESC'),
                    'limit' => 1
                ))
        ));
        $gameInfo   =   $Controller->Common->currentGame();
        $Controller->set('gameInfo',$gameInfo);
        $Controller->set('image_mkt_big',$image_mkt_big);
        $Controller->set('image_mkt_small',$image_mkt_small);
        $Controller->set('listGame',$listGame);
    }
    public function after_giftcode($Controller)
    {

        $image_mkt_small = $Controller->Website->Media->find('item', array(
            'conditions' => array(
                'type' => 'image_mkt_small_vi'
            ),
            'contain' => array(
                'Image' => array(
                    'order' => array('Image.position' => 'DESC'),
                    'limit' => 1
                ))
        ));
        $image_mkt_big = $Controller->Website->Media->find('item', array(
            'conditions' => array(
                'type' => 'image_mkt_big_vi'
            ),
            'contain' => array(
                'Image' => array(
                    'order' => array('Image.position' => 'DESC'),
                    'limit' => 1
                ))
        ));
        $Controller->set('image_mkt_big',$image_mkt_big);
        $Controller->set('image_mkt_small',$image_mkt_small);
        $Controller->set('title_for_layout', 'Giftcode Vua Chiến Hạm - Nhận code Vua Chiến Hạm.');
        $Controller->set('description_for_layout', 'Giftcode Vua Chiến Hạm | Nhận code Vua Chiến Hạm.');
    }
    public function after_landing($Controller)
    {
        
        $MobileDetect = new Mobile_Detect();

        if ($MobileDetect->isMobile()) {
            $Controller->view = 'landing_mobile';
        }
        $image_mkt_small = $Controller->Website->Media->find('item', array(
            'conditions' => array(
                'type' => 'image_mkt_small_vi'
            ),
            'contain' => array(
                'Image' => array(
                    'order' => array('Image.position' => 'DESC'),
                    'limit' => 1
                ))
        ));
        $image_mkt_big = $Controller->Website->Media->find('item', array(
            'conditions' => array(
                'type' => 'image_mkt_big_vi'
            ),
            'contain' => array(
                'Image' => array(
                    'order' => array('Image.position' => 'DESC'),
                    'limit' => 1
                ))
        ));
        $Controller->set('image_mkt_big',$image_mkt_big);
        $Controller->set('image_mkt_small',$image_mkt_small);
        $Controller->set('title_for_layout', 'Vua Chiến Hạm - Siêu phẩm Chiến thuật quân sự đến từ Hàn Quốc | Tải Game Vua Chiến Hạm');
        $Controller->set('description_for_layout', 'Vua Chiến Hạm - Siêu phẩm chiến thuật quân sự đến từ Hàn Quốc, nơi tái hiện chân thực những trận hải chiến kinh điển trong Thế chiến thứ 2 | Tải Game Vua Chiến Hạm');
    }

    public function after_teaser($Controller){

        $Controller->Common->currentGame();
        $Controller->set('title_for_layout', 'Vua Chiến Hạm - Siêu phẩm Chiến thuật quân sự đến từ Hàn Quốc | Teaser');
        $Controller->set('description_for_layout', "Vua Chiến Hạm - Siêu phẩm chiến thuật quân sự đến từ Hàn Quốc, nơi tái hiện chân thực những trận hải chiến kinh điển trong Thế chiến thứ 2. | Teaser");
    }

}