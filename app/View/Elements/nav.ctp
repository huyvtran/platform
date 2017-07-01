<style>.navbar .nav .fa-bug {
		font-size:16px;}</style>
<div class="navbar navbar-fixed-top  navbar-inverse ">
<div class="navbar-inner">
<div class="container">
	<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>
	<!-- <a class="brand" href="<?php #echo $this->Html->url(array('controller' => 'admin', 'admin' => false)) ?>" > -->
	</a>

	<div class="nav-collapse navbar-collapse collapse" aria-expanded="true">
		<ul class="nav">
            <?php if ($this->Session->read('Auth.User') && !in_array($this->Session->read('Auth.User.role'), array('Distributor', 'Stats', 'User', 'Guest')) ) { ?>
			<li class="dropdown" >
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

			<?php
			foreach ($menu as $name1 => $categories) {
				if (!empty($categories['categories'])) {
					$activeMenu = '';
					foreach ($categories['activeMenu'] as $controller) {
						$activeMenu = $this->Nav->thisController($controller);
					}
					$categories = $categories['categories'];
					?>
					<li class="dropdown <?php echo $activeMenu ?>">
						<a class="dropdown-toggle" data-toggle="dropdown" href=""><?php echo $name1 ?> <b class="caret"></b></a>
						<ul class="dropdown-menu">
							<?php
							$checkDivider = false;
							foreach ($categories as $name2 => $category) {
								if(strpos($name2, 'divider') === false){
									$checkDivider = false;
									if (is_string($category)) {
										?>
										<li><?php echo $this->Html->link($name2, $category); ?> </li>
										<?php
									} else { # is array
									if (!empty($category)) {
									?>
									<li class="dropdown-submenu">
										<a tabindex="-1" href="#" data-toggle="dropdown"><?php echo $name2 ?></a>
										<ul class="dropdown-menu">
											<?php
											foreach ($category as $name3 => $childCategory) {
												?>
												<li><?php echo $this->Html->link($name3, $childCategory); ?></li>
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

			<li class="dropdown">
			<?php
			if ($this->Session->read('Auth.User')) {
			?>
				<a class="dropdown-toggle" data-toggle="dropdown" href=""><?php echo $this->Session->read('Auth.User.username') ?> <b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li>
					<?php
					echo $this->Html->link('Reset Password', '/users/reset_password_web');
					?>
					</li>
					<li>
					<?php
					echo $this->Html->link('Logout', '/users/logout');
					?>
					</li>
				</ul>
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
			</li>

            <?php if(isset($error_phone) && in_array($this->Session->read('Auth.User.role'), array('Admin', 'Developer'))){
                ?>
                <li class="dropdown">
                    <?php
                        echo $this->Html->link("<i class='fa fa-phone'> <i class='badge'> $error_phone </i> </i> Error", array(
                            'controller' => 'UserInfors', 'action' => 'alert_error', 'admin' => true), array('escape' => false));
                    ?>
                </li>
            <?php } ?>
            <?php } ?>

            <?php if ( $this->Session->read('Auth.User') ){ ?>
            <li class="dropdown">
                <?php if (empty($_SERVER['APPLICATION_ENV'])) { ?>
                    <a href="http://stats.muoriginfree.com:8880/stats/">(Stats)</a>
                <?php } else { ?>
                    <a href="<?php echo $this->request->webroot ?>stats">(Stats)</a>
                <?php } ?>
            </li>
            <?php } ?>
		</ul>
	</div>

</div>
</div>
</div>
