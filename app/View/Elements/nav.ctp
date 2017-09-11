<?php if ($this->Session->read('Auth.User')) { ?>
<!-- Main Header -->
<header class="main-header">
    <!-- Logo -->
    <a href="<?php echo Router::url('/'); ?>" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>CMS</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>CMS</b></span>
    </a>
    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li>
                    <a href="#" onclick="return false">
                        <i title="Admin Home" class="fa fa-home" style="color: white" onclick=";window.location.href=this.getAttribute('href');return false;" href="<?php echo $this->Html->url("/admin") ?>"></i>
                        <span title="Pick a domain" class='text-warning'  onclick=";window.location.href=this.getAttribute('href');return false;"  href="<?php echo $this->Html->url(array(
                         'controller' => 'websites', 'admin' => true, 'action' => 'setsession'
                         ));
                         ?>">
                         <?php
                         if ($this->Session->read('Admin.website.title'))
                             echo '<span title="' . $this->Session->read('Admin.website.url') . '">' . $this->Session->read('Admin.website.title') . '</span>';
                         else {
                             echo '-----';
                         }
                         ?>
                     </span>
                 </a>
             </li>

             <?php if ($this->Session->read('Auth.User') && !in_array($this->Session->read('Auth.User.role'), array('Stats', 'User', 'Guest')) ) { ?>
             <li>
                <?php if (empty($_SERVER['APPLICATION_ENV'])) { ?>
                <a href="http://stats.muoriginfree.com:8880/stats/">(Stats)</a>
                <?php } else { ?>
                <a href="<?php echo $this->request->webroot ?>stats">(Stats)</a>
                <?php } ?>
            </li>
            <?php } ?>
            <!-- User Account Menu -->
            <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <!-- The user image in the navbar-->
                    <?php echo $this->Html->image('avatar.png', ['class' => 'user-image', 'alt' => 'User Image']); ?>
                    <!-- hidden-xs hides the username on small devices so only the image appears. -->
                    <span class="hidden-xs"><?php echo $this->Session->read('Auth.User.username') ?></span>
                </a>
                <ul class="dropdown-menu">
                    <!-- The user image in the menu -->
                    <li class="user-header">
                        <?php echo $this->Html->image('avatar.png', ['class' => 'img-circle', 'alt' => 'User Image']); ?>
                        <p>
                            <?php echo $this->Session->read('Auth.User.username') ?></span> - <?php echo $this->Session->read('Auth.User.role') ?></span>
                            <small>Member since <?php echo date('m, Y', strtotime($this->Session->read('Auth.User.created'))) ?></small>
                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            <?php echo $this->Html->link('Reset Password', '/users/reset_password_web', ['class' => 'btn btn-default btn-flat']); ?>
                        </div>
                        <div class="pull-right">
                            <?php echo $this->Html->link('Logout', '/users/logout', ['class' => 'btn btn-default btn-flat']); ?>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
</header>

<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <?php
            foreach ($menu as $name1 => $categories) {
                if (!empty($categories['categories'])) {
                 $activeMenu = '';
                   /*foreach ($categories['activeMenu'] as $controller) {
                      $activeMenu = $this->Nav->thisController($controller);
                  }*/
                  $categories = $categories['categories'];
                  ?>
                  <li class="treeview <?php echo $activeMenu ?>">
                      <a class="treeview-toggle" data-toggle="dropdown" href=""><i class="fa fa-tachometer"></i><span><?php echo $name1 ?> <b class="caret"></b></span></a>
                      <ul class="treeview-menu">
                       <?php
                       $checkDivider = false;
                       foreach ($categories as $name2 => $category) {
                        if(strpos($name2, 'divider') === false){
                         $checkDivider = false;
                         if (is_string($category)) {
                          ?>
                          <li><a href="<?php echo $this->Html->url($category)?>"><i class="fa fa-circle-o"></i> <span><?= $name2 ?></span></a></li>
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
                                                    <li><a href="<?php echo $this->Html->url($childCategory)?>"><?= $name3 ?></a></li>
                                                    <?php
                                                }
                                                ?>
                                            </ul>
                                            <li>
                                             <?php
                                         }
                                     }
                                 }else{
                                     if(!$checkDivider) {
                                      echo "<li class='divider'></li>";
                                      $checkDivider = true;
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
          <?php if(isset($error_phone) && in_array($this->Session->read('Auth.User.role'), array('Admin', 'Developer'))){
            ?>
            <li>
                <?php
                echo $this->Html->link("<i class='fa fa-phone'> <i class='badge'> $error_phone </i> </i> Error", array(
                    'controller' => 'UserInfors', 'action' => 'alert_error', 'admin' => true), array('escape' => false));
                    ?>
                </li>
                <?php } ?>
            </ul>
        </section>
    </aside>
    <?php } ?>
