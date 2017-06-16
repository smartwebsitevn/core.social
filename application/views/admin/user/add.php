<?php echo macro()->page(); ?>
<!-- Main content wrapper -->
<div class="portlet">
	<div class="portlet-heading bg-primary">
		<div class="portlet-title">
			<h4><i class="fa fa-pencil"></i>  <?php echo lang('mod_user'); ?></h4>
		</div>

	</div>
	<div class="portlet-body ">
		<form class="form form-horizontal" id="form" method="post" action="<?php echo $action; ?>">
		<div class="tc-tabs"><!-- Nav tabs style 1 -->
			<ul class="nav nav-tabs  tab-color-dark background-dark white">
				<li class="active"><a href="#user_account" data-toggle="tab"><i class="fa fa-user bigger-130"> </i>Thông tin tài khoản</a></li>
				<li><a href="#user_info" data-toggle="tab"><i class="fa fa-info bigger-130"> </i>Thông tin cá nhân</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="user_account">
					<?php $this->load->view('admin/user/form/account', $this->data); ?>

				</div>
				<div class="tab-pane " id="user_info">
					<?php $this->load->view('admin/user/form/info', $this->data); ?>

				</div>

				<div class="form-actions">
					<div class="form-group">
						<div class="col-sm-offset-3 col-sm-9">
							<input type="submit" value="<?php echo  lang('button_update'); ?>" class="btn btn-primary" />
							<input type="reset" value="<?php echo lang('button_reset'); ?>" class="btn btn-inverse"/>

						</div>
					</div>
				</div>
			</div>
		</div><!--nav-tabs style 1-->

	</form>
	</div>
</div>