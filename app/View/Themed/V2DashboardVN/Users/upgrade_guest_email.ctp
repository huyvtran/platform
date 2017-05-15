<section id="wrapper">
    <article class="flogin">
        <?php
        echo $this->Form->create('User', array(
                'inputDefaults' => array('div' => false, 'label' => false, 'errorMessage' => false))
        );
        ?>
        <?php
        echo $this->Session->flash('flash', array('element' => 'error_dashboardv2'));
        ?>
        <div class="tt-row tt-rowtext">
            <?php
            echo $this->Form->input('email', array(
                'id' => 'email', 'class' => 'reset full',
                'placeholder' => 'Email'
            ));
            ?>
            <span class="icon-clear" >x</span>
        </div>
        <div class="tt-row tt-rowtext">
            <?php
            echo $this->Form->input('username', array(
                'id' => 'username', 'class' => 'reset full',
                'placeholder' => __('Tên đăng nhập dài 5-20 ký tự'), 'minlength' => 5, 'maxlength' => 20
            ));
            ?>
        </div>
        <div class="tt-row tt-rowtext">
            <?php
            echo $this->Form->input('password', array(
                'type' => 'text',
                'id' => 'password', 'class' => 'reset full',
                'placeholder' => __('Mật khẩu (dài 6-15 ký tự)'), 'minlength' => 6, 'maxlength' => 20,
                'autocapitalize' => 'off', 'autocomplete' => 'off'
            ));
            ?>
        </div>
        <?php
        echo $this->Form->button(__('Đăng ký'), array(
            'class' => 'fbutton btn-dnhap', 'name' => 'btn-Registr',
        ));
        ?>
        <?php
        echo $this->Form->end();
        ?>
    </article>
</section>