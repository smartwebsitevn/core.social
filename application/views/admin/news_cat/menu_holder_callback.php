<!-- Main content wrapper -->
<table class="table table-bordered table-striped table-hover tc-table news-cat-table"	>
	<thead>
	<tr>
		<th class=""><?php echo lang('name') ?></th>
		<th class="col-1 textC"><label><input type="checkbox" class="tc act_select_all"><span class="labels"></span></label></th>

	</tr>
	</thead>

	<tbody>
	<?php foreach ($list as $row):
		?>

			<tr >
				<td>
					<?php echo $row->name ?>
				</td>

				<td class="textC">
					<label>
						<input  class="tc act_select_module" name="ids" type="checkbox" value="<?php echo $row->id; ?>"
							<?php //if (isset($info->permissions[$c]) && count($info->permissions[$c]) == count($ps)) echo 'checked="checked"'; ?>
							/>
						<span class="labels"></span>
					</label>
				</td>


			</tr>
	<?php endforeach; ?>
	<tr><td colspan="10"><a  class="btn btn-primary act_choice pull-right">
				<i class="fa fa-pencil"></i> <?php echo lang('button_update'); ?>
			</a></td></tr>
	</tbody>
</table>
<script type="text/javascript">
	$(document).ready(function(){

	})
</script>

<script type="text/javascript">
	(function($)
	{
		$(document).ready(function()
		{
			var main = $('.news-cat-table');

			$('.news-cat-table th input.act_select_all').on('click' , function(){
				var checkboxes =main.find(':checkbox').not($(this));
				checkboxes.prop('checked',$(this).is(':checked'));

			});

			$('.act_choice').click(function() {
				//alert($('input[name="ids"]:checked').serialize());
				var values = $('input[name="ids"]:checked').map(function () {
					return this.value;
				}).get();
				if (values == ''){
					//alert('Please choice');
					//return false;
					values='';
				}
				else
					values = 'news_cat:'+values;
				//alert(values);
				$('input[name="holder"]', window.parent.document).val(values);
				$.colorbox.close();
			});
		});
	})(jQuery);
</script>