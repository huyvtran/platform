<?php
class CreateQuickpayOrdersTable extends CakeMigration {

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
				'quickpay_orders' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'id'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5, 'after' => 'user_id'),
					'order_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'game_id'),
					'quick_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'order_id'),
					'currency' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'quick_id'),
					'customer_data' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'currency'),
					'accepted' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5, 'after' => 'customer_data'),
					'test_mode' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5, 'after' => 'accepted'),
					'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'test_mode'),
					'state' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'type'),
					'quick_data' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'state'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'quick_data'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'order_id' => array('column' => 'order_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'quickpay_orders'
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
