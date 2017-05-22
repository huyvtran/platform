<?php
$popoverContent = "<ul class='unstyled' style='display:none' id='target{$gameId}'>";
$popoverContent .= '<li>' . $this->Html->link('View by Country', array('action' => 'country', 'game_id' => $gameId, 'fromTime' => $fromTime, 'toTime' => $toTime)) . '</li>';
$popoverContent .= '<li>' . $this->Html->link('View by Distributor', array('action' => 'distributor', 'game_id' => $gameId, 'fromTime' => $fromTime, 'toTime' => $toTime)) . '</li>';
$popoverContent .= "</ul>";
echo $popoverContent;

echo '<a href="#" class="options" data-target="target' . $gameId . '">' . $games[$gameId] . '</a>';


$this->Js->buffer('

$(".options").click(function(){ return false })

$(".options").popover({html: true, content: function() {
	return $("#" + $(this).data("target")).html()
}});

');
