<?php
class CreateTablesToStats extends CakeMigration {

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
				'authorization_codes' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'code' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'id'),
					'user_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'code'),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'after' => 'user_id'),
					'expires' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'after' => 'created'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'expires'),
					'redirect_url' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'game_id'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'),
				),
				'log_logins_by_day' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'value' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'after' => 'id'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index', 'after' => 'value'),
					'day' => array('type' => 'date', 'null' => false, 'default' => NULL, 'after' => 'game_id'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'unique' => array('column' => array('game_id', 'day'), 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'),
				),
				'log_logins_by_month' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'id'),
					'value' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'after' => 'game_id'),
					'time' => array('type' => 'date', 'null' => false, 'default' => NULL, 'after' => 'value'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'),
				),
				'log_logins_country_by_day' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'value' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'after' => 'id'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'after' => 'value'),
					'country' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'game_id'),
					'day' => array('type' => 'date', 'null' => false, 'default' => NULL, 'after' => 'country'),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'after' => 'day'),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'authorization_codes', 'log_logins_by_day', 'log_logins_by_month', 'log_logins_country_by_day'
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
