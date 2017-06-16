<!-- Common -->
<?php echo macro()->page(array('toolbar' => array())); ?>

<!-- Main content wrapper -->
<div class="portlet">
	<div class="portlet-heading bg-primary">
		<div class="portlet-title">
			<h4>
				<i class="fa fa-pencil"></i>
				<?php echo lang('mod_module'); ?></h4>
		</div>
		<div class="portlet-widgets">
			<span class="divider"></span>
			<a data-parent="#accordion" data-toggle="collapse"><i class="fa fa-chevron-down"></i></a>
		</div>
	</div>
	<div class="panel-collapse collapse in" >
		<div class="portlet-body ">

		<form class="form  form-horizontal" id="form" action="<?php echo $action; ?>" method="post">
				<!-- Info -->
				<?php $this->load->view('admin/module/setting/info', $this->data); ?>

				<!-- Setting -->
				<?php /*
					if (count($setting_params))
					{
						if (isset($info->_view_setting))
						{
							module($info->key)->load->view($info->_view_setting, $this->data);
						}
						else
						{
							$this->load->view('admin/module/setting/setting', $this->data);
						}
					}
 					*/
				?>
			<div class="form-actions">
				<div class="form-group formSubmit">
					<div class="col-sm-offset-3 col-sm-10">
						<input type="submit" value="<?php echo lang('button_update'); ?>" class="btn btn-primary" />
						<input type="reset" value="<?php echo lang('button_reset'); ?>" class="btn" />
					</div>
				</div>
			</div>

	</form>
		</div>
	</div>
</div>
