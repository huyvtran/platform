<?php

App::uses('AppController', 'Controller');

class ArticlesController extends AppController {

	public $components = array(
		// 'Security' => array(
		// 	'csrfExpires' => '+180 minutes'
		// ),
		'Site' => array(
			'requiredAction' => array('admin_add', 'admin_edit', 'admin_index')
		),
		'Search.Prg'
	);

	public $cacheAction = array(
		'index' => '+30 minutes'
	);

	public $presetVars = array(
		array('field' => 'title', 'type' => 'value'),
		array('field' => 'category_id', 'type' => 'value')
	);
	
	public function beforeFilter()
	{
		parent::beforeFilter();
        $website = $this->Session->read('Admin.website');
        if( empty($website['id']) ){
            $this->redirect(array('controller' => 'Websites', 'action' => 'setsession', 'admin' => true));
        }
		$this->layout = 'default_bootstrap';
		if (!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') {
			$this->Article->enablePublishable('find', false);
		}
	}

    public function admin_index()
    {
        $this->Prg->commonProcess();

        $parsedConditions = $this->Article->parseCriteria($this->passedArgs);

        $order = array(
            'Article.created' => 'DESC'
        );
        if (!empty($this->request->params['named']['category_id'])) {
            $order = array('Article.position' => 'DESC');
        }
        $this->paginate = array(
            'Article' => array(
                'conditions' => array_merge(
                    array(
                        'Article.website_id' => $this->Session->read('Admin.website.id')
                    ),
                    $parsedConditions
                ),
                'order' => $order,
                'contain' => array('Category', 'User')
            )
        );

        $categories = $this->Article->Category->generateTreeList(array(
            'website_id' => $this->Session->read('Admin.website.id')), null, null, '-- ');

        $articles = $this->Paginator->paginate();

        $this->set(compact('articles', 'categories'));
    }

    public function admin_add($id = null)
    {
        if (!empty($this->request->data)) {
            if ($userid = $this->Auth->user('id')) {
                $this->request->data['Article']['user_id'] = $userid;
            }
            if ($this->request->data['Article']['body_markdown'] != null && $this->request->data['Article']['markup'] == 'markdown') {
                $this->request->data['Article']['body'] = $this->request->data['Article']['body_markdown'];
            }
            try {
                $dataSource = $this->Article->getDatasource();
                $dataSource->begin();

                $this->request->data['Article']['website_id'] = $this->Session->read('Admin.website.id');

                $time_noti = null;
                if(isset($this->request->data['Article']['published_date_date']) && $this->request->data['Article']['published_date_date'] != '' &&
                    isset($this->request->data['Article']['published_date_hour']) && $this->request->data['Article']['published_date_hour'] != ''){
                    $this->request->data['Article']['published_date'] = $this->request->data['Article']['published_date_date'] ." ". $this->request->data['Article']['published_date_hour'].':00';
                    $time_noti = $this->request->data['Article']['published_date'];

                    if (!empty($id)) {
                        $article = $this->Article->findById($id);
                        if (empty($article)) {
                            throw new NotFoundException('Không tìm thấy bài viết này');
                        }
                        if($article['Article']['published'] && strtotime($time_noti) >= strtotime($article['Article']['published_date']))
                            $this->request->data['Article']['published_date'] = $article['Article']['published_date'];
                    }
                }
                // add time start and finished for event
                if(isset($this->request->data['Article']['category_id']) && $this->request->data['Article']['category_id']!= ''){
                    $this->loadModel('Categories');
                    $category_id = $this->request->data['Article']['category_id'];
                    $category = $this->Categories->findById($category_id);
                    if($category['Categories']['slug'] == 'events'){
                        if(isset($this->request->data['Article']['event_start_hour']) && $this->request->data['Article']['event_start_hour'] != '' &&
                            isset($this->request->data['Article']['event_start_date']) && $this->request->data['Article']['event_start_date'] != ''){
                            $this->request->data['Article']['event_start'] = $this->request->data['Article']['event_start_date'] ." ". $this->request->data['Article']['event_start_hour'].':00';
                        }else{
                            $this->Session->setFlash('Không thành công - Bài viết của bạn là sự kiện nên phải có ngày bắt đầu và kết thúc sự kiện!', 'error');
                            $this->redirect(array('action' => 'index'));
                        }
                        if(isset($this->request->data['Article']['event_end_hour']) && $this->request->data['Article']['event_end_hour'] != '' &&
                            isset($this->request->data['Article']['event_end_date']) && $this->request->data['Article']['event_end_date'] != ''){
                            $this->request->data['Article']['event_end'] = $this->request->data['Article']['event_end_date'] ." ". $this->request->data['Article']['event_end_hour'].':00';
                        }else{
                            $this->Session->setFlash('Không thành công - Bài viết của bạn là sự kiện nên phải có ngày bắt đầu và kết thúc sự kiện!', 'error');
                            $this->redirect(array('action' => 'index'));
                        }
                    }
                }
                if(isset($this->request->data['Article']['published_date'])) {
                    $time_future = $this->request->data['Article']['published_date'];
                    $time_now = date('Y-m-d H:i:s');
                    $time_end = strtotime($time_now) + 3*24*3600;
                    $time_end = date('Y-m-d H:i:s',$time_end);
                }

                if(isset($this->request->data['Article']['published_date']) &&
                    isset($time_now) && isset($time_end) &&
                    strtotime($time_future) >= strtotime($time_end)
                ){
                    $this->Session->setFlash('không thành công - giới hạn auto public là 3 ngày sau ngày hiện tại', 'error');
                    $this->redirect(array('action' => 'index'));
                }

                if ($this->Article->save($this->request->data)) {
                    # setNotification
//                    if (	!empty($this->request->data['Article']['notify'])
//                        || 	!empty($this->request->data['Article']['notify_all'])
//                    ) {
//                        if (empty($this->request->data['Article']['category_id'])) {
//                            $dataSource->rollback();
//                            throw new Exception("You need choose category, if you want to create notification from this article.");
//                        }
//                        if (!empty($this->request->data['Article']['notify_all'])) {
//                            $gameId = 99999999;
//                            $this->Article->setNft($this->Article->id, $gameId, $time_noti);
//                        } else {
//                            $this->loadModel('Game');
//                            $this->Game->contain();
//                            $games = $this->Game->findAllByWebsiteId($this->Session->read('Admin.website.id'));
//                            foreach ($games as $game) {
//                                if (in_array($game['Game']['os'], array('android', 'ios', 'wp'))) {
//                                    $this->Article->setNft($this->Article->id, $game['Game']['id'],$time_noti);
//                                }
//                            }
//                        }
//                    }

                    $dataSource->commit();
                    $this->Session->setFlash("Bài viết <strong>" . $this->request->data['Article']['title'] . "</strong> đã được lưu lại",
                        "success");
                    $this->redirect(array('action' => 'index'));
                } else {
                    throw new Exception(current(current($this->Article->validationErrors)));
                }
            } catch (Exception $e) {
                $this->Session->setFlash($e->getMessage(), "error");
            }
        }

        if (!empty($id)) {
            $this->Article->contain(array('User', 'Website', 'Category'));
            $this->request->data = $this->Article->findById($id);
            if(isset($this->request->data['Article']['published_date'])) {
                $public_date_int = strtotime($this->request->data['Article']['published_date']);
                $this->request->data['Article']['published_date_date'] = date('Y-m-d', $public_date_int);
                $this->request->data['Article']['published_date_hour'] = date('H:i', $public_date_int);
            }
            if(isset($this->request->data['Article']['event_start'])) {
                $public_date_int = strtotime($this->request->data['Article']['event_start']);
                $this->request->data['Article']['event_start_date'] = date('Y-m-d', $public_date_int);
                $this->request->data['Article']['event_start_hour'] = date('H:i', $public_date_int);
            }
            if(isset($this->request->data['Article']['event_end'])) {
                $public_date_int = strtotime($this->request->data['Article']['event_end']);
                $this->request->data['Article']['event_end_date'] = date('Y-m-d', $public_date_int);
                $this->request->data['Article']['event_end_hour'] = date('H:i', $public_date_int);
            }
            if(isset($this->request->data['Article']['tags'])){
                $tags = str_replace(' ','',$this->request->data['Article']['tags']);
                $this->request->data['Article']['tag_values'] = explode(',',$tags);
            }
            if (empty($this->request->data)) {
                throw new NotFoundException('Không tìm thấy bài viết này');
            }
        }

        $categories = $this->Article->Category->generateTreeList(array('website_id' => $this->Session->read('Admin.website.id')), null, null, '-- ');

        $this->set('categories', $categories);
        $this->render('admin_add');
    }

    public function admin_edit($id = null)
    {
        $this->Article->enablePublishable('find', false);
        $this->admin_add($id);
    }

	public function admin_publish($id = null)
	{
		if (!$id || !$article = $this->Article->findById($id)) {
			throw new NotFoundException('Không tìm thấy bài viết này');
		}

		if ($this->Article->publish($id)) {
			$this->Session->setFlash('Đã đăng bài viết <strong>'.$article['Article']['title'].'</strong>',
				'success');
		} else {
			$this->Session->setFlash('Có lỗi xảy ra');
		}

		$this->redirect($this->referer(array('action' => 'index'), true));
	}

	public function admin_unpublish($id = null)
	{
		if (!$id || !$article = $this->Article->findById($id)) {
			throw new NotFoundException('Không tìm thấy bài viết này');
		}

		if ($this->Article->unPublish($id)) {
            $this->Article->id = $id;
            $this->Article->saveField('published_date', null, array('callbacks' => false));
			$this->Session->setFlash('Đã hủy đăng bài viết <strong>' . $article['Article']['title'] . '</strong>',
				'success');
		} else {
			$this->Session->setFlash('Có lỗi xảy ra');
		}

		$this->redirect($this->referer(array('action' => 'index'), true));
	}

	public function admin_moveToTop($id = null)
	{
		$this->Article->id = $id;
		if (!$this->Article->exists()) {
			throw new NotFoundException();
		}

		$this->Article->recursive = -1;
		if ($this->Article->moveToBottom($this->Article->id)) {
			$this->Article->__clearCache($this->Article->id);
			$this->Session->setFlash('Đã di chuyển bài viết lên trên đầu', "success");
		} else {
			$this->Session->setFlash('Không thể di chuyên lên hoặc bài viết đã ở vị trí đầu tiên',
				"error");
		}

		$this->redirect($this->referer(array('action' => 'index'), true));

	}

	public function admin_moveup($id = null) {
		$this->Article->id = $id;
		if (!$this->Article->exists()) {
			throw new NotFoundException();
		}

		$this->Article->recursive = -1;
		if ($this->Article->moveDown($this->Article->id)) {
			$this->Article->__clearCache($this->Article->id);
			$this->Session->setFlash('Đã di chuyển bài viết lên', "success");
		} else {
			$this->Session->setFlash('Không thể di chuyên lên hoặc bài viết đã ở vị trí đầu tiên',
				"error");
		}

		$this->redirect($this->referer(array('action' => 'index'), true));
	}

	public function admin_movedown($id = null) {
		$this->Article->id = $id;
		if (!$this->Article->exists()) {
			throw new NotFoundException();
		}
		$this->Article->recursive = -1;

		if ($this->Article->moveUp($this->Article->id)) {
			$this->Article->__clearCache($this->Article->id);
			$this->Session->setFlash('Đã di chuyển bài viết xuống', "success");
		} else {
			$this->Session->setFlash('Không thể di xuống lên hoặc bài viết đã ở vị trí cuối cùng',
				"error");
		}

		$this->redirect($this->referer(array('action' => 'index'), true));
	}

	public function admin_delete($id) {
		$article = $this->Article->findById($id);
		if (empty($article)) {
			throw new NotFoundException("Bài viết này không tồn tại");
		}
		if ($this->Article->delete($id)) {
			$this->Session->setFlash('Bài viết <strong>' . h($article['Article']['title']) . '</strong> đã xóa',
				'success');
		} else {
			$this->Session->setFlash('Không thể xóa bài viết', 'error');
		}
		$this->redirect($this->referer(array('action' => 'index'), true));
	}

	public function isAuthorized() {
		return true;
	}
}