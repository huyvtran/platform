<style type="text/css">
    .center{
        text-align:center;
    }
    .center1{
        margin-left:40px
    }
    #icon1{
        margin-left:120px;
    }
    #icon2{
        margin-left:60px;
    }
</style>
<div class="result"></div>
<?php
    $this->extend('/Common/blank');
    $permissions = Permission::$permission;
    if ($game) {
        $game_title = $game[0]['Game']['title'];
        $alias      = $game[0]['Game']['alias'];
    }
    function checkPermission($value){
        $data = ($value == 1) ? 'checked' : '';
        return $data;
    }
?>
<div class="users index">
    <h2>Share Permission</h2>
    <hr/>
    <h3>Game : <?php echo  $game_title ?></h3>
    <div class='row'>
        <div class='span3'>
            <?php
                echo $this->Form->create('User', array(
                    'url' => array(
                        'controller' => 'permissions',
                        'action'     => 'game',
                        '?'          => array('alias' => $alias),
                    ),
                    'class' => 'simple', 'style' => 'width:200px;',
                ));
                echo $this->Form->input('username', array('required' => false));
                echo $this->Form->input('email', array('required' => false, 'type' => 'text'));
                echo $this->Form->submit('Search', array(
                    'class' => 'btn',
                    'name'  => 'submit',
                    'div'   => false,
                ));
                echo $this->Form->end();
            ?>
        </div>
    </div>

    <br/>
    <table class = "table">
        <tr>
            <th><small>ID</small></th>
            <th><small>Username</small></th>
            <th><small>Email</small></th>
            <th><small>Active</small></th>
            <?php foreach ($permissions as $key => $val) { ?>
            <th><small class="<?php echo ($key == 'default') ? 'center1' : 'center2'?>"><?php echo $val; ?></small></th>
            <?php } ?>
            <th><small>Role</small></th>
            <th><small>Delete</small></th>
        </tr>
<?php
    if (isset($users) && !empty($users)) {
        foreach ($users as $user):
?>
        <tr>
            <td><?php echo h($user['User']['id']); ?></td>
            <td>
                <a href="javascript:void(0)" class="a-popover" uid="<?php echo $user['User']['id']; ?>"><?php echo h($user['User']['username']); ?></a>
                <div id="popup<?php echo $user['User']['id']; ?>" style="display: none">
                    <table class='table table-bordered'>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                        </tr>
                        <?php
                            if (isset($game_user) && !empty($game_user)) {
                                foreach ($game_user as $game) {
                                    if ($user['User']['id'] == $game['Permission']['user_id']) {
                        ?>
                        <tr>
                            <td><?php echo h($game['Game']['id']); ?></td>
                            <td><?php echo h($game['Game']['title']); ?></td>
                        </tr>
                        <?php
                                    }
                                }
                            }
                        ?>
                    </table>
                </div>
            </td>
            <td id="mail<?php echo $user['User']['id']; ?>"><?php echo h($user['User']['email']); ?></td>
            <td>
                <?php
                    echo ($user['User']['active']) ? "<span style='color:green'>Active</span>" : "<span style='color:red'>Deactive</span>";
                ?>
            </td>
            <?php foreach ($permissions as $key => $val) : ?>
            <td>
                <?php
                    $id  = $user['User']['id'];
                    $key = strtolower($key);
                    $disable = (isset($user['Disable']["$key"])) ? $user['Disable']["$key"] : '';
                    if (isset($user['User']["game-$key"])) {
                        switch($key) {
                            case 'default' :
                                if (isset($user['User']["website-default"])) {
                                    $data = (checkPermission($user['User']["game-default"]) == "checked" && checkPermission($user['User']["website-default"]) == "checked") ? "checked" : '';
                                } else {
                                    $data = '';
                                }
                                break;
                            default :
                                $data = checkPermission($user['User']["game-$key"]);
                                break;
                        }
                    } else {
                        $data = '';
                    }
                    if (in_array($this->Session->read('Auth.User.role'), array('Admin', 'Developer'))) {
                        echo "<label class='center'>" . $this->Form->checkbox('role', array('name' => "$key", 'id' => "$id", 'class' => 'box', 'checked' => "$data",
                                'type' => 'checkbox', 'disabled' => $disable)) . "</label>";
                    } else if ($data == 'checked') {
                        if ($key == 'default') {
                            echo "<label id='icon1' class='icon-ok'></label>";
                        } else {
                            echo "<label id='icon2' class='icon-ok'></label>";
                        }
                    } else {
                        echo "<label class='center'>" . $this->Form->checkbox('role', array('name' => "$key", 'id' => "$id", 'class' => 'box', 'checked' => "$data",
                                'type' => 'checkbox', 'disabled' => $disable)) . "</label>";
                    }
                ?>
            </td>
            <?php endforeach;
                if (in_array($this->Session->read('Auth.User.role'), array('Admin', 'Developer'))
                    && isset($this->request->data['submit']) && $this->request->data['submit'] == 'Search') {
                    $all_role = Permission::$role;
            ?>
            <td>
            <?php if (!in_array($user['User']['role'], $all_role)) { ?>
                <p>
                    <span style="color:red;font-weight:bold">Current role : </span>
                    <span class="role<?php echo $user['User']['id']; ?>"><?php echo $user['User']['role']; ?></span>
                </p>
            <?php } ?>
                <select class="role_sel<?php echo $user['User']['id']; ?>">
                    <option value='0'>Select Role</option>
            <?php
                foreach ($all_role as $val) {
                    echo '<option value = "' . $val . '"';
                    echo ($user['User']['role'] == $val) ? 'selected = "selected"' : '';
                    echo '>' . $val . '</option>';
                }
            ?>
                </select>
            </td>
            <?php
                } else {
            ?>
            <td class="role<?php echo $user['User']['id']; ?>"><?php echo h($user['User']['role']); ?></td>
            <?php
                }
                if (!isset($users_id)) {
                    echo '<td class="actions btn-group">' . $this->Html->link('Delete', '/admin/permissions/delete/user_id:' . $user['User']['id'] . '/alias:' . $alias,
                            array('class' => 'btn btn-mini'), sprintf('Bạn chắc chắn muốn xóa User : %s ???', $user['User']['username'])) . '</td>';
                }
            ?>
        </tr>
    <?php endforeach; } ?>
    </table>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.box').click(function() {
            var user_id    = $(this).attr('id');
            var per        = $(this).attr('name');
            var url        = '<?php echo Router::Url(array('controller'=>'Permissions','action'=>'game')); ?>';
            var value      = 0;
            var alias      = '<?php echo $alias; ?>';
            var roles      = '<?php echo implode(',', Permission::$role);?>';
            var email      = $('#mail' + user_id).text();
            var checked    = $('input:checkbox[id="' + user_id + '"]').is(':checked');
            var is_checked = $(this).is(':checked');
            var role_check = $('.role' + user_id).text().trim();
            if (is_checked) {
                value = $(this).val();
            }
            if (checked == false) {
                $(this).parent().parent().parent().fadeOut();
            }
            if ($('td').hasClass('role' + user_id)) {
                var role  = $('.role' + user_id).text();
            } else {
                if ($('.role_sel' + user_id).find(':selected').val() == 0 && roles.indexOf(role_check) == -1) {
                    alert('Vui lòng chọn role mới cho user, role hiện tại của user không thể sử dụng tool');
                    return false;
                } else {
                    var role = $('.role_sel' + user_id).find(':selected').val();
                }
            }
            var arr = {
                per:per,
                role:role,
                email:email,
                alias:alias,
                value:value,
                user_id:user_id,
            };
            $.ajax({
                type:'POST',
                url:url,
                dataType:'json',
                data:{arr:arr},
                success:function(result) {
                    var html = '';
                    if (result.code == 1 || result.code == 2) {
                        html += '<div class="alert alert-success">';
                        html += '<a class="close" data-dismiss="alert">x</a>';
                        html += '<b>';
                        html +=  result.message;
                        html += '</b>';
                        html += '</div>';
                    } else if (result.code == 3 || result.code == 4 || result.code == 5 || result.code == 6 || result.code == 7) {
                        html += '<div class="alert alert-error">';
                        html += '<a class="close" data-dismiss="alert">x</a>';
                        html += '<b>';
                        html +=  result.message;
                        html += '</b>';
                        html += '</div>';
                    }
                    $('.result').html(html);
                }
            });
        })
        $('.a-popover').each(function() {
            var user_id = $(this).attr('uid');
            var name    = $(this).text();
            $(this).popover({
                title: "Các game mà user <span style='color:green'>"+name+'</span> được phân quyền :',
                content:  $('#popup'+user_id).html(),
                placement: 'right',
                html: true,
            });
        });
        $('body').on('click', function (e) {
            $('.a-popover').each(function () {
                if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
                    $(this).popover('hide');
                }
            });
        });
    })
</script>