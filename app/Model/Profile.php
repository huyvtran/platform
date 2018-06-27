<?php
App::uses('AppModel', 'Model');

class Profile extends AppModel {

    public $actsAs = array(
        'Search.Searchable',
        'Utils.Serializable' => array(
            'field' => array('devices', 'data')
        ),
    );

    public $validate = array(
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
    );

    public function checkBirthday() {
        if (isset($this->data[$this->alias]['birthday']) && strtotime($this->data[$this->alias]['birthday']) > time()){
            return false;
        }
        return true;
    }
}