<?php
App::uses('AppModel', 'Model');

class Profile extends AppModel {

    public $actsAs = array(
        'Search.Searchable'
    );
    public $belongsTo = array('User');
    public $filterArgs = array(
        'from_time' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'Profile.birthday >= '),
        'to_time' => array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'Profile.birthday <= '),
        'from_time1' => array('type' => 'expression', 'method' => 'fromTimeCond1', 'field' => 'Profile.created >= '),
        'to_time1' => array('type' => 'expression', 'method' => 'toTimeCond1', 'field' => 'Profile.created <= '),
    );

    public $validate = array(
        'email_contact' => array(
            'required' => array(
                'rule'       => 'notEmpty',
                'required'   => true,
                'allowEmpty' => false,
                'message'    => 'Hãy nhập email.'
            ),
            'email' => array(
                'rule'    => array('email', true),
                'message' => 'Email không hợp lệ.'
            ),
            'email_deny_word' => array(
                'rule'    => 'email_deny_word',
                'message' => 'Email tồn tại từ khóa xấu.'
            ),
            'unique_email_contact' => array(
                'rule'=>array('isUnique'),
                'message' => 'Địa chỉ email này đã được sử dụng.'),
        ),
        'peopleId'=>array(
            'required' => array(
                'rule'       => 'notEmpty',
                'required'   => true,
                'allowEmpty' => false,
                'message'    => 'CMND không được để trống.'
            ),
            'minLength' => array(
                'rule' => array('minLength', 9),
                'message' => 'CMND phải từ 9 đến 13 ký tự.'),
            'maxLength' => array(
                'rule'    => array('maxLength', 13),
                'message' => 'CMND phải từ 9 đến 13 ký tự.'
            )
        ), 'phone'=>array(
            'required' => array(
                'rule'       => 'notEmpty',
                'required'   => true,
                'allowEmpty' => false,
                'message'    => 'Số điện thoại không được để trống.'
            ),
            'phone_check' => array(
                'rule'    => 'phone_check',
                'message' => 'Số điện thoại không hợp lệ.'
            ),
            'unique_phone' => array(
                'rule'=>array('isUnique'),
                'message' => 'SĐT này đã được sử dụng.'),
        ),
        'peopleId_place_get'=>array(
            'required' => array(
                'rule'       => 'notEmpty',
                'required'   => true,
                'allowEmpty' => false,
                'message'    => 'Nơi cấp không được để trống.'
            )),
        'peopleId_date_get'=>array(
            'required' => array(
                'rule'       => 'notEmpty',
                'required'   => true,
                'allowEmpty' => false,
                'message'    => 'Ngày cấp không được để trống.'
            ),
            'checkDate'=>array(
                'rule'       => 'checkDate',
                'message'    => 'Ngày cấp không hợp lệ'
            ),
            'date' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Bạn phải nhập đúng định dạng YYYY-MM-DD.',
            ),
             'peopleId_place_get_deny_word' => array(
                'rule' => 'peopleId_place_get_deny_word',
                'message' => 'Nơi cấp tồn tại từ khóa xấu.',
            )
        ),
        'birthday'=>array(
            'required' => array(
                'rule'       => 'notEmpty',
                'required'   => true,
                'allowEmpty' => false,
                'message'    => 'Ngày sinh không được để trống.'
            ),
            'checkBirthday'=>array(
                'rule'       => 'checkBirthday',
                'message'    => 'Ngày sinh không hợp lệ'
            ),
            'date' => array(
                'rule' => array('date', 'ymd'),
                'message' => 'Bạn phải nhập đúng định dạng YYYY-MM-DD.',
            )
        ),
        'address'=>array(
            'address_deny_word' => array(
                'rule' => 'address_deny_word',
                'message' => 'Địa chỉ tồn tại từ khóa xấu.',
            )
        ),'fullname'=>array(
            'fullname_deny_word' => array(
                'rule' => 'fullname_deny_word',
                'message' => 'Họ tên tồn tại từ khóa xấu.',
            )
        )
    );
}