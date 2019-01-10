<?php
App::uses('AppModel', 'Model');

class Profile extends AppModel {

    public $actsAs = array(
        'Search.Searchable',
        'Utils.Serializable' => array(
            'field' => array('devices', 'data')
        ),
    );
}