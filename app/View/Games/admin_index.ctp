<?php
$this->extend('/Common/blank');
?>
<style type="text/css">
	.addition-game-info, .alert-error-pupupover, .alert-error-popupover {cursor: pointer;}
	.games-index input[type=text]{margin-bottom: 0px;}
    .games{float: left;}
    td .missing-setups {margin-bottom: 0px;padding:5px;width: 70px;}
    td .complete-setups {margin-bottom: 0px; padding-left: 30px; padding-bottom: 13px; padding-top: 0px; width: 70px;}
    .games-close{text-decoration: line-through;}
    td .complete-setups .icon-ok {background-position: -288px 7px;height: 20px;}
    .hide-row {display:none;}
</style>
<script type="text/javascript">
	$(document).ready(function() {
	if (typeof(Storage) !== "undefined") {
		var status = '';
		if(localStorage.getItem("showorhide") == null){
			localStorage.setItem("showorhide", "hide");
			status = 'hide';
		}
		else
		{
			status = localStorage.getItem("showorhide");
		}
	} else {
	    alert("Sorry, your browser does not support Web Storage...");
	}
    if(status == 'hide')
    	$(".hide-row").hide();
	});
</script>
<div class="games index" id='games'>
	<h3>Games - Index</h3>
	<div class='rows'>
		<div class='span4'>
		<?php
			echo $this->Form->create('Game', array('class' => 'form-inline'));

			echo $this->Form->input('title', array(
				'empty' => '-- All Games --',
				'value' => empty($this->request->params['named']['title']) ? '': $this->request->params['named']['title'],
				'label' => false,
				'placeholder' => 'Search game title',
				'div' => false
			));
			echo " ";
			echo $this->Form->submit('Search', array('class' => 'btn btn-small',
	    		'div' => false));
			echo $this->Form->end();
		?>
		</div>

		<div class='span7'>
			<button class='btn' id='show-all-game'>Show-All</button>
			<?php 
				echo $this->Html->link("<button class='btn'>Game-Permissions</button>", array('controller' => 'games', 'admin' => true, 'action' => 'permission'), array('escape' => false));
				echo '&nbsp;';
				echo $this->Html->link("<button class='btn'>New Game</button>", array('action' => 'add'), array('escape' => false));
			?>
			<div class="paging">
				<?php
				echo $this->Paginator->prev('< previous', array(), null, array('class' => 'prev disabled'));
				echo $this->Paginator->numbers(array('separator' => ''));
				echo $this->Paginator->next('next >', array(), null, array('class' => 'next disabled'));
				?>
			</div>
		</div>
	</div>
	<table class="table table-striped games-index">
		<tr>
			<th>ID</th>
			<th><?php echo $this->Paginator->sort('title'); ?></th>
			<th><?php echo $this->Paginator->sort('app'); ?></th>
			<th><?php echo $this->Paginator->sort('os'); ?></th>
			<th><?php echo $this->Paginator->sort('slug'); ?></th>
			<th><?php echo $this->Paginator->sort('alias'); ?></th>
            <th><?php echo 'language'; ?></th>
			<th><?php echo 'Missing' ?></th>
            <th><?php echo 'Last update' ?></th>
			<th class="actions">Actions</th>
		</tr>
		<tbody class="list">
		<?php foreach ($games as $game): ?>
            <?php
	            $gamesClose = "";
	            $class_hide = "";
	            if(!empty($game['Game']['data']['is_close']) && $game['Game']['data']['is_close'] == 1) {
	                $gamesClose = "games-close";
	            	$class_hide = "hide-row";
	            }
	        ?>
			<tr class="<?php echo $class_hide; ?>">
				<td><?php echo $game['Game']['id'] ?></td>
                <td>
					<?php
					$gameInfo = '<div>';
					$gameInfo .= '<ul>';
					$gameInfo .= '<li><strong>AppKey: </strong>' . $game['Game']['app'] . '</li>';
					$gameInfo .= '<li><strong>SecretKey:  </strong>' . $game['Game']['secret_key'] . '</li>';
					$gameInfo .= '</ul>';
					$gameInfo .= '</div>';
					?>
					<span class='addition-game-info <?php print $gamesClose; ?>' data-toggle='popover' data-placement='left' data-content='<?php echo $gameInfo ?>'>
					<?php
					echo h($game['Game']['title']);
					?>
					</span>
				</td>
				<td>
					<input type='text' value="<?php echo h($game['Game']['app']) ?>" style="width:50px"/>
				</td>
				<td>
					<?php
					switch ($game['Game']['os']) {
						case 'pc':
							$class = 'label label-default';
							break;
						case 'ios':
							$class = 'label label-warning';
							break;
						case 'android':
							$class = 'label label-success';
							break;
						case 'wp':
							$class = 'label label-info';
							break;
						default:
							$class = '';
					}
					echo "<span class='$class'>";
					if ($game['Game']['os'] == 'android') {
						echo 'adr';
					} else {
						echo $game['Game']['os'];
					}

					echo "</span>";
				?>
				</td>
				<td><?php echo h($game['Game']['slug']); ?></td>
				<td><?php echo h($game['Game']['alias']); ?></td>
                <td><?php echo h($game['Game']['language_default']); ?></td>
				<td><?php

				if (!empty($game['Game']['errors'])) {
                        $count_error_tech = 0;
                        $count_error_content = 0;

                        $error_show = "";
                        if(!empty($game['Game']['errors']['tech']) || !empty($game['Game']['errors']['sdk'])){
                            $error_show = $error_show . "<h4>Admin and Developer</h4><ul>";

                            if(!empty($game['Game']['errors']['tech']))
                                foreach ($game['Game']['errors']['tech'] as $error) {
                                    $error_show = $error_show . "<li>" . $error . "</li><br/>";
                                    $count_error_tech++;
                                }

                            if(!empty($game['Game']['errors']['sdk']))
                                foreach ($game['Game']['errors']['sdk'] as $error) {
                                    $error_show = $error_show . "<li>" . $error . "</li><br/>";
                                    $count_error_tech++;
                                }

                            $error_show = $error_show . "</ul><hr/>";
                        }

                        if(!empty($game['Game']['errors']['content'])){
                            $error_show = $error_show . "<h4>Content</h4><ul>";
                            foreach ($game['Game']['errors']['content'] as $error){
                            $error_show = $error_show . "<li>".$error."</li><br/>";
                            $count_error_content++;
                        }
                        $error_show = $error_show . "</ul>";
                        }
                    if($count_error_content < 10) $count_error_content = '0' . $count_error_content;
                    $classError = 'alert-error';
                    if ($count_error_content + $count_error_tech < 8) {
                    	$classError = 'alert-warning';
                    }
                    $count_error = '<i class="icon-warning-sign"></i> <strong>'. $count_error_tech .
                                   '</strong> - <strong>' . $count_error_content . '</strong>';
                    if($this->Session->read('Auth.User.role') == 'Content')
                        $count_error = '<i class="icon-warning-sign"></i>' . $count_error_content;
                        
					echo "<div class='alert $classError alert-error-pupupover missing-setups' data-toggle='popover'
                            data-placement='left' data-content='" . $error_show . "'>" . $count_error
                        .'</div>';
				}else{
                    echo "<strong class='alert alert-success complete-setups'><i class='icon-ok'></i></strong>";
                }
				?></td>
                <td><?php if(isset($game['Game']['last_username'])) echo $game['Game']['last_username']; ?></td>
				<td class="actions btn-group">
					<?php
					$role = $this->Session->read('Auth.User.role');
					if (in_array($role, array('Admin', 'Developer'))) {
						echo $this->Html->link('Edit', array('action' => 'edit', $game['Game']['id']), array('class' => 'btn btn-mini'));
						echo $this->Html->link('Edit Content', array('action' => 'editDescription', $game['Game']['id']), array('class' => 'btn btn-mini'));
					}
					?>
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<div class="paging">
		<?php
		echo $this->Paginator->prev('< previous', array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next('next >', array(), null, array('class' => 'next disabled'));
		?>
	</div>
</div>
<!-- <div class="actions">
	<h3>Actions</h3>
	<ul>
		<li><?php echo $this->Html->link('New Game', array('action' => 'add')); ?></li>
	</ul>
</div> -->

<script type="text/javascript">
$(document).ready(function(event){
	$(".addition-game-info").popover({html: true})
    $(".alert-error-pupupover").popover({
        html: true,
        placement: 'left',
        title: '<span class="text-info"><strong>Information error</strong></span>'+
        '<button type="button" id="popover_close" class="close">x</button>'
    });
    $("#show-all-game").click(function(){
    	$(".hide-row").toggle();
    	status = localStorage.getItem("showorhide");
    	if(status == 'hide')
    		localStorage.setItem("showorhide", "show");
    	else
    		localStorage.setItem("showorhide", "hide");
	});
    $('body').on('click', function (e) {
        $('.alert-error-pupupover').each(function () {
            //the 'is' for buttons that trigger popups
            //the 'has' for icons within a button that triggers a popup
            if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                $(this).popover('hide');
            }
        });
    });
});

$(document).click(function(e) {
    if(e.target.id=="popover_close" )
    {
        $('.alert-error-pupupover').popover('hide');
    }
});

</script>