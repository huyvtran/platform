<?php
class AlterTableProfiles extends CakeMigration {

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
				'profiles' => array(
					'devices' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'birthday'),
					'data' => array('type' => 'text', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8', 'after' => 'devices'),
					'indexes' => array(
						'user_id_birthday' => array('column' => array('user_id', 'birthday'), 'unique' => 0),
					),
				),
			),
			'drop_field' => array(
				'profiles' => array('email_contact', 'email_contact_token', 'email_contact_token_expires', 'email_contact_verified', 'fullname', 'peopleId', 'peopleId_place_get', 'peopleId_date_get', 'phone', 'address', 'province', 'gender', 'question1', 'answer1', 'question2', 'answer2', 'phone_verified', 'facebook_link', 'birthday2', 'phone_code', 'country', 'indexes' => array('birthday', 'user_id_and_birthday', 'birthday2', 'user_id_birthday2')),
			),
		),
		'down' => array(
			'drop_field' => array(
				'profiles' => array('devices', 'data', 'indexes' => array('user_id_birthday')),
			),
			'create_field' => array(
				'profiles' => array(
					'email_contact' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'email_contact_token' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_general_ci', 'charset' => 'utf8'),
					'email_contact_token_expires' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'email_contact_verified' => array('type' => 'boolean', 'null' => false, 'default' => '0'),
					'fullname' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'peopleId' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'peopleId_place_get' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'peopleId_date_get' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
					'phone' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 15, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'address' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'province' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'gender' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'question1' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 10, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'answer1' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'question2' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'answer2' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'phone_verified' => array('type' => 'boolean', 'null' => true, 'default' => '0'),
					'facebook_link' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'birthday2' => array('type' => 'datetime', 'null' => true, 'default' => NULL, 'key' => 'index'),
					'phone_code' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 8, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'country' => array('type' => 'string', 'null' => true, 'default' => NULL, 'collate' => 'utf8_unicode_ci', 'charset' => 'utf8'),
					'indexes' => array(
						'birthday' => array('column' => 'birthday', 'unique' => 0),
						'user_id_and_birthday' => array('column' => array('user_id', 'birthday'), 'unique' => 0),
						'birthday2' => array('column' => 'birthday2', 'unique' => 0),
						'user_id_birthday2' => array('column' => array('user_id', 'birthday2'), 'unique' => 0),
					),
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
