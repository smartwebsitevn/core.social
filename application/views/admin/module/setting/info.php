
<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var $main = $('#form');

		// Layout handle
		var $layout = $main.find('[name="layout[_]"]');
		var $layout_method = $main.find('#layout_method');
		
		$layout.change(function()
		{
			toggle_layout_method();
		});
		
		toggle_layout_method();
		
		function toggle_layout_method()
		{
			($layout.val()) ? $layout_method.show() : $layout_method.hide();
		}
				
		$layout_method.find('#act_method_list').click(function()
		{
			$layout_method.find('#method_list').toggle();
			return false;
		});
		
	});
})(jQuery);
</script>


<style>
#method_list table td {
	padding: 0 10px 5px 0;
}
#method_list table td:first-child {
	width: 100px;
	font-weight: bold;
}
</style>


<div class="form-group">
	<label  class="col-sm-12 fontB blue f14 textL control-label ">
		<?php echo lang('tab_info'); ?>
	</label>
</div>


<div class="form-group">
	<label class="col-sm-3  control-label" for="param_name"><?php echo lang('name'); ?>:<span class="req">*</span></label>
	<div class="col-sm-9">
		<input class="form-control" name="name" value="<?php echo $info->name; ?>" id="param_name" _autocheck="true" type="text" />
		<span name="name_autocheck" class="autocheck"></span>
		<div name="name_error" class="clear error"></div>
	</div>
	<div class="clear"></div>
</div>

<div class="form-group">
	<label class="col-sm-3  control-label"><?php echo lang('status'); ?></label>
	<div class="col-sm-9">
		<label class="tcb-inline">
			<input name="status" value="1" type="checkbox" class="tc tc-switch tc-switch-7" <?php echo form_set_checkbox((int)$info->status, 1)?> />
			<span class="labels"></span>
		</label>


	</div>
	<div class="clear"></div>
</div>
<?php /* ?>
<div class="form-group">
	<label class="col-sm-3  control-label"><?php echo lang('layout'); ?>:</label>
	<div class="col-sm-9">
		<select name="layout[_]" class="form-control ">
			<option value=""></option>
			<?php foreach ($layouts as $k => $v): ?>
				<option value="<?php echo $k; ?>" 
					<?php echo form_set_select($k, (isset($info->layout['_'])) ? $info->layout['_'] : ''); ?>
				>
					<?php echo $v['name']; ?>
				</option>
			<?php endforeach; ?>
		</select>
		<div class="clear"></div>
		
		<?php if ( ! empty($site_methods)): ?>
			<div id="layout_method" class="pt5">
				<a href="#act" id="act_method_list"><?php echo lang('layout_method'); ?></a>
				<div class="clear pb5"></div>
				
				<div id="method_list" style="display:none;">
					<table cellpadding="0" cellspacing="0" width="100%">
					<?php foreach ($site_methods as $method): ?>
						<tr>
							<td><?php echo $method; ?></td>
							<td>
								<select name="layout[<?php echo $method; ?>]" style="min-width:150px;">
									<option value=""></option>
									<?php foreach ($layouts as $k => $v): ?>
										<option value="<?php echo $k; ?>" 
											<?php echo form_set_select($k, (isset($info->layout[$method])) ? $info->layout[$method] : ''); ?>
										>
											<?php echo $v['name']; ?>
										</option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
					<?php endforeach; ?>
					</table>
					<div class="formNote clear"><?php echo lang('note_layout_method'); ?></div>
				</div>
			</div>
		<?php endif; ?>
		
		<div name="layout_error" class="clear error"></div>
	</div>
	<div class="clear"></div>
</div>
<?php */ ?>
<div class="form-group">
	<label class="col-sm-3  control-label" for="param_sort_order"><?php echo lang('sort_order'); ?>:</label>
	<div class="col-sm-9">
		<input name="sort_order" class="form-control " value="<?php echo $info->sort_order; ?>" id="param_sort_order" _autocheck="true" type="text" />
		<span name="sort_order_autocheck" class="autocheck"></span>
		<div name="sort_order_error" class="clear error"></div>
	</div>
	<div class="clear"></div>
</div>
