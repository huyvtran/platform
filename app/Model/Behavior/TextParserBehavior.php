<?php

use \Michelf\MarkdownExtra;

class TextParserBehavior extends ModelBehavior {
	
	public $_settings = array(
		'fields' => array('body'),
		'overwrite' => false,
		'affix' => 'parsed',
		'affix_position' => 'prefix'
	);

	public function setup(Model $model, $config = array()) {
		$this->settings[$model->alias] = $this->_settings;
		$this->settings[$model->alias] = Set::merge($this->settings[$model->alias], $config);
	}
	
	public function beforeSave(Model $model)
    {
        foreach ($this->settings[$model->alias]['fields'] as $fieldName) {
            if (!isset($model->data[$model->alias][$fieldName]) || empty($model->data[$model->alias][$fieldName])) {
                continue;
            }
            if (isset($model->data['Article']['markup'])) {
                if ($this->settings[$model->alias]['overwrite']) {
                    $model->data[$model->alias][$fieldName] = ($model->data['Article']['markup'] == 'markdown') ? $this->_parse($model->data[$model->alias][$fieldName]) : $model->data[$model->alias][$fieldName];
                } else {
                    $affix = $this->settings[$model->alias]['affix'];
                    $cleanedField = "{$fieldName}_{$affix}";
                    if ($this->settings[$model->alias]['affix_position'] == 'prefix') {
                        $cleanedField = "{$affix}_{$fieldName}";
                    }
                    $model->data[$model->alias][$cleanedField] = ($model->data['Article']['markup'] == 'markdown') ? $this->_parse($model->data[$model->alias][$fieldName]) : $model->data[$model->alias][$fieldName];
                }

            } else {
                if ($this->settings[$model->alias]['overwrite']) {
                    $model->data[$model->alias][$fieldName] = $this->_parse($model->data[$model->alias][$fieldName]);
                } else {
                    $affix = $this->settings[$model->alias]['affix'];
                    $cleanedField = "{$fieldName}_{$affix}";
                    if ($this->settings[$model->alias]['affix_position'] == 'prefix') {
                        $cleanedField = "{$affix}_{$fieldName}";
                    }
                    $model->data[$model->alias][$cleanedField] = $this->_parse($model->data[$model->alias][$fieldName]);
                }
            }
        }
        return true;
    }

	function _parse($data) {
		if (	!App::import('Vendor', 'Markdown', array('file' => 'php-markdown' . DS . 'Michelf' . DS . 'Markdown.php'))
			|| 	!App::import('Vendor', 'MarkdownExtra', array('file' => 'php-markdown' . DS . 'Michelf' . DS . 'MarkdownExtra.php'))
		) {
			throw new InternalErrorException('Can not import markdown');
		}

		$rgex = '/\n(https?:\/\/(?:www)?\.youtube\.com\/watch\?v=([^\s]+))(\s*$|\s*\n)/i';
		if (preg_match_all($rgex, $data, $matches)) {
			foreach($matches[1] as $match) {
				App::import('Helper', 'Youtube');
				$Youtube = new YoutubeHelper(new View());
				$data = str_replace($match, '<div>' . $Youtube->iframe($match) . '</div>', $data);
			} 
		}
		return MarkdownExtra::defaultTransform($data);
	}

}