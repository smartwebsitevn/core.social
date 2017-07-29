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
			$main.find('.file_list').sortable({
				items: '.file_item',
				placeholder: 'file_item item_placeholder',
				stop: function(event, ui) 
				{
					var items = new Array();
					$main.find('.file_item').each(function()
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
	<div class="file_list">
		<?php foreach ($list as $row): ?>
			<div class="file_item" data-item="<?php echo $row->id; ?>">
				<div class="file_name">
					<a href="<?php echo $row->_url; ?>" title="<?php echo $row->orig_name; ?>" target="_blank">
						<?php echo $row->orig_name; ?>
					</a>
				</div>
				
				<div class="file_actions">
					<a  href="#0" _url="<?php echo $row->_url_del; ?>" class="btn btn-danger btn-xs  verify_action" title="<?php echo lang('delete'); ?>"
						notice="<?php echo lang('notice_confirm_delete'); ?>:<br><strong><?php echo $row->orig_name; ?></strong>"
					><?php echo  lang('button_delete') ?></a>
					<a href="#0"  class="btn btn-primary btn-xs act-notify-modal" data-content="<?php echo $row->_url; ?>" ><?php echo  lang('button_get_link') ?></a>
					<div class="clear"></div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	
	<div class="clear"></div>
</div>

<?php endif; ?>