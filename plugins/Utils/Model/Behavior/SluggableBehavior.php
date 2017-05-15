<?php
/**
 * Copyright 2007-2010, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2007-2010, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */


App::uses('Multibyte', 'I18n');

/**
 * Utils Plugin
 *
 * Utils Sluggable Behavior
 *
 * @package utils
 * @subpackage utils.models.behaviors
 */
class SluggableBehavior extends ModelBehavior {

/**
 * Settings to configure the behavior
 *
 * @var array
 */
	public $settings = array();

/**
 * Default settings
 *
 * label 		- The field used to generate the slug from
 * slug 		- The field to store the slug in
 * scope 		- conditions for the find query to check if the slug already exists
 * separator 	- the character used to separate the words in the slug
 * length		- the maximum length of the slug
 * unique		- check if the slug is unique
 * update		- update the slug or not
 * trigger		- defines a property in the model that has to be true to generate the slug
 *
 * Note that trigger will temporary bypass update and act like update is set to true.
 *
 * @var array
 */
	protected $_defaults = array(
		'label' => 'title',
		'slug' => 'slug',
		'scope' => array(),
		'separator' => '_',
		'length' => 255,
		'unique' => true,
		'update' => false,
		'trigger' => false);

/**
 * Initiate behaviour
 *
 * @param object $Model
 * @param array $settings
 */
	public function setup(Model $Model, $settings = array()) {
		$this->settings[$Model->alias] = array_merge($this->_defaults, $settings);
	}

/**
 * beforeSave callback
 *
 * @param object $Model
 */
	public function beforeSave(Model $Model) {
		$settings = $this->settings[$Model->alias];
		if (is_string($this->settings[$Model->alias]['trigger'])) {
			if ($Model->{$this->settings[$Model->alias]['trigger']} != true) {
				return true;
			}
		}

		if (empty($Model->data[$Model->alias])) {
			return true;
		} else if (empty($Model->data[$Model->alias][$this->settings[$Model->alias]['label']])) {
			return true;
		} else if (!$this->settings[$Model->alias]['update'] && !empty($Model->id) && !is_string($this->settings[$Model->alias]['trigger'])) {
			return true;
		}

		$slug = $Model->data[$Model->alias][$settings['label']];
		if (method_exists($Model, 'beforeSlugGeneration')) {
			$slug = $Model->beforeSlugGeneration($slug, $settings['separator']);
		}

		$settings = $this->settings[$Model->alias];
		if (method_exists($Model, 'multibyteSlug')) {
			$slug = $Model->multibyteSlug($slug, $settings['separator']);
		} else {
			$slug = $this->multibyteSlug($Model, $slug);
		}

		if ($settings['unique'] === true || is_array($settings['unique'])) {
			$slug = $this->makeUniqueSlug($Model, $slug);
		}

		if (!empty($Model->whitelist) && !in_array($settings['slug'], $Model->whitelist)) {
			$Model->whitelist[] = $settings['slug'];
		}
		$Model->data[$Model->alias][$settings['slug']] = $slug;
		return true;
	}

/**
 * Searche if the slug already exists and if yes increments it
 *
 * @param object $Model
 * @param string the raw slug
 * @return string The incremented unique slug
 *
 */
	public function makeUniqueSlug(Model $Model, $slug = '') {
		$settings = $this->settings[$Model->alias];
		$conditions = array();
		if ($settings['unique'] === true) {
			$conditions[$Model->alias . '.' . $settings['slug'] . ' LIKE'] = $slug . '%';
		} else if (is_array($settings['unique'])) {
			foreach ($settings['unique'] as $field) {
				$conditions[$Model->alias . '.' . $field] = $Model->data[$Model->alias][$field];
			}
			$conditions[$Model->alias . '.' . $settings['slug'] . ' LIKE'] = $slug . '%';
		}

		if (!empty($Model->id)) {
			$conditions[$Model->alias . '.' . $Model->primaryKey . ' !='] = $Model->id;
		}

		$conditions = array_merge($conditions, $this->__scopeCondition($Model));

		$duplicates = $Model->find('all', array(
			'recursive' => -1,
			'conditions' => $conditions,
			'fields' => array($settings['slug'])));

		if (!empty($duplicates)) {
			$duplicates = Set::extract($duplicates, '{n}.' . $Model->alias . '.' . $settings['slug']);
			$startSlug = $slug;
			$index = 1;

			while ($index > 0) {
				if (!in_array($startSlug . $settings['separator'] . $index, $duplicates)) {
					$slug = $startSlug . $settings['separator'] . $index;
					$index = -1;
				}
				$index++;
			}
		}
		return $slug;
	}

/**
 * Add aditional conditions to make scope of list.
 *
 * @param AppModel $model
 */
   private function __scopeCondition($model) {
		extract($this->settings[$model->alias]);
		$scopes = array();
		if (is_string($scope)) {
			if ($scope=='') {
				return $scopes;
			}
			$scopes[$model->alias . '.' . $scope] = $model->data[$model->alias][$scope];
		} elseif (is_array($scope)) {
			foreach ($scope as $k => $v) {
				if (is_numeric($k)) {
					$scopeEl = $v;
					$v = $model->data[$model->alias][$scopeEl];
				} else {
					$scopeEl = $k;
				}
				$scopes[$model->alias . '.' . $scopeEl] = $v;
			}
		}
		return $scopes;
	}

/**
 * Generates a slug from a (multibyte) string
 *
 * @param object $Model
 * @param string $string
 * @return string
 */
	public function multibyteSlug(Model $Model, $string = null) {
		$string = strtr($string, $this->chars);
		$str = mb_strtolower($string);
		$str = preg_replace('/\xE3\x80\x80/', ' ', $str);
		$str = preg_replace('[\'s ]', 's ', $str);
		$str = str_replace($this->settings[$Model->alias]['separator'], ' ', $str);
		$str = preg_replace( '#[:\#\*"()~$^{}`@+=;,<>!&%\.\]\/\'\\\\|\[]#', "\x20", $str );
		$str = str_replace('?', '', $str);
		$str = trim($str);
		$str = preg_replace('#\x20+#', $this->settings[$Model->alias]['separator'], $str);
		return $str;
	}

	public $chars = array(
		'à' => 'a',
		'á' => 'a',
		'ả' => 'a',
		'ã' => 'a',
		'ạ' => 'a',

		'ă' => 'a',
		'ằ' => 'a',
		'ắ' => 'a',
		'ẳ' => 'a',
		'ẵ' => 'a',
		'ặ' => 'a',

		'â' => 'a',
		'ầ' => 'a',
		'ấ' => 'a',
		'ẩ' => 'a',
		'ẫ' => 'a',
		'ậ' => 'a',

		'đ' => 'd',

		'è' => 'e',
		'é' => 'e',
		'ẻ' => 'e',
		'ẽ' => 'e',
		'ẹ' => 'e',

		'ê' => 'e',
		'ề' => 'e',
		'ế' => 'e',
		'ể' => 'e',
		'ễ' => 'e',
		'ệ' => 'e',

		'ì' => 'i',
		'í' => 'i',
		'ỉ' => 'i',
		'ĩ' => 'i',
		'ị' => 'i',

		'ò' => 'o',
		'ó' => 'o',
		'ỏ' => 'o',
		'õ' => 'o',
		'ọ' => 'o',

		'ô' => 'o',
		'ồ' => 'o',
		'ố' => 'o',
		'ổ' => 'o',
		'ỗ' => 'o',
		'ộ' => 'o',

		'ơ' => 'o',
		'ờ' => 'o',
		'ớ' => 'o',
		'ở' => 'o',
		'ỡ' => 'o',
		'ợ' => 'o',

		'ù' => 'u',
		'ú' => 'u',
		'ủ' => 'u',
		'ũ' => 'u',
		'ụ' => 'u',

		'ư' => 'u',
		'ừ' => 'u',
		'ứ' => 'u',
		'ử' => 'u',
		'ữ' => 'u',
		'ự' => 'u',

		'ỳ' => 'y',
		'ý' => 'y',
		'ỷ' => 'y',
		'ỹ' => 'y',
		'ỵ' => 'y',

		'À' => 'A',
		'Á' => 'A',
		'Ả' => 'A',
		'Ã' => 'A',
		'Ạ' => 'A',

		'Ă' => 'A',
		'Ằ' => 'A',
		'Ắ' => 'A',
		'Ẳ' => 'A',
		'Ẵ' => 'A',
		'Ặ' => 'A',

		'Â' => 'A',
		'Ầ' => 'A',
		'Ấ' => 'A',
		'Ẩ' => 'A',
		'Ẫ' => 'A',
		'Ậ' => 'A',

		'Đ' => 'D',

		'È' => 'E',
		'É' => 'E',
		'Ẻ' => 'E',
		'Ẽ' => 'E',
		'Ẹ' => 'E',

		'Ê' => 'E',
		'Ề' => 'E',
		'Ế' => 'E',
		'Ể' => 'E',
		'Ễ' => 'E',
		'Ệ' => 'E',

		'Ì' => 'I',
		'Í' => 'I',
		'Ỉ' => 'I',
		'Ĩ' => 'I',
		'Ị' => 'I',

		'Ò' => 'O',
		'Ó' => 'O',
		'Ỏ' => 'O',
		'Õ' => 'O',
		'Ọ' => 'O',

		'Ô' => 'O',
		'Ồ' => 'O',
		'Ố' => 'O',
		'Ổ' => 'O',
		'Ỗ' => 'O',
		'Ộ' => 'O',

		'Ơ' => 'O',
		'Ờ' => 'O',
		'Ớ' => 'O',
		'Ở' => 'O',
		'Ỡ' => 'O',
		'Ợ' => 'O',

		'Ù' => 'U',
		'Ú' => 'U',
		'Ủ' => 'U',
		'Ũ' => 'U',
		'Ụ' => 'U',

		'Ư' => 'U',
		'Ừ' => 'U',
		'Ứ' => 'U',
		'Ử' => 'U',
		'Ữ' => 'U',
		'Ự' => 'U',

		'Ỳ' => 'Y',
		'Ý' => 'Y',
		'Ỷ' => 'Y',
		'Ỹ' => 'Y',
		'Ỵ' => 'Y',

		'ứ' => 'u',
		'ầ' => 'a',
		'ế' => 'e',
		'ó' => 'o',
		'ỏ' => 'o',
	);
}
