<?php
class CreateIndexToCompensePaymentsTable extends CakeMigration {

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
				'compense_payments' => array(
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5, 'key' => 'index'),
					'card_code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'card_serial' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'key' => 'index'),
				),
				'payments' => array(
					'time' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index'),
				),
			),
			'create_field' => array(
				'compense_payments' => array(
					'indexes' => array(
						'game_id_type' => array('column' => array('game_id', 'type'), 'unique' => 0),
						'card_serial_card_code' => array('column' => array('card_serial', 'card_code'), 'unique' => 0),
						'card_code' => array('column' => 'card_code', 'unique' => 0),
						'modified' => array('column' => 'modified', 'unique' => 0),
					),
				),
				'payments' => array(
					'indexes' => array(
						'time' => array('column' => 'time', 'unique' => 0),
					),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'compense_payments' => array(
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5),
					'card_code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'card_serial' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
				),
				'payments' => array(
					'time' => array('type' => 'integer', 'null' => false, 'default' => NULL),
				),
			),
			'drop_field' => array(
				'compense_payments' => array('', 'indexes' => array('game_id_type', 'card_serial_card_code', 'card_code', 'modified')),
				'payments' => array('', 'indexes' => array('time')),
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
