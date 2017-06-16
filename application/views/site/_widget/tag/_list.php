<div class="tagbox">
	<i class="fa fa-tags" aria-hidden="true"></i> Tag: <br/>
	<?php foreach($list as $row){?>
		<a href="<?php echo $row->_url_view ?>" title="<?php echo $row->name ?>"><?php echo $row->name ?></a>
	<?php } ?>
</div>