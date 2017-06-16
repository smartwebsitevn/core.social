<?php

/**
 * Page
 */
$this->register('page', function(array $input = array()){ ob_start(); ?>
	<?php
	$mod 	= array_get($input, 'mod', t('uri')->rsegment(1));
	$act 	= array_get($input, 'act', t('uri')->rsegment(2));
	$title 		=array_get($input, 'title', lang($act).' '.lang('mod_'.$mod));
	$content 		= array_get($input, 'content','');
	$toolbar = array_get($input, 'toolbar');

	$form = array_get($input, 'form');
	$table = array_get($input, 'table');
	?>

	<?php //echo macro()->page_wrap_start();	?>
	<?php echo macro()->page_heading($title); ?>
	<?php if ( ! empty($toolbar)) echo macro()->page_toolbar($toolbar); ?>
	<?php echo macro()->page_body_start(); ?>
	<?php if ( ! empty($form)) echo macro('mr::form')->form($form); ?>
	<?php if ( ! empty($table)) echo macro('mr::table')->table($table); ?>
	<?php echo $content; ?>
	<?php echo macro()->page_body_end(); ?>
	<?php// echo macro()->page_wrap_end(); ?>
	<?php return ob_get_clean(); });


/**
 * Page title
 */
$this->register('page_heading', function($title, $attr = array(), $args = array()){ ob_start();
	$attr = array_merge(array('class' => 'panel panel-default'), $attr);
	//$fa		= array_get($args, 'fa', 'info');

	?>
	<div id="page" <?php echo t('html')->attr($attr )?>>
	<div class="page-heading panel-heading">
		<h1 class="panel-title"><?php echo $title ?></h1>
	</div>
	<?php return ob_get_clean(); });



/**
 * Page Content
 */
$this->register('page_body_start', function(array $attr = array()){ ob_start();
	$attr = array_merge(array('class' => 'page-body panel-body'), $attr);
	?>
	<div <?php echo t('html')->attr($attr )?>>
	<?php return ob_get_clean(); });


$this->register('page_body_end', function(){ ob_start(); ?>
	<div class="clearfix"></div>
	</div>
	</div>
	<?php return ob_get_clean(); });






/**
 * Hien thi thong tin
 */
$this->register('info', function(array $list){ ob_start(); ?>

	<table class="table table-bordered table-hover">
		<tbody>

		<?php foreach ($list as $key => $value): ?>

			<tr>

				<?php if ( ! is_string($key) && is_string($value)): ?>

					<td colspan="2"><?php echo $value; ?></td>

				<?php else: ?>

					<td><b><?php echo $key; ?></b></td>
					<td><?php echo $value; ?></td>

				<?php endif ?>

			</tr>

		<?php endforeach ?>

		</tbody>
	</table>

<?php return ob_get_clean(); });


/**
 * Status
 */
$this->register('status_color', function($status, $label = null){ ob_start(); ?>

	<span class="label label-primary label-<?php echo $status; ?>">
		<?php echo $label ?: lang('status_'.$status); ?>
	</span>

	<?php return ob_get_clean(); });



/**
 * More_block
 */
$this->register('more_block', function($content, $height = 200){ ob_start(); ?>
	<div class="more_block" <?php echo $height?'data-height="'.$height.'"':'' ?>>
		<div class="more_block_content">
			<?php echo $content ?>
		</div>
		<div class="mt5">
			<a href="javascript:void(0)" class="act_block_all">+ <?php echo lang("view_more") ?> >></a>
			<a href="javascript:void(0)" class="act_block_short"><?php echo lang("view_less") ?> <<</a>
		</div>
	</div>
	<?php return ob_get_clean(); });

/**
 * More_list
 */
$this->register('more_list', function($content,$num=5, $item = ".item"){ ob_start(); ?>
	<div class="more_list" <?php echo $item?'data-item="'.$item.'"':'' ?>   <?php echo $num?'data-num="'.$num.'"':'' ?>   >
		<div class="more_block_list">
			<?php echo $content ?>
		</div>
		<div class="mt5">
			<a href="javascript:void(0)" class="act_list_all">+ <?php echo lang("view_more") ?> >></a>
			<a href="javascript:void(0)" class="act_list_short"><?php echo lang("view_less") ?> <<</a>
		</div>
	</div>
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
	?>
	<nav class="navbar-menu" class=" <?php echo $modal_class ?>" >
	<div class="navbar-header">
		<button data-target="#<?php echo $modal_id ?>" data-toggle="collapse" class="navbar-toggle " type="button">
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
$this->register('filter_dropdown_category', function ($input) {
	$obj = array_get($input, 'obj');
	$param = array_get($input, 'param');
	$value = array_get($input, 'value', '');
	$values = array_get($input, 'values', []);
	$name = array_get($input, 'name',lang($param));


	//== holder
	$req = array_get($input, 'req');
	$desc = array_get($input, 'desc', '');
	$unit = array_get($input, 'unit', '');
	$placeholder = array_get($input, 'placeholder', '');
	$class = array_get($input, 'class', '');
	$attr = array_get($input, 'attr', array());
	ob_start(); ?>
	<!-- city-->
	<div class="dropdown search-dropdown <?php echo $class ?>" <?php echo t('html')->attr($attr) ?>>
                        <div class="dropdown-toggle" type="button" data-toggle="dropdown">
                            <span class="search-rendered">Phân loại<?php //echo lang("all_cat_idcat") ?></span>
                            <span class="search-caret"></span>
                        </div>
                        <span class="search-remove"></span>
                        <ul class="dropdown-menu">
                            <?php foreach ($values as $cat) {
                                if ($cat->parent_id > 0) continue;

                                $cat = mod($obj)->add_info_url($cat);
								if (is_array($value))
									$active_status = (in_array( $cat->id, $value)) ? 1 : 0;
								else
									$active_status =  $cat->id == $value ? 1 : 0;
								?>
                                <li class="search-results act-filter-dropdown <?php echo $active_status ? 'active' : '' ?>"
                                    data-name="cat_id" data-value="<?php echo $cat->id ?>">
                                    <a class="search-results-option" href="#"
                                       title="<?php echo $cat->name ?>"><?php echo $cat->name ?></a>
                                    <?php if ($cat->parent_id == 0 && $cat->_subs): ?>
                                        <ul class="sub-menu">
                                            <?php foreach ($cat->_subs as $row) {
                                                $row = mod($obj)->add_info_url($row);
												if (is_array($value))
													$active_status = (in_array( $row->id, $value)) ? 1 : 0;
												else
													$active_status =  $row->id == $value ? 1 : 0;
                                                ?>
                                                <li class="search-results act-filter-dropdown <?php echo $active_status ? 'active' : '' ?>"
                                                    data-name="cat_id" data-value="<?php echo $row->id ?>">
                                                    <a class="search-results-option" href="#"
                                                       title="<?php echo $row->name ?>"><?php echo $row->name ?></a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
	<?php return ob_get_clean();
});
$this->register('filter_dropdown_country', function ($input) {
	$param = array_get($input, 'param');
	$value = array_get($input, 'value', '');
	$values = array_get($input, 'values', []);
	$name = array_get($input, 'name',lang($param));

	//== holder
	$req = array_get($input, 'req');
	$desc = array_get($input, 'desc', '');
	$unit = array_get($input, 'unit', '');
	$placeholder = array_get($input, 'placeholder', '');
	$class = array_get($input, 'class', '');
	$attr = array_get($input, 'attr', array());
	ob_start(); ?>
	<!-- city-->
	<div class="dropdown search-dropdown <?php echo $class ?>" <?php echo t('html')->attr($attr) ?>>
		<div class="dropdown-toggle" type="button" data-toggle="dropdown">
			<span class="search-rendered"><?php echo $name ?></span>
			<span class="search-caret"></span>
		</div>
		<span class="search-remove"></span>

		<div class="dropdown-menu p10">
			<div class="form-group">
				<input type="text" placeholder="Nhập tên quốc gia muốn tìm<?php //echo lang("city_out_the_country") ?>"
					   class="form-control lg searachSelect">
			</div>
			<div class="slimscroll limit-height">
				<ul>
					<?php $path = public_url() . '/img/world/'; ?>
					<?php foreach ($values as $v):
						if (is_array($value))
							$active_status = (in_array( $v->id, $value)) ? 1 : 0;
						else
							$active_status =  $v->id == $value ? 1 : 0;
						?>
						<li class="search-results  act-filter-dropdown <?php echo $active_status ? 'active' : '' ?>"
							data-name="<?php echo $param  ?>" data-value="<?php echo $v->id; ?>">
							<a class="search-results-option" href="#"
							   data-value="<?php echo $v->id; ?>">
								<img
									src="<?php echo $path . strtolower($v->code) . '.gif' ?>"> <?php echo $v->name; ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
	<?php return ob_get_clean();
});
$this->register('filter_dropdown_obj', function ($input) {
	$param = array_get($input, 'param');
	$value = array_get($input, 'value', '');
	$values = array_get($input, 'values', []);
	$name = array_get($input, 'name',lang($param));


	//== holder
	$req = array_get($input, 'req');
	$desc = array_get($input, 'desc', '');
	$unit = array_get($input, 'unit', '');
	$placeholder = array_get($input, 'placeholder', '');
	$class = array_get($input, 'class', '');
	$attr = array_get($input, 'attr', array());
	ob_start(); ?>
	<?php if ($values): ?>
		<div class="dropdown search-dropdown <?php echo $class ?>" <?php echo t('html')->attr($attr) ?>>
			<div class="dropdown-toggle" type="button" data-toggle="dropdown">
				<span class="search-rendered"><?php echo $name ?></span>
				<span class="search-caret"></span>
			</div>
			<span class="search-remove"></span>
			<ul class="dropdown-menu">
				<?php foreach ($values as $row):
					if (is_array($value))
						$active_status = (in_array($row->id, $value)) ? 1 : 0;
					else
						$active_status =$row->id== $value ? 1 : 0;

					?>
					<li class="search-results  act-filter-dropdown <?php echo $active_status ? 'active' : '' ?>"
						href="Javascript:;"
						data-name="<?php echo $param ?>" data-value="<?php echo $row->id ?>">
						<a class="search-results-option " href="#0"><?php echo $row->name ?></a>
						<?php if ($active_status): ?>
							<input name="<?php echo $name ?>[]" value="<?php echo $row->id ?>" type="hidden">
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
	<?php return ob_get_clean();
});
$this->register('filter_dropdown_list', function ($input) {
	$param = array_get($input, 'param');
	$value = array_get($input, 'value', '');
	$values = array_get($input, 'values', []);
	$name = array_get($input, 'name',lang($param));


	//== holder
	$req = array_get($input, 'req');
	$desc = array_get($input, 'desc', '');
	$unit = array_get($input, 'unit', '');
	$placeholder = array_get($input, 'placeholder', '');
	$class = array_get($input, 'class', '');
	$attr = array_get($input, 'attr', array());
	ob_start(); ?>
	<?php if ($values):
		?>
		<div class="dropdown search-dropdown <?php echo $class ?>" <?php echo t('html')->attr($attr) ?>>
			<div class="dropdown-toggle" type="button" data-toggle="dropdown">
				<span class="search-rendered"><?php echo $name ?></span>
				<span class="search-caret"></span>
			</div>
			<span class="search-remove"></span>
			<ul class="dropdown-menu">
				<?php foreach ($values as $v => $label):
					if (is_array($value))
						$active_status = (in_array($v, $value)) ? 1 : 0;
					else
						$active_status = $v == $value ? 1 : 0;
					?>
					<li class="search-results  act-filter-dropdown <?php echo $active_status ? 'active' : '' ?>"
						href="Javascript:;"
						data-name="<?php echo $param  ?>" data-value="<?php echo $v ?>">
						<a class="search-results-option " href="#0"><?php echo $label ?></a>
						<?php if ($active_status): ?>
							<input name="<?php echo $param ?>" value="<?php echo $v ?>" type="hidden">
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	<?php endif; ?>
	<?php return ob_get_clean();
});
$this->register('filter_list', function ($input) {
	$name = array_get($input, 'name');
	$param = array_get($input, 'param');
	$value = array_get($input, 'value', '');
	$values = array_get($input, 'value', []);

	//== holder
	$req = array_get($input, 'req');
	$desc = array_get($input, 'desc', '');
	$unit = array_get($input, 'unit', '');
	$placeholder = array_get($input, 'placeholder', '');
	$class = array_get($input, 'class', '');
	$attr = array_get($input, 'attr', array());
	ob_start(); ?>
	<?php if ($values): ?>
		<?php foreach ($values as $v => $label):
			if (is_array($value))
				$active_status = (in_array( $v->id, $value)) ? 1 : 0;
			else
				$active_status =  $v->id == $value ? 1 : 0;
			?>
			<div class="act-filter-dropdown <?php echo $active_status ? 'active' : '' ?>" href="Javascript:;"
				 data-name="<?php echo $param ?>" data-value="<?php echo $v ?>">
				<label><?php echo $label ?></label>
				<?php if ($active_status): ?>
					<input name="<?php echo $param ?>" value="<?php echo $v ?>" type="hidden">
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php return ob_get_clean();
});