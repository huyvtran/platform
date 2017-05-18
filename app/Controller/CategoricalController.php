<?php

App::uses('AppController', 'Controller');

class CategoricalController extends AppController{
	
	public $helpers = array('Text');
	
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->set('modelClass', $this->modelClass);
	}
	
	public function admin_moveup($id = null, $delta = null)
	{
		$this->{$this->modelClass}->id = $id;
		if (!$this->{$this->modelClass}->exists()) {
		   throw new BadRequestException();
		}
	
		if ($delta > 0) {
			if ($this->{$this->modelClass}->moveUp($this->Category->id, abs($delta))){
				$this->Session->setFlash('Đã di chuyển nó lên', "success");
			}else{
				$this->Session->setFlash('Không thể di chuyên lên hoặc nó đã ở vị trí đầu tiên', "error");
			}
		} else {
			$this->Session->setFlash('Please provide a number of positions the category should be moved up.');
		}
	
		$this->redirect(array('action' => 'tree'));
	}
	
	public function admin_movedown($id = null, $delta = null)
	{
		$this->{$this->modelClass}->id = $id;
		if (!$this->{$this->modelClass}->exists()) {
		   throw new BadRequestException();
		}
	
		if ($delta > 0) {
			if ($this->{$this->modelClass}->moveDown($this->{$this->modelClass}->id, abs($delta))){
				$this->Session->setFlash('Đã di chuyển nó xuống', "success");
			}else{
				$this->Session->setFlash('Không thể di xuống lên hoặc nó đã ở vị trí cuối cùng', "error");
			}
		} else {
			$this->Session->setFlash('Please provide a number of positions the category should be moved up.');
		}
	
		$this->redirect(array('action' => 'tree'));
	}
}
