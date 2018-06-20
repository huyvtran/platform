<?php
class AddFieldsDeviceIdStatusOnGoogleInappOrders extends CakeMigration {

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
				'google_inapp_orders' => array(
					'device_id' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'ip'),
					'status' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 1, 'after' => 'device_id'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'google_inapp_orders' => array('device_id', 'status',),
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
