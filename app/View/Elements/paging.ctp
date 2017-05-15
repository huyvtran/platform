<?php

if (empty($lastNum))
            $lastNum = 0;
if (empty($class))
            $class = '';
if (!empty($showCount)) {
            
}
if (empty($addClass))
            $addClass = '';
if ($this->Paginator->hasPage(null, 2)) {
    if (!isset($quick)) {
        echo "<div class='paging $addClass '>";
		
		if ($this->Paginator->current() > 5)
        	echo $this->Paginator->first('First', array('class' => "$class"));

        echo $this->Paginator->numbers(array('separator' => '', 'last' => $lastNum, 'modulus' => 5, 'class' => $class));
        echo '</div>';
    } else {
        echo "<div class='paginator $addClass '>";
        $first = $this->Paginator->first('« First', array('class' => "$class"));
        if ($first == '')
                    echo "<span class='ajaxindex disabled'>First &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>";
        echo $first;
        echo $this->Paginator->prev('Previous', array('class' => "$class"), null, array('class' => "disabled ", 'tag' => 'span'));
        echo $this->Paginator->next('Next', array('class' => "$class"), null, array('class' => "disabled", 'tag' => 'span'));
        echo "<span class='$class disabled'>" . $this->Paginator->counter(" Page %page%") . "</span>";
        echo '</div>';
    }
}
?>