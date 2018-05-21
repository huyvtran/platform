<?php
class CreateCardManualsTable extends CakeMigration {

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
				'card_manuals' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'order_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'id'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'order_id'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5, 'key' => 'index', 'after' => 'user_id'),
					'card_code' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'game_id'),
					'card_serial' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'card_code'),
					'price' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'card_serial'),
					'status' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'after' => 'price'),
					'time' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'status'),
					'detail' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'time'),
					'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'detail'),
					'chanel' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 2, 'after' => 'type'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'chanel'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'game_id' => array('column' => 'game_id', 'unique' => 0),
						'order_id' => array('column' => 'order_id', 'unique' => 0),
						'card_serial_card_code' => array('column' => array('card_serial', 'card_code'), 'unique' => 0),
						'card_code' => array('column' => 'card_code', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'card_manuals'
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
