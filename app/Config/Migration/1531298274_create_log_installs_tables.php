<?php
class CreateLogInstallsTables extends CakeMigration {

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
				'log_installs' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index', 'after' => 'id'),
					'device' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 128, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'game_id'),
					'country' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'device'),
					'ip' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'key' => 'index', 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'country'),
					'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'key' => 'index', 'after' => 'ip'),
					'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'created_game_id' => array('column' => array('created', 'game_id'), 'unique' => 0),
						'game_id' => array('column' => 'game_id', 'unique' => 0),
						'country' => array('column' => 'country', 'unique' => 0),
						'ip' => array('column' => 'ip', 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_unicode_ci', 'engine' => 'InnoDB'),
				),
				'log_installs_by_day' => array(
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
				'log_installs_country_by_day' => array(
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
				'log_installs', 'log_installs_by_day', 'log_installs_country_by_day'
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
