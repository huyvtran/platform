<?php $this->extend('/Common/blank');

?>
<style type="text/css">
	.options-giftcode {
		display: none;
	}
	label {
		font-weight: bold;
		color: #444;
	}
	#addition-options .checkbox label{
		font-weight: normal;
	}
</style>


<div class="row emailMarketings">
<div class="span12">
<h4>Admin Edit Email Marketing</h4>

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

    echo $this->Form->input('game_id', array(
        'empty' => true,
        'options' => $distinctGames,
        'label' => 'Game',
        'after' => '<br/><small><em>Choose game that you want to send email to ads for this game</em></small><br/><br/>'
    ));

	echo $this->Form->input('type', array(
		'empty' => true,
		'id' => 'choose',
		'options' => array(
			EmailMarketing::TYPE_ALL => 'Advertising',
			EmailMarketing::TYPE_GIFTCODE => 'Giftcode'
		)
	));
?>
    <div class="options-giftcode"> <br/>
        <?php
        if (empty($countGiftcodes) || $countGiftcodes < 30000) {
            echo $this->Form->input('EmailMarketing.data.giftcodes', array(
                'type' => 'textarea',
                'after' => '<br/><em><small>OR upload file</small></em>'
            ));
        } else {
            echo '<div class="alert alert-warning">Can not show the list giftcodes because too much giftcodes</div>';
        }
//        echo $this->Form->input('EmailMarketing.giftcodefile', array(
//            'type' => 'file',
//            'after' => '<br/><em><small>a giftcode per line - note: this file replace above text.</small></em>'
//        ))
        ?>
    </div>
</div><!-- /span5 -->

<div class='span4'>
    <div class="options-send-all">
        <h4 class='page-header'>Config list emails</h4>
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
</div><!-- /span4 -->

<div class='span3'>
	<div class='options-info'>
		<h3 class='page-header'>Info<small><small><em> about your submit</em></small></small></h3>
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

//		// disable email textare when get users by game id
//		function selectGameId()
//		{
//			console.log( $("#EmailMarketingDataCountries").val());
//			if ($("#EmailMarketingDataGameId").val() != null || $("#EmailMarketingDataCountries").val() != null) {
//				$("#EmailMarketingDataAddresses").attr('disabled', 'disabled');
//				$("#addition-options").removeClass('fade');
//				$("#addresses-textarea").addClass('fade');
//				$("#checkEmailTotal").removeAttr("disabled");
//			} else {
//				$("#addition-options").addClass('fade');
//				$("#addresses-textarea").removeClass('fade');
//				$("#checkEmailTotal").attr("disabled", "disabled");
//
//			}
//		}

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


		$("#checkEmailTotal").click(function() {
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

		$('#result').hide();

	});
</script>