<script type="text/javascript">
(function($)
{
	$(document).ready(function()
	{
		var main = $('form.form');

		// Chon tat ca permissions
		$('table th input.act_select_all').on('click' , function(){
			var checkboxes =main.find(':checkbox').not($(this));
			checkboxes.prop('checked',$(this).is(':checked'));

		});

		$('table  input.act_select_module_group').on('click' , function(){
			var checkboxes =main.find('tr.data_module_'+$(this).data('group')+' :checkbox').not($(this));
			checkboxes.prop('checked',$(this).is(':checked'));

		});

		$('table  input.act_select_module').on('click' , function(){
			var checkboxes = $(this).closest( "tr").find(' :checkbox').not($(this));
			checkboxes.prop('checked',$(this).is(':checked'));

		});

	});
})(jQuery);
</script>

<?php

     $_data = function($data )
    	{
          $info = $data['info'];
		  ob_start();?>
			<div name="permissions_error" class=" error"></div>
			<table class="table table-bordered table-striped table-hover tc-table "	>
			<thead>
			<tr>
					<th class="col-small center"><label><input type="checkbox" class="tc act_select_all"><span class="labels"></span></label></th>
					<th class="col-4">Module</th>
					<th class="col-8" ><?php echo lang('permissions') ?></th>

			</tr>
			</thead>

			<tbody>
			<?php //pr($data);
			foreach ($data['permissions_groups'] as $group => $items):
				if(!$items /*|| in_array($group,array("home"))*/) 	continue;  ?>
					<tr class="bg-primary">
						<td class="col-small center">
							<label>
								<input  class="tc act_select_module_group" data-group="<?php  echo $group?>" type="checkbox"  />
								<span class="labels"></span>
							</label>
						</td>
						<td colspan="10"><?php echo lang('group_'.$group) ?></td></tr>
				   <?php foreach ($data['permissions'] as $c => $ps):
				    	if(in_array($c, $items)):	?>
						<tr class="data_module data_module_<?php echo $group ?>" >
							<td class="col-small center">
								<label>
									<input  class="tc act_select_module" type="checkbox" value="<?php echo $c; ?>"
									<?php if (isset($info->permissions[$c]) && count($info->permissions[$c]) == count($ps)) echo 'checked="checked"'; ?>
									/>
								<span class="labels"></span>
								</label>
							</td>
							<td>
									<?php echo $data['controllers_name'][$c]; ?>
							</td>
							<td>
								<?php foreach ($ps as $p => $p_i): ?>
								  <div class="tcb mr20" style="display: inline">
										<input type="checkbox"  class="tc" name="permissions[<?php echo $c; ?>][]" value="<?php echo $p; ?>"
											<?php if (isset($info->permissions[$c]) && in_array($p, $info->permissions[$c])) echo 'checked="checked"'; ?>
											/>
									  <span class="labels">
										<?php
										if ($p_i['name']) echo $p_i['name'];
										elseif (isset($this->lang->language["permissions_{$p}"])) echo lang("permissions_{$p}");
										else echo ucfirst($p);
										?>
										  </span>
									  </div>

								<?php endforeach; ?>
							</td>

						</tr>
							<?php //else: ?>
							<?php //echo 'a='.$c;pr($ps,0); ?>
					<?php endif; ?>
						<?php endforeach; ?>
			<?php endforeach; ?>
			</tbody>
			</table>
			<div name="permissions_error" class=" error"></div>
			<div class="clear"></div>
		<?php return ob_get_clean();
	};
	$info =isset($info) ? (array) $info : null;

     $_macro = $this->data;
	$_macro['form']['data'] =$info;


   $_macro['form']['rows'][] = array(
		'param' => 'name',
		'req' 	=> true,
	);
	$_macro['form']['rows'][] = array(
		'param' => 'level','type'=>'select',
		'value'=>$info['level'],'values_single'=>$levels,
		'req' 	=> true,
	);
$_macro['form']['rows'][] = array(
	'param' => 'sort_order',
	'req' 	=> true,
);
    	$_macro['form']['rows'][] = array(
		'type' 	=> 'ob',
        'value'=> $_data($this->data),
	);

	echo macro()->page($_macro);

