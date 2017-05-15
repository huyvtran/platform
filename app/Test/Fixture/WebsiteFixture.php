<?php
/**
 * WebsiteFixture
 *
 */
class WebsiteFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'Website');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'title' => '108heroes',
			'url' => 'funtap.vn',
			'theme2' => 'Funtap',
			'url2' => '',
			'theme' => 'Funtap',
			'theme_mobile' => 'Funtap',
			'created' => '2013-10-05 22:30:24',
			'modified' => '2013-10-05 22:30:24',
			'lang' => 'vie',
			'published' => 1
		),
	);

}
