<?php echo macro()->page(); ?>
<!-- Main content wrapper -->
<div class="portlet">
	<div class="portlet-heading bg-primary">
		<div class="portlet-title">
			<h4><i class="fa fa-pencil"></i>  <?php echo lang('mod_'.$class); ?></h4>
		</div>

	</div>
	<div class="portlet-body ">
		<form class="form form-horizontal" id="form" method="post" action="<?php echo $action; ?>">
			<div class="form-group param_text  ">
				<label class="col-sm-3  control-label " for="_9f462453d954dc9fc492f1155399334c">
					<?php echo lang("no")?>:
					<span class="req">*</span>
				</label>
				<div class="col-sm-9">
					<input type="text" class="form-control " id="_9f462453d954dc9fc492f1155399334c" name="no" value="<?php echo isset($info->no) ? $info->no : '' ?>" />
					<div name="no_error" class="error help-block"></div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group param_text  ">
				<label class="col-sm-3  control-label " for="_9f462453d954dc9fc492f1155399334c">
					<?php echo lang("status")?>:
				</label>

				<div class="col-sm-9">
					<select type="text" class="form-control " id="_9f462453d954dc9fc492f1155399334c" name="status">
						<option value=""><?php echo lang('default') ?></option>
						<?php foreach(mod('tracking')->config('status') as $key => $row){ ?>
							<option value="<?php echo $key ?>"<?php if(isset($info->status) && $info->status == $key){?> selected<?php }?>><?php echo lang('tracking_'.$row) ?></option>
						<?php } ?>
					</select>
					<div name="status_error" class="error help-block"></div>
					<p class="text-muted" style="font-style: italic; font-size: 11px;"><?php echo lang("status_notice")?></p>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group param_text  ">
				<label class="col-sm-3  control-label " for="_9f462453d954dc9fc492f1155399334c">
					<?php echo lang("customer")?>:
				</label>
				<div class="col-sm-9">
					<input type="text" data-autocomplate="<?php echo admin_url($class.'/getaddress') ?>" class="form-control " id="_9f462453d954dc9fc492f1155399334c" name="customer" value="<?php echo isset($info->customer) ? $info->customer : '' ?>"/>
					<div name="customer_error" class="error help-block"></div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group param_text  ">
				<label class="col-sm-3  control-label " for="_9f462453d954dc9fc492f1155399334c">
					<?php echo lang("content")?>:
				</label>
				<div class="col-sm-9">
					<textarea type="text" class="form-control " id="_9f462453d954dc9fc492f1155399334c" name="content"><?php echo isset($info->content) ? $info->content : '' ?></textarea>
					<div name="content_error" class="error help-block"></div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group param_date  ">
				<label class="col-sm-3  control-label " for="_686a18d6d5f869f158216c252368615d">
					<?php echo lang("created")?>:
				</label>
				<div class="col-sm-9">
					<input name="created" value="<?php echo isset($info->created) ? get_date($info->created, 'time') : get_date('', 'time') ?>"
						   id="_686a18d6d5f869f158216c252368615d" class="datetime_picker mask_datess" style="width:150px;"
						   type="text"
						   id="_686a18d6d5f869f158216c252368615d"                                />
					<div name="created_error" class="error help-block"></div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group param_date  ">
				<label class="col-sm-3  control-label " for="_686a18d6d5f869f158216c252368615d">
					<?php echo lang("delivery")?>:
				</label>
				<div class="col-sm-9">
					<input name="delivery" value="<?php echo isset($info->delivery) && $info->delivery ? get_date($info->delivery, 'time') : '' ?>"
						   id="_686a18d6d5f869f158216c252368615d" class="datetime_picker mask_datess" style="width:150px;"
						   type="text"
						   id="_686a18d6d5f869f158216c252368615d"                                />
					<div name="delivery_error" class="error help-block"></div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group param_text  ">
				<label class="col-sm-3  control-label ">
					<?php echo lang("address_from")?>:
				</label>
				<div class="col-sm-9">
					<input type="text" data-autocomplate="<?php echo admin_url($class.'/getaddress') ?>" class="form-control " name="address_from" value="<?php echo isset($info->address_from) ? $info->address_from : '' ?>"/>
					<div name="address_from_error" class="error help-block"></div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="form-group param_text  ">
				<label class="col-sm-3  control-label " for="_9f462453d954dc9fc492f1155399334c">
					<?php echo lang("address_to")?>:
				</label>
				<div class="col-sm-9">
					<input type="text" data-autocomplate="<?php echo admin_url($class.'/getaddress') ?>" class="form-control " name="address_to" value="<?php echo isset($info->address_to) ? $info->address_to : '' ?>"/>
					<div name="address_to_error" class="error help-block"></div>
				</div>
				<div class="clearfix"></div>
			</div>


			<table class="table table-border table-tracking">
				<tr>
					<th>STT</th>
					<th><?php echo lang("from")?></th>
					<th><?php echo lang("to")?></th>
					<th><?php echo lang("content")?></th>
					<th><?php echo lang("vehicle")?></th>
					<th><?php echo lang("reference")?></th>
					<th><?php echo lang("status")?></th>
					<th><?php echo lang("time_start")?></th>
					<th><?php echo lang("time_end")?></th>
				</tr>
				<?php if(isset($info->data) && $info->data){
					$i =1 ;
					foreach($info->data as $row){?>
				<tr class="table-clone">
					<td><?php echo $i; ?></td>
					<td><input data-autocomplate="<?php echo admin_url($class.'/getaddress') ?>" type="text" class="form-control" name="tracking_from[]" class="required" value="<?php echo $row->tracking_from ?>" /></td>
					<td><input data-autocomplate="<?php echo admin_url($class.'/getaddress') ?>" type="text" class="form-control" name="tracking_to[]" class="required"  value="<?php echo $row->tracking_to ?>"/></td>
					<td><textarea style="height: 34px" class="form-control" name="tracking_content[]"><?php echo $row->tracking_content ?></textarea></td>
					<td>
						<select class="form-control" name="tracking_vehicle[]">
							<?php foreach(mod('tracking')->config('vehicle') as $key => $val){ ?>
								<option value="<?php echo $key ?>" <?php if($row->tracking_vehicle == $key){?> selected<?php }?>><?php echo lang('tracking_'.$val) ?></option>
							<?php } ?>
						</select>
					</td>
					<td><input type="text" class="form-control" name="tracking_reference[]"  value="<?php echo $row->tracking_reference ?>"/></td>
					<td>
						<select class="form-control" name="tracking_status[]">
							<option value=""><?php echo lang('default') ?></option>
							<?php foreach(mod('tracking')->config('status') as $key => $val){ ?>
								<option value="<?php echo $key ?>" <?php if($row->tracking_status == $key){?> selected<?php }?>><?php echo lang('tracking_'.$val) ?></option>
							<?php } ?>
						</select>
					</td>
					<td><input type="text" class="form-control datetime_picker mask_datess" name="tracking_timestart[]" value="<?php echo isset($row->tracking_timestart) && $row->tracking_timestart ? get_date($row->tracking_timestart, 'time') : '' ?>" /></td>
					<td><input type="text" class="form-control datetime_picker mask_datess" name="tracking_timeend[]" value="<?php echo isset($row->tracking_timeend) && $row->tracking_timeend ? get_date($row->tracking_timeend, 'time') : '' ?>" /></td>
				</tr>
				<?php $i++;}
				} else { ?>
					<tr class="table-clone">
						<td>1</td>
						<td><input data-autocomplate="<?php echo admin_url($class.'/getaddress') ?>" type="text" class="form-control" name="tracking_from[]" class="required" /></td>
						<td><input data-autocomplate="<?php echo admin_url($class.'/getaddress') ?>" type="text" class="form-control" name="tracking_to[]" class="required" /></td>
						<td><textarea style="height: 34px" class="form-control" name="tracking_content[]"></textarea></td>
						<td>
							<select class="form-control" name="tracking_vehicle[]">
								<?php foreach(mod('tracking')->config('vehicle') as $key => $row){ ?>
									<option value="<?php echo $key ?>"><?php echo lang('tracking_'.$row) ?></option>
								<?php } ?>
							</select>
						</td>
						<td><input type="text" class="form-control" name="tracking_reference[]" /></td>
						<td>
							<select class="form-control" name="tracking_status[]">
								<option value=""><?php echo lang('default') ?></option>
								<?php foreach(mod('tracking')->config('status') as $key => $row){ ?>
									<option value="<?php echo $key ?>"><?php echo lang('tracking_'.$row) ?></option>
								<?php } ?>
							</select>
						</td>
						<td><input type="text" class="form-control datetime_picker mask_datess" name="tracking_timestart[]" value="<?php echo get_date('', 'time') ?>" /></td>
						<td><input type="text" class="form-control datetime_picker mask_datess" name="tracking_timeend[]" value="<?php echo get_date('', 'time') ?>" /></td>
					</tr>
				<?php } ?>
				<tr class="btn-add">
					<td colspan="9"><a href="#" class="btn-add-tr"> Thêm </a></td>
				</tr>
			</table>
					<div class="form-actions">
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-9">
								<input type="submit" value="<?php echo  lang('button_update'); ?>" class="btn btn-primary" />
								<input type="reset" value="<?php echo lang('button_reset'); ?>" class="btn btn-inverse"/>

							</div>
						</div>
					</div>

		</form>
	</div>
</div>

<script>
	$(document).ready(function (){
		$('.table-tracking .btn-add-tr').click(function (){
			var tr = $('.table-tracking .table-clone:first').clone();
			tr.removeClass('table-clone');
			tr.find('input:not(.datetime_picker), textarea').val('');
			tr.find('td:first').text($('.table-tracking tr').length - 1);
			tr.insertBefore($('.table-tracking .btn-add'));
			start();
			// kick hoat datetime
			return false;
		});
		start();
	})
	function start() {
		$('[data-autocomplate]').each(function (){
			$(this).autocomplete({
				source: $(this).data('autocomplate'),
				select: function (a, b) {
					$(this).text(b.item.name);

				}
			});
		})

		$('.datetime_picker').datetimepicker({
			//language:  'fr',
			weekStart: 1,
			todayBtn: 1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			forceParse: 0,
			showMeridian: 1,
			format: 'dd-mm-yyyy h:i'
		});
	}

</script>