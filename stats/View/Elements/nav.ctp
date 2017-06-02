<nav class="navbar navbar-default navbar-inverse " role="navigation">
<div class="navbar-header">
	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
		<span class="sr-only">Toggle navigation</span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	</button>	
	<a class="navbar-brand" href="<?php echo $this->Html->url('/')?>" >
	Stats
	</a>
</div>
<div class="collapse navbar-collapse navbar-ex1-collapse">
<?php

if ($this->Session->read('Auth.User')) {
?>
	<ul class="nav navbar-nav">
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
					foreach ($categories as $name2 => $category) {
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
									<li><?php echo $this->Html->link($name3, $childCategory);?></li>	
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

		$role = $this->Session->read('Auth.User.role');
		if ($role == 'Admin') {
		?>
		<li class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="">Debug <b class="caret"></b></a>
			<ul class="dropdown-menu">
				<li>
					<?php 
					echo $this->Html->link('Logs', array(
						'admin' => true, 'controller' => 'administrators', 'action' => 'readLog')) ?>
				</li>
				<li><?php
					echo $this->Html->link('Clear Cache', array(
						'admin' => true, 'controller' => 'administrators', 'action' => 'clearCache')) ?>
				</li>
				<li><?php
					echo $this->Html->link('Cmd', array(
						'admin' => true, 'controller' => 'administrators', 'action' => 'cmd')) ?>
				</li>
			</ul>
		</li>

		<li class="dropdown">	
			<?php if (empty($_SERVER['APPLICATION_ENV'])) { ?>
				<a href="http://admin.muoriginfree.com:8880/admin">(Admin)</a>
			<?php } else { ?>
				<a href="<?php echo substr($this->request->webroot, 0, -6) ?>admin">(Admin)</a>
			<?php } ?>
		</li>
		<?php
		}
		?>
	</ul>
<?php
}
?>
	<ul class="nav navbar-nav navbar-right">
		<li class="dropdown">
		<?php
		if ($this->Session->read('Auth.User')) {
		?>
			<a class="dropdown-toggle" data-toggle="dropdown" href=""><?php echo $this->Session->read('Auth.User.username') ?> 
			<b class="caret"></b>
			</a>
			<ul class="dropdown-menu">			
				<li>
				<?php
				echo $this->Html->link('Logout', '/users/logout');
				?>
				</li>
				<li>
				<?php
				echo $this->Html->link('Reset Password', 'http://admin.smobgame.com/plf/users/reset_password_web');
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
	</ul>
</div>
</nav>
