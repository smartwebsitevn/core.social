
<!-- Common -->
<?php
$toolbar =array();
if(isset($url_translate)){
	$toolbar[]=array(
		'url' 	=> $url_translate,
		'title' => lang('translate'),
		'icon' => 'plus',
		'attr'=>array('class'=>'btn btn-danger'),
	);
}
echo macro()->page(array('toolbar' => $toolbar)); ?>

<!-- Js main -->
<?php $this->load->view('admin/setting/index/js', $this->data); ?>

<?php $_id = '_'.random_string('unique'); ?>
<!-- Content -->
<div class="portlet">
	<div class="portlet-heading bg-primary">
		<div class="portlet-title">
			<h4>
				<i class="fa fa-list-ul"></i>
				<?php echo lang('list'); ?> <?php echo lang('mod_setting'); ?>
			</h4>

		</div>
		<div class="portlet-widgets">
			<a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $_id; ?>"><i class="fa fa-chevron-down"></i></a>
			<span class="divider"></span>

		</div>
		<div class="clearfix"></div>
	</div>
	<div  id="<?php echo $_id; ?>" class="panel-collapse collapse in">
		<div class="portlet-body">
			<form class="form form-horizontal" id="form" method="post" action="<?php echo $action; ?>">
				<div class="tc-tabs">
				<ul class="nav nav-tabs tab-color-dark background-dark white">
					<li class="active"><a href="#tab_general" data-toggle="tab"><i class="fa fa-dashboard"></i> <?php echo  lang('tab_general') ?></a></li>
					<!--<li><a href="#tab_image" data-toggle="tab"><i class="fa fa-picture-o"></i>  <?php /*echo lang('tab_image')*/?></a></li>-->
					<li><a href="#tab_local" data-toggle="tab"><i class="fa fa-table"></i> <?php echo lang('tab_local') ?></a></li>
					<li><a href="#tab_server" data-toggle="tab"><i class="fa fa-desktop"></i> <?php echo lang('tab_server') ?></a></li>
					<li><a href="#tab_security" data-toggle="tab"><i class="fa fa-table"></i>  <?php echo  lang('tab_security') ?></a></li>
					<li><a href="#tab_connect" data-toggle="tab"><i class="fa fa-link"></i> <?php echo  lang('tab_connect') ?></a></li>
					<!--<li><a href="#tab_license" data-toggle="tab"><i class="fa fa-file-text-o"></i> <?php /*echo lang('tab_license') */?></a></li>-->
				</ul>
				<div class="tab-content">

					<div class="tab-pane active" id="tab_general">
                		<?php $this->load->view('admin/setting/index/general'); ?>
                	</div>

					<!--<div class="tab-pane" id="tab_image">
                		<?php /*$this->load->view('admin/setting/index/image');*/?>
                	</div>-->
					<div class="tab-pane" id="tab_local">
						<?php $this->load->view('admin/setting/index/local');?>
					</div>
					<div class="tab-pane" id="tab_server">
						<?php $this->load->view('admin/setting/index/server');?>
					</div>
					<div class="tab-pane" id="tab_security">
						<?php $this->load->view('admin/setting/index/security');?>
					</div>
					<div class="tab-pane" id="tab_connect">
						<?php $this->load->view('admin/setting/index/connect');?>
					</div>
					<!--<div class="tab-pane" id="tab_license">
						<?php /*$this->load->view('admin/setting/index/license');*/?>
					</div>-->
					<div class="form-actions">
						<div class="form-group formSubmit">
							<div class="col-sm-offset-2 col-sm-10">
								<input type="submit" value="<?php echo lang('button_update'); ?>" class="btn btn-primary" />
								<input type="reset" value="<?php echo lang('button_reset'); ?>" class="btn" />
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
				</div>

				</div>

			</form>
		</div>
	</div>
</div>
