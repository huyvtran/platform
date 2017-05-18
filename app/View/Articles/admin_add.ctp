<?php
$this->extend('/Common/blank');
?>
<style type='text/css'>
	#wmd-preview-second,#ck-pre{
		max-height: 1000px;
		overflow: auto;
	}
    .date-ranger-picker .input{ float: left;}
    #editor_chzn{
        margin-bottom: 15px;
    }
    #cke_ckeditor{width: auto !important;max-width: 98% !important;margin: 40px auto}
</style>
<div class = "row">
	<?php
	echo $this->Form->create('Article',
		array(
		'type' => 'file',
		'url' => Router::url(null, true),
		'id' => 'article-form'
	));
	?>
	<div class = "span7">
		<h2 class = 'page-header'>
			<?php
			if ($this->request->action == 'admin_add') {
				echo 'Articles - Add';
			} elseif ($this->request->action == 'admin_edit') {
				echo 'Articles - Edit ';
				echo '<small> ' . $this->data['Article']['title'] . '</small>';
			}
			?>
		</h2>
		<?php
		if (!empty($this->data['Article']['id'])) {
			echo $this->Form->input('id');
		}
		?>
		<div class="row">
			<div class="span3">
			<?php
			echo $this->Form->input('file',
				array('type' => 'file', 'label' => array('text' => 'Ảnh đại diện', 'div' => false)));
			?>
			<?php
			echo $this->Form->input('Article.category_id', array(
				'label' => 'Thuộc mục',
				'empty' => '--Không thuộc mục nào--'
			));
			echo $this->Form->input('title');
			?>
			</div>
			<div class="span4">
				<?php
				if ($this->request->action == 'admin_edit') {
				?>
				<div class='row'>
					<div class='span3'>
						<?php
						echo 'Viết bởi ' . $this->request->data['User']['username'];
						?>
					</div>
				</div>
				<?php
				}
				$role = $this->Session->read('Auth.User.role');
                $disabled = (isset($this->data['Article']['markup'])) ? 'disabled' : '';
				echo $this->Form->input('slug', array('label' => array('text' => 'Slug'), 'disabled' => 'disabled'));
				echo $this->Form->hidden('Article.markup');
                echo $this->Form->input('Article.markup', array('type' => 'select','id' => 'editor','label' => 'Format', 'disabled' => $disabled, 'options' => array('html' => 'HTML', 'markdown' => 'Markdown')));
                ?>
			</div>
		</div>
		<?php
		if (!empty($this->data['Article']['id'])) {
			if ($this->data['Article']['position'] == 0) {
				echo $this->Form->input('position');
			}
		}
		?>
	</div>

	<div class='span5'>

		<a href='<?php echo $this->Html->url(array('controller' => 'pages', 'action' => 'editor-help-vi', 'admin' => false)) ?>'><strong>* Guide: how to edit</strong></a>
		<?php
		echo $this->Form->input('summary', array(
			'class' => 'span4', 'label' => array('text' => 'Summary <em><small>(required)</small></em>'),
			'required'=> true,
			'placeholder' => 'Mô tả ngắn tầm 170 ký tự của bài viết'));
		?>
	</div>
</div>
<div class='row'>
    <div id="markdown">
        <div class='span6'>
            <?php
                $data = (isset($this->data['Article']['body'])) ? $this->data['Article']['body'] : '';
                echo $this->Post->displayEdit('Article.body_markdown', array('class' => 'span6', 'rows' => 15, 'default'=>$data));
            ?>
        </div>
        <div class='span6'>
            <?php
                echo $this->Post->displayPreview();
            ?>
        </div>
    </div>
    <div id="html">
        <div class='span12'>
            <?php
                echo $this->Form->input('Article.body',array(
                    'class' => 'span12',
                    'label' => false,
                    'id' => 'ckeditor',
                    'style' => array('color:black'),
                ));
            ?>
        </div>
    </div>
</div>

<div class='row'>
    <div class='span7'>
        <br/>
        Thay đổi thời gian để hẹn giờ public bài viết (có thể bị delay 5 phút)<br/>
        <span class='date-ranger-picker'>
            <?php
                echo $this->Form->input('Article.published_date_hour',array(
                    'placeholder' => 'Time',
                    'class'=>'timepicker form-control span2',
                    'label'=>''
                ));

                echo $this->Form->input('Article.published_date_date',array(
                    'placeholder' => 'Date',
                    'class'=>'datepicker form-control span2',
                    'label'=>''
                ));
            ?>
        </span>
        <br/>
    </div>
</div>

<div class='row'>
	<div class='span3'>
		<em><small>Đánh dấu đặc điểm cho bài viết: </small></em>
		<?php
            echo $this->Form->input('is_new', array('label' => 'Bài mới'));
            echo $this->Form->input('is_hot', array('label' => 'Bài Hot'));
		?>
	</div>
	<div class='span4'>
		<em><small>Gửi thông báo đến toàn bộ người chơi game này: </small></em>
		<?php
		echo $this->Form->input('notify',
			array(
			'type' => 'checkbox', 'label' => array('text' => 'Notify')
		));
		?>
	</div>

</div>

<a href="#" class='show-config-box'>Nếu bài viết là sự kiện hãy chọn thời gian bắt đầu và kết thúc sự kiện [+]</a>
<div class='config-box'>
    <div class="row">
        <div class="span4">
            <p><em><small>Thời gian bắt đầu sự kiện:</small></em></p>
            <span class='date-ranger-picker'>
                <?php
                    echo $this->Form->input('Article.event_start_hour',array(
                        'placeholder' => 'Time',
                        'class'=>'timepicker form-control span2',
                        'label'=>''
                    ));

                    echo $this->Form->input('Article.event_start_date',array(
                        'placeholder' => 'Date',
                        'class'=>'datepicker form-control span2',
                        'label'=>''
                    ));
                ?>
            </span>
        </div>
        <div class="span4">
            <p><em><small>Thời gian kết thúc sự kiên:</small></em></p>
            <span class='date-ranger-picker'>
                <?php
                    echo $this->Form->input('Article.event_end_hour',array(
                        'placeholder' => 'Time',
                        'class'=>'timepicker form-control span2',
                        'label'=>''
                    ));
                    echo $this->Form->input('Article.event_end_date',array(
                        'placeholder' => 'Date',
                        'class'=>'datepicker form-control span2',
                        'label'=>''
                    ));
                ?>
            </span>
        </div>
    </div>
</div>
<div class='row'>
    <div class='span12'>
    <?php
    echo '<div class="form-actions">';
    echo $this->Form->submit('Post bài',
        array(
            'class' => 'btn btn-primary',
            'label' => false
        ));
    echo '</div>';
    ?>
    </div>
</div>
<?php

echo $this->Form->end();
?>
<hr/>
<h4>This article's links: </h4>
<ul>
<?php
if ($this->request->action == 'admin_edit') {
	if (!empty($this->request->data['Website']['url'])) {
		echo '<li><strong>Link desktop</strong>: http://' . $this->request->data['Website']['url'] . '/news/'
			. $this->request->data['Category']['slug'] . '/'
			. $this->request->data['Article']['slug'] . '</li>';
	}
	if (!empty($this->request->data['Website']['url2'])) {
		echo '<li><strong>Link mobile</strong>: http://' . $this->request->data['Website']['url2'] . '/news/'
			. $this->request->data['Category']['slug'] . '/'
			. $this->request->data['Article']['slug'] . '</li>';
	}
}
?>
</ul>
<small>* Can not to view direct these links, unless it was pushlished </small>
<?php echo $this->Html->script('/js/ckeditor/ckeditor.js');?>
<?php echo $this->Html->script('/js/ckeditor/config.js?ver=6');?>
<script type="text/javascript">
    $(document).ready(function() {
        var format = '<?php if (isset($this->data['Article']['markup'])) echo $this->data['Article']['markup'];?>';
        $('#markdown').hide();
        $('#wmd-input-second').attr('required', false);
        if (format == 'markdown') {
            $('#html').hide();
            $('#markdown').show();
            $('#wmd-input-second').attr('required', true);
        }
        $('#editor').change(function() {
            var value = $(this).find(":selected").val();
            if (value == 'markdown') {
                $('#html').hide();
                $('#markdown').show();
                $('#wmd-input-second').attr('required', true);
            } else if (value == 'html') {
                $('#html').show();
                $('#markdown').hide();
                $('#wmd-input-second').attr('required', false);
            }
        })
    });
    CKEDITOR.config.filebrowserUploadUrl = "<?php echo Router::Url(array('controller'=>'Images','action'=>'art_ck_upload')); ?>";
    var config = [
        ['Source','-','Save','NewPage','Preview','Print','-','Templates'],
        ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'],
        ['Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt'],
        ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
        '/',
        ['Bold','Italic','Underline','Strike','-','Subscript','Superscript','-','RemoveFormat'],
        ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr', 'BidiRtl'],
        ['Link','Unlink','Anchor'],
        ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe'],
        '/',
        ['Styles','Format','Font','FontSize'],
        ['TextColor','BGColor'],
        ['Maximize', 'ShowBlocks'],
        ['About'],
    ];
    CKEDITOR.replace('ckeditor', {toolbar:config, width: "1000px"});
	CKEDITOR.config.forcePasteAsPlainText = true;
	CKEDITOR.on( 'dialogDefinition', function( ev )
    {
        var dialogName = ev.data.name;
        var dialogDefinition = ev.data.definition;
        if (dialogName == 'image') {
            dialogDefinition.removeContents('advanced');
            dialogDefinition.onShow = function() {
                this.selectPage('Upload');
            };
            var infoTab = dialogDefinition.getContents('info');
            infoTab.remove('txtBorder');
            infoTab.remove('cmbAlign');
            infoTab.remove('txtWidth');
            infoTab.remove('txtHeight');
            infoTab.remove('txtVSpace');
            infoTab.remove('txtHSpace');
            infoTab.remove('txtHSpace');
            infoTab.remove('ratioLock');
            infoTab.remove('htmlPreview');
        }
    });
    //preview markdown
	$(function() {
		if ($("#wmd-preview-second").height() > 300) {
			$("#wmd-input-second").height($("#wmd-preview-second").height() - 30);
		}
	});
    $(function() {
        $(".send-all").show();
        $(".notchosen").chosen(
            {
                no_results_text: "Không tìm thấy kết quả. Ấn enter để thêm tag này như một tag mới!"
            }
        );
        $('.config-box').hide();
        // Set DateAPicker Js defaults
        $(".show-config-box").click(function() {
            var boxconfig = $(this).next();
            if (boxconfig.is(':visible')) {
                boxconfig.hide();
            } else {
                boxconfig.show();
            }
            return false;
        });
        $('.datepicker').each(function(i, e) {
            var bindElement = $(e).data('bind');
            var now_date = new Date();
            now_date.setDate(now_date.getDate()-1);
            var $input = $(e).pickadate({
                format: 'yyyy-mm-dd',
                formatSubmit: 'yyyy-mm-dd',
                min: now_date
//                max: now_date.getDate() + 7
            });
            var picker = $input.pickadate('picker');
        });
        $('.timepicker').each(function(i, e) {
            var bindElement = $(e).data('bind');
            var $input = $(e).pickatime({
                format: 'HH:i',
                formatSubmit: 'HH:i'
            });
            var picker = $input.pickatime('picker');
        });
    });
</script>
