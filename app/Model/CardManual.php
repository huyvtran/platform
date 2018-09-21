<?php

App::uses('AppModel', 'Model');

class CardManual extends AppModel {

	public $useTable = 'card_manuals';

    public $actsAs = array(
        'Search.Searchable'
    );

    public $validate = array(
        'card_serial' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter a card seria'
            )
        ),
		'card_code' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please enter a card code'
            )
        ),
        'card_price' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please chose a price'
            )
        ),
        'price' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Please chose a price'
            )
        ),
        'type' => array(
            'required' => array(
                'rule' => 'notEmpty',
                'message' => 'Please chose a type'
            ),
            'confirmSeri' => array(
                'rule' => 'confirmSeri',
                'message' => 'Serial or cardcode is not format.'
            )
        ),
    );

    public $filterArgs = array(
        'order_id'  => array('type' => 'value'),
        'game_id'   => array('type' => 'value'),
        'status'    => array('type' => 'value'),
        'chanel'    => array('type' => 'value'),
        'type'      => array('type' => 'value'),
        'username'  => array('type' => 'like', 'field' => array('User.id', 'User.username', 'User.email')),
        'cardnumber'=> array('type' => 'value', 'field' => 'card_serial'),
        'cardcode'  => array('type' => 'value', 'field' => 'card_code'),
        'from_time' => array('type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'CardManual.time >= '),
        'to_time'   => array('type' => 'expression', 'method' => 'toTimeCond', 'field' => 'CardManual.time <= '),

    );

    public function fromTimeCond($data = array())
    {
        return date('U', strtotime(date('d-m-Y 0:0:0', $data['from_time'])));
    }

    public function toTimeCond($data = array())
    {
        return date('U', strtotime(date('d-m-Y 23:59:59', $data['to_time'])));
    }

    function paginateCount($conditions = array(), $recursive = 0, $extra = array())
    {
        $parameters = compact('conditions');
        if ($recursive != $this->recursive) {
            $parameters['recursive'] = $recursive;
        }

        $extra['recursive'] = -1;
        $extra['contain'] = array();
        if(isset($conditions['OR']['User.username LIKE'])    ||
            isset($conditions['OR']['User.email LIKE'])
        ){
            $this->bindModel(array(
                'belongsTo' => array('User')
            ));
            $extra['contain'] = array_merge($extra['contain'], array('User'));
        }

        return $this->find('count', array_merge($parameters, $extra));
    }

    public function confirmSeri($type = null) {
        if ( isset($type['type'])
            && isset($this->data[$this->alias]['card_serial'])
            && isset($this->data[$this->alias]['card_code'])
        ) {
            if (strpos($this->data[$this->alias]['card_code'], '-') !== false
                || strpos($this->data[$this->alias]['card_code'], ' ') !== false
            ) {
                return false;
            }

            if (strpos($this->data[$this->alias]['card_serial'], '-') !== false
                || strpos($this->data[$this->alias]['card_serial'], ' ') !== false
            ) {
                return false;
            }

            ClassRegistry::init('Payment');
            if($type['type'] == Payment::TYPE_NETWORK_ZING) return true;
            if($type['type'] == Payment::TYPE_NETWORK_GATE) return true;
            if($type['type'] == Payment::TYPE_NETWORK_VCOIN) return true;

            # verify vinaphone
            if($type['type'] == Payment::TYPE_NETWORK_VINAPHONE){
                if( strlen($this->data[$this->alias]['card_serial']) == 14
                    && ( strlen($this->data[$this->alias]['card_code']) == 12 || strlen($this->data[$this->alias]['card_code']) == 14)
                ) {
                    return true;
                }
            }

            # verify mobifone
            if($type['type'] == Payment::TYPE_NETWORK_MOBIFONE){
                if( strlen($this->data[$this->alias]['card_serial']) == 15 && strlen($this->data[$this->alias]['card_code']) == 12 ) return true;
            }

            # verify viettel
            if($type['type'] == Payment::TYPE_NETWORK_VIETTEL){
                if( strlen($this->data[$this->alias]['card_serial']) == 11 && strlen($this->data[$this->alias]['card_code']) == 13 ) return true;
                if( strlen($this->data[$this->alias]['card_serial']) == 14 && strlen($this->data[$this->alias]['card_code']) == 15 ) return true;
            }
        }
        return false;
    }
}
