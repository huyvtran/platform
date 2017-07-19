<?php

App::uses('AppModel', 'Model');

class OnepayOrder extends AppModel {

	public $useTable = 'onepay_orders'; // bảng lưu log khi gọi tới cổng thanh toán giao dịch

}
