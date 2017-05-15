<?php
if ($this->layout == 'default_bootstrap') {
	if (is_string($message)) {
		echo "	<div class='alert alert-error'>
					<a class='close' data-dismiss='alert'>×</a>
					 $message
				</div>";
	} else{
		foreach($message as $field => $msg) {
			if (is_string($msg)) {
				echo "	<div class='alert alert-error'>
							<a class='close' data-dismiss='alert'>×</a>
							 $msg
						</div>";
			} else {
				foreach($msg as $key => $value) {
					echo "	<div class='alert alert-error'>
							<a class='close' data-dismiss='alert'>×</a>
							 $value
						</div>";
				}
			}
		}

	}

} else {

	if (is_string($message)) {
		echo "<div class='message msg-error'>$message</div>";

	} elseif (is_array($message)) {
		
		$hasPassword = false;
		foreach($message as $field => $msg) {
			if ($field == 'password') {
				$hasPassword = true;
			}
			if ($field == 'temppassword' && $hasPassword = true) {
				continue;
			}
			echo "<div class='message msg-error'>" .  current($msg) . "</div>";
		}
	}
}
?>