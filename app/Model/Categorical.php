<?php

App::uses('Model', 'Model');

class Categorical extends AppModel {

	public $useTable = false;
	
	public function __construct($id = false, $table = null, $ds = null)
	{
		$this->actsAs = Hash::merge(array(
			'Tree' => array('parent' => strtolower(get_class($this)) . '_id'),
			'Utils.Sluggable' => array(
				'label' => 'title',
				'separator' => '-'
			)
		), $this->actsAs);

		$this->belongsTo['ParentCategory'] = array(
				'className' => get_class($this),
				'foreignKey' => get_class($this) . '_id',
		);
			
		$this->hasMany['ChildCategory'] = array(
				'className' => get_class($this),
				'foreignKey' => get_class($this) . '_id',
				'dependent' => false
		);
		
		$this->validate['title'] = array('rule' => 'notEmpty');

		parent::__construct($id, $table, $ds);
	}
	
}