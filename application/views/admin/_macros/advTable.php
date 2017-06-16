<?php

/**
 * Table
 */
$this->register('table', function (array $input) {
    ob_start(); 
    $title = array_get($input, 'title');
    $total = array_get($input, 'total');
    $filters = array_get($input, 'filters');
    $filters_options = array_get($input, 'filters_options',array());
    $orders = array_get($input, 'orders');
    $columns = array_get($input, 'columns');

    $rows = array_get($input, 'rows', array());
    $actions = array_get($input, 'actions', array());
    $actions_key = array_get($input, 'actions_key', 'id');
    $actions_row = array_get($input, 'actions_key', array('install', 'uninstall', 'setting', 'set_default', 'translate', 'view', 'add', 'edit', 'del', 'delete',));

    $pages_config = array_get($input, 'pages_config');

    $sort = array_get($input, 'sort');
    $sort_key = array_get($input, 'sort_key', 'id');
    $sort_url_update = array_get($input, 'sort_url_update');

    $_id = '_' . random_string('unique');

    if ($sort)
    {
?>
    <script type="text/javascript">
        (function ($) {
            $(document).ready(function () {
                handle_sort_list($('#<?php echo $_id; ?>'), '<?php echo $sort_url_update; ?>');
            });
        })(jQuery);
    </script>
<?php
    }

    if (! empty($filters) ) {
        echo $this->macro->filters( $filters, $filters_options );
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
                            'asc' => 'headerSortUp',
                            'desc' => 'headerSortDown',
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

                            $col_attr = t('html')->attr(array_merge(array('class' => $class_base), $col_attr));
                            ?>

                            <?php if ($order = array_get($orders, $col)): ?>

                            <th onclick="window.parent.location = '<?php echo $order['url']; ?>'"
                                class="sortCol <?php echo array_get($_order_class, $order['status']); ?>"
                                <?php echo $col_attr ?> ><?php echo $col_name; ?></th>

                        <?php else: ?>
                            <th <?php echo $col_attr ?> ><?php echo $col_name; ?></th>

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
                                                    value="<?php echo $u; ?>"><?php echo lang('action_' . $a); ?></option>
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
    $show_action = array_get($options, 'show_action',true);
    $auto_break = array_get($options, 'auto_break',true);// ngat o vi tri phan tu bao nhieu
    ob_start();
    ?>
    <form class="list_filter form" action="<?php echo current_url(); ?>" method="get">
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
                        if(!is_array($filter))    
                            echo $filter;
                        else      
                            echo $this->macro->filter($filter, $filters); 
                        ?>
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
                        if(!is_array($filter))    
                            echo $filter;
                        else      
                            echo $this->macro->filter($filter, $filters); ?>
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
*   'type'          = Loai bien. VD: text. Cac loai bien duoc ho tro:
*                     ob,text,  select, select_multi, radio, checkbox, date,
*   'name'          = Tieu de cua bien
*   'value'         = Gia tri mac dinh
*   'values'        = Cac gia tri tuy chon cua bien, ap dung voi type = select, select_multi, radio, checkbox
*                       VD: array('value1' => 'Name1', 'value2' => 'Name2', ...)
*/
$this->register('filter', function (array $row, $rows = null) {
    ob_start();

    $param = array_get($row, 'param', '');
    $name = array_get($row, 'name', lang($param));
    $type = array_get($row, 'type', 'text');
    $refer = array_get($row, 'refer');
    $refer_value = array_get( $row, 'refer_value', null );
    $ajax = ( array_get($row, 'ajax') ? (object) array_get($row, 'ajax') : null ) ;

    // $value_df = '';
    // if ($type == 'select') ;
    // $value_df = '*';
    $value = array_get($row, 'value');

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

    // Đối tượng hiển thị phụ thuộc
    $hide = false;
    if( $refer )
    {
        foreach ($rows as $item) 
        {
            if( array_get($item, 'param', '') == $refer )
            {
                $tmp_value = array_get($item, 'value');
                if( $refer_value !== null )
                {
                    if( is_array($refer_value) )
                    {
                        if(! in_array( $tmp_value, $refer_value ) )
                        {
                            $hide = true;
                        }
                    } 
                    else if( $tmp_value != $refer_value )
                        $hide = true;
                }
                else
                {
                    if(! $tmp_value )
                        $hide = true;
                }
                break;
            }
        }

        ?>
        <script type="text/javascript">
            $('input[name=<?php echo $refer ?>], select[name=<?php echo $refer ?>]').on('change', function(){
                var value = $(this).val();
                <?php
                    if( $refer_value !== null )
                    {
                ?>
                    if( value == <?php echo $refer_value ?> )
                <?php
                    }
                    else
                    {
                ?>
                    if( value )
                <?php
                    }
                ?>
                {
                    <?php 
                        if( $ajax )
                        {
                            ?>
                            $.ajax({
                                type: "post",
                                url: "<?php echo admin_url( t('uri')->rsegment(1) . '/load' . $ajax->loader ) ?>",
                                data: { 
                                    value: $(this).val(),
                                    refer: '<?php echo $refer ?>',
                                    source: '<?php echo $ajax->source ?>'
                                },
                                success: function(output){
                                    var data = $.parseJSON( output );
                                    var rs = '<option value="">-=<?php echo lang('select_choice') ?>=-</option>';
                                    $.each(data, function(i, k){
                                        rs += '<option value="'+k.id+'">'+ k.<?php echo $values_row[2] ?> +'</option>';
                                    });
                                    $('.refer_<?php echo $param ?> select').html(rs);
                                    $('.refer_<?php echo $param ?> select').select2( "val", "" );
                                }
                            })
                            <?php
                        }
                    ?>
                    $('.refer_<?php echo $param ?>').slideDown('250');
                }
                else
                {
                    $('.refer_<?php echo $param ?>').slideUp('250');
                }
            });
        </script>
        <?php
    }

    $filter = $param;
    
    if ($type == 'sp')
    {

?>
        <div class="clear"></div>
<?php 
    }

    else
    {
?>
        <div class="filter <?php echo ( $refer ? 'refer_' . $param : '' ) ?>" <?php echo ( $hide ? 'style="display: none;"' : '' ) ?>>
        <?php 
            if (! $use_placeholder)
            { 
        ?>
            <span class="mr5"><?php echo $name; ?></span>
        <?php 
            } 
        ?>

            <div class="input-group">
            <?php  
                if ($type == 'ob')
                {
                    echo $value;
                }

                else if ($type == 'text')
                {
            ?>
                    <input <?php echo $placeholder ?> name="<?php echo $filter; ?>"
                        value="<?php echo strip_tags($value); ?>" type="text"
                        class="form-control"
                        <?php echo t('html')->attr($attr); ?>
                    />

            <?php 
                }

                 else if ($type == 'number')
                {
                    echo t('html')->input($filter, $value, array_merge(array(
                        'class' => 'form-control input_number',
                        'placeholder' => $name
                    ), $attr)); 
                }
                else if ($type == 'select2')
                {
            ?>
                <select name="<?php echo $filter; ?>" class="form-control select_multi" <?php echo t('html')->attr($attr); ?> >
                <?php 
                    if (!array_get($values_opts, 'value_required', false))
                    {
                ?>
                    <option value="-1">-=<?php echo $name ?>=-</option>
                <?php
                    }
                    if ($values)
                    { 
                        foreach ($values as $v => $n)
                        {
                            if (is_array($n)): 
                            ?>
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

                            <?php 
                            endif;
                        }
                    } 
                    elseif ($values_single)
                    {
                        foreach ($values_single as $v)
                        { 
                        ?>
                            <option value="<?php echo $v; ?>" <?php echo form_set_select($v, $value); ?> >
                                <?php echo lang(array_get($values_opts, 'name_prefix', '') . $v . array_get($values_opts, 'name_suffix', '')) ?>
                            </option>
                        <?php 
                        }
                    }
                    elseif ($values_row && count($values_row[0]) > 0)
                    { 
                        foreach ($values_row[0] as $row)
                        { 
                            ?>
                            <option
                                value="<?php echo $row[$values_row[1]]; ?>" <?php echo form_set_select($row[$values_row[1]], $value); ?>>
                                <?php echo $row[$values_row[2]]; ?>
                            </option>
                        <?php 
                        }
                    } 
                    ?>
                </select>

            <?php 
                }
                else if ($type == 'select' || $type == 'select_multi')
                {
                    if ($type == 'select')
                    {
                ?>
                    <select name="<?php echo $filter; ?>" class="form-control" <?php echo t('html')->attr($attr); ?> >
                <?php
                    } else {
                ?>
                    <select name="<?php echo $filter; ?>[]" class="form-control select_multi" multiple="multiple" onchange="this.form.submit()" <?php echo t('html')->attr($attr); ?> >
                <?php 
                    }
                    
                    if (!array_get($values_opts, 'value_required', false))
                    {
                ?>
                        <option value="">-=<?php echo $name ?>=-</option>
                <?php
                    }
                    
                    if ($values)
                    {
                        foreach ($values as $v => $n)
                        {
                            if (is_array($n))
                            {
                        ?>
                                <optgroup label="<?php echo $v; ?>">
                                    <?php foreach ($n as $n_v => $n_n): ?>
                                        <option
                                            value="<?php echo $n_v; ?>" <?php echo form_set_select($n_v, $value); ?>
                                            ><?php echo $n_n; ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                        <?php 
                            }
                            else
                            { 
                        ?>
                                <option value="<?php echo $v; ?>" <?php echo form_set_select($v, $value); ?> >
                                    <?php echo lang(array_get($values_opts, 'name_prefix', '') . $n . array_get($values_opts, 'name_suffix', '')) ?>
                                </option>
                            <?php 
                            } 
                        } 
                    }
                    else if ($values_single)
                    {
                        foreach ($values_single as $v) 
                        {
                        ?>
                            <option value="<?php echo $v; ?>" <?php echo form_set_select($v, $value); ?> >
                                <?php echo lang(array_get($values_opts, 'name_prefix', '') . $v . array_get($values_opts, 'name_suffix', '')) ?>
                            </option>
                    <?php 
                        } 
                    }

                    else if ($values_row && count($values_row[0]) > 0)
                    { 
                        foreach ($values_row[0] as $row)
                        {
                        ?>
                        <option
                            value="<?php echo $row[$values_row[1]]; ?>" <?php echo form_set_select($row[$values_row[1]], $value); ?>>
                            <?php echo $row[$values_row[2]]; ?>
                        </option>
                    <?php 
                        } 
                    } 
                ?>
                    </select>
            <?php 
                } 
                else if ($type == 'date')
                { 
            ?>
                <input <?php echo $placeholder ?> name="<?php echo $filter; ?>"
                    value="<?php echo (is_numeric($value)) ? get_date($value) : $value; ?>"
                    class="form-control text-right mask_date date_picker"
                    type="text"
                    <?php echo t('html')->attr($attr); ?>
                />
            <?php 
                }
            ?>
                <span class="input-group-addon"><i class="fa fa-filter"></i></span>
            </div>
        </div>
<?php 
    }

    return ob_get_clean();
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
    foreach ($list as $act => $opt):

        $url = array_get($opt, 'url');
        $confirm = array_get($opt, 'confirm', false);
        $notice = array_get($opt, 'notice', lang('notice_confirm_' . $act));
        $title = array_get($opt, 'title', lang($act));
        $class = array_get($opt, 'class', '');
        $color = array_get($opt, 'icon', array_get($colors, $act, $act));
       // $icon = array_get($opt, 'icon', array_get($icons, $act, $act));

        if ($confirm) $class .= ' verify_action';
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
$this->register('action_row', function ($row, $actions = array('edit', 'del'), array $opts = array()) {
    $_macro = array();
    $row = (object)$row;
     //pr($opts);
    //pr($row);
    foreach ((array)$actions as $p) {
        if (!isset($row->{'_can_' . $p}) || !isset($row->{'_url_' . $p}) || !$row->{'_can_' . $p}) continue;

        $opt = array_get($opts, $p, array());
        $opt['url'] = $row->{'_url_' . $p};

        if (in_array($p, array('uninstall', 'set_default', 'del', 'delete'))) {
            $opt = array_merge(array('confirm' => true), $opt);
        }

        $_macro[$p] = $opt;
    }
    //pr($_macro);
    return $this->macro->action($_macro);
});


/**
 * Action sort
 */
$this->register('action_sort', function () {
    ob_start(); ?>

    <a title="<?php echo lang('sort'); ?>" class="js-sortable-handle" style="cursor:move;"
        ><i class="fa fa-arrows"></i></a>

    <?php return ob_get_clean();
});
