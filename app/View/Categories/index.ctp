<?php
// News listing template
?>
<!--nocache-->
<?php
if (in_array($this->theme,
	array('HaitacDashboard', 'HaitacDashboardAndroid'))
) {
$this->Nav->markAsReadedNtf($this->Session->read('Auth.User.id'));
}
?>
<!--/nocache-->

<?php
if (!$this->request->is('ajax')) {
?>
<div class="game-news-list">
<section id="wrapper">
	<?php
	$isHelp = '';
	if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'help') {
		$isHelp = 'help';
	}
	?>
	<article class="content <?php echo $isHelp ?>">
		<ul class="game-list">
<?php
}
?>
			<?php
			if (!empty($articles)) {
			foreach($articles as $article) {
			?>
			<li class="news-item">
				<a href="<?php echo $this->Html->url(array('controller' => 'articles', 'action' => 'view', 'category' => $article['Category']['slug'], 'slug' => $article['Article']['slug'])) ?>">
			<?php

			echo $this->Nav->image($article['Avatar'], 96, 60, array(
				'class' => 'news-thumb', 'retina' => true, 
				'empty' => array('u' => 'no_thumb.jpg')
				)
			);
			?>
			<div class="news-data">
				<h2>
					<?php
					$title = '';
					$isEvent = ($article['Category']['slug'] == 'events') ? 'event' : false;
					$isNew = ($article['Category']['slug'] == 'news') ? 'news' : false;

					if ($isEvent) {
						//$title .= $this->Html->image('/uncommon/dashboard/images/event-indicator.png', array('class' => 'absmiddle', 'alt' => 'event')) . ' ';
                        $title .= $this->Html->image('/uncommon/dashboard/images/blank.png', array('class' => 'event', 'alt' => 'event')) . ' ';
					} elseif ($isNew) {
                        $title .= $this->Html->image('/uncommon/dashboard/images/blank.png', array('class' => 'news', 'alt' => 'news')) . ' ';
                    }
                    
					$title .= $article['Article']['title'];
                    if ($article['Article']['is_new']) {
                        $title .= ' ' . $this->Html->image('/uncommon/dashboard/images/new.gif', array('class' => 'absmiddle', 'alt' => 'new')) . ' ';
                    }
                    if ($article['Article']['is_hot']) {
						$title .= ' ' . $this->Html->image('/uncommon/dashboard/images/hot.gif', array('class' => 'absmiddle', 'alt' => 'hot'));
					}
					echo $title;
					?>
				</h2>
				<abbr title="<?php echo $this->Time->toAtom($article['Article']['published_date']) ?>">
					<?php echo $this->Nav->niceShort($article['Article']['published_date'])?></abbr>
			</div>
			</a>
			</li>
			<?php
			}}
			?>
<?php
if (!$this->request->is('ajax')) {

			if ($this->Paginator->hasNext('Article')) {
			?>
			<li class="more" id="more-article">
				<a href="javascript:void(0);"><span><?php echo __('Thêm')?>...</span></a>
			</li>
			<?php
			}
			?>
		</ul>
	</article>
</section>

<script type='text/javascript'>
$(function() {

	var fetchUrl = '<?php echo $this->Html->url(array("controller" => 
		"categories", "action" => "index", $article["Category"]["slug"], "page"))?>';
	var page = <?php echo (int) $this->Paginator->current('Article');?>;
	var end = <?php echo $this->Paginator->counter('{:pages}');?>;
	var fetching = false;
	// $(window).scroll(function() {

	// 	if (!fetching && ($(window).scrollTop() >= $(document).height() - $(window).height() - 200)) {
	$("#more-article").click(function() {
        var $this = $(this);
		if (!fetching) {
			fetching = true;
			page++;
            $this.addClass('loading');
			$.get(fetchUrl + ':' + page + '.ajax', function(data) {
				
				appendMore(data);
				fetching = false;
                $this.removeClass('loading');

				if (page >= end) {
					$("#more-article").remove();	
				}
			})
		}
		return false;
	});
	// 	}
	// })
});

function appendMore(articles) {
    $("#more-article").before(articles);
}
</script>
</div>
<?php
}
?>