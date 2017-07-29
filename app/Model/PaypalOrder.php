<?php

App::uses('AppModel', 'Model');

class PaypalOrder extends AppModel {

	public $useTable = 'paypal_orders'; // bảng lưu log khi gọi tới cổng thanh toán giao dịch

}
