<?php $this->extend('/Common/blank'); ?>
<div class='row'>
	<div class='span4'>
		<h2 class = 'page-header'><?php echo 'Articles - Index';?></h2>
	</div>
	<div class='span8'>
		<?php
		echo $this->Form->create('Article', array('action' => 'index', 'class' => 'form-inline'));
		echo $this->Form->input('title', array(
			'label' => false, 'placeholder' => 'search title',
			'value' => empty($this->request->params['named']['title']) ? false : $this->request->params['named']['title'],
			'empty' => '--Choose Title--',
    		'div' => false, 'required' => false
		));
		echo ' ';
		echo $this->Form->input('category_id', array(
			'label' => false, 'empty' => '--All Categories--',
			'selected' => empty($this->request->params['named']['category_id']) ? false : $this->request->params['named']['category_id'],
    		'div' => false
		));		
		echo ' ';
		echo $this->Form->submit('Search', array('class' => 'btn btn-small',
    		'div' => false));
		echo $this->Form->end();
		?>
	</div>
</div>
<?php
if (empty($articles)) {
	echo '<em>Chua có bài viết nào.</em>';
	goto e;
}
?>
<p>
	<?php
	echo $this->Paginator->counter(
	array('format' => 'Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%'));
	?>
</p>

<table class="table table-striped">
	<thead>
		<tr>
			<th><?php echo 'Avatar' ?></th>
			<th><?php echo $this->Paginator->sort('title', 'Title');?></th>
			<th><?php echo $this->Paginator->sort('created');?></th>
			
			<th><?php echo $this->Paginator->sort('position');?></th>
			
			<th><?php echo $this->Paginator->sort('User.id', 'last edit');?></th>
			<th><?php echo $this->Paginator->sort('published_date');?></th>
			<th class="actions">Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php
foreach ($articles as $article):
		?>
		<tr>
			<td><?php echo $this->Nav->image($article['Avatar'], 20, 20, array('empty' => 'transparent.gif')) ?></td>
			<td>
				<?php
				echo $this->Html->link($article['Article']['title'],
					array('action' => 'edit', $article['Article']['id']),
					array('style' => 'color: #333', 'title' => h('Slug: ' . $article['Article']['slug']))
				);
				if (!empty($article['Category'])) {
					echo '<br/>';
					echo '<small class="muted">';
					echo $this->Html->link($article['Category']['title'], array('action' => 'index', 'category_id' => $article['Category']['id']), array('class' => 'muted'));
					echo '</small>';
				}
				if ($article['Article']['is_hot']) {
					echo ' <span class="label-important label"> Hot </span>';
				}
				if ($article['Article']['is_new']) {
					echo ' <span class="label-warning label"> New </span>';
				}
				?>

			</td>
			<td>
				<?php 
				echo $this->Time->timeAgoInWords($article['Article']['created'], array(
					'end' => '1 year',
					'accuracy' => array('day' => 'day', 'week' => 'week', 'month' => 'month')
				));
				?>
			</td>
			
			<td><?php echo $article['Article']['position'] ?></td>
			
			<td>
				<?php
				echo $article['User']['username'];
				?>
			</td>			
			<td>
				<?php
				if (empty($article['Article']['published'])) {
                    if(isset($article['Article']['published_date']) &&
                        strtotime($article['Article']['published_date']) > strtotime(date('Y-m-d H:i:s',time()))){
                            echo '<span style="color: red"> next: '.$this->Time->timeAgoInWords($article['Article']['published_date'], array(
                                'end' => '1 year',
                                'accuracy' => array('day' => 'day', 'week' => 'week', 'month' => 'month')
                            )).'</span>';
                    }
					else echo '<span style="color: red">No</span>';
				} else {
					echo '<span style="color: green">' .  $this->Time->format($article['Article']['published_date'], '%d-%m-%Y') . '</span>';
				}
				?>
			</td>
			<td class="btn-group">
				<?php
				if (empty($article['Article']['published'])) {
                    if(isset($article['Article']['published_date']) &&
                        strtotime($article['Article']['published_date']) > strtotime(date('Y-m-d H:i:s',time())))
                        echo '<span class="btn btn-mini" style="width: 49px">AutoPublish</span>';
                    else
					    echo $this->Html->link('Publish', array('controller' => 'articles', 'action' => 'publish', $article['Article']['id'], 'admin' => true), array('class' => 'btn btn-mini', 'style' => 'width: 49px'));
				} else {
					echo $this->Html->link('<span class="muted">UnPublish</span>', array('controller' => 'articles', 'action' => 'unpublish', $article['Article']['id']), array('class' => 'btn btn-mini', 'escape' => false));
				}
				echo $this->Html->link('Edit', array('action' => 'edit',
					$article['Article']['id']
				), array('class' => 'btn btn-mini'));
				echo $this->Html->link('Down', array('action' => 'movedown',
					$article['Article']['id']
				), array('class' => 'btn btn-mini'));
				echo $this->Html->link('Up', array('action' => 'moveup',
					$article['Article']['id']
				), array('class' => 'btn btn-mini'));
				echo $this->Html->link('moveToTop', array('action' => 'moveToTop',
					$article['Article']['id']
				), array('class' => 'btn btn-mini'));
				echo $this->Html->link('Delete', array(
					'action' => 'delete',
					$article['Article']['id']
				), array('class' => 'btn btn-mini'), sprintf('Are you sure you want to delete # %s?', $article['Article']['id']));
				?>
			</td>
		</tr> <?php endforeach;?>
	</tbody>
</table>
<?php
echo $this->element('paging');
e:
?>
<div class='actions'>
<?php
echo $this->Html->link('Add Article', array('action' => 'add'), array('class' => 'btn'));
?>
</div>
<h4>Tips: </h4>
<ul>
<li>* Nếu trang hiển thị bài viết  của  2 categories trở lên thì sẽ order theo "published_date" (default)</li>
<li>* Nếu hiển thị bài viết của 1 category thì sắp xếp theo "position" (default)</li>
</ul>

<?php
	// uncomment after update fluentd server
	// echo $this->element('get_admin_logs');
?>