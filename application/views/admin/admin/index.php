<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		// Load cur matrix
		var url = window.location.href.split('#');
		if (url[1])
		{
			$.colorbox({
				href: '#'+url[1],
				inline: true,
				opacity: 0.75
			});
		}
		
		// Confirm action
		$('.act_matrix').each(function()
		{
			var id = $(this).attr('_id');
			
			$(this).nstUI({
				method:	'confirmAction',
				confirmAction: {
					field_load: 'matrix_load_'+id,
					event_complete: function()
					{
						var url = window.location.href.split('#');
						url = url[0]+'#matrix_'+id;
						
						window.location.href = url;
						window.location.reload();
					}
				}
			});
		});
		
	});
})(jQuery);
</script>

<?php
    $_row_matrix = function($row)
	{
		ob_start();?>

      	<?php if ($row->_can_matrix_reset): ?>
        	<a href="#matrix_<?php echo $row->id; ?>?lightbox&inline=true" class="lightbox">
        		<?php echo lang('button_matrix_view'); ?>
        	</a>
        <?php endif; ?>

		<?php return ob_get_clean();
	};
	$_macro = $this->data;
	$_macro['table'] = array_only($this->data, array('total', 'actions', 'pages_config'));

   //	$_macro['table']['sort'] 	= true;
   //	$_macro['table']['sort_url_update'] = $sort_url_update;


	$_macro['table']['columns'] = array(
		'id' 		=> lang('id'),
		'name'		=> lang('name'),
		'phone'		=> lang('phone'),
		//'balance'		=> lang('balance'),
		'admin_group'	=> lang('admin_group'),
        'matrix_card'	=> lang('matrix_card'),
		'blocked'		=> lang('status'),
		'action' 	=> lang('action'),
	);

	$_rows = array();
	foreach ($list as $row)
	{
		$r = (array) $row;
		$r['admin_group'] 	= $row->admin_group->name;
		$r['balance'] 	= $row->_balance;
		$r['blocked'] 	= $row->blocked?'<i class="fa fa-lock"> '.lang('account_blocked').'</i>':'';
        $r['matrix_card'] 	= $_row_matrix($row);
		//$r['action'] 	= $_row_action($row);

		$_rows[] = $r;
	}
	$_macro['table']['rows'] = $_rows;

	echo macro()->page($_macro);
?>
<!-- List matrix -->
<div class="hide">
	<?php foreach ($list as $row): ?>
		<?php if ($row->_can_matrix_reset): ?>
			<div class="widget mg0 form_load" style="width:600px;" id="matrix_<?php echo $row->id; ?>" style="position:relative;">

				<div class="title">
					<img src="<?php echo public_url('admin'); ?>/images/icons/dark/key.png" class="titleIcon" />
					<h6><?php echo lang('title_matrix_card', '<font class="blue">'.$row->username.'</font>'); ?></h6>

					<div class="num">
						<a href="" _url="<?php echo $row->_url_matrix_reset; ?>" class="blueNum ml5 act_matrix" _id="<?php echo $row->id; ?>"
							_notice="<?php echo lang('notice_confirm_reset_matrix', $row->username); ?>"
						>
							<?php echo lang('button_matrix_reset'); ?>
						</a>

						<div id="matrix_load_<?php echo $row->id; ?>"></div>
					</div>
				</div>

				<?php if (count($row->matrix)): ?>
					<table cellpadding="0" cellspacing="0" width="100%" class="sTable mTable myTable">
						<tbody>
							<tr>
								<td></td>
								<?php foreach ($row->matrix[1] as $c => $v): ?>
									<td class="f15 fontB textC"><?php echo $c; ?></td>
								<?php endforeach; ?>
							</tr>

							<?php foreach ($row->matrix as $r => $c_v): ?>
								<tr>
									<td class="f16 fontB textC"><?php echo $r; ?></td>
									<?php foreach ($c_v as $c => $v): ?>
										<td class="textC f14"><?php echo $v; ?></td>
									<?php endforeach; ?>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endif; ?>

			</div>
		<?php endif; ?>
	<?php endforeach; ?>
</div>

<style>
table.tMatrix tbody tr:nth-child(even) {
	background-color: none;
}
table.tMatrix tbody tr:nth-child(odd) {
	background-color: #f4f4f4;
}
table.tMatrix tbody tr:first-child {
	background-color: #f2f2f2;
}
table.tMatrix tbody td:first-child {
	background-color: #f2f2f2;
}
</style>
