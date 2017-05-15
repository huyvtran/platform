<?php
$count  =   strlen($user['User']['email']);
$email_contact  =   substr($user['User']['email'],0,3).'****@***'.substr($user['User']['email'],$count-5,$count);
$ispop = array();
if(isset($this->request->query['ispop'])){
    $ispop = array('ispop'=>true);
}
?>
<?php echo $this->Session->flash('send_success');?>
<div class="box-thongtin">
    <?php echo $this->Session->flash('error_dashboardv2');?>
    <p class="rs tt-text">
        <?php echo __("Hãy nhập mã PIN được gửi vào mail"); ?> <strong><?php echo $email_contact ;?></strong> .
    </p>
    <div class="box-ttBtn box-ttForm cf">
        <?php
            echo $this->Form->create('User', array());
        ?>
            <div class="tt-row tt-rowtext">
                <?php
                    echo $this->Form->input('codePin', array(
                        'id' => 'codePin',
                        'placeholder' => __('Nhập PIN'),
                        'label' => false,
                        'div' => false,
                        'errorMessage' => false,
                        'type'  =>'password'
                    ));
                ?>
                <span class="icon-clear">x</span>
            </div>
            <p class="rs"><?php echo __("Không nhận được PIN?") ;?>  <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'sendAgainPassword','?'=>array_merge(array('redirect'=>'confirmCreatePassword'),$ispop))); ?>">
                    <?php echo __("Gửi lại") ; ?></a></p>
            <p class="rs tt-text"><?php echo __("Nhập mật khẩu mới") ; ?></p>
            <div class="tt-row tt-rowtext">
                <?php
                    echo $this->Form->input('password', array(
                        'id' => 'password',
                        'placeholder' => __('Mật khẩu mới'),
                        'label' => false,
                        'div' => false,
                        'errorMessage' => false,
                        'type'=>'password'
                    ));
                ?>
                <span class="icon-clear">x</span>
            </div>
            <div class="tt-row">
                <?php
                    echo $this->Form->input('temppassword', array(
                        'id' => 'repassword',
                        'placeholder' => __('Xác nhận mật khẩu'),
                        'label' => false,
                        'div' => false,
                        'errorMessage' => false,
                        'type'=>'password'
                    ));
                ?>
                <span class="icon-clear">x</span>
            </div>
            <?php
                echo $this->Form->button(__('Hoàn thành'), array(
                    'class' => 'ttBtn-red'
                ));
            ?>
            <a href="<?php echo $this->Html->url(array('controller'=>'UserInfors','action' => 'infoUserLogin','?'=>array_merge(array('isRedirect'=>true),$ispop))); ?>" class="ttBtn ttBtn-gray"><?php echo __("Hủy"); ?></a>
        <?php   echo $this->Form->end(); ?>

    </div>
</div>