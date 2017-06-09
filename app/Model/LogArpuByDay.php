<?php

App::uses('AppModel', 'Model');

/**
 * Lưu record chứa thông tin thiết bị của users tại thời điểm login
 * có thể sử dụng để thống kê thông tin thiết bị sử dụng.
 */
class LogArpuByDay extends AppModel {

	public $useTable = 'log_arpu_by_day';
	
}