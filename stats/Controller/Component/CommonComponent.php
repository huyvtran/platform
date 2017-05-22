<?php

App::uses('Component', 'Controller');

class CommonComponent extends Component {

	public $components = array(
		'Session', 'Auth'
	);

	function __construct(ComponentCollection $collection, $settings = array())
	{
		parent::__construct($collection, $settings);
	}
	
	public function initialize(Controller $controller)
	{
		$this->Controller = $controller;
	}

	public function getWebConfig()
	{
		$this->Controller->loadModel('Website');
		$this->Controller->Website->contain('Game');
		$website = $this->Controller->Website->findByUrl(env('SERVER_NAME'));
		
		if (	empty($website)
			|| 	$this->Controller->request->header('mobgame_appkey')
		) {
			return false;
		}
		$this->Controller->set(compact('website'));
		return $website;
	}

	public function array_values_multi($array, &$vals = array())
	{
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$this->array_values_multi($value, $vals);
			}else{
				$vals[] = $value; 
			}
		}
		return $vals;
	}

	public function isOwner($id)
	{
		$field = $this->Controller->{$this->Controller->modelClass}->find('first', array(
			'conditions' => array(
				$this->Controller->{$this->Controller->modelClass}->alias . '.id' => $id,
				$this->Controller->{$this->Controller->modelClass}->alias . '.user_id' => $this->Auth->user('id')
			),
			'recursive' => -1
		));
		if (empty($field))
			return false;
		return $field;
	}
	
	public function preventRapidSubmit($model, $options)
	{
		if (!$this->Auth->loggedIn()){
			return true;
		}
		$conditions = array();
		if (!empty($options['conditions']))
			  $conditions = array_merge($conditions, $options['conditions']);
		if ($this->Controller->modelClass != $model) {
			  $this->UseModel = $this->Controller->{$this->Controller->modelClass}->$model;
		} else {
			  $this->UseModel = $this->Controller->{$this->Controller->modelClass};
		}
		if (isset($options['total']) && isset($options['time'])) {
			$d = $options['time'];
			if (is_string($options['time'])) {
				$d = strtotime($options['time']) - time();
			}
			$count = $this->UseModel->find('count', array(
			  'conditions' => array_merge(array("$model.user_id" => $this->Auth->user('id'), array("$model.created >" => date('Y-m-d H:i:s', time() - $d))), $conditions)
				  ));
			if ($count >= $options['total']) {
				$this->Session->setFlash(
				__("You reached to limit, please wait for %s seconds from last to continue", $d),"error");
				return false;
			} else {
				return true;
			}
		} else {
			trigger_error('miss options');
		}
	}

	public function setReferer($referer = null){
		if (!$referer)
			$referer = $this->Controller->referer(null, true);
		if (Router::normalize($this->Auth->loginAction) != $referer)
			$this->Session->write('Referer',$referer);
	}

	public function redirect(){
		if ($referer = $this->Session->read('Referer')){
			$this->Session->delete('Referer');
			$this->Controller->redirect($referer);
		}else{
			if (strpos($this->Controller->referer(), '/users/login') === false)
				$this->Controller->redirect($this->Controller->referer());
			else{
				$this->Controller->redirect('/');
			}
		}
	}

}
?>