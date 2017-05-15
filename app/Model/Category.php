<?php

App::uses('AppModel', 'Model');

class Category extends AppModel {

    const SUPPORT_WEBSITE_MOBGAME_ID = 36;
    const SUPPORT_WEBSITE_FUNTAP_ID  = 37;

	public $actsAs = array(
		'Tree' => array('parent' => 'category_id'),
		'Utils.Sluggable' => array(
			'label' => 'title',
			'separator' => '-',
			'unique' => false
		),
        'Search.Searchable',
	);

	public $belongsTo = array(
		'ParentCategory' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
		),
		'Website'
	);
		
	public $hasMany = array(
		'ChildCategory' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
			'dependent' => false
		),
		'Article'
	);
	
	public $validate = array(
		'title' => array('rule' => 'notEmpty')
	);

}