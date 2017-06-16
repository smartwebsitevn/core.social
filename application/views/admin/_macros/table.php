<?php

/**
 * Table
 */
$this->register('table', function (array $input) {
    ob_start(); ?>

    <?php
    $title = array_get($input, 'title');
    $total = array_get($input, 'total');
    $filters = array_get($input, 'filters');
    $filters_options = array_get($input, 'filters_options',array());
    $stats = array_get($input, 'stats');
    $orders = array_get($input, 'orders');
    $columns = array_get($input, 'columns');
    $rows = array_get($input, 'rows', array());
    $actions = array_get($input, 'actions', array());
    $actions_key = array_get($input, 'actions_key', 'id');
    $actions_row = array_get($input, 'actions_row', array('install', 'uninstall', 'setting', 'set_default', 'translate', 'view', 'add', 'edit', 'del', 'delete',));
    $pages_config = array_get($input, 'pages_config');
    $sort = array_get($input, 'sort');
    $sort_key = array_get($input, 'sort_key', 'id');
    $sort_url_update = array_get($input, 'sort_url_update');

    $_id = '_' . random_string('unique');
    ?>


    <?php if ($sort): ?>
        <script type="text/javascript">
            (function ($) {
                $(document).ready(function () {
                    handle_sort_list($('#<?php echo $_id; ?>'), '<?php echo $sort_url_update; ?>');
                });
            })(jQuery);
        </script>
    <?php endif; ?>

    <?php
		if ( ! empty($stats))
		{
			echo $this->macro->stats($stats);
		}
		
		if ( ! empty($filters))
		{
			echo $this->macro->filters($filters,$filters_options);
		}
    ?>

    <div class="portlet">
        <div class="portlet-heading bg-primary">
            <div class="portlet-title">
                <h4>
                    <i class="fa fa-list-ul"></i>
                    <?php echo $title ?>
                    <?php if ($total): ?>
                        <small class="text-white">(<?php echo lang('total'); ?>: <?php echo $total; ?>)</small>
                    <?php endif; ?>
                </h4>


            </div>
            <div class="portlet-widgets">
                <a data-toggle="collapse" data-parent="#accordion" href="#<?php echo $_id; ?>"><i
                        class="fa fa-chevron-down"></i></a>
                <span class="divider"></span>

            </div>
            <div class="clearfix"></div>
        </div>
        <div id="<?php echo $_id; ?>" class="panel-collapse collapse in">
            <div class="portlet-body no-padding">
                <table class="table table-bordered table-striped table-hover tc-table ">
                    <thead>
                    <tr>
                        <?php if (!empty($actions)): ?>
                            <th class="col-small center" data-sort-ignore="true"><label><input type="checkbox"
                                                                                               class="tc"><span
                                        class="labels"></span></label></th>

                        <?php endif; ?>

                        <?php
                        $_order_class = array(
                            'asc' => 'order asc',
                            'desc' => 'order desc',
                        );


                        $_col_class_default = array(
                            'id' => ' col-1 ',
                            'sort_order' => ' col-1 textC',
                            'amount' => ' col-2 textC',
                            'option' => ' col-1 textC',
                            'feature' => ' col-1 textC',
                            'is_feature' => ' col-1 textC',
                            'status' => ' col-1 textC',
                            'created' => ' col-2 textC',
                            'updated' => ' col-2 textC',
                        );

                        foreach ($columns as $col => $col_name):
                            $col_attr = array();
                            if (is_array($col_name)) {
                                $col_attr = $col_name[1];
                                $col_name = $col_name[0];

                            }

                            $class_base = " column_{$col} ";
                            if (!$col_attr) // neu cot khong set thuoc tinh
                                $class_base .= array_get($_col_class_default, $col, '');

                            $col_attr = array_merge(array('class' => $class_base), $col_attr);
                            ?>

                            <?php if ($order = array_get($orders, $col)): ?>

								<th <?php echo t('html')->attr($col_attr) ?>>

									<a href="<?php echo $order['url']; ?>"
									   class="order <?php echo $order['status']; ?>"
									><?php echo $col_name; ?></a>

								</th>

							<?php else: ?>

								<th <?php echo t('html')->attr($col_attr) ?>>
									<?php echo $col_name; ?>
								</th>

							<?php endif; ?>

                        <?php endforeach; ?>
                    </tr>
                    </thead>

                    <tbody class="list_item">
                    <?php foreach ($rows as $row): // pr($row,false);?>

                        <?php if ($sort): ?>
                            <tr _list="1" _item="<?php echo array_get($row, $sort_key); ?>">
                        <?php else: ?>
                            <tr>
                        <?php endif; ?>

                        <?php if (!empty($actions)): ?>
                            <td class="col-small center"><label><input type="checkbox"
                                                                       name="<?php echo $actions_key; ?>[]"
                                                                       value="<?php echo array_get($row, $actions_key); ?>"
                                                                       class="tc"/><span class="labels"></span></label>
                            </td>
                        <?php endif; ?>

                        <?php foreach ($columns as $col => $col_name): ?>

                            <?php if ($col != 'action'): ?>
                                <td class="<?php echo "column_{$col}"; ?>  <?php echo array_key_exists($col,$_col_class_default)?'textC':''  ?>">
                                <?php echo array_get($row, $col); ?>
                            <?php else: ?>
                                <td class=" center  <?php echo "column_{$col}"; ?>" style="min-width:15%">
                                    <?php if (isset($row['action'])): ?>
                                        <?php echo array_get($row, $col); ?>

                                    <?php else: ?>

                                    <div class="btn-group btn-group-xs action-buttons">

                                        <?php if ($sort): ?>
                                            <a title="<?php echo lang('sort'); ?>" class=" js-sortable-handle"
                                               style="cursor:move;">
                                                <i class="fa fa-arrows-alt icon-only"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php echo $this->macro->action_row($row, $actions_row); ?>


                                        <?php /*if (isset($row['_can_translate']) && $row['_can_translate']): ?>
										<a href="<?php echo $row['_url_translate']; ?>" title="<?php echo lang('translate'); ?>" class="tipS"
										><i class="fa fa-file-text-o icon-only"></i></a>
									<?php endif; ?>
									 <?php if (isset($row['_can_set_default']) && $row['_can_set_default']): ?>
										 <?php
										 $name ="";
										 foreach(array('name','title','key') as $k)
											 if(isset( $row[$k]))
												 $name = $row[$k];
										 ?>
            							<a href="" _url="<?php echo $row['_url_set_default']; ?>" title="<?php echo lang('lang_set_default'); ?>" class="tipS verify_action"
            								notice="<?php echo lang('notice_confirm_set_default'); ?>
            								<?php if($name): ?>
											:<br><b><?php echo $name; ?></b>
											<?php endif; ?>">
            								<img src="<?php echo public_url('admin') ?>/images/icons/color/set_default.png" />
            							</a>
            						<?php endif; ?>
                                    <?php if (isset($row['_can_view']) && $row['_can_view']): ?>
										<a href="<?php echo $row['_url_view'] ?>" title="<?php echo lang('view'); ?>" class="tipS"
										><i class="fa fa-eye icon-only"></i></a>
									<?php endif; ?>
									<?php if (isset($row['_can_edit']) && $row['_can_edit']): ?>
										<a href="<?php echo $row['_url_edit'] ?>" title="<?php echo lang('edit'); ?>" class=" tipS"
										><i class="fa fa-pencil icon-only"></i></a>
									<?php endif; ?>
									
									<?php if (isset($row['_can_del']) &&  $row['_can_del']): ?>
										<?php
										$name ="";
										foreach(array('name','title','key') as $k)
											if(isset( $row[$k]))
												$name = $row[$k];
										?>
										<a href="" _url="<?php echo $row['_url_del']; ?>" title="<?php echo lang('delete'); ?>" class="tipS verify_action"
											notice="<?php echo lang('notice_confirm_delete'); ?>
											<?php if($name): ?>
											:<br><b><?php echo $name; ?></b>
											<?php endif; ?>"
										><i class="fa fa-times icon-only"></i></a>

									<?php endif; */ ?>
                                        <?php endif; ?>
                                    </div>
                                </td>

                            <?php endif; ?>

                        <?php endforeach; ?>
                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                    <tfoot class="auto_check_pages">
                    <tr>
                        <td colspan="20">

                            <?php if (!empty($actions)): ?>
                                <div class="list_action itemActions pull-left">

                                    <div class="input-group">
                                        <select name="action" class="left mr10 form-control">
                                            <option value=""><?php echo lang('select_action'); ?></option>
                                            <?php foreach ($actions as $a => $u): ?>
                                                <option
                                                    value="<?php echo $u; ?>"><?php echo lang('button_' . $a); ?></option>
                                            <?php endforeach; ?>
                                        </select>
								<span class="input-group-btn">
									<a href="#submit" id="submit" class="btn btn-primary">
                                        <i class="fa fa-calendar"></i> <?php echo lang('button_submit'); ?>
                                    </a>
								</span>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php
                            if (!empty($pages_config)) {
                                t('widget')->admin->pages($pages_config);
                            }
                            ?>

                        </td>
                    </tr>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>

    <?php return ob_get_clean();
});


/**
 * Filter
 */
$this->register('filters', function (array $filters, $options=array()) {
    $action = array_get($options, 'action', current_url(TRUE));
    $show_action = array_get($options, 'show_action',true);
    $auto_break = array_get($options, 'auto_break',true);// ngat o vi tri phan tu bao nhieu
    ob_start();
    ?>
    <form class="list_filter form" action="<?php echo $action; ?>" method="get">
        <div class="filter-wraper">
            <?php if($auto_break):
                $rows = array_chunk($filters, 5);
                $total_filter = count($filters);
                $d = 0;
                ?>
                <?php foreach ($rows as $row => $filters) : ?>
                <div class="filters">
                    <?php foreach ($filters as $filter) : ?>
                        <?php
                        if(!is_array($filter))    echo $filter;
                        else      echo $this->macro->filter($filter); ?>
                        <?php $d++; endforeach; ?>
                    <?php if ($show_action && $d == $total_filter): ?>
                        <div class="filter act <?php //echo  $total_filter%5 == 0?' mt10 fltrt':''?>">
                            <button class="btn btn-primary btn-small" type="submit"
                                    style="margin:0;"><?php echo lang('filter'); ?></button>
                            <a class="btn btn-small"
                               href="<?php echo current_url() ?>"><?php echo lang('reset'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <?php else: ?>
                <div class="filters">
                    <?php foreach ($filters as $filter) : ?>
                        <?php
                        if(!is_array($filter))    echo $filter;
                        else      echo $this->macro->filter($filter); ?>
                    <?php endforeach; ?>
                    <?php if ($show_action): ?>
                        <div class="filter act <?php //echo  $total_filter%5 == 0?' mt10 fltrt':''?>">
                            <button class="btn btn-primary btn-small" type="submit"   style="margin:0;"><?php echo lang('filter'); ?></button>
                            <a class="btn btn-small"
                               href="<?php echo current_url() ?>"><?php echo lang('reset'); ?></a>
                        </div>
                    <?php endif; ?>
                </div>


            <?php endif; ?>
        </div>
    </form>
    <div class="clr"></div>


    <?php return ob_get_clean();
});



/**
 * Filter row
 */

/*
 *
* Danh sach cac tuy chon:
* 	'type'			= Loai bien. VD: text. Cac loai bien duoc ho tro:
* 					  ob,text,  select, select_multi, radio, checkbox, date,
* 	'name'			= Tieu de cua bien
* 	'value'			= Gia tri mac dinh
* 	'values'		= Cac gia tri tuy chon cua bien, ap dung voi type = select, select_multi, radio, checkbox
* 						VD: array('value1' => 'Name1', 'value2' => 'Name2', ...)
*/
$this->register('filter', function (array $row) {
    ob_start(); ?>

    <?php

    $param = array_get($row, 'param', '');

    $name = array_get($row, 'name', lang($param));
    $type = array_get($row, 'type', 'text');


    $value_df = '';
    if ($type == 'select') ;
    $value_df = '*';
    $value = array_get($row, 'value', $value_df);

    // values la 1 doi tuong| mang gom key va name
    $values = array_get($row, 'values', array());

    // values la 1 doi tuong| mang gom 1 key
    $values_single = array_get($row, 'values_single', array());

    // values la 1 doi tuong| mang gom nhieu thuoc tinh
    $values_row = array_get($row, 'values_row', array());

    // cac option cua value
    $values_opts = array_get($row, 'values_opts', array());

    if ($values_single)
        $values_single = object_to_array($values_single);

    if ($values_row)
        $values_row = object_to_array($values_row);

    $desc = array_get($row, 'desc');
    $attr = array_get($row, 'attr', array());
    //echo '<br>$param:'.$param.':'. $value ;

    $use_placeholder = true;
    $placeholder = '';
    if ($use_placeholder) {
        $placeholder = ' placeholder="' . $name . '" ';

    }
    //$filter 	= 'filter_'.$param;
    $filter = $param;

    ?>
    <?php if ($type == 'sp'): ?>
        <div class="clear"></div>
    <?php else: ?>
        <div class="filter">
            <?php if (!$use_placeholder): ?>
                <span class="mr5"><?php echo $name; ?></span>
            <?php endif; ?>

            <div class="input-group">
                <?php if ($type == 'ob'): ?>
                    <?php echo $value; ?>

                <?php elseif ($type == 'text'): ?>
                    <input <?php echo $placeholder ?> name="<?php echo $filter; ?>"
                                                      value="<?php echo strip_tags($value); ?>" type="text"
                                                      class="form-control"
                        <?php echo t('html')->attr($attr); ?>
                        />


                <?php elseif ($type == 'select' || $type == 'select_multi'): ?>
                    <?php if ($type == 'select'): ?>
                        <?php //<!--onchange="this.form.submit()"--> ?>
                        <select name="<?php echo $filter; ?>" class="form-control"   <?php echo t('html')->attr($attr); ?>          >
                    <?php else: ?>
                        <select name="<?php echo $filter; ?>[]" class="form-control select_multi" multiple="multiple"
                        onchange="this.form.submit()"
                        <?php echo t('html')->attr($attr); ?>
                        >
                    <?php endif; ?>
                    <?php if (!array_get($values_opts, 'value_required', false)): ?>
                        <option value="">-=<?php echo $name ?>=-</option>
                    <?php endif; ?>
                    <?php if ($values): ?>
                        <?php foreach ($values as $v => $n): ?>

                            <?php if (is_array($n)): ?>
                                <optgroup label="<?php echo $v; ?>">
                                    <?php foreach ($n as $n_v => $n_n): ?>
                                        <option
                                            value="<?php echo $n_v; ?>" <?php echo form_set_select($n_v, $value); ?>
                                            ><?php echo $n_n; ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php else: ?>
                                <option value="<?php echo $v; ?>" <?php echo form_set_select($v, $value); ?> >
                                    <?php echo lang(array_get($values_opts, 'name_prefix', '') . $n . array_get($values_opts, 'name_suffix', '')) ?>
                                </option>

                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php elseif ($values_single): ?>
                        <?php foreach ($values_single as $v): ?>
                            <option value="<?php echo $v; ?>" <?php echo form_set_select($v, $value); ?> >
                                <?php echo lang(array_get($values_opts, 'name_prefix', '') . $v . array_get($values_opts, 'name_suffix', '')) ?>
                            </option>
                        <?php endforeach; ?>


                    <?php elseif ($values_row && count($values_row[0] > 0)): ?>
                        <?php foreach ($values_row[0] as $row): ?>
                            <option
                                value="<?php echo $row[$values_row[1]]; ?>" <?php echo form_set_select($row[$values_row[1]], $value); ?>>
                                <?php echo $row[$values_row[2]]; ?>
                            </option>
                        <?php endforeach; ?>

                    <?php endif; ?>
                    </select>


                <?php elseif ($type == 'date'): ?>
                    <input <?php echo $placeholder ?> name="<?php echo $filter; ?>"
                                                      value="<?php echo (is_numeric($value)) ? get_date($value) : $value; ?>"
                                                      class="form-control text-right mask_date date_picker"
                                                      type="text"
                        <?php echo t('html')->attr($attr); ?>
                        />
                <?php endif; ?>
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
            </div>
        </div>
    <?php endif; ?>
    <?php return ob_get_clean();
});
/**
 * Action
 */
$this->register('action', function (array $list) {
    ob_start(); ?>

<?php
    $colors = array(
        'install' => 'info',
        'uninstall' => 'danger',
        'setting' => 'info',
        'set_default' => 'info',
        'translate' => 'info',
        'view' => 'info',
        'add' => 'info',
        'edit' => 'warning',
        'del' => 'danger',
        'delete' => 'danger',

    );
    //pr($list);
    foreach ($list as $act => $opt):

        $url = array_get($opt, 'url');
        $confirm = array_get($opt, 'confirm', false);
        $notice = array_get($opt, 'notice', lang('notice_confirm_' . $act));
        $title = array_get($opt, 'title', lang($act));
        $class = array_get($opt, 'class', '');
        $color = array_get($opt, 'icon', array_get($colors, $act, 'info'));
       // $icon = array_get($opt, 'icon', array_get($icons, $act, $act));

        if ($confirm) $class .= ' verify_action';

        if ($act == 'translate'  )
        {
            $class .= ' lightbox';
        }
		if ($act == 'edit' || $act == 'add' )
		{
			$url = url_add_return($url);
		}
?>

        <?php if ($confirm): ?>
        <a  href="" _url="<?php echo $url; ?>" notice="<?php echo $notice; ?>"
        <?php else : ?>
        <a href="<?php echo $url; ?>"
         <?php endif; ?>
        title="<?php echo $title; ?>"
        class="btn btn-<?php echo $color ?> btn-xs <?php echo $class; ?>"  >
        <?php echo lang('button_' . $act) ?>
        </a>


    <?php endforeach; ?>

    <?php return ob_get_clean();
});


/**
 * Action row
 */
$this->register('action_row', function ($row, $actions = array('edit', 'del'), array $opts = array())
{
    $_macro = array();

    $row = (object) $row;

    foreach ((array) $actions as $p)
    {
		if ( ! data_get($row, 'can:'.$p, data_get($row, '_can_'.$p))) continue;

        $opt = array_get($opts, $p, array());

        $opt['url'] = data_get($row, 'adminUrl:'.$p, data_get($row, '_url_'.$p));

        if (in_array($p, array('uninstall', 'set_default', 'del', 'delete')))
        {
            $opt = array_merge(array('confirm' => true), $opt);
        }

        $_macro[$p] = $opt;
    }

    return $this->macro->action($_macro);
});


/**
 * Tao actions cho data
 */
$this->register('actions_data', function ($data, array $actions = null)
{
	$actions = $actions ?: ['edit', 'translate'];

	$list = [];

	foreach ($actions as $act => $opt)
	{
		if ( ! is_array($opt))
		{
			$act = $opt;
			$opt = [];
		}

		if ( ! data_get($data, 'can:'.$act, data_get($data, '_can_'.$act))) continue;

		$opt['url'] = data_get($data, 'adminUrl:'.$act, data_get($data, '_url_'.$act));

		$list[$act] = $opt;
	}

    return $this->macro->action($list);
});


/**
 * Action sort
 */
$this->register('action_sort', function()
{
    ob_start(); ?>

    <a title="<?php echo lang('sort'); ?>" class="js-sortable-handle" style="cursor:move;"
	><i class="fa fa-arrows"></i></a>

    <?php return ob_get_clean();
});


/**
 * Stats
 */
$this->register('stats', function(array $list)
{
    ob_start(); ?>

	<ul class="list-group">

	<?php foreach ($list as $label => $value): ?>

		<li class="list-group-item">

			<b><?php echo $label ?></b>

			<h4 class="pull-right text-danger" style="margin: 0;">
				<?php echo $value; ?>
			</h4>

			<div class="clearfix"></div>
		</li>

	<?php endforeach ?>

	</ul>

    <?php return ob_get_clean();
});

/**
 * danh sach lang
 */
$this->register('langs_url', function ($row) {
    ob_start();
    if(isset($row->_lang_url) && $row->_lang_url){
        $lang = [];
        foreach($row->_lang_url as $key => $url){
            $lang[] = '<a href="'.$url.'" target="_blank">'.$key.'</a>';
        }
        echo implode(', ', $lang);
    }?>

    <?php return ob_get_clean();
});