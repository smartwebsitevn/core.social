<?php  if (count($list)): ?>

<?php $_rd = random_string('unique'); ?>

<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var $main = $('.file_list_<?php echo $_rd; ?>');
		var sort = <?php echo 1;//(int) $sort; ?>;

		$main.find('.do_action').nstUI('doAction',{
			event_complete: function(data, settings)
			{
				load_ajax($main.parents('#file_list'));
			}
		});
		// Hide it
		$main.find('.hideit').click(function()
		{
			$(this).fadeOut();
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


	<style type="text/css">
		/**
         * List file upload
         */

		.file_item {
			float: left;
			margin: 0 10px 10px 0;
			padding: 5px 20px 5px 5px;
			width: 150px;
			position: relative;
			background: #fff;
			border: 1px solid #ccc;
			border-left: 4px solid #7ad03a;
		}

		.file_item:hover {
			border-left-color: #d54e21;
		}

		.file_item a {
			/*color: #595959;*/
		}

		.file_item .file_name {
			font-size: 11px;
			font-weight: bold;
			white-space: nowrap;
			overflow: hidden;
			text-overflow: ellipsis;
		}

		.file_item .file_actions {
			/* position: absolute; top: 5px; right: 8px; */
		}

		.file_item .file_actions a {
			/* display: inline; margin-left: 5px; font-weight: bold; */
		}

		.file_item .file_actions a:first-child {
			margin-left: 0;
		}

		.file_item.item_placeholder {
			border: 1px dashed #bbb;
			width: 50px;
			height: 20px;
		}


	</style>


<div class="file_list_<?php echo $_rd; ?>">
	
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
					<a  data-url="<?php echo $row->_url_del; ?>" data-action="confirm" class="btn btn-danger btn-xs do_action" title="<?php echo lang('delete'); ?>"
						data-notice="<?php echo lang('notice_confirm_delete'); ?>:<br><strong><?php echo $row->orig_name; ?></strong>"
						><?php echo lang('delete'); ?></a>
					<a href="#0"  class="btn btn-primary btn-xs act-notify-modal" data-content="<?php echo $row->_url; ?>" ><?php echo  lang('button_get_link') ?></a>
					<div class="clear"></div>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
	
	<div class="clear"></div>
</div>

<?php endif; ?>