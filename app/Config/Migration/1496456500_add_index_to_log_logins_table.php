<?php
class AddIndexToLogLoginsTable extends CakeMigration {

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
				'log_logins' => array(
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index'),
					'ip' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
				),
			),
			'create_field' => array(
				'log_logins' => array(
					'indexes' => array(
						'game_id' => array('column' => 'game_id', 'unique' => 0),
						'ip' => array('column' => 'ip', 'unique' => 0),
					),
				),
			),
			'create_table' => array(
				'log_payments_by_day' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'value' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'id'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 5, 'key' => 'index', 'after' => 'value'),
					'day' => array('type' => 'date', 'null' => false, 'default' => NULL, 'after' => 'game_id'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'day'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'game_id_day' => array('column' => array('game_id', 'day'), 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'alter_field' => array(
				'log_logins' => array(
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10),
					'ip' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
				),
			),
			'drop_field' => array(
				'log_logins' => array('', 'indexes' => array('game_id', 'ip')),
			),
			'drop_table' => array(
				'log_payments_by_day'
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
