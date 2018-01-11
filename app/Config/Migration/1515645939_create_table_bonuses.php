<?php
class CreateTableBonuses extends CakeMigration {

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
				'bonuses' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'order_id' => array('type' => 'string', 'null' => false, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'id'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'order_id'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'user_id'),
					'price' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'game_id'),
					'bonus' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'price'),
					'status' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'bonus'),
					'chanel' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'status'),
					'note' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'chanel'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'note'),
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
				'bonuses'
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
