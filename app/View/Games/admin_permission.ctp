<?php
$this->extend('/Common/blank');
?>
<style type="text/css">
    .addition-game-info, .alert-error-pupupover, .alert-error-popupover {
        cursor: pointer;
    }
    #user {
        max-width:330px;
    }
</style>
<div class="games index">
    <div class='rows'>
        <div class='span4'><h2>Games Permissions</h2></div>
        <div class='span2 offset5'>
        <?php
            echo $this->Html->link("<button class='btn'>Game-Index</button>", array('controller' => 'games', 'admin' => true, 'action' => 'index'), array('escape' => false));
        ?>
        </div>
    </div>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Users</th>
            <th>Total Users</th>
            <th>Permission</th>
        </tr>
        <?php foreach ($games as $game) :?>
            <tr>
                <td><?php echo $game['Game']['id'] ?></td>
                <td><?php echo h($game['Game']['title']); ?></td>
                <td id="user">
                 <?php
                    $tmp = array();
                    foreach ($users as $user) :
                        if ($game['Game']['id'] == $user['Permission']['foreign_key'] && !in_array($user['User']['role'], array('MarketingAdmin', 'Admin', 'Developer', 'SDKDeveloper','Accounting'))) {
                            $tmp[] = $user['User']['username'];
                            $data  = implode(", ", $tmp);
                        }
                    endforeach;
                    $text = "<span style='color:red'>No User</span>";
                    echo (!empty($tmp)) ? $data : $text;
                    unset($tmp);
                    unset($text);
                 ?>
                </td>
                <td><?php
                        $count = 0;
                        foreach ($users as $user) {
                            if ($game['Game']['id'] == $user['Permission']['foreign_key']) {
                                $count += 1;
                            }
                        }
                        echo $count;
                    ?>
                </td>
                <td>
                    <?php
                      echo $this->Html->link('Edit Permissions', '/admin/permissions/game?alias=' . $game['Game']['alias'], array('class' => 'btn btn-mini'));
                    ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>