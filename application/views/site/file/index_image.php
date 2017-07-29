<?php if (count($list)): ?>

<?php $_rd = random_string('unique'); ?>

<script type="text/javascript"> 
(function($)
{
	$(document).ready(function()
	{
		var $main = $('.file_list_<?php echo $_rd; ?>');
		var sort = <?php echo (int) $sort; ?>;
		
		// Hide it
		$main.find('.hideit').click(function()
		{
			$(this).fadeOut();
		});
		
		// Lightbox
		$main.find('.lightbox').nstUI({
			method:	'lightbox'
		});
		
		// Xac thuc khi xoa file
		$main.find('.verify_action').nstUI('verifyAction',{
			event_complete: function(data, settings)
			{
				load_ajax($main.parents('#file_list'));
			}
		});

		// Sort
		if (sort)
		{
			$main.find('.file_image_list').sortable({
				items: '.file_image_item',
				placeholder: 'file_image_item item_placeholder',
				stop: function(event, ui) 
				{
					var items = new Array();
					$main.find('.file_image_item').each(function()
					{
						items.push( $(this).attr('data-item') );
					});
					
					$(this).nstUI('loadAjax', {
						url: '<?php echo $url_update_order; ?>',
						data: {items: items.join()},
						field: {load: '_', show: ''},
					});
				},
			});
		}
		
	});
})(jQuery);
</script>


<style>
.file_list_<?php echo $_rd; ?> .nNote {
	margin: 0 0 10px 0;
}
</style>


<div class="file_list_<?php echo $_rd; ?>">
	
	<!-- Message -->
	<?php if ( ! empty($message)):?>
		<?php $this->widget->admin->message($message); ?>
	<?php endif; ?>
	
	<!-- List -->
	<div class="file_image_list">
		<?php foreach ($list as $row): ?>
			<div class="file_image_item" data-item="<?php echo $row->id; ?>">
				<div class="file_image_img">
					<a href="<?php echo $row->_url; ?>?lightbox&rel=<?php echo $_rd; ?>" class="lightbox">
						<img src="<?php echo $row->_url; ?>" />
					</a>
				</div>
				
				<div class="file_image_actions">
					<a href="<?php echo $row->_url_modify; ?>" onclick="call_image_croper(this); return false;"  title="<?php echo lang('modify'); ?>" >
						<img src="<?php echo public_url('admin') ?>/images/icons/color/edit.png" />
					</a>
					
					<a href="" _url="<?php echo $row->_url_del; ?>" class="verify_action" title="<?php echo lang('delete'); ?>" 
						notice="<?php echo lang('notice_confirm_delete'); ?>:<br><strong><?php echo $row->orig_name; ?></strong>"
					>
						<img src="<?php echo public_url('admin') ?>/images/icons/color/delete.png" />
					</a>
					
					<div class="clear"></div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	
	<div class="clear"></div>
</div>

<?php endif; ?>