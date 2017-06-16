<?php echo macro()->page(); ?>

<!-- Main content wrapper -->
<div class="portlet">
	<div class="portlet-heading bg-primary">
		<div class="portlet-title">
			<h4><i class="fa fa-pencil"></i>  <?php echo lang('mod_widget'); ?></h4>
		</div>

	</div>
	<div class="portlet-body ">

		<!-- Form -->
		<form class="form form-horizontal" id="form" method="post" action="<?php echo $action; ?>">

			<!-- Info -->
			<?php $this->load->view('admin/widget/setting/info', $this->data); ?>

			<!-- Setting -->
			<?php
			if (count($setting_params))
			{
				if (isset($info->_view_setting))
				{
					module($info->key)->load->view($info->_view_setting, $this->data);
				}
				else
				{
					$this->load->view('admin/widget/setting/setting', $this->data);
				}
			}
			?>


			<div class="form-actions">
				<div class="form-group formSubmit">
					<div class="col-sm-offset-2 col-sm-10">
						<input type="submit" value="<?php echo  lang('button_update'); ?>" class="btn btn-primary" />
						<input type="reset" value="<?php echo lang('button_reset'); ?>" class="btn"/>
					</div>
				</div>
			</div>

			<div class="clear"></div>
		</form>
	</div>
</div>
