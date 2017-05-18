<!--nocache-->
<?php
if (in_array($this->Session->read('Auth.User.role'), array('Test', 'Admin', 'Developer', 'SDKDeveloper'))) {
	echo '<code>';
	echo "Dump for testing users: <br/>";
	echo "Distributor Key: " . $this->request->header('mobgame_distributor') . '<br/>';
	echo "AppKey: " . $this->request->header('mobgame_appkey') . '<br/>';
	echo "SDK Version: " . $this->request->header('mobgame_sdk_version') . '<br/>';
	echo "Game Version: " . $this->request->header('mobgame_app_version') . '<br/>';
	echo "OS: " . $this->request->header('mobgame_os') . '<br/>';
	echo "Network: " . $this->request->header('mobgame_network') . '<br/>';
	echo "Device: " . $this->request->header('mobgame_device') . '<br/>';
	echo "Resolution: " . $this->request->header('mobgame_resolution') . '<br/>';
	echo "Language: " . Configure::read('Config.language') . '<br/>';
	echo "User role: " . $this->Session->read('Auth.User.role') . '<br/>';
	echo "User ID: " . $this->Session->read('Auth.User.id') . '<br/>';
	echo "Account ID: " . $this->Session->read('Auth.Account.id') . '<br/>';
	echo '</code>';
}

?>
<!--/nocache-->