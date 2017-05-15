<?php

App::uses('AppHelper', 'Helper');

class PostHelper extends AppHelper {

	public $helpers = array(
		'Html', 'Js', 'Form', 'Session'
	);

	public $showed = false;

	public function beforeRenderFile($viewFile)
	{
		$this->Html->script('Markdown.Converter.js', array('inline' => false));
		$this->Html->script('Markdown.Sanitizer.js', array('inline' => false));
		$this->Html->script('Markdown.Editor.js', array('inline' => false));
		$this->Html->script('Markdown.Extra.js', array('inline' => false));
		$this->Html->script('a/articles/edit.js', array('inline' => false));
		$this->Html->css('wmd', null, array('inline' => false));
		parent::beforeRenderFile($viewFile);
	}

	public function display($fieldName, $options = array())
	{
		$this->displayEdit($fieldName, $options);
		$this->displayPreview();
	}

	public function displayEdit($fieldName, $options = array())
	{
		$this->display = true;
		echo '<div id="wmd-editor" class="wmd-panel">
	<div id="wmd-button-bar-second"></div>';
		echo $this->Form->input($fieldName, array_merge(array(
			'class' => 'span7',
			'label' => false,
			'id' => 'wmd-input-second',
			'style' => array('color:black')
		), $options));
		?>
		</div>
		<?php
	}

	public function displayPreview()
	{
		echo '<div id="wmd-preview-second" class = "wmd-preview-a"></div>';
	}

	public function afterRenderFile($viewFile, $content) 
	{
		if ($this->showed == false) {
			ob_start();
		?>
			<div id="uploadImage" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<?php
				echo $this->Form->create('Image', array(
						'url' => array('controller' => 'images', 'action' => 'upload'),
						'type' => 'file',
						'inputDefaults' => array('div' => false, 'label' => false)
					));
				?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<h3 id="myModalLabel">Thêm hình ảnh</h3>
				</div>
				<div class="modal-body">
					<div class="tabbable">
						<ul class="nav nav-tabs">
							<li class="active">
								<a href="#imageA" data-toggle="tab">Bằng upload</a>
							</li>
							<li class="">
								<a href="#imageB" data-toggle="tab">Bằng link</a>
							</li>
						</ul>
						<div class="tab-content">
							<div class="tab-pane active" id="imageA">
								<?php
								echo $this->Form->input('image', array(
									'type' => 'file', 'label' => array(
										'text' => 'Hình', 'div' => false), 'id' => 'fileBox' ));
								?>
								<br/><br/>
								<div class="progress progress-striped active hide">
									<div class="bar" style="width: 0%;"></div>
								</div>
							</div>
							<div class="tab-pane" id="imageB">
								<?php
								echo $this->Form->input('link', array(
									'label' => array(
										'text' => 'Link',
										'div' => false
									),
									'id' => 'linkBox'
									));
								?>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button class="btn" data-dismiss="modal" aria-hidden="true" id='modal-close'>Close</button>
					<button class="btn btn-primary" id='modal-accept' >Thêm</button>
				</div>
				<?php
				$this->Form->end();
				?>
			</div>
		<?php
			$modal = ob_get_clean();
			$this->showed = true;
			return $content . $modal;
		}
	}	
	public function help()
	{
		
	}

}
?>