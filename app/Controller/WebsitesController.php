<?php

App::uses('AppController', 'Controller');

class WebsitesController extends AppController {

	public $components = array(
		'Security' => array('csrfExpires' => '+180 minutes')
	);

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'default_bootstrap';
	}

	public function admin_lock($id)
	{
		
		if ($state = $this->Website->toggle($id, 'lock')) {
			$this->Session->setFlash('You just has been locked website successful', 'success');	
		} else {
			$this->Session->setFlash('You just has been unlocked website successful');	
		}
		$this->redirect($this->referer());
	}

	public function admin_setsession($websiteId = null)
	{
		if (strpos($this->Session->read('Referer'), '/admin/websites/setsession') !== false) {
			$this->Session->delete('Referer');
		}

		if ($this->request->is('post')) {
			$website = $this->Website->findById($websiteId);
			$this->Session->write('Admin.website', $website['Website']);
			$this->Cookie->write('Admin.website', $website['Website']);
			# redirect if session has referrer
			if ($this->Session->read('Referer')) {
				$this->Common->redirect();
			}
		}
		
		$this->loadModel('Permission');
		$role = $this->Auth->user('role');
		if (!in_array($role, array('Admin', 'Developer'))) {
			$ids = $this->Permission->getRightIds('Website', $this->Auth->user('id'));
			$websites = $this->Website->find('all', array(
				'conditions' => array('Website.id' => $ids),
				'order' => array('Website.id' => 'DESC'),
				'recursive' => -1
			));
		} else {
			$websites = $this->Website->find('all', array(
				'order' => array('Website.id' => 'DESC'),
			));
		}

		$this->set(compact('websites'));
	}

    public function admin_index()
    {
        $this->Website->contain(array('Game'));
        $this->Paginator->settings['Website']['order'] = array('Website.id' => 'desc');
        $websites = $this->paginate();
        $this->set(compact('websites'));
    }

    public function admin_add($id = null)
    {
        if ($this->request->is('post') || $this->request->is('put')) {

            $lang = 'eng';
            if(isset($this->request->data['Website']['lang']))
                $lang = $this->request->data['Website']['lang'];

            if (empty($id)) {
                $websites = $this->Website->find('all',
                    array(
                        'contain' => array(
                            'Category' => array(
                                'Article',
                                'conditions' => array(
                                    'Category.slug' => 'faq',
                                    'Category.type' => 'Help',
                                )
                            )
                        ),
                        'conditions' => array('lang' => $lang),
                        'order' => array('created' => 'DESC')
                    )
                );

                foreach($websites as $website){
                    if(isset($website['Category'][0]['Article']) && !empty($website['Category'][0]['Article']) ){
                        $category_data = array(
                            'Category'=>array(
                                'title' => $website['Category'][0]['title'],
                                'slug' => $website['Category'][0]['slug'],
                                'description' => $website['Category'][0]['description'],
                                'type' => $website['Category'][0]['type'],
                            )
                        );
                        $articles_data = $website['Category'][0]['Article'];
                        break;
                    }
                }
            }

            if ( $websites_new = $this->Website->save($this->request->data)) {

                // create category and articles
                if(isset($category_data) && isset($articles_data)){
                    $category_data['Category']['website_id'] = $websites_new['Website']['id'];

                    $this->loadModel('Category');
                    $category_new = $this->Category->save($category_data);

                    $this->loadModel('Article');
                    foreach($articles_data as $article){
                        $art = array(
                            'Article' => array(
                                'title' => $article['title'],
                                'body' => $article['body'],
                                'parsed_body' => $article['parsed_body'],
                                'summary' => $article['summary'],
                                'user_id' => $article['user_id'],
                                'category_id' => $category_new['Category']['id'],
                                'slug' => $article['slug'],
                                'is_hot' => $article['is_hot'],
                                'is_new' => $article['is_new'],
                                'is_event' => $article['is_event'],
                                'website_id' => $websites_new['Website']['id']
                            )
                        );
                        $this->Article->create();
                        $this->Article->save($art);
                    }
                }

                $this->Session->setFlash('The website has been saved', 'success');
                $this->redirect(array('action' => 'index'));
            } else {
                $this->Session->setFlash($this->Website->validationErrors, 'error');
            }
        }
        if (!empty($id)) {
            $this->Website->contain('Game');
            $this->request->data = $this->Website->findById($id);
        }
        $games = $this->Website->Game->find('list');
        $this->set(compact('games'));
        $this->render('admin_add');
    }

    public function admin_edit($id = null)
    {
        if (!$this->Website->exists($id)) {
            throw new NotFoundException('Invalid website');
        }
        $this->admin_add($id);
    }

    public function admin_delete($id = null)
    {
        $this->Website->id = $id;
        if (!$this->Website->exists()) {
            throw new NotFoundException('Invalid website');
        }
        $this->request->onlyAllow('post', 'delete');
        if ($this->Website->delete()) {
            $this->Session->setFlash('Website deleted', 'success');
            $this->redirect(array('action' => 'index'));
        }
        $this->Session->setFlash('Website was not deleted', 'error');
        $this->redirect(array('action' => 'index'));
    }
}
