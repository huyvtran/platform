<?php

App::uses('AppModel', 'Model');

class WaitingPayment extends AppModel {

	public $useTable = 'waiting_payments';

	const STATUS_WAIT  		= 0; // khi tạo giao dịch nạp
	const STATUS_QUEUEING  	= 1; // chờ cổng game trả về, có thể bị timeout
	const STATUS_COMPLETED  = 2; // xác nhận thành công
	const STATUS_ERROR  	= 3; // cổng game trả về, thẻ lỗi hoặc đã sử dụng
}
