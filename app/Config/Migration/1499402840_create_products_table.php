<?php
class CreateProductsTable extends CakeMigration {

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
				'products' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'title' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'id'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'after' => 'title'),
					'productid' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'game_id'),
					'appleid' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'productid'),
					'price' => array('type' => 'float', 'null' => false, 'default' => NULL, 'length' => '19,2', 'after' => 'appleid'),
					'platform_price' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'price'),
					'type' => array('type' => 'string', 'null' => false, 'default' => 'PAYPAL', 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'platform_price'),
					'chanel' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 2, 'after' => 'type'),
					'description' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'chanel'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'description'),
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
				'products'
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
