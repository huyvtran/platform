<?php

App::uses('AppModel', 'Model');

class Payment extends AppModel
{

	public $useTable = 'payments';

	# thanh toán trong nước
	const TYPE_NETWORK_VIETTEL = 'VTT';
	const TYPE_NETWORK_VINAPHONE = 'VNP';
	const TYPE_NETWORK_MOBIFONE = 'VMS';
	const TYPE_NETWORK_GATE = 'GATE';

	# thanh toán quốc tế
	const TYPE_NETWORK_BANKING = 'Banking';
	const TYPE_NETWORK_CARD = 'CARD';
	const TYPE_NETWORK_SMS = 'SMS';

	const CHANEL_VIPPAY = 1;
	const CHANEL_HANOIPAY = 2;
	const CHANEL_PAYPAL = 3;
	const CHANEL_MOLIN = 4;
	const CHANEL_ONEPAY = 5;
	const CHANEL_PAYMENTWALL = 6;

	const CHANEL_VIPPAY_2 = 7;
	const CHANEL_ONEPAY_2 = 8;

	const CHANEL_VIPPAY_3 = 9;

	const CHANEL_BONUS = 10;

    const CHANEL_APPOTA = 11;

	public $belongsTo = [
		'User', 'Game',
	];

	public $actsAs = [
		'Search.Searchable',
	];

	public $filterArgs = [
		'order_id' => ['type' => 'value'],
		'game_id' => ['type' => 'value'],
		'username' => ['type' => 'like', 'field' => ['User.id', 'User.username', 'User.email']],
		'type' => ['type' => 'value'],
		'chanel' => ['type' => 'value'],
		'cardnumber' => ['type' => 'value', 'field' => 'card_serial'],
		'cardcode' => ['type' => 'value', 'field' => 'card_code'],
		'from_time' => ['type' => 'expression', 'method' => 'fromTimeCond', 'field' => 'time >= '],
		'to_time' => ['type' => 'expression', 'method' => 'toTimeCond', 'field' => 'time <= '],

	];

	public function fromTimeCond($data = [])
	{
		return date('U', strtotime(date('d-m-Y 0:0:0', $data['from_time'])));
	}

	public function toTimeCond($data = [])
	{
		return date('U', strtotime(date('d-m-Y 23:59:59', $data['to_time'])));
	}

	public function convertType($type)
	{
		$result = "";
		switch ($type) {
			case Payment::TYPE_NETWORK_VIETTEL :
				$result = "Viettel";
				break;
			case Payment::TYPE_NETWORK_VINAPHONE :
				$result = "Vinaphone";
				break;
			case Payment::TYPE_NETWORK_MOBIFONE :
				$result = "Mobifone";
				break;
			case Payment::TYPE_NETWORK_GATE :
				$result = "Gate";
				break;
		}

		return $result;
	}

	function paginateCount($conditions = [], $recursive = 0, $extra = [])
	{
		$parameters = compact('conditions');
		if ($recursive != $this->recursive) {
			$parameters['recursive'] = $recursive;
		}

		$extra['recursive'] = -1;
		$extra['contain'] = [];
		if (isset($conditions['OR']['User.username LIKE']) ||
			isset($conditions['OR']['User.email LIKE'])
		) {
			$extra['contain'] = array_merge($extra['contain'], ['User']);
		}

		return $this->find('count', array_merge($parameters, $extra));
	}
}
