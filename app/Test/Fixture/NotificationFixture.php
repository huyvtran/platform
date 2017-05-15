<?php
/**
 * GameFixture
 *
 */
class NotificationFixture extends CakeTestFixture {

/**
 * Import
 *
 * @var array
 */
	public $import = array('model' => 'Notification');

/**
 * Records
 *
 * @var array
 */
	public $records = array(
		array(
			'id' => 1,
			'title' => 'Lorem ipsum dolor sit amet, aliquet feugiat',
			'content' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
			'link' => 'http://localhost:8088/plf/admin',
			'type' => 'Manual',
			'created' => '2015-02-20 10:10:46',
			'finished' => '2015-02-20 10:12:46',
			'repeat' => 0,
			'user_id' => 99999999,
			'status' => 0,
			'is_hot' => 0,
			'is_new' => 0,
			'is_news' => 0,
			'is_event' => 0,
			'game_id' => 8,
			'model' => 'Article',
			'foreign_key' => 130
		),
	);

}
