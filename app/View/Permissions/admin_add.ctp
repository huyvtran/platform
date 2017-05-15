<style type='text/css'>
	form > div > label {
		font-weight: bold;
	}
    .col-xs-15
    .col-sm-15{
        position: relative;
        min-height: 1px;
        padding-right: 10px;
        padding-left: 10px;
    }
    @media (min-width: 1200px) {
        .col-sm-15 {
            width: 30%;
            float: left;
        }
    }
    .col-xs-15 {
        width: 25%;
        float: left;
        height: 25px;
        overflow: hidden;
    }
    .submit .btn{
        margin-top: 15px;
        clear: both;
        float: left;
    }
    h3{
        clear: both;
    }
    .all-game input[type="checkbox"]{
        margin-right: 5px;
    }
</style>
<?php
$this->extend('/Common/default');
?>
<div class="result"></div>
<h2 class='page-header'>Permissions/Admin Add</h2>
<?php
$user_id = $user['User']['id'];
echo '<em> You are adding permissions for, user: <strong>' . $user['User']['username'] . '</strong></em>';
?>
<a class='btn' href="<?php echo Router::url(array('controller' => 'Permissions', 'action' => 'admin_simpleadd', '?' => array('user_id' => $user['User']['id'])))?>">Add Simple Permission</a>
<?php
$all = Permission::ALL;
echo '<div class="game index">';
if (!empty($foreign_keys)) {
    foreach ($foreign_keys as $id => $val) {
        echo '<h3><span>'. ucwords($id) .'</span></h3>';
        if (isset($foreign_key_checked)) {
            $checked = false;
            foreach ($foreign_key_checked as $keyT => $valT) {
                if (isset($valT) && !empty($valT)) {
                    foreach ($valT as $k => $v) {
                        if ($k == $all && $v == "game-$id") {
                            $checked = 'checked';
                        }
                    }
                }
            }
        }
        echo "<label class='all-game'>" . $this->Form->checkbox('Permission.foreign_key', array('name' => $id, 'game_id' => $all, 'id' => $user_id, 'type' => 'checkbox', 'class' => 'box', 'checked' => $checked)) . "All Games</label>";
        foreach ($val as $key => $valTmp) {
            $checked = false;
            if(isset($foreign_key_checked)) {
                foreach ($foreign_key_checked as $keyT => $valT) {
                    if (isset($valT[$key]) && $id == $keyT && $valT[$key] == "game-$id") {
                        $checked = 'checked';
                    }
                }
            }
            echo '<div class="col-xs-15 col-sm-3">';
            echo "<label>" . $this->Form->checkbox('Permission.foreign_key', array('name' => $id, 'game_id' => $key, 'id' => $user_id, 'type' => 'checkbox', 'checked' => $checked, 'class' => 'box')) . " $valTmp</label>";
            echo '</div>';
        }
    }
}
if (!empty($distributor)) {
    echo '<h3><span>Distributor</span></h3>';
    $status = false;
    if (!empty($distributor_checked_all) && in_array(Permission::ALL, $distributor_checked_all)) $status = 'checked';
    echo "<label class='all-game'>" . $this->Form->checkbox('Permission.foreign_key', array('name' => Permission::Distributor, 'game_id' => $all, 'id' => $user_id, 'type' => 'checkbox', 'class' => 'box', 'checked' => $status)) . "All Distributor</label>";
    foreach ($distributor as $key => $value) {
        $check = false;
        if (isset($distributor_checked)) {
            foreach ($distributor_checked as $v) {
                if ($key == $v) {
                    $check = 'checked';
                }
            }
        }
        echo '<div class="col-xs-15 col-sm-3">';
        echo "<label>" . $this->Form->checkbox('Permission.foreign_key', array('name' => Permission::Distributor, 'game_id' => $key, 'id' => $user_id, 'type' => 'checkbox','checked' => $check, 'class' => 'box'))." ". ucfirst($value) ."</label>";
        echo '</div>';
    }
}
echo '</div>';
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.box').click(function () {
            var game_id = $(this).attr('game_id');
            var per = $(this).attr('name');
            var user_id = $(this).attr('id');
            var url = '<?php echo Router::Url(array('controller' => 'Permissions', 'action' => 'add')); ?>';
            var value = 0;
            var is_checked = $(this).is(':checked');
            if (is_checked) {
                value = $(this).val();
            }
            var arr = {
                per: per,
                value: value,
                game_id: game_id,
                user_id: user_id
            };
            $.ajax({
                type: 'POST',
                url: url,
                dataType: 'json',
                data: {arr: arr},
                success: function (result) {
                    var html = '';
                    if (result.code == 1 || result.code == 0) {
                        html += '<div class="alert alert-success">';
                        html += '<a class="close" data-dismiss="alert">x</a>';
                        html += '<b>';
                        html += result.message;
                        html += '</b>';
                        html += '</div>';
                    } else if (result.code == 2 || result.code == 3 || result.code == 7) {
                        html += '<div class="alert alert-error">';
                        html += '<a class="close" data-dismiss="alert">x</a>';
                        html += '<b>';
                        html += result.message;
                        html += '</b>';
                        html += '</div>';
                    }
                    $('.result').html(html);
                }
            });
        })
    })
</script>
