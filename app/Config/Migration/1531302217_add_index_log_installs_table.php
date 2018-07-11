<?php
class AddIndexLogInstallsTable extends CakeMigration {

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
			'create_field' => array(
				'log_installs' => array(
					'indexes' => array(
						'game_id_device_id' => array('column' => array('game_id', 'device_id'), 'unique' => 1),
					),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'log_installs' => array('', 'indexes' => array('game_id_device_id')),
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
