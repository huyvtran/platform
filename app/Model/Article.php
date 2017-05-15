<?php

class Article extends AppModel {
		
	public $validationDomain = 'not_translate';
			
	public $cacheQueries = true;

	public $actsAs = array(
		'Utils.Sluggable' => array(
			'separator' => '-',
			'update'	=> false,
			'unique' => true,
			'scope' => array(
				'website_id'
			)
		),
		'Search.Searchable',
		'TextParser',
		'Utils.Publishable',
		'Utils.List' => array('scope' => array('category_id')),
        'Tags.Taggable'
	);

	public $belongsTo = array(
		'Category' => array('counterCache' => true),
		'User',
		'Website'
	);

	public $filterArgs = array(
		'category_id' => array('type' => 'value', 'field' => 'category_id'),
		'title' => array('type' => 'like', 'field' => 'title'),
	);

	public $validate = array(
		'title' => array(
			'rule' => 'notEmpty',
			'message' => 'Bạn chưa nhập tiêu đề',
			'required' => true,
			'allowEmpty' => false
		),
		'body' => array(
			'minLength' => array(
				'rule'       => array('minLength', 30),
				'message'    => 'Nột dung phải có ít nhất 30 kí tự',
				'required'   => true,
				'allowEmpty' => false
			),
			'maxLength' => array(
				'rule'    => array('maxLength', 60000),
				'message' => 'Nội dung không thể dài quá 60000 kí tự'
			),
		),
		'user_id' => array(
			'rule'       => 'notEmpty',
			'required'   => true,
			'allowEmpty' => false
		)
	);
}
?>