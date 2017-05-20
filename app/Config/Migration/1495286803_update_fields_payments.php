<?php
class UpdateFieldsPayments extends CakeMigration {

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
				'payments' => array(
					'card_serial' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'card_code'),
				),
				'pre_payments' => array(
					'card_serial' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'card_code'),
				),
				'transactions' => array(
					'card_serial' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'card_code'),
				),
				'waiting_payments' => array(
					'card_serial' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'card_code'),
				),
			),
			'drop_field' => array(
				'payments' => array('card_seria',),
				'pre_payments' => array('card_seria', 'price',),
				'transactions' => array('card_seria',),
				'waiting_payments' => array('card_seria',),
			),
			'alter_field' => array(
				'waiting_payments' => array(
					'price' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
				),
			),
		),
		'down' => array(
			'drop_field' => array(
				'payments' => array('card_serial',),
				'pre_payments' => array('card_serial',),
				'transactions' => array('card_serial',),
				'waiting_payments' => array('card_serial',),
			),
			'create_field' => array(
				'payments' => array(
					'card_seria' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
				),
				'pre_payments' => array(
					'card_seria' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'price' => array('type' => 'integer', 'null' => false, 'default' => NULL),
				),
				'transactions' => array(
					'card_seria' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
				),
				'waiting_payments' => array(
					'card_seria' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
				),
			),
			'alter_field' => array(
				'waiting_payments' => array(
					'price' => array('type' => 'string', 'null' => false, 'default' => NULL, 'length' => 45, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
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
