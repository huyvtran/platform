<?php
class CreateLogRetentionByDayTable extends CakeMigration {

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
				'log_retention_by_day' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index', 'after' => 'id'),
					'day' => array('type' => 'date', 'null' => false, 'default' => NULL, 'after' => 'game_id'),
					'return1' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'after' => 'day'),
					'return3' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'after' => 'return1'),
					'return7' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'after' => 'return3'),
					'return30' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'after' => 'return7'),
					'reg1' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'after' => 'return30'),
					'reg3' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'after' => 'reg1'),
					'reg7' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'after' => 'reg3'),
					'reg30' => array('type' => 'integer', 'null' => false, 'default' => '0', 'length' => 10, 'after' => 'reg7'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'reg30'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'unique' => array('column' => array('game_id', 'day'), 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'log_retention_by_day'
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
