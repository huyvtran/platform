<?php
$this->extend('/Common/default');
?>

<div class="row">
	<div class = 'span6 offset2'>
	<h4><?php __("Thay đổi mật khẩu") ?></h4>
<?php
echo $this->Form->create('User', array(
		'url' => array('action' => 'reset_password_web'),
		'inputDefaults' => array('div' => false, 'label' => false, 'errorMessage' => false)
	));
?>
<div class="unit modal-form">
	<?php
    echo $this->Session->flash('flash');
    echo $this->Session->flash('auth', array('element' => 'info'));		
	?>
	<div class="control-group">
		<label for="email" class="control-label"><?php echo __("Địa chỉ email") ?></label>
		<?php
		echo $this->Form->input('email', array('placeholder' => 'Email', 'required' => 'required', 'class' => 'reset full', 'type' => 'email'));
		?>
	</div>
	<div class="list-item">
		<div class="captcha">
            <div class="tt-row r50">
                <?php echo $this->Form->input('captcha', array('placeholder' => 'Captcha', 'required' => 'required', 'class' => 'input-control', 'autocapitalize' => 'off')); ?>
            </div>
            <div class="captcha r50 cf">
                <?php echo $this->Html->image('/captcha/captcha/view/' . uniqid(), array('id' => 'captcha-image')); ?>
                <a class="captcha-reload left" id='captcha-reload'>OOO</a>
            </div>
		</div>
	</div>
</div>
<div class="unit modal-form">
	<?php echo $this->Form->button(__('Gửi'), array('class' => 'btn btn-primary')); ?>
	<a  href="<?php echo $this->Html->url(array('controller' => 'users', 'action' => 'login')) ?>" class="btn"><?php echo __("Hủy") ?></a>	
</div>
	</div>
</div>
<?php echo $this->Form->end(); ?>
<script type='text/javascript'>
    $(function(){
        $('#captcha-reload').click(function(){
            var date = new Date();
            $("#captcha-image").attr('src',
                '<?php echo $this->Html->url(array("plugin" => "captcha", "controller" => "captcha", "action" => "view"));?>/' + date.getTime());
        })
    })
</script>