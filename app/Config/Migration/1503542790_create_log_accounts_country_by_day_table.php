<?php
class CreateLogAccountsCountryByDayTable extends CakeMigration {

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
				'log_accounts_country_by_day' => array(
					'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'primary'),
					'game_id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'key' => 'index', 'after' => 'id'),
					'country' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_general_ci', 'charset' => 'utf8', 'after' => 'game_id'),
					'day' => array('type' => 'date', 'null' => false, 'default' => NULL, 'after' => 'country'),
					'value' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'length' => 10, 'after' => 'day'),
					'created' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'after' => 'value'),
					'modified' => array('type' => 'datetime', 'null' => false, 'default' => NULL, 'after' => 'created'),
					'indexes' => array(
						'PRIMARY' => array('column' => 'id', 'unique' => 1),
						'game_id_country_day' => array('column' => array('game_id', 'country', 'day'), 'unique' => 0),
					),
					'tableParameters' => array('charset' => 'utf8', 'collate' => 'utf8_general_ci', 'engine' => 'InnoDB'),
				),
			),
		),
		'down' => array(
			'drop_table' => array(
				'log_accounts_country_by_day'
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
