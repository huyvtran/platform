<?php

App::uses('AppModel', 'Model');

class Product extends AppModel {

	public $belongsTo = array('Game');
	public $virtualFields = array(
		'name' => "CONCAT(Product.type, ' - ', Product.price, ' - ', Product.platform_price)"
	);
	public $actsAs = array(
		'Search.Searchable'
	);
	
	public $validation = array(
		'game_id'   => array('rule' => 'notEmpty'),
		'title'     => array('rule' => 'notEmpty'),
        'chanel'    => array('rule' => 'notEmpty'),
		'price'     => array('rule' => 'notEmpty'),
        'platform_price'    => array('rule' => 'notEmpty')
	);

	public $filterArgs = array(
		'game_id' => array('type' => 'value', 'field' => 'game_id'),
	);	
}
