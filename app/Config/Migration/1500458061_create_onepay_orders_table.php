<?php
class CreateOnepayOrdersTable extends CakeMigration {

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
				'onepay_orders' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'order_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'id'),
					'order_info' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 500, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'order_id'),
					'order_type' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'order_info'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'after' => 'order_type'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5, 'after' => 'user_id'),
					'amount' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'game_id'),
					'card_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'amount'),
					'card_type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'card_name'),
					'response_code' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10, 'collate' => 'utf8_unicode_ci', 'comment' => 'trước commit giao dịch', 'charset' => 'utf8', 'after' => 'card_type'),
					'trans_status' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 125, 'collate' => 'utf8_unicode_ci', 'comment' => 'trước commit giao dịch', 'charset' => 'utf8', 'after' => 'response_code'),
					'chanel' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 2, 'after' => 'trans_status'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'chanel'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'onepay_orders'
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
