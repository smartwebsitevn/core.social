<div class="portlet">
		<div class="portlet-heading dark">
			<div class="portlet-title">
				<h4>
				<a href="<?php echo admin_url('user')?>">
				<?php echo lang('title_user_newest')?>		
				</a>			
				</h4>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="panel-collapse collapse in">
			<div class="portlet-body no-padding">
            	<table class="table table-bordered table-hover">
            		<tbody>
            		    <tr>
            		        <th><?php echo lang('name') ?></th>
						    <th><?php echo lang('username'); ?></th>
						    <th><?php echo lang('created'); ?></th>  
						    
            		    </tr>
            		    <?php foreach ($list as $row):?>
            			<tr>
            			    <td><?php echo $row->name.'<br>'.$row->email?></td>
        					<td><a href="<?php echo admin_url('user').'?id='.$row->id?>"><?php echo $row->username.'<br>'.$row->email?></a></td>
        					<td><?php echo format_date($row->created, 'full')?></td>
            			</tr>
            			 <?php endforeach;?>
            		</tbody>
            	</table>
           </div>	
		</div>
</div>