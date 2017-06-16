<?php
$public_url_admin=public_url('admin');
?>

	<?php foreach($list as $row): ?>
		<div class="itemdiv dialogdiv item-<?php echo $row->id ?>">
			<div class="user">
				<img alt="" src="<?php echo $public_url_admin; ?>/ekoders/images/user-profile-1.jpg" />
			</div>

			<div class="body">
				<div class="time">
					<i class="fa fa-clock-o"></i> <?php echo format_date($row->created,'full') ?>
				</div>

				<div class="name">
					<a href="#"><?php echo $row->admin_name ?></a>
				</div>
				<div class="text"><?php echo $row->content ?></div>

				<div class="tools">
					<a href="javascript:void(0)" class="btn btn-xs btn-primary act-del"  data-id="<?php  echo $row->id;?>"  data-url="<?php echo  admin_url('tool_chat/del/'.$row->id)?>">
						<i class="icon-only fa fa-share"></i> <?php echo lang('del') ?>
					</a>
				</div>
			</div>
		</div>
	<?php endforeach; ?>

<script type="application/javascript">
	// For Tasks Widget
	//--------------------------------
	$(document).ready(function () {

			$("#i-chat #in-chat .act-del").on('click', function() {
				var id = $(this).data('id')
				if (confirm("<?php echo lang('notice_confirm_del'); ?>") == true){
					$.ajax({
						type: "POST",dataType: "json",async: false,   url :  $(this).data('url'),
						data: {"_submit" :true},
						success:function(data){
							if(data.complete){
								$("#i-chat #in-chat .item-"+id).remove();
							}
						}
					});
				}
			});
	})


</script>