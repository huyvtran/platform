<?php
class CreateEmailMarketingAndLinkTrackingTables extends CakeMigration {

/**
 * Migration description
 *
 * @var string
 * @access public
 */
	public $description = '';

/**
 * Actions to be performed
 *
 * @var array $migration
 * @access public
 */
	public $migration = array(
		'up' => array(
			'create_table' => array(
				'email_marketings' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'id'),
					'body' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'title'),
					'parsed_body' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'body'),
					'published_date' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'key' => 'index', 'after' => 'parsed_body'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'after' => 'published_date'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'user_id'),
					'layout' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'game_id'),
					'file' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'big5_chinese_ci', 'charset' => 'big5', 'after' => 'layout'),
					'status' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 2, 'after' => 'file'),
					'data' => array('type' => 'text', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'status'),
					'type' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 2, 'after' => 'data'),
					'from_time' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'type'),
					'to_time' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'from_time'),
					'field' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'to_time'),
					'total' => array('type' => 'integer', 'null' => false, 'default' => '0', 'after' => 'field'),
					'view' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'after' => 'total'),
					'click' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'after' => 'view'),
					'ad_for_game_id' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'after' => 'click'),
					'relating_users_on_email_marketing_ids' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'ad_for_game_id'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'relating_users_on_email_marketing_ids'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'published_date_status' => array('column' => array('published_date', 'status'), 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
				'link_trackings' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'model' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 32, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'id'),
					'foreign_key' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 32, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'model'),
					'convert_link' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 256, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'foreign_key'),
					'original_link' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 256, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'convert_link'),
					'count' => array('type' => 'integer', 'null' => true, 'default' => '0', 'after' => 'original_link'),
					'type' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4, 'after' => 'count'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'type'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'model_foreign_key_original_link_type' => array('column' => array('model', 'foreign_key', 'original_link', 'type'), 'unique' => 0, 'length' => array('255')),
						'convert_link' => array('column' => 'convert_link', 'unique' => 0, 'length' => array('255')),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'email_marketings', 'link_trackings'
			),
		),
	);

/**
 * Before migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function before($direction) {
		return true;
	}

/**
 * After migration callback
 *
 * @param string $direction, up or down direction of migration process
 * @return boolean Should process continue
 * @access public
 */
	public function after($direction) {
		return true;
	}
}
