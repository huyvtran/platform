<?php

App::uses('AppHelper', 'Helper');

class NavHelper extends AppHelper {
	
	public $helpers = array(
		'Time', 'Html', 'Utils.Gravatar', 'Form', 'Imagine.Imagine'
	);

	/**
	 *
	 */
	public function thisMenu($controller, $action = null, $slug = null, $string = null)
	{
		$string = func_get_arg(func_num_args() - 1);

		if (	(isset($controller) && $this->request->params['controller'] == $controller)
			&&	(func_num_args() < 3 || (isset($this->request->params['action']) && in_array($this->request->params['action'], (array) $action)))
			&&	(func_num_args() < 4 || (isset($this->request->params['pass'][0]) && in_array($this->request->params['pass'][0], (array) $slug)))
		) {
			return $string;
		}
		return '';
	}

	function thisSlug($slug, $string = null)
	{
		$slug = (array) $slug;
		$slug = array_map('strtolower', $slug);
		if (!empty($this->request->params['pass'][0]) && in_array(strtolower($this->request->params['pass'][0]), $slug)) {
			if (!$string)
				return 'active';
		}
	}

	function thisAction($action, $string = null)
	{
		$action = (array) $action;
		$action = array_map('strtolower', $action);
		if(in_array(strtolower($this->request->params['action']), $action)) {
			if (!$string)
				return 'active';
		}
	}
	


	function thisController($controller, $string = null)
	{
		$controller = (array) $controller;
		$controller = array_map('strtolower', $controller);

		if(in_array(strtolower($this->request->params['controller']), $controller)) {
			if (!$string)
				return 'active';
		} 
	}

	function showCounter($model, $id)
	{
		$key = 'views_count_' . $model . '_' . $id;
		$counter = Cache::read($key, 'count');
		if ($counter){
			return $counter;
		}else{
			$nameVar = Inflector::variable($model);
			return $this->_View->viewVars[$nameVar][$model]['views_count'];
		}
	}
	
	public function niceShort($date)
	{
		if ($this->Time->isToday($date)) {
			return __d('cake', 'Today, %s', $this->Time->format("h:i A", $date));
		}
		if ($this->Time->wasYesterday($date)) {
			return __d('cake', 'Yesterday, %s', $this->Time->format("h:i A", $date));
		}
		return $this->Time->format("m/d/Y", $date);
	}

	function timeAgo($time, $options = array())
	{
		$options = array_merge(array('tag' => 'span'), $options);
		$timeString = $this->Time->timeAgoInWords($time, array('end' => '+1 day', 'format' => 'F Y'));
		$timeString = explode(',', $timeString);
		
		if (count($timeString) > 1) {
			$timeString = __d('cake', '%s ago', $timeString[0] . ' ');
		} else {
			$timeString = $timeString[0];
		}
		return $this->Html->tag($options['tag'], $timeString, $options);
	}
	
	/**
	 * @param $image image's data don't contain alias
	 * @param $options :
	 * 	- retina use retina
	 *  - empty.u url no avatar
	 */
	function image($image, $width, $height, $options = array())
	{
		if (!empty($image['name'])) {

			if (!empty($image['data'])) {
				foreach($image['data'] as $img) {

					if (	!empty($img['width'])
						&&	$width == $img['width']
						&& 	(!$height || ($height == $img['height']))
						&&	(	(	empty($options['retina']) && empty($img['is_retina']))
								|| (!empty($options['retina']) && !empty($img['is_retina']))
							)
					) {
						$url = Configure::read('static') . str_replace('\\', '/', $img['dir']);
					}
				}
			}

			if (empty($url)) {
				if (isset($width, $height)) {
					if ($imgUrl = $this->Imagine->imageUrl(
						array('controller' => 'images', 'action' => 'resize', $image['id'], 'admin' => false),
						array('thumbnail' => array_merge(array('width' => $width, 'height' => $height), $options))
					)) {
						return $this->image($imgUrl, $width, $height, $options);					
					}
				}
			}
		}

		if (empty($url)) {
			if (isset($options['empty'])) {
				if (is_string($options['empty'])) {
					$url = $options['empty'];
				} else {
					$url = "noAvatar.png";
				}
			} else {
				return 'NoImage';
			}
		}

		return $this->Html->image($url,
			array_merge(
				array('width' => $width, 'height' => $height),
				$options
			)
		);
	}
	
	function avatarUser($user, $size, $options = array())
	{
		if (!empty($user['Avatar']['s' . $size])) {
			return $this->Html->image($user['Avatar']['s' . $size]['url'],
				array_merge(
					array('width' => $user['Avatar']['s' . $size]['width'], 'height' => $user['Avatar']['s' . $size]['height']),
					$options
				)
			);
		} else {
			if (isset($user['email'])){
				$email = $user['email'];
			}else if (isset($user['User']['email'])){
				$email = $user['User']['email'];
			}else{
				return false;
			}
			return $this->Gravatar->image($email,
				array_merge(
					array(
						'size' => $size,
						'rating' => 'x',
						'default' => 'monsterid',
						'width' => $size,
						'height' => $size
					),
					$options
				)
			);
		}
	}
	
	function isFavorited($model, $type, $foreignKey, $userId)
	{
		$cache = Cache::read("favorite+$model+$type+$foreignKey+$userId", 'keys');
		if ($cache === false){
			$id = $this->requestAction("/favorites/isFavorited/$model/$type/$foreignKey/$userId");
			if ($id === false)
				$id = 0;
			Cache::write("favorite+$model+$type+$foreignKey+$userId", $id, 'keys');
			return $id;
		}else{
			return $cache;
		}
	}

	function hasNotifications($userid)
	{
		$cache = Cache::read("notifications+" .$userid, 'keys');
		if ($cache === false){
			$notifications = $this->requestAction("/notifications/hasNotifications/$userid");
			if ($notifications === false)
				$notifications = 0;
			Cache::write("notifications+" . $userid, $notifications, 'keys');
			return $notifications;
			
		}else{
			return $cache;
		}
	}
	
	function turnNotification($model, $foreign_key, $userid){
		$cache = Cache::read("notifications+turn+$model+$foreign_key+$userid", 'keys');
		if ($cache === false){
			$turn = $this->requestAction("/unsubcribeTemps/turn/$model/$foreign_key/$userid");
			if ($turn === false)
				$turn = 0;
			Cache::write("notifications+turn+$model+$foreign_key+$userid", $turn, 'keys');
			return $turn;
			
		}else{
			return $cache;
		}
	}
	
	function lastlink($thread)
	{
		$pageNum = ceil($thread['post_count'] / Configure::read('Thread.paging'));
		$toHash = array();
		if ($pageNum > 1) {
			$toHash = $thread['post_count'] % Configure::read('Thread.paging');
			if ($toHash == 0)
				$toHash = Configure::read('Thread.paging');
			$toHash = array('#' => 'post-' . $toHash);

		}else{
			$toHash = array('#' => 'post-' . $thread['post_count']);
		}
		return array_merge(array(
			'user' 		 => false,
			'controller' => 'threads',
			'action' 	 => 'view',
			$thread['slug'],
			'page' 		 => $pageNum 
			),$toHash
		);
	}
	
	function checkbox($fieldName, $label, $description)
	{
		$a = '<div class = "control-group">';
		$a .= $this->Form->label($fieldName, $label, array('class' => 'control-label'));
		$a .= '<div class = "controls">';
		$a .= '<label class = "checkbox">';
		$a .= $this->Form->checkbox($fieldName) . $description; 
		$a .= '</label>';
		$a .= '</div>';
		$a .= '</div>';

		return $a;
	}
	
	public function breadCrumb($paths){
		$out = '<div class = "breadcrumb">';
		$total = count($paths);
		$this->Html->addCrumb('Diễn đàn', array(
			'controller' => 'forums', 'action' => 'index'
		));
		foreach ($paths as $k => $path){
			if ($k + 1 < $total){
				$this->Html->addCrumb(h($path['Forum']['name']), array(
					'controller' => 'forums', 'action' => 'index', $path['Forum']['slug']
				));
			}else
				$this->Html->addCrumb(h($path['Forum']['name']));
		}
		$out .=$this->Html->getCrumbs('  &rsaquo;  ');
		$out .='</div>';
		return $out;
	}
}
?>