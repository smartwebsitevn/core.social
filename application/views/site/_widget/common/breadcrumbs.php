<ol class="breadcrumb">
	<?php $i=1; foreach ($items as $item): ?>
		<li <?php if ($item['current']) echo 'class="active"'; ?>>
			<a href="<?php echo $item['url']; ?>"
				<?php if ($item['title'] != '') echo 'title="'.$item['title'].'"'; ?>>
				<?php if($i==1) echo '<i class="fa fa-home" aria-hidden="true"></i> '; ?>
				<?php echo $item['name']; ?>
			</a>
		</li>
		<?php $i++; endforeach; ?>
</ol>