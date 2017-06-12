<?php $this->extend('/Common/blank');

?>
<style type="text/css">
	.options-giftcode {
		display: none;
	}
	.input  {
		margin-bottom: 10px;
	}
	label {
		font-weight: bold;
		color: #444;
	}
	.fade {
		opacity: 0.5;
	}
	#addition-options .checkbox label{
		font-weight: normal;
	}
	#result {
		overflow-y: scroll;
		height: 250px;
		border: 1px solid silver;
		border-radius: 5px;
		padding-left: 10px;
		width: 275px;
	}
</style>


<div class="row emailMarketings">
<div class="span12">
<h4>Admin Edit Email Marketing</h4>

<em>*** Chú ý khi gửi email quảng cáo cho game mới ra, nếu sử dụng tập users của game nào thì phải lựa chọn game đó, chứ ko phải là game mới ra. <br/>
    Ví dụ, dùng tập user của Vua Hải Tặc (VHT) để quảng cáo game mới ra là Quỷ Kiếm thì phải select game VHT, như vậy domain gửi đi sẽ là @haitacmobi.com ,
    domain này thân thiện với tập user của VHT, và tỉ lệ vào spambox sẽ giảm. ***
</em>

<?php
	echo $this->Form->create('EmailMarketing', array('type' => 'file', 'id' => 'form'));
	if (!empty($this->request->data['EmailMarketing']['data']['giftcodes'])) {
		$countGiftcodes = substr_count($this->request->data['EmailMarketing']['data']['giftcodes'], "\n") + 1;
	}
	if (!empty($this->request->data['EmailMarketing']['data']['addresses'])) {
		$countAddresses = substr_count($this->request->data['EmailMarketing']['data']['addresses'], "\n") + 1;
	}
?>
</div>
</div>

<div class='row'>
<div class='span5'>
	<h3 class='page-header'>Basic Field</h3>
<?php
	echo $this->Form->input('title');
	
	echo $this->Form->input('type', array(
		'empty' => true,
		'id' => 'choose',
		'options' => array(
			EmailMarketing::TYPE_ALL => 'Advertising',
			EmailMarketing::TYPE_GIFTCODE => 'Giftcode'
		)
	));
	echo $this->Form->input('game_id', array(
		'empty' => true,
		'options' => $distinctGames,
		'label' => 'Game',
		'after' => '<br/><small><em>Choose game that you want to send email to ads for this game</em></small><br/><br/>'
	));
?>

	<div class="options-send-all">
		<h3 class='page-header'>Config list emails</h3>
        <?php
//		echo $this->Form->input('EmailMarketing.data.countries', array(
//			'type' => 'select',
//			'label' => 'Countries <span class="options-giftcode">(optional)</span>',
//			'multiple' => 'multiple',
//			'options' => $this->Nav->countriesAndCountries(),
//			'after' => '<br/><small><em>System will get list emails from countries</em></small>'
//		));
		?>

		<?php
		echo $this->Form->input('EmailMarketing.data.game_id', array(
			'type' => 'select',
			'label' => 'Games <span class="options-giftcode">(optional)</span>',
			'multiple' => 'multiple',
			'options' => $games,
			'after' => '<br/><small><em>System will get list emails from users played these games</em></small>'
		));
		?>
		<div id='addresses-textarea'>
		<?php
		if (empty($countAddresses) || $countAddresses < 30000) {
			echo $this->Form->input('EmailMarketing.data.addresses', array(
				'type' => 'textarea',
				'label' => 'Emails (optional)',
				'placeholder' => 'If you choose get list emails from above input "Get users from these games" then not need to fill this list',
				'after' => '<br/><small><em>If you choose get list emails from above input "Get users from these games" then not need to fill this list </em></small><br/><br/>',
			));
		} else {
			echo '<div class="alert alert-warning">Can not show the list emails because too much emails</div>';
		}
		?>
		</div>
	</div>
</div><!-- /span5 -->

<div class='span4'>
	<div class="options-giftcode">
		<h3 class='page-header'>Giftcodes </h3>
		<?php

		if (empty($countAddresses) || $countGiftcodes < 30000) {
			echo $this->Form->input('EmailMarketing.data.giftcodes', array(
				'type' => 'textarea',
				'after' => '<br/><em><small>OR upload file</small></em>'
			));
		} else {
			echo '<div class="alert alert-warning">Can not show the list giftcodes because too much giftcodes</div>';
		}			
		echo $this->Form->input('EmailMarketing.giftcodefile', array(
			'type' => 'file',
			'after' => '<br/><em><small>a giftcode per line - note: this file replace above text.</small></em>'
		))
		?>
	</div>
</div><!-- /span4 -->

<div class='span3'>
	<div class='options-info'>
		<h3 class='page-header'>Info<small><small><em> about your submit</em></small></small></h3>
		<div>
		<a href="<?php echo $this->Html->url(array('controller' => 'emailMarketings', 'action' => 'getCountListEmails')) ?>">
			<button class="btn" onclick="" id="checkEmailTotal">Check email total in databases</button>
		</a>
			<div><em><small>Warning: this email total after your check is at current time, it will be more than now, because users will still register and login in next time. </em></small>
			</div>
		</div>
		<div class='well' id='total-email-checking'>Click button to check</div>
		<?php
		if (!empty($this->request->data['EmailMarketing']['data']['giftcodes'])) {
			$count = substr_count($this->request->data['EmailMarketing']['data']['giftcodes'], "\n") + 1;
			echo "<div class='well'>" . $count . " giftcodes</div>";
		}
		?>
		<?php
		if (!empty($this->request->data['EmailMarketing']['data']['addresses'])) {
			$count = substr_count($this->request->data['EmailMarketing']['data']['addresses'], "\n") + 1;
			echo "<div class='well'>" . $count . " addresses</div>";
		}
		?>
	</div>
</div><!-- /span3 -->
</div><!-- /row -->


<div clas='row'>
	<div class='span12'>
		<?php
			echo '<div class="form-actions">';
			echo $this->Form->submit('Submit', array('class' => 'btn btn-primary'));
			echo '</div>';
			echo $this->Form->end();
		?>

		<div class="actions">
			<h3><?php echo 'Actions'; ?></h3>
			<ul>
				<li><?php echo $this->Html->link('List Email Marketings', array('action' => 'index')); ?></li>
			</ul>
		</div>

	</div><!-- /span12 -->
</div><!-- /row -->


<script type="text/javascript" >

	$(function() {
		$('#EmailMarketingDataGameIdDuplicate').chosen();
		if ($('#duplicate_email').is(':checked')) {
			$('#div_dup').show();
		} else {
			$('#div_dup').hide();
		}
		$('#duplicate_email').click(function () {
			var value = 0;
			var is_checked = $(this).is(':checked');
			if (is_checked) {
				value = $(this).val();
			}
			if (value == 1) {
				$('#div_dup').show();
			} else {
				$('#div_dup').hide();
			}
		});
		function selectGameId1()
		{
			if ($("#EmailMarketingDataGameIdDuplicate").val() != null) {
				$("#EmailMarketingDataAddressesDuplicate").attr('disabled', 'disabled');
				$("#EmailMarketingDataAddressesDuplicate").addClass('fade');
			} else {
				$("#EmailMarketingDataAddressesDuplicate").removeAttr('disabled');
				$("#EmailMarketingDataAddressesDuplicate").removeClass('fade');
			}
			if ($("#EmailMarketingDataAddressesDuplicate").val() != '') {
				$('#EmailMarketingDataGameIdDuplicate').prop('disabled', true).trigger("liszt:updated");
				$("#EmailMarketingDataGameIdDuplicate").addClass('fade');
			} else {
				$('#EmailMarketingDataGameIdDuplicate').prop('disabled', false).trigger("liszt:updated");
				$("#EmailMarketingDataGameIdDuplicate").removeClass('fade');
			}
		}
		selectGameId1();
		$('#EmailMarketingDataGameIdDuplicate').change(function() {
			selectGameId1();
		});
		$('#EmailMarketingDataAddressesDuplicate').keyup(function() {
			selectGameId1();
		});
		$('#form').submit(function(e){
			if ($('#duplicate_email').is(':checked')) {
				if ($('#EmailMarketingDataGameIdDuplicate').val() == null && $('#EmailMarketingDataAddressesDuplicate').val() == '') {
					e.preventDefault();
					alert('You can not empty Games Duplicate or Emails Duplicate if you choose Duplicate Email !');
				}
			}
		});
		showAdditionsOptions();
		$("#choose").change(function() {
			showAdditionsOptions();			
		});
		function showAdditionsOptions() {
			switch (parseInt($("#choose").val())) {
				case 0:
					$(".options-send-all").show();
					$(".options-giftcode").hide();
					$(".notchosen").chosen();
					break;
				case 1:
					$(".options-giftcode").show();
					$(".notchosen").chosen();
					break;
			}
		}

		// disable email textare when get users by game id
		function selectGameId()
		{
			console.log( $("#EmailMarketingDataCountries").val());
			if ($("#EmailMarketingDataGameId").val() != null || $("#EmailMarketingDataCountries").val() != null) {
				$("#EmailMarketingDataAddresses").attr('disabled', 'disabled');
				$("#addition-options").removeClass('fade');
				$("#addresses-textarea").addClass('fade');
				$("#checkEmailTotal").removeAttr("disabled");
			} else {
				$("#addition-options").addClass('fade');
				$("#addresses-textarea").removeClass('fade');
				$("#checkEmailTotal").attr("disabled", "disabled");
				
			}
		}

		//show and hide date ranger picker
		function selectAdditionOptions()
		{
			if ($("#EmailMarketingDataField").val() == '') {
				$(".date-ranger-picker").hide();
			} else {
				$(".date-ranger-picker").show();
			}
		}		
		selectAdditionOptions();
		$("#EmailMarketingDataField").change(function() {
			selectAdditionOptions();
		});
		selectGameId();
		$("#EmailMarketingDataGameId, #EmailMarketingDataCountries").change(function() {
			selectGameId();
		});


		$("#checkEmailTotal").click(function() {
			console.log($('form').serializeArray());
			if ($('#EmailMarketingDataGameId').val() == null && $('#EmailMarketingDataCountries').val() == null) {
				alert("You don't choose game in setting: 'Get users from these games' yet ");
				$("#total-email-checking").text("0 emails can be sent");
			} else {
				$.post(BASE_URL + "/emailMarketings/getCountListEmails.json", $('form').serialize(), function(result) {
					if (result.code == 1) {
						$("#total-email-checking").text(result.data + " emails can be sent");
					} else {
						alert('Error happen, please try again or report for developer');
					}
				})
			}
			return false;
		})


		// Set DateAPicker Js defaults
		$('.date-ranger-picker .datepicker').each(function(i, e) {
			var bindElement = $(e).data('bind');
			var $input = $(e).pickadate({
				onStart: function() {
					if ($("#" + bindElement).val() != '') {
						this.set('select', $("#" + bindElement).val() * 1000);
					}
				},
				onSet: function(thingSet) {
					if (thingSet.select !== undefined) {
						$("#" + bindElement).val(thingSet.select / 1000);
					} else {
						if (!thingSet.max) {
							$("#" + bindElement).val("");
						}
					}
				}
			});	
			var picker = $input.pickadate('picker');
			picker.set('max', true);
		});

		$("#EmailMarketingDataGameId").chosen();
		$("#EmailMarketingDataCountries").chosen();
		$('#result').hide();
		$('#show_email').click(function(){
			var url = '<?php echo Router::Url(array('controller'=>'EmailMarketings','action'=>'show_email'));?>';
			var segment = $('#segment').val();
			var mail_id = $('#email_id').val();
			if (segment == '') {
				alert('Bạn chưa chọn Segment');
				return false;
			}
			if (mail_id == '') {
				alert('Bạn chưa nhập Email marketing id');
				return false;
			}
			if (segment != '' && mail_id != '') {
				$.ajax({
					type: 'POST',
					url: url,
					dataType: 'json',
					data: {segment: segment, mail_id: mail_id},
					success: function(result) {
						var html = '';
						if (result == 1) {
							alert('Không có email nào trong segment này hoặc id của email bạn nhập chưa đúng !');
						} else {
							$.each(result, function (key, value) {
								if (value != null && value != '') {
									html += '<p>' + value + '</p>';
								}
							});
							$('#result').html(html);
							$('#result').show();
						}
					}
				});
			}
		});
	});
</script>