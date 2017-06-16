<?php
/**
 * Page
 */
$this->register('page', function(array $input = array()){
	ob_start(); ?>

<?php 

	$mod 	= array_get($input, 'mod', t('uri')->rsegment(1));
	$act 	= array_get($input, 'act', t('uri')->rsegment(2));
   //	$breadcrumbs 	= array_get($input, 'breadcrumbs');
   	$breadcrumbs =array();
	$message 		= array_get($input, 'message', get_message());
	$contents 		= array_get($input, 'contents');

	$toolbar = array_get($input, 'toolbar', array(
				array(
					'url' 	=> admin_url($mod.'/add'),
					'title' => lang('add'),
					'icon' => 'plus',
					'attr'=>array('class'=>'btn btn-danger'),
				),
				array(
					'url' 	=> admin_url($mod),
					'title' => lang('list'),
					'icon' => 'list',
					'attr'=>array('class'=>'btn btn-primary'),
				),
			));

	$toolbar_addon = array_get($input, 'toolbar_addon',array());
	$toolbar =array_merge($toolbar_addon,$toolbar);

	$toolbar_sub = array_get($input, 'toolbar_sub','');
	$form = array_get($input, 'form');
	if ( ! empty($form))
	{
		$form['title'] = array_get($form, 'title', lang($act).' '.lang('mod_'.$mod));
	}

	$form_translate = array_get($input, 'form_translate');
	
	$table = array_get($input, 'table');
	if ( ! empty($table))
	{
		$table['title'] = array_get($table, 'title', lang('list').' '.lang('mod_'.$mod));
	}
	
?>

<!-- BEGIN PAGE HEADING ROW -->
		<div class="row">
			<div class="col-lg-12">
				<!-- BEGIN BREADCRUMB -->
				<div class="breadcrumbs">
					<!-- Breadcrumbs -->
					<?php if ( ! empty($breadcrumbs)): //pr($breadcrumbs);
						t('widget')->admin->breadcrumbs($breadcrumbs); ?>
					<?php else:?>
						<ul class="breadcrumb">
							<li>
								<a href="<?php echo admin_url()?>">Home</a>
							</li>
							<li><a href="<?php echo admin_url($mod)?>"><?php echo lang('mod_'.$mod)?></a></li>
                            <?php if($act != 'index'):?>
							<li class="active"><?php echo lang($act)?></li>
                            <?php endif;?>
						</ul>
					<?php endif;?>
					
				</div>
				<!-- END BREADCRUMB -->
				<div class="page-header title">
					<h1><?php echo lang('mod_' . $mod); ?>
						<?php /* ?>
						<span class="sub-title"> <?php echo lang($mod.'_info')?></span>
						<?php */ ?>

					</h1>
					<?php if (!empty($toolbar)): ?>
						<?php echo macro()->toolbar($toolbar); ?>
					<?php endif; ?>
					<div class="clearfix"></div>
				</div>
				<?php // echo macro()->page_title(lang('mod_'.$mod), lang($mod.'_info')); ?>
				<?php echo macro()->page_setting(); ?>
				<?php if ( ! empty($toolbar_sub)): ?>
				<?php echo macro()->toolbar_sub($toolbar_sub); ?>
				<?php endif;?>


			</div><!-- /.col-lg-12 -->
		</div><!-- /.row -->
		<!-- END PAGE HEADING ROW -->

	<!-- Message -->
	<?php if ( ! empty($message)):?>
		<?php t('widget')->admin->message($message); ?>
	<?php endif; ?>

	<!-- Content -->
	<div class="row">
		<div class="col-lg-12">
		
		<?php if ( ! empty($form)) echo macro('mr::form')->form($form); ?>
		
		<?php if ( ! empty($form_translate)) echo macro('mr::form')->translate($form_translate); ?>
		
		<?php if ( ! empty($table)) echo macro('mr::table')->table($table); ?>
		
		<?php echo $contents; ?>
		</div>
	</div>

<?php return ob_get_clean(); });


/**
 * Page title
 */
$this->register('page_title', function($title, $desc){ ob_start(); ?>
<!-- PAGE TITLE ROW -->
<div class="page-header title">
		<h1><?php echo $title?> <span class="sub-title"><?php echo $desc?></span></h1>								
</div>
<?php return ob_get_clean(); });


/**
 * Toolbar
 */
$this->register('toolbar', function(array $list){ ob_start(); ?>
	<ul>
		<?php foreach ($list as $item):

			$attr 	= array_get($item, 'attr', array());
			?>
			<li><a href="<?php echo $item['url']; ?>"  <?php echo t('html')->attr($attr); ?>>
					<?php if(isset($item['icon'])):?>
						<i class="fa fa-<?php echo $item['icon']?>"></i>
					<?php endif;?>
					<?php echo t('html')->span($item['title']); ?>
				</a></li>
		<?php endforeach; ?>
	</ul>
	<?php return ob_get_clean(); });$this->register('toolbar', function (array $list) {
	ob_start(); ?>
	<div class="pull-right">
		<?php foreach ($list as $item):

			$attr = array_get($item, 'attr', array('class' => 'btn btn-primary'));
			?>
			<a href="<?php echo $item['url']; ?>" <?php echo t('html')->attr($attr); ?>>
				<?php if (isset($item['icon'])): ?>
					<i class="fa fa-<?php echo $item['icon'] ?>"></i>
				<?php endif; ?>
				<?php echo t('html')->span($item['title']); ?>
			</a>
		<?php endforeach; ?>
	</div>
	<div class="clearfix"></div>
	<?php return ob_get_clean();
});/**
 * Toolbar sub
 */
$this->register('toolbar_sub', function(array $list){ ob_start(); //pr($list); ?>


	<ul class="nav nav-tabs">
		<?php foreach ($list as $item):

			$attr 	= array_get($item, 'attr', array());
			?>
			<li <?php echo t('html')->attr($attr); ?> ><a  href="<?php echo $item['url']; ?>" >
					<?php if(isset($item['icon'])):?>
						<i class="fa fa-<?php echo $item['icon']?>"></i>
					<?php endif;?>
					<?php echo t('html')->span($item['title']); ?>
				</a></li>
		<?php endforeach; ?>
	</ul>

	<?php return ob_get_clean(); });
/**
 * Toolbar
 */
$this->register('page_setting', function(){ ob_start(); ?>

<?php ?>
				<!-- /#ek-layout-button -->	
				<div class="qs-layout-menu">
					<div class="btn btn-gray qs-setting-btn" id="qs-setting-btn">
						<i class="fa fa-cog bigger-150 icon-only"></i>
					</div>
					<div class="qs-setting-box" id="qs-setting-box">
					
						<div class="hidden-xs hidden-sm">
							<span class="bigger-120">Layout Options</span>
							
							<div class="hr hr-dotted hr-8"></div>
							<label>
								<input type="checkbox" class="tc" id="fixed-navbar" />
									<span id="#fixed-navbar" class="labels"> Fixed NavBar</span>
							</label>
							<label>
								<input type="checkbox" class="tc" id="fixed-sidebar" />
									<span id="#fixed-sidebar" class="labels"> Fixed NavBar+SideBar</span>
							</label>
							<label>
								<input type="checkbox" class="tc" id="sidebar-toggle" />
									<span id="#sidebar-toggle" class="labels"> Sidebar Toggle</span>
							</label>
							<label>
								<input type="checkbox" class="tc" id="in-container" />
									<span id="#in-container" class="labels"> Inside<strong>.container</strong></span>
							</label>
						
							<div class="space-4"></div>
						</div>
						
						<span class="bigger-120">Color Options</span>
						
						<div class="hr hr-dotted hr-8"></div>
						
						<label>
								<input type="checkbox" class="tc" id="side-bar-color" />
								<span id="#side-bar-color" class="labels"> SideBar (Light)</span>
						</label>
						
						<ul>									
							<li><button class="btn" style="background-color:#d15050;" onclick="swapStyle('<?php echo public_url(); ?>/admin/ekoders/css/themes/style.css')"></button></li>
							<li><button class="btn" style="background-color:#86618f;" onclick="swapStyle('<?php echo public_url(); ?>/admin/ekoders/css/themes/style-1.css')"></button></li> 
							<li><button class="btn" style="background-color:#ba5d32;" onclick="swapStyle('<?php echo public_url(); ?>/admin/ekoders/css/themes/style-2.css')"></button></li>
							<li><button class="btn" style="background-color:#488075;" onclick="swapStyle('<?php echo public_url(); ?>/admin/ekoders/css/themes/style-3.css')"></button></li>
							<li><button class="btn" style="background-color:#4e72c2;" onclick="swapStyle('<?php echo public_url(); ?>/admin/ekoders/css/themes/style-4.css')"></button></li>
						</ul>
							
					</div>
				</div>
				<!-- /#ek-layout-button -->			
				<?php ?>
<?php return ob_get_clean(); });

/**
 * Status
 */
$this->register('status_color', function($status,$text=''){ ob_start(); ?>

	<?php
	$statuss = array(
					'on', 'off', 'no', 'yes', // common
					'success', 'pending', 'failed', 'canceled','fraude',// tran
					'paid', 'unpaid', 'canceled','overdue', 'partial','draft', // invoice
					'completed', 'pending', 'canceled','processing', 'failed', 'expired','refunded', 'chargeback',	// order
					'active', 'inactive', 'canceled','suspended', 'restored', 'deleted','expired','refunded', // service
		);
	if(!$text)
		$text = in_array($status, $statuss)	? $status : 'status_'.$status;
	?>

	<span class="label label-<?php echo $status; ?>">
		<?php echo lang($text); ?>
	</span>

<?php return ob_get_clean(); });


/**
 * Hien thi thong tin
 */
$this->register('info', function(array $list){ ob_start(); ?>

	<table class="table table-bordered table-striped table-hover tc-table">
		<tbody>

		<?php foreach ($list as $key => $value): ?>

			<tr>

				<?php if ( ! is_string($key) && is_string($value)): ?>

					<td colspan="2"><?php echo $value; ?></td>

				<?php else: ?>

					<td  class="row_label"><b><?php echo $key; ?></b></td>
					<td  class="row_item"><?php echo $value; ?></td>

				<?php endif ?>

			</tr>

		<?php endforeach ?>

		</tbody>
	</table>

<?php return ob_get_clean(); });



/**
 * Modal
 */
$this->register('modal_start', function ($input = array()) {
	ob_start();
	$modal_id = array_get($input, 'id',random_string());
	$modal_class = array_get($input, 'class','');
	$modal_name = array_get($input, 'name');
	?>
	<div id="<?php echo $modal_id ?>" class="modal fade <?php echo $modal_class ?>" tabindex="-1" role="dialog"
	>
	<div role="document" class="modal-dialog">
	<div class="modal-content">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 id="myModalLabel" class="modal-title"><?php echo $modal_name ?></h4>
	</div>
	<div class="modal-body">

	<?php return ob_get_clean();
});

$this->register('modal_end', function () {
	ob_start(); ?>
	</div>
	</div>
	</div>
	</div>

	<?php return ob_get_clean();
});

/**
 * navbar_collapse
 */
$this->register('navbar_collapse_start', function ($input = array()) {
	ob_start();
	$modal_id = array_get($input, 'id',random_string());
	$modal_class = array_get($input, 'class','');
	$modal_name = array_get($input, 'name');
	?>
	<nav class="navbar-menu" class=" <?php echo $modal_class ?>" >
	<div class="navbar-header">
		<button data-target="#<?php echo $modal_id ?>" data-toggle="collapse" class="navbar-toggle" type="button">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
	</div>
	<div id="<?php echo $modal_id ?>"  class="collapse navbar-collapse">

	<?php return ob_get_clean();
});

$this->register('navbar_collapse_end', function () {
	ob_start(); ?>
	</div>
	</nav>

	<?php return ob_get_clean();
});




$this->register('active_status_public', function($status,$public){ ob_start();
	$class= 'text-muted';
	$public_l = $status_l = 'warning';
	$class_pub = 'text-danger';
	if($status) {
		$class = 'text-success';
		$status_l = 'success';
	}
	if($public){
		$class_pub = 'text-info';
		$public_l = 'success';
	}
	?>

	<p class="<?php echo $class; ?>">
		<?php echo lang('status_'.$status_l); ?>
	</p>
	<p class="<?php echo $class_pub; ?>" style="font-size: 12px">
		<?php echo lang('public_'.$public_l); ?>
	</p>
	<?php return ob_get_clean(); });
