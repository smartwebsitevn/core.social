<ul id="todo-sortlist" class="task-widget list-group task-lists">
	<?php foreach($list as $row): ?>
		<li class="list-group-item item-<?php echo $row->id ?>">
			<div class="tcb">
				<label class="<?php echo $row->status?'todo-done':'' ?>">
					<input type="checkbox" data-url="<?php echo  admin_url('tool_todo/edit/'.$row->id)?>" class="tc" <?php echo  $row->status?" checked":'' ?>/>
						<span  class="labels">
							<?php echo $row->content ?>
						</span>
				</label>
			</div>
			<div class="tools">
				<a  href="javascript:void(0)" class="btn btn-xs btn-primary act-del"   data-id="<?php  echo $row->id;?>"  data-url="<?php echo  admin_url('tool_todo/del/'.$row->id)?>">
					<i class="icon-only fa fa-share"></i> <?php echo lang('del') ?>
				</a>
			</div>
		</li>
	<?php endforeach; ?>
</ul>

<script type="application/javascript">
	// For Tasks Widget
	//--------------------------------
	$(document).ready(function () {
		$("#todo-sortlist li input").on('click', function() {
			$.ajax({
				type: "POST",dataType: "json",async: false,   url :  $(this).data('url'),
				data: {"_submit" :true,'status':this.checked},



			});

		});
		$("#todo-sortlist li .act-del").on('click', function() {
			var id = $(this).data('id')
			if (confirm("<?php echo lang('notice_confirm_del'); ?>") == true){
				$.ajax({
					type: "POST",dataType: "json",async: false,   url :  $(this).data('url'),
					data: {"_submit" :true},
					success:function(data){
						if(data.complete){
							$("#todo-sortlist li.item-"+id).remove();
						}
					}
				});
			}
		});
	})


</script>