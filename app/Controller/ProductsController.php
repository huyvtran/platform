<?php

App::uses('AppController', 'Controller');

class ProductsController extends AppController {

	public $components = array(
		'Security' => array(
			'csrfExpires' => '+180 minutes'
		),
		'Search.Prg'
	);

	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->layout = 'default_bootstrap';
	}

	public function admin_index()
	{
		$this->Prg->commonProcess();

		$parsedConditions = array();
		if (!empty($this->passedArgs)) {
			$parsedConditions = $this->Product->parseCriteria($this->passedArgs);
		}

		$this->paginate = array(
			'Product' => array(
				'order' => array('Product.id' => 'DESC'),
				'conditions' => $parsedConditions
			)
		);
		$this->Product->contain(array('Game'));
		$products = $this->paginate('Product');

		$games = $this->Product->Game->find('list', array('fields' => array('id', 'title_os')));
		$this->set(compact('products', 'games'));
	}

	public function admin_add($id = null)
	{
		if ($this->request->is('post') || $this->request->is('put')) {
			$this->Product->create();
			if ($this->Product->save($this->request->data)) {
				$this->Session->setFlash('The product has been saved', 'success');
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash('The product could not be saved. Please, try again.', 'error');
			}
		}
		if (!empty($id)) {
			$this->Product->contain('Game');
			$this->request->data = $this->Product->findById($id);
		}
		$games = $this->Product->Game->find('list', array('fields' => array('id', 'title_os')));
		$this->set(compact('games'));
		$this->render('admin_add');
	}

	public function admin_edit($id = null)
	{
		if (!$this->Product->exists($id)) {
			throw new NotFoundException('Invalid product');
		}
		$this->admin_add($id);
	}

	public function admin_delete($id = null)
	{
		$this->Product->id = $id;
		if (!$this->Product->exists()) {
			throw new NotFoundException('Invalid product');
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Product->delete()) {
			$this->Session->setFlash('Product deleted', 'success');
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash('Product was not deleted', 'error');
		$this->redirect(array('action' => 'index'));
	}
}
