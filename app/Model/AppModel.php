<?php

App::uses('Model', 'Model');

class AppModel extends Model {

	public $actsAs = array('Containable');
	
	public function __construct($id = false, $table = null, $ds = null) {
		$className = get_class($this);
		$defaultRecursiveModels = array(
			'AppModel', 'AccessToken', 'Account', 'Game', 'Genre', 'LogEntergame', 
			'LogLogin', 'Permission', 'Profile', 'User', 'Variable', 'Website', 'Payment'
		);
		if (!in_array($className, $defaultRecursiveModels)) {
			$this->recursive = -1;

		}
		parent::__construct($id, $table, $ds);
	}
	# don't auto clear cache as Cakephp's default, cache'll clear manually
	protected function _clearCache($type = null)
	{
		return true;
	}

	public function saveField($name, $value, $validate = false)
	{
		if (empty($this->id)) {
			throw new FatalErrorException('$this->id phải được set');
		}
		return parent::saveField($name, $value, $validate);
	}

	function paginateCount($conditions = array(), $recursive = 0, $extra = array())
	{
		$parameters = compact('conditions');
		if ($recursive != $this->recursive) {
			$parameters['recursive'] = $recursive;
		}
		$extra['recursive'] = -1;
		$extra['contain'] = array();
		return $this->find('count', array_merge($parameters, $extra));
	}
}
