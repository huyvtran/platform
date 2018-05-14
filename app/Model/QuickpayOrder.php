<?php

App::uses('AppModel', 'Model');

class QuickpayOrder extends AppModel {

	public $useTable = 'quickpay_orders'; // bảng lưu log khi gọi tới cổng thanh toán giao dịch

}
