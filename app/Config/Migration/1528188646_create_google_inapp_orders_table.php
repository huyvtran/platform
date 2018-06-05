<?php
class CreateGoogleInappOrdersTable extends CakeMigration {

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
				'google_inapp_orders' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'order_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'id'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'index', 'after' => 'order_id'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'user_id'),
					'role_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'game_id'),
					'area_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'role_id'),
					'google_order_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 40, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'area_id'),
					'package_name' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'google_order_id'),
					'google_product_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 256, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'package_name'),
					'purchase_time' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'google_product_id'),
					'purchase_state' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 40, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'purchase_time'),
					'purchase_token' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 256, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'purchase_state'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'purchase_token'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'user_id_game_id' => array('column' => array('user_id', 'game_id'), 'unique' => 0),
						'google_order_id' => array('column' => 'google_order_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'google_inapp_orders'
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
