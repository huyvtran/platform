<?php
class AddKeyPaymentsTable extends CakeMigration {

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
			'alter_field' => array(
				'payments' => array(
					'order_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5, 'key' => 'index'),
					'card_code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'card_serial' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
				),
			),
			'create_field' => array(
				'payments' => array(
					'indexes' => array(
						'game_id_type' => array('column' => array('game_id', 'type'), 'unique' => 0),
						'user_id_game_id' => array('column' => array('user_id', 'game_id'), 'unique' => 0),
						'order_id' => array('column' => 'order_id', 'unique' => 0),
						'card_serial_card_code' => array('column' => array('card_serial', 'card_code'), 'unique' => 0),
						'card_code' => array('column' => 'card_code', 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'payments' => array(
					'order_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5),
					'card_code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'card_serial' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
				),
			),
			'drop_field' => array(
				'payments' => array('', 'indexes' => array('game_id_type', 'user_id_game_id', 'order_id', 'card_serial_card_code', 'card_code')),
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
