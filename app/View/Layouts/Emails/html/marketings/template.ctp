<!DOCTYPE HTML>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $title; ?></title>
<?php
# this code for admin
if ($this->Session->read('Auth.User')) {
	echo $this->Html->script('createjs/lib/jquery/jquery.js');
	echo $this->Html->script('createjs/lib/jquery-ui/jquery-ui.js');
	echo $this->Html->script('createjs/lib/underscore/underscore.js');
	echo $this->Html->script('createjs/lib/backbone/backbone.js');
	echo $this->Html->script('createjs/lib/vie/vie.js');
	echo $this->Html->script('ckeditor/ckeditor.js');
	echo $this->Html->script('createjs/dist/create');
	echo $this->Html->css('/js/createjs/themes/create-ui/css/create-ui.css');
	echo $this->Html->css('/js/createjs/themes/midgard-notifications/midgardnotif.css');
}
?>

</head>
<body>
<?php
# this code for admin
if ($this->Session->read('Auth.User')) {
?>
<div about="Email" aria-disabled="true" style='width:700px'>

	<h3> SEND ALL USERS </h3>

	<span style='text-decoration: underline'>Email Title:</span>
	<h4 property="schema:headline"><?php echo $title; ?></h4>
	<hr/>
	<span style='text-decoration: underline'>Email Body:</span>
	<div property="schema:text">
	<?php
	}
	?>

	<?php echo $content_for_layout; ?>

	<?php
	# this code for admin
	if ($this->Session->read('Auth.User')) {
	?>
	</div>
	<hr/>
</div>
<div class="actions">
	<h3><?php echo 'Actions'; ?></h3>
	<ul>

		<li><?php echo $this->Form->postLink('Delete', array('action' => 'delete', $this->Form->value('EmailMarketing.id'), null, 'Are you sure you want to delete # %s?', $this->Form->value('EmailMarketing.id'))); ?></li>
		<li><?php echo $this->Html->link('List Email Marketings', array('action' => 'index')); ?></li>
	</ul>
	<h3>Hint:</h3>
	special characters sử dụng trong email (chú ý kí tự đặc biệt không được viết hoa): 
	<pre>
	<strong style='color:red'>Xin hãy copy đoạn bên dưới "****"</strong>
	@giftcode: chỉ sử dụng cho email dạng giftcode
	@friendlyName: sẽ sử dụng name của user, nếu user không có name sẽ sử dụng username hoặc email
	@unsubscribe[tieudeUnscribe]: Copy nội dung 1 trong các ngôn ngữ:
		****
		Thanks for reading, If you don't want to continue, please @unsubscribe[click here]
		Cảm ơn bạn đã đọc e-mail, nếu bạn không muốn tiếp tục nhận vui lòng click  @unsubscribe[vào đây]
		感谢您的关注，若您想日后拒绝收邮件请 @unsubscribe[点击此处]!
		ขอบคุณมากที่อ่านอีเมล์นี้ ถ้าคุณไม่อยากรับอีเมล์ดังนี้อีกกรุณากดที่ @unsubscribe[ตรงนี้]
	<pre>
</div>
<script>
	jQuery(document).ready(function () {
		
		CKEDITOR.config.filebrowserUploadUrl = "<?php echo $this->Html->url('/images/upload.json') ?>";
		CKEDITOR.config.customConfig = "<?php echo $this->Html->url('/js/ckeditor/config.js?ver=2') ?>";

		jQuery('body').prepend('<div style="height: 100px;width:100%"></div');

		jQuery('body').midgardCreate({
			url: function() {
				return "<?php echo $this->Html->url('/admin/emailMarketings/edit/' . $email['EmailMarketing']['id'] . '?template=' . $layout) ?>";
			},
			editor: 'ckeditor'
		});

		// Set a simpler editor for title fields
		jQuery('body').midgardCreate('configureEditor', 'ckeditor', 'ckeditorWidget', {
		});
		jQuery('body').midgardCreate('setEditorForProperty', 'default', 'ckeditor');

		// Disable editing of author fields
		// jQuery('body').midgardCreate('setEditorForProperty', 'dcterms:author', null);

	});
	// Backbone.sync = function(method, model, options) {
	// if (console && console.log) {
	//   console.log('Model contents', model.toJSONLD());
	// }
	// options.success(model);
	// };
	$( document ).ready(function() {
	    $('span[data-sheets-userformat]').contents().unwrap();
	});
</script>
<?php
}
?>
</body>
</html>