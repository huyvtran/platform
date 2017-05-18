<?php

App::uses('AppController', 'Controller');
App::uses('CategoricalController', 'Controller');

class CategoriesController extends CategoricalController {

	public $components = array(
		'Security' => array(
			'csrfExpires' => '+180 minutes'
		),
		'Site' => array(
			'requiredAction' => array('admin_add', 'admin_edit', 'admin_index')
		),
        'Search.Prg'
	);

	public $cacheAction = array(
		'index' => '+30 minutes'
	);

	public function beforeFilter()
	{
		parent::beforeFilter();
        $website = $this->Session->read('Admin.website');
        if( empty($website['id']) ){
            $this->redirect(array('controller' => 'Websites', 'action' => 'setsession', 'admin' => true));
        }
		$this->layout = 'default_bootstrap';
	}

	public function admin_index()
	{
		$this->Paginator->settings = array(
			'Category' => array(
				'conditions' => array('Category.website_id' => $this->Session->read('Admin.website.id')),
				'order' => $this->modelClass . '.lft ASC',
				'contain' => array('ParentCategory', 'Website')
			)
		);
		$this->set('categories', $this->paginate('Category'));
		$this->layout = 'default_bootstrap';
	}

	public function admin_add($id = null)
	{
		try {
			if ($this->request->is('post') || $this->request->is('put')) {
				# Get node parent level
				if (!empty($this->request->data['Category']['category_id'])) {
					$path = $this->Category->getPath($this->request->data['Category']['category_id']);
					$this->request->data['Category']['level'] = count($path);
				}
				$this->request->data['Category']['website_id'] = $this->Session->read('Admin.website.id');
				if ($this->Category->save($this->request->data)) {
					$this->Session->setFlash('The category <strong>' . $this->request->data[$this->modelClass]['title'] . '</strong> has been saved', 'success');
					$this->redirect(array('action' => 'index'));
				}			
			}
		} catch (OutOfBoundsException $e) {
			$this->Session->setFlash($e->getMessage());
		} catch (Exception $e) {
			$this->Session->setFlash($e->getMessage());
			$this->redirect(array('action' => 'index'));
		}

		$categories = $this->Category->generateTreeList(array(
			'id <>' => $id, 'website_id' => $this->Session->read('Admin.website.id')), null, null, '-- ');
		if (!empty($id)) {
			$this->request->data = $category = $this->{$this->modelClass}->find('first', array(
				'contain' => array('ParentCategory'),
				'conditions' => array($this->modelClass . '.id' => $id)
			));
		}		
		$this->set(compact('categories'));
		$this->layout = 'default_bootstrap';
		$this->render('/Categories/admin_add');
	}

	public function admin_edit($id = null)
	{
		if (!$id) {
			throw new BadRequestException('Không tìm thấy mục này');
		}

		$this->admin_add($id);
	}

    /**
     * Show all articles when slug param is null
     */
    public function index($slug = null)
    {
        // kiểm tra biên page xem có ở named ko, nếu ko thì gán từ params vào
        if( !empty($this->request->params['page']) ) {
            $this->request->params['named']['page'] = $this->request->params['page'];
        }

        $slug = empty($this->request->params['slug']) ? $slug : $this->request->params['slug'];
        $this->Common->setLanguage();
        # return if request is created by requestAction
        if (	!empty($this->request->params['requested'])
            &&	!empty($this->viewVars['articles'])
        ) {
            return $this->viewVars['articles'];
        }
        $type = 'Article';
        if (isset($this->request->params['named']['type'])) {
            $type = $this->request->params['named']['type'];
        }

        $this->Common->setTheme();
        $website = $this->Common->currentWebsite();

        $order = array('Article.position' => 'DESC');
        # get a category data
        if ($slug) {
            # multi category split by + sign
            if($slug == 'news events'){
                $slug = str_replace(' ','+',$slug);
            }
            $slugs = explode('+', $slug);
            foreach($slugs as $slug) {
                $category = $this->Category->find('first', array(
                    'conditions' => array(
                        'Category.slug' => $slug,
                        'Category.website_id' => $website['id']
                    )
                ));
                if (!empty($category)) {
                    $categoryIds[] = $category['Category']['id'];
                }
            }

            # If this is combile more than two categories, then order by published_date
            if (count($slugs) >= 2) {
                $order = array('Article.published_date' => 'DESC');
            }
            if (empty($categoryIds)) {
                # return if request is created by requestAction
                if (!empty($this->request->params['requested'])) {
                    return false;
                }
                throw new NotFoundException('The category could not be found');

            }
            $conditions = array(
                'Article.category_id' => $categoryIds
            );
        } else {
            # this category is normal type
            $this->Category->contain();
            $categories_conditions = array(
                'conditions' => array(
                    'Category.website_id' => $website['id'],
                    /*'Category.type' => $type*/
                )
            );
            if( !empty($this->request->params['slug']) ) {
                $categories_conditions['conditions']['Category.slug'] = $this->request->params['slug'];
            }
            $categories = $this->Category->find('all', $categories_conditions);

            $conditions = array(
                'Article.category_id' => Hash::extract($categories, '{n}.Category.id')
            );
        }

        # Overwrite current view if file view_by_{type} existed
        if ( 	(isset($this->theme) && file_exists(APP . 'View' . DS . 'Themed' . DS . $this->theme . DS . 'View' . DS . 'Categories' . DS . 'index_by_' . strtolower($type) . '.ctp'))
            ||	file_exists(APP . 'View' . DS . 'Categories' . DS . 'index_by_' . strtolower($type) . '.ctp')
        ) {
            $this->view = 'index_by_' . strtolower($type);
        } else {
            $this->view = 'index';
        }

        $limit = 10;
        if (!empty($this->request->params['limit'])) {
            $limit =100;
        }
        $this->Paginator->settings = Hash::merge(
            $this->Paginator->settings,
            array(
                'Article' => array(
                    'conditions' => array($conditions, array(
                        'Article.website_id' => $website['id'],
                        'Article.published' => true
                    )),
                    'fields' => array(),
                    'contain' => array('Avatar', 'Category'),
                    'order' => $order,
                    'limit' => $limit
                )
            )
        );

        try {
            $articles = $this->paginate('Article');

            # return if request is created by requestAction
            if (!empty($this->request->params['requested'])) {
                return $articles;
            }

        } catch (Exception $e) {
            $result = array(
                'name' => 'Not Found'
            );
        }

        $game = $this->Common->currentGame();
        if (!$this->request->is('ajax')) {
            $this->layout = 'default';
        }

        $this->set(compact('articles', 'category', 'slugs', 'game'));
    }
}