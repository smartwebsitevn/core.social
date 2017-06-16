<?php
$_macro = $this->data;
/*$_macro['toolbar_sub'] = array(
    array('url' => admin_url('product'), 'title' => lang('mod_product'),'attr'=>array('class'=>'active')),
    array('url' => admin_url('product_link'), 'title' => lang('mod_product_link'), )
);*/
//$_macro['toolbar_sub'] = $this->_toolbar;
echo macro()->page($_macro);
?>
	<!--<style type="text/css">
        .form-group {
            border-bottom: 1px solid #f1f1f1;
            margin-bottom: 15px;
            padding: 10px 0 20px;
        }
    </style>-->
	<!-- Main content wrapper -->
	<div class="portlet">
		<div class="portlet-heading bg-primary">
			<div class="portlet-title">
				<h4><i class="fa fa-pencil"></i> <?php echo lang('mod_product'); ?></h4>
			</div>

		</div>
		<div class="portlet-body ">

			<!-- Form -->
			<form class="form form-horizontal" id="form" method="post" action="<?php echo $action; ?>">
				<div class="tc-tabs">
					<ul class="nav nav-tabs tab-color-dark background-dark white">
						<li class="active"><a href="#tab_general" data-toggle="tab"><i class="fa fa-dashboard"></i> <?php echo lang('tab_general') ?></a></li>
						<li><a href="#tab_detail" data-toggle="tab"><i class="fa fa-table"></i> <?php echo lang('tab_detail') ?></a></li>
						<?php /* ?>
						<li><a href="#tab_to_attribute" data-toggle="tab"><i class="fa fa-table"></i> <?php echo lang('tab_to_attribute') ?></a></li>
 						<li><a href="#tab_to_option" data-toggle="tab"><i class="fa fa-table"></i> <?php echo lang('tab_to_option') ?></a></li>
						<?php */ ?>
						<li><a href="#tab_to_discount" data-toggle="tab"><i class="fa fa-table"></i> <?php echo lang('tab_to_discount') ?></a></li>
						<li><a href="#tab_to_special" data-toggle="tab"><i class="fa fa-table"></i> <?php echo lang('tab_to_special') ?></a></li>
						<li><a href="#tab_to_addon" data-toggle="tab"><i class="fa fa-table"></i> <?php echo lang('tab_to_addon') ?></a></li>

						<?php /* ?>

						<li><a href="#tab_advance" data-toggle="tab"><i class="fa fa-table"></i> <?php echo lang('tab_advance') ?></a></li>
						<?php */ ?>

					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_general"><?php t('view')->load('tpl::product/form/general'); ?></div>
						<div class="tab-pane" id="tab_detail"><?php t('view')->load('tpl::product/form/detail'); ?></div>
						<?php /* ?>
						<div class="tab-pane" id="tab_to_attribute"><?php //t('view')->load('tpl::product/form/to_attribute'); ?></div>

					 <div class="tab-pane" id="tab_to_option"><?php t('view')->load('tpl::product/form/to_option'); ?></div>
<?php */ ?>
 						<div class="tab-pane" id="tab_to_discount"><?php t('view')->load('tpl::product/form/to_discount'); ?></div>
						<div class="tab-pane" id="tab_to_special"><?php t('view')->load('tpl::product/form/to_special'); ?></div>
						<div class="tab-pane" id="tab_to_addon"><?php t('view')->load('tpl::product/form/to_addon'); ?></div>

						<?php /* ?>
						<div class="tab-pane" id="tab_advance"><?php t('view')->load('tpl::product/form/advance'); ?></div>
						<?php */ ?>

						<div class="form-actions">
							<div class="form-group formSubmit">
								<div class="col-sm-offset-2 col-sm-10">
									<input type="submit" name="submit_back" value="<?php echo lang('button_update'); ?>"
										   class="btn btn-primary"/>
									<input value="<?php echo lang("button_reset") ?>" class="btn" type="reset">
								</div>
							</div>
						</div>
						<div class="clearfix"></div>
					</div>

				</div>


				<div class="clear"></div>
			</form>
		</div>
	</div>

<?php t('view')->load('tpl::product/_js') ?>