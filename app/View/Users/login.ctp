<?php
$this->extend('/Common/default');
?>
<div class="row">
	<div class = 'span6 offset2'>
        <?php echo $this->Session->flash('flash');?>
        <?php echo $this->Session->flash('auth', array('element' => 'info'));?>
		<h3 class = 'page-header'>Sign in to Your Account</h3>
    <?php
    echo $this->Form->create('User', array(
        'url' => '/users/login',
        'inputDefaults' => array(
            'div' => array('class' => 'control-group'),
            'error' => array('attributes' => array(
                    'wrap' => 'span',
                    'class' => 'help-inline'
                ))
        )
    ));
    echo $this->Form->input('email', array(
        'placeholder' => 'Email',
        'required' => 'required',
        'label' => false,
        'type' => 'text',
        'before' => '<label>Username/Email</label><div class = "input-prepend"><span class="add-on"><i class="icon-envelope"></i></span>',
        'after' => '</div>'
    ));
    echo $this->Form->input('password', array(
        'placeholder' => 'Password',
        'required' => 'required',
        'label' => false,
        'before' => '<label>Password</label><div class = "input-prepend"><span class="add-on"><i class="icon-lock"></i></span>',
        'after' => '</div>'
    ));
    echo $this->Form->hidden('remember_me', array(
        'type' => 'checkbox',
        'value'=>1,
        'id' => 'LoginRemember'
    ));
    echo $this->Form->submit('Login', array(
        'class' => 'btn',
        'label' => false
    ));
    echo $this->Form->end();
    ?>
    <hr/>
   </div>
</div>
