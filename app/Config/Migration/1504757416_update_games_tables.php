<?php
class UpdateGamesTables extends CakeMigration {

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
			'drop_field' => array(
				'games' => array('app_testflightid', 'app_paypalid', 'paypal', 'show_on_mobpage', 'mobhub_link', 'mobhub_published', 'mobhub_description', 'mobhub_package', 'mobhub_md5', 'show_on_funtap', 'show_on_mail', 'show_on_gate_app', 'show_image_gate_app', 'hide_all_payment',),
			),
			'alter_field' => array(
				'games' => array(
					'group' => array('type' => 'integer', 'null' => true, 'default' => NULL, 'length' => 5),
				),
			),
		),
		'down' => array(
			'create_field' => array(
				'games' => array(
					'app_testflightid' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'app_paypalid' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'paypal' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'show_on_mobpage' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
					'mobhub_link' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'mobhub_published' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'mobhub_description' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'mobhub_package' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'mobhub_md5' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 32, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'show_on_funtap' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
					'show_on_mail' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 2),
					'show_on_gate_app' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'show_image_gate_app' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'hide_all_payment' => array('type' => 'integer', 'null' => true, 'default' => '0', 'length' => 4),
				),
			),
			'alter_field' => array(
				'games' => array(
					'group' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
				),
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
