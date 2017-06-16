<script type="text/javascript">
	$(document).ready(function () {
		$('a.delete').click(function(){
			if(!confirm("Bạn chắc chắn muốn xóa"))
			{
				return false;
			}
		});
	})
</script>

<div class="panel panel-default">
   <div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('title_user_bank'); ?> <small>(Tổng số <?php echo count($list)?>)</small></h3>
	</div>
	<div class="panel-body">
    	<table class="table table-bordered table-hover">
    	     <thead>
    			<tr>
    				<td><?php echo lang('id'); ?></td>
    				<td><?php echo lang('bank'); ?></td>
    				<td><?php echo lang('bank_branch'); ?></td>
    				<td><?php echo lang('city'); ?></td>
    				<td><?php echo lang('bank_account'); ?></td>
    				<td><?php echo lang('bank_account_name'); ?></td>
    				<td><?php echo lang('status'); ?></td>
    				<td><?php echo lang('action'); ?></td>
    			</tr>	
    		</thead>
    		<?php foreach ($list as $row): ?>
    				<tr>
    					<td>
    						<div class="fontB f12 blue"><?php echo $row->id; ?></div>
    					</td>
    					
    					<td>
    						<?php echo $row->bank->name; ?>
    					</td>
    					
    					<td>
    						<?php echo $row->bank_branch; ?>
    					</td>
    					<td>
    						<?php echo $row->city->name; ?>
    					</td>
    					<td>
    						<?php echo $row->bank_account; ?>
    					</td>
    					<td>
    						<?php echo $row->bank_account_name; ?>
    					</td>
    					<td>
    					    <font class="<?php echo mod('order')->status_name($row->status) ?>">
        						<?php echo macro()->status_color(mod('order')->status_name($row->status), lang('order_status_' . mod('order')->status_name($row->status))) ?>
        					</font>
    					</td>
    					<td class="option link textC">
    						 <a class="btn btn-xs btn-success lightbox" href="<?php echo site_url('user_bank/edit/'.$row->id)?>" type="button"><?php echo lang('edit')?></a>
                             <a class="btn btn-xs btn-danger delete" href="<?php echo site_url('user_bank/del/'.$row->id)?>" type="button"><?php echo lang('del')?></a>
    					</td>
    				</tr>
    			<?php endforeach; ?>
    	</table>
    	<div class="clear"></div>
    		<div class="clear"></div>
            <a href="<?php echo site_url('user_bank/add')?>"  style="width:240px;margin:10px auto" class="btn btn-success btn-block ">Thêm tài khoản</a>
           <div class="clear"></div>
     </div>
</div>
