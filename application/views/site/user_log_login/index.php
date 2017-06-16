
<div class="t-box">
	<div class="box-title">
		<h1><?php echo lang('title_log_login'); ?></h1>
	</div>
	
	<div class="box-content">
	
	
		<div class="right mb10">
			<form class="t-form form-filter" action="<?php echo $action; ?>" method="get">
			
				<div class="row">
					<span><?php echo lang('from_date'); ?>:</span>
					<input name="created" value="<?php echo $filter['created']; ?>" style="width:80px;" type="text" class="t-input datepicker" />
				</div>
				
				<div class="row">
					<span><?php echo lang('to_date'); ?>:</span>
					<input name="created_to" value="<?php echo $filter['created_to']; ?>" style="width:80px;" type="text" class="t-input datepicker" />
				</div>
				
				<div class="row">
					<input type="submit" value="<?php echo lang('button_search'); ?>" class="button button-border small blue f" />
					<input type="reset" value="<?php echo lang('button_reset'); ?>" class="button button-border small black f" onclick="window.location.href = '<?php echo $action; ?>'; " />
				</div>
			</form>
			<div class="clear"></div>
		</div>
		
		
		<table cellpadding="0" cellspacing="0" width="100%" class="tDefault myTable">
		<thead>
			<tr>
				<td>IP</td>
				<td><?php echo lang('login_time'); ?></td>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($list as $row): ?>
				<tr>
					<td><?php echo $row->ip; ?></td>
					<td><?php echo $row->_created_full; ?></td>
				</tr>
			<?php endforeach; ?>
			
		</tbody></table>
		<div class="clear"></div>
		
		<div class="auto_check_pages">
			<?php $this->widget->site->pages($pages_config); ?>
		</div>
		<div class="clear"></div>
		
	</div>
</div>
