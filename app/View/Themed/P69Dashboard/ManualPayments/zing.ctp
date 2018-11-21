<body>
<div class="toolbar">
    <div class="toolbar-left">
        <a href="<?php echo $currentGame['data']['payment']['url_sdk']; ?>"><i class="fa fa-home fa-lg" aria-hidden="true"></i></a>
    </div>
    <div class="toolbar-brand">
        <?php echo 'GATE'; ?>
    </div>
    <div class="toolbar-right">
        <a href="#" onclick="document.location = 'js-oc:kunlunClose:null';return false">
            <i class="fa fa-times fa-lg" aria-hidden="true"></i>
        </a>
    </div>
</div>
<div class="container">
	<center> <span style="color: green"> <?php echo $str_bonus; ?> </span></center>
	<center> <span style="color: red"><?= $this->Session->flash('error'); ?> </span></center>
	<br/>
	<?php
	echo $this->Form->create(false, array(
		'name' => 'frmInvite',
		'id' => 'frmInvite',
		'class' => 'form-horizontal',
		'inputDefaults' => array(
			'class' => 'form-control input-sm',
			'div' => 'form-group',
			'between' => '<div class="col-md-10">',
			'after' => '<p class="help-block"></p></div>',
		)
	));

	echo $this->Form->input('card_serial', array(
		'class' => 'form-control input-sm',
		'type' => 'text',
		'label' => array(
			'text' => 'Card serial',
			'class' => 'col-md-2 control-label'
		)
	));

	echo $this->Form->input('card_code', array(
		'class' => 'form-control input-sm',
		'type' => 'text',
		'label' => array(
			'text' => 'Card code',
			'class' => 'col-md-2 control-label'
		)
	));
	?>
	<div class="form-group">
		<div class="col-md-offset-2 col-md-9">
			<button type="submit" class="btn btn-primary">Submit</button>
		</div>
	</div>
	<div class="form-group">
		<div class="col-md-offset-2 col-md-9">
			<button type="submit" class="btn btn-primary">Submit</button>
			<a href="https://www.seagm.com/fpt-gate-card-vn" class="btn btn-primary">Buy Gate</a>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
</div>
</body>