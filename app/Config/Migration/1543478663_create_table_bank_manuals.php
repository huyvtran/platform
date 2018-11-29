<?php
class CreateTableBankManuals extends CakeMigration {

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
				'bank_manuals' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'order_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 50, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'id'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5, 'key' => 'index', 'after' => 'order_id'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'game_id'),
					'buyer_name' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'user_id'),
					'buyer_phone' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 15, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'buyer_name'),
					'buyer_email' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'buyer_phone'),
					'role_id' => array('type' => 'string', 'null' => true, 'default' => '1', 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'buyer_email'),
					'area_id' => array('type' => 'string', 'null' => true, 'default' => '1', 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'role_id'),
					'price' => array('type' => 'string', 'null' => true, 'default' => '0', 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'area_id'),
					'status' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 1, 'after' => 'price'),
					'detail' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'status'),
					'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'detail'),
					'chanel' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 2, 'after' => 'type'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'chanel'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'game_id' => array('column' => 'game_id', 'unique' => 0),
						'order_id' => array('column' => 'order_id', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'bank_manuals'
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
