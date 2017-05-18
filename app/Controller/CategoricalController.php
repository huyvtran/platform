<?php

App::uses('AppController', 'Controller');

class CategoricalController extends AppController{
	
	public $helpers = array('Text');
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('modelClass', $this->modelClass);
	}

    public function admin_delete($id = null)
    {
        try {
            $result = $this->{$this->modelClass}->delete($id);
            if ($result === true) {
                $this->Session->setFlash(sprintf('%s deleted', $this->modelClass));
                $this->redirect(array('action' => 'index'));
            }
        } catch (Exception $e) {
            $this->Session->setFlash($e->getMessage());
            $this->redirect(array('action' => 'index'));
        }
    }
}
