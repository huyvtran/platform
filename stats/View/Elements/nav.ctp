<!-- Main Header -->
<header class="main-header">

    <!-- Logo -->
    <a href="<?php echo $this->Html->url('/')?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>VNT</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><?php echo $this->Html->image('logo.png', ['height' => '50px', 'alt' => 'VNTAP']); ?></span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button -->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <?php
                if ($this->Session->read('Auth.User')) {
                ?>
                    <!-- Link to admin page -->
                    <li>
                        <?php if (empty($_SERVER['APPLICATION_ENV'])) { ?>
                            <a href="http://admin.muoriginfree.com:8880/admin">CMS</a>
                        <?php } else { ?>
                            <a href="<?php echo substr($this->request->webroot, 0, -6) ?>admin">(Admin)</a>
                        <?php } ?>
                    </li>
                    <!-- End link admin page -->

                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <?php echo $this->Html->image('avatar5.png', array('class' => 'user-image', 'alt' => 'User Image')); ?>
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs"><?php echo $this->Session->read('Auth.User.username') ?> </span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <?php echo $this->Html->image('user2-160x160.jpg', array('class' => 'img-circle', 'alt' => 'User Image')); ?>

                            <p>
                                <?php echo $this->Session->read('Auth.User.username') ?> - <?php echo $this->Session->read('Auth.User.role') ?>
                                <small>Member since <?php echo date('m, Y', strtotime($this->Session->read('Auth.User.created'))) ?></small>
                            </p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-right">
                                <?php echo $this->Html->link('Logout', '/users/logout', array('class' => 'btn btn-default')); ?>
                            </div>
                        </li>
                    </ul>
                </li>
                    <?php
                } else {
                ?>
                    <li>
                        <a href="<?php echo $this->Html->url(array(
                            'action' => 'login', 'controller' => 'users', 'admin' => false
                        ));
                        ?>">
                            Login
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </nav>
</header>
<?php

if ($this->Session->read('Auth.User')) {
?>
<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel (optional)
        <div class="user-panel">
            <div class="pull-left image">
                <?php echo $this->Html->image('avatar5.png', array('class' => 'img-circle', 'alt' => 'User Image')); ?>
            </div>
            <div class="pull-left info">
                <p><?php echo $this->Session->read('Auth.User.username') ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>-->
        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
        <?php
        foreach ($menu as $name1 => $categories) {
            if (!empty($categories['categories'])) {
                $activeMenu = '';
                foreach ($categories['activeMenu'] as $controller) {
                    $activeMenu = $this->Nav->thisController($controller);
                }
                $categories = $categories['categories'];
                ?>
                <li class="treeview <?php echo $activeMenu ?>">
                    <a class="treeview-toggle" data-toggle="dropdown" href=""><i class="fa fa-tachometer"></i><span><?php echo $name1 ?> <b class="caret"></b></span></a>
                    <ul class="treeview-menu">
                        <?php
                        foreach ($categories as $name2 => $category) {
                        if (is_string($category)) {
                            ?>
                            <li><a href="<?php echo $this->Html->url('/')?><?= $category ?>"><i class="fa fa-circle-o"></i> <span><?= $name2 ?></span></a></li>
                            <?php
                        } else { # is array
                        if (!empty($category)) {
                        ?>
                        <li class="treeview-submenu">
                            <a tabindex="-1" href="#" data-toggle="dropdown"><?php echo $name2 ?></a>
                            <ul class="treeview-menu">
                                <?php
                                foreach ($category as $name3 => $childCategory) {
                                    ?>
                                    <li><a href="<?php echo $this->Html->url('/')?><?= $childCategory ?>"><?= $name3 ?></a></li>
                                    <?php
                                }
                                ?>
                            </ul>
                        <li>
                            <?php
                            }
                            }
                            }
                            ?>
                    </ul>
                </li>
                <?php
            }
        }
        ?>
        </ul>
        <!--<ul class="sidebar-menu" data-widget="tree">
            <li class="header">HEADER</li>
            <li class="active"><a href="#"><i class="fa fa-link"></i> <span>Link</span></a></li>
            <li><a href="#"><i class="fa fa-link"></i> <span>Another Link</span></a></li>
            <li class="treeview">
                <a href="#"><i class="fa fa-link"></i> <span>Multilevel</span>
                    <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
                </a>
                <ul class="treeview-menu">
                    <li><a href="#">Link in level 2</a></li>
                    <li><a href="#">Link in level 2</a></li>
                </ul>
            </li>
        </ul>-->
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
<?php } ?>