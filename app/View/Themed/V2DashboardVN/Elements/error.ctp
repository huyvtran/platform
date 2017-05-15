<?php
if( $this->view != 'connect_facebook'){
    if ($this->layout == 'default_bootstrap') {
        if (is_string($message)) {
            echo "	<div class='rs tt-error'>
					<a class='close' data-dismiss='alert'>×</a>
					 $message
				</div>";
        } else {
            foreach($message as $field => $msg) {
                echo "	<div class='rs tt-error'>
					<a class='close' data-dismiss='alert'>×</a>
					 $msg
				</div>";
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
}else{
    if (is_string($message)) {
        echo '<i class="ico-error"></i>'.$message;

    } else {
        foreach($message as $field => $msg) {
            echo '<i class="ico-error"></i>'.current($msg);
        }
    }
}

?>