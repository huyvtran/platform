<?php

App::uses('AppModel', 'Model');

class Variable extends AppModel {

	public $validate = array(
		'name' => array(
			'rule' => 'notEmpty'
		),
		'value' => array(
			'rule' => 'notEmpty'
		)
	);

	public function afterSave($created)
	{
		# temporary use simple resolve, clear all notification in all games.
		Cache::clear(false, 'variable');
	}

	public function afterDelete()
	{
		Cache::clear(false, 'variable');	
	}

	public function getVar($name)
	{
		$var = $this->find('first', array('conditions' => array(
			'name' => $name
		)));
		if (empty($var)) {
			return false;
		}
		return $var['Variable']['value'];
	}

	public function setVar($name, $value)
	{
		$variable = $this->find('first', array('conditions' => array(
			'name' => $name
		)));
		if (empty($variable)) {
			$this->save(array($this->alias => array(
				'name' => $name,
				'value' => $value
			)));
		} else {
			$this->id = $variable[$this->alias]['id'];
			$this->saveField('value', $value);
		}
	}
}