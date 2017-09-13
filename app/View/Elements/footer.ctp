<footer class="main-footer">
	<div class="pull-right hidden-xs">
    <?php echo 'Elapsed Time: <b>' . round(microtime(true) - TIME_START, 2) * 1000 . ' ms</b>' ?>
        |
        <?php
        if ($this->Session->read('Auth.User')) {
           ?>
           <?php echo 'Your user ID: <b>' . $this->Session->read('Auth.User.id') . '</b>'; ?>
           <?php
       }
       ?>
   </div>
   <strong>Copyright &copy; 2017 <a href="http://vntap.vn">VNTap</a>.</strong> All rights reserved.
</footer>