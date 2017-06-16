<?php
/**
 * File macros bổ sung
 * - Ajax load
 * - Ajax search
 * - Select2
 * - Select multi
 * 
 * 
 */
$this->register('form', function (array $input) {
    $action = array_get($input, 'action', current_url(TRUE));
    $attr   = array_get($input, 'attr', []);
    $title  = array_get($input, 'title');
    $rows   = array_get($input, 'rows', []);
    $data   = array_get($input, 'data', []);
    $btn_submit = array_get($input, 'btn_submit', lang('button_submit'));
    $btn_reset  = array_get($input, 'btn_reset', lang('button_reset'));

    $make_form = function() use ($action, $attr, $rows, $data, $btn_submit, $btn_reset)
    {
        ob_start();

        echo t('html')->form(
            $action, 
            array_merge(array('class' => 'form-horizontal form_action'), $attr)
        ); 

        foreach ($rows as $row)
        {
            if (is_string($row))
            {
                echo $row;
            }
            else
            {
                $row['value'] = array_get($row, 'value', array_get($data, $row['param']));

                echo $this->macro->row($row, $rows);
            }
        }
        
        echo $this->macro->submit($btn_submit);
        echo t('html')->end('form');

        return ob_get_clean();
    };

    if ( ! $title ) return $make_form();

    echo $title;
    echo $make_form();
});




/*
*
* Danh sach cac tuy chon:
*   'type'          = Loai bien. VD: text.
*   Cac loai bien duoc ho tro:
*                     ob,static,hidden,text,number, password textarea, html, bool, select, radio, checkbox,
*                    select_multi, spinner, date, color, file, file_multi, image, image_multi
*   'name'          = Tieu de cua bien
*   'value'         = Gia tri mac dinh
*   'values'        = Cac gia tri tuy chon cua bien, ap dung voi type = select, select_multi, radio, checkbox
*                       VD: array('value1' => 'Name1', 'value2' => 'Name2', ...)
*   'refer'         = Đối tượng hiển thị phụ thuộc
*/
$this->register('row', function (array $row, $rows = null) {
    ob_start();
    $type = array_get($row, 'type', 'text');
    $param = array_get($row, 'param', '');
    $value = array_get($row, 'value');
   

    if ($type == 'hidden')
    {
?>
        <input type="hidden" name="<?php echo $param ?>" value="<?php echo $value ?>">
<?php 
        return ob_get_clean();
    }
    

    $refer = array_get($row, 'refer');
    $refer_reverse = array_get($row, 'refer_reverse');
    $ajax = ( array_get($row, 'ajax') ? (object) array_get($row, 'ajax') : null ) ;
    $_id = '_' . random_string('unique');
    $name = array_get($row, 'name', lang($param));

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


    $req = array_get($row, 'req');
    $desc = array_get($row, 'desc');
    $unit = array_get($row, 'unit');
    $extra = array_get($row, 'extra');

    $attr = array_get($row, 'attr', array());
    $attr['id'] = $_id;
    if($placeholder = array_get($row, 'placeholder'))
        $attr['placeholder'] = $placeholder;





    // Đối tượng hiển thị phụ thuộc
    $hide = false;
    if( $refer )
    {
        foreach ($rows as $item) 
        {
            if( array_get($item, 'param', '') == $refer )
            {
                if( array_get($item, 'value') )
                    $hide = $refer_reverse ? true : false;
                else 
                    $hide = $refer_reverse ? false : true;

                break;
            }
        }

        ?>
        <script type="text/javascript" defer="defer">
            $('input[name=<?php echo $refer ?>], select[name=<?php echo $refer ?>], select[name="<?php echo $refer ?>[]"]').on('change', function(){
                if( $(this).attr('type') == 'checkbox' )
                    var value = $(this).is(':checked');
                else
                    var value = $(this).val();

                if( value <?php echo $refer_reverse ? '==' : '!=' ?>  0 )
                {
                    <?php 
                        if( $ajax )
                        {
                            ?>
                            $.ajax({
                                type: "post",
                                url: "<?php echo site_url( t('uri')->rsegment(1) . '/load' . $ajax->loader ) ?>",
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


?>

<div class="form-group <?php echo 'param_' . $type; ?> <?php echo ( $refer ? 'refer_' . $param : '' ) ?>" <?php echo ( $hide ? 'style="display: none;"' : '' ) ?>>
<?php 
    if ($name) 
    {
    
?>
        <label class="col-sm-4  control-label " for="<?php echo $_id; ?>">
            <?php
                $_name = $name;
                if ($unit) $_name .= ' (' . $unit . ')';
                if ($_name) $_name .= ':';

                echo ucfirst($_name);
            
                if ($req) 
                    echo t('html')->span('*', array('class' => 'req')); 
            ?>
        </label>
        <div class="col-sm-8">
<?php 
    }
    else
    {
?>
        <div class="col-sm-12">
<?php
    } 
    
    if ($type == 'ob')
    {
        echo $value; 
    }
    else if ($type == 'static')
    {
    ?>
        <span <?php echo t('html')->attr(array_merge(array('style' => 'line-height: 32px;font-size:16px;font-weight:bolder'), $attr)) ?>>
            <?php echo $value ?>
        </span>
    <?php 
    }
    
    /* Textbox */
    else if ($type == 'text')
    {
        echo t('html')->input($param, $value, array_merge(array('class' => 'form-control '), $attr)); 
    }

    /* Number */
    else if ($type == 'number')
    {
        echo t('html')->input($param, $value, array_merge(array('class' => 'form-control input_number'), $attr)); 
    }
       
    /* Password */ 
    else if ($type == 'password')
    {
        echo t('html')->password($param, null, array_merge(array('class' => 'form-control'), $attr)); 
    }

    /* textarea */
    else if ($type == 'textarea')
    {
        echo t('html')->textarea($param,
            $value,
            array_merge(array('size' => '4', 'class' => ' form-control'), $attr)
        );
    }
        
    /* Number */
    else if ($type == 'spinner')
    {
    ?>
        <div class="spinner">
            <div class="input-group input-small">
                <?php echo t('html')->input(
                    $param,
                    $value,
                    array_merge(array('class' => 'spinner-input form-control'), $attr)
                ); ?>
                <div class="spinner-buttons input-group-btn btn-group-vertical">
                    <button type="button" class="btn spinner-up btn-xs">
                        <i class="fa fa-chevron-up icon-only"></i>
                    </button>
                    <button type="button" class="btn spinner-down btn-xs">
                        <i class="fa fa-chevron-down icon-only"></i>
                    </button>
                </div>
            </div>
        </div>
    <?php 
    }

    /* html */
    else if ($type == 'html')
    {
    ?>
        <textarea name="<?php echo $param; ?>" id="<?php echo $_id; ?>" class="form-control editor"
            _config='{
                "height": <?php echo array_pull($attr, 'height', 200); ?>
            }'
            <?php echo t('html')->attr($attr); ?>
        >
            <?php echo $value; ?>
        </textarea>
    <?php 
    }

    /* Checkbox */
    elseif ($type == 'checkbox')
    {
        $value = is_numeric($value) ? $value : 1; // set valua mac dinh neu khong truyen
    ?>
        <label class="tcb-inline">
            <?php
            echo t('html')->checkbox($param, 1, form_set_checkbox(1, (int)$value), array_merge(array('class' => 'tc'), $attr));
            ?>
            <span class="labels"><?php echo isset($attr['placeholder']) ? $attr['placeholder'] : ''; ?></span>
        </label>
        <?php 
    }

    else if ($type == 'bool' || $type == 'bool_status')
    {
        if (empty($values)) {
            if ($type == 'bool')
                $values = array(lang('no'), lang('yes'));
            else
                $values = array(lang('off'), lang('on'));

            $value = is_numeric($value)?$value:1; // set valua mac dinh neu khong truyen
        } else
            $values = (array)$values; 

        if (count($values) == 1)
        {
            ?>

            <label class="tcb-inline"><?php
                echo t('html')->checkbox($param, 1, form_set_checkbox(1, (int)$value), array_merge(array('class' => 'tc'), $attr));
                ?>
                <span class="labels"><?php echo reset($values); ?></span>
            </label>

        <?php 
        } 
        else
        {
            foreach ($values as $v => $n)
            {
                ?>
                <label class="tcb-inline ">
                    <?php echo t('html')->radio($param, $v, form_set_checkbox((int)$v, (int)$value), array_merge(array('class' => 'tc'), $attr)); ?>
                    <span class="labels"><?php echo $n ?></span>
                </label>
            <?php 
            }
        } 
    }

    /* Select 2 */
    else if ($type == 'select2')
    {
    ?>
        <select name="<?php echo $param; ?>" class="form-control select_multi" style="width:100%;" <?php echo t('html')->attr($attr); ?> >
           
        <?php 
            if (! array_get($values_opts, 'value_required', false))
            {
        ?>
                <option value="">-=<?php echo lang('select_choice') ?>=-</option>
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
                    <?php 
                        foreach ($n as $n_v => $n_n)
                        { 
                            ?>
                            <option value="<?php echo $n_v; ?>" <?php echo form_set_select($n_v, $value); ?>  >
                                <?php echo $n_n; ?>
                            </option>
                            <?php 
                        } 
                    ?>
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
                    $row = isset($row) ? (array)$row : array(); 
                    ?>
                    <option value="<?php echo $row[$values_row[1]]; ?>" <?php echo form_set_select($row[$values_row[1]], $value); ?>>
                        <?php echo $row[$values_row[2]]; ?>
                    </option>
                <?php 
                }
            }
        ?>
        </select>
    
    <?php 
    }
    
    /* Select */
    else if ($type == 'select' || $type == 'select_multi')
    { 
        ?>

            <?php if ($type == 'select'): ?>
                <select name="<?php echo $param; ?>" class="form-control " style="width:100%;" <?php echo t('html')->attr($attr); ?> >
            <?php else: ?>
                <select name="<?php echo $param; ?>[]" multiple="multiple" class="form-control select_multi" style="width:100%;" <?php echo t('html')->attr($attr); ?> >
            <?php endif; ?>
               
            <?php if (!array_get($values_opts, 'value_required', false)): //pr($values_opts);?>
                <option value="<?php echo array_get($values_opts, 'default_value', '') ?>">-=<?php echo array_get($values_opts, 'default_name', lang('select_choice')) ?>=-</option>
            <?php endif; ?>


            <?php if ($values): ?>
                <?php foreach ($values as $v => $n): ?>
                    <?php if (is_array($n)): ?>
                        <optgroup label="<?php echo $v; ?>">
                            <?php foreach ($n as $n_v => $n_n): ?>
                                <option value="<?php echo $n_v; ?>" <?php echo form_set_select($n_v, $value); ?>  >
                                <?php echo $n_n; ?>
                                </option>
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
            <?php elseif ($values_row && count($values_row[0]) > 0): ?>
                <?php foreach ($values_row[0] as $row):
                    $row = isset($row) ? (array)$row : array(); ?>
                    <option
                        value="<?php echo $row[$values_row[1]]; ?>" <?php echo form_set_select($row[$values_row[1]], $value); ?>>
                        <?php echo $row[$values_row[2]]; ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
            </select>

    <?php 
    }

    /* Search */ 
    else if ($type == 'search')
    {
    ?>

            <div class="quick-search">
            <select name="<?php echo $param; ?>s[]" multiple="multiple" class="form-control select_multi" style="width:100%;" <?php echo t('html')->attr($attr); ?>>
                <?php 
                    if ($values_row && count($values_row[0]) > 0)
                    {
                        foreach ($values_row[0] as $row)
                        {
                            $row = isset($row) ? (array)$row : array(); 
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
                echo t('html')->input( $param, '', array_merge( array('class' => 'form-control'), $attr ) ); 
            ?>
            <ul id="quick-<?php echo $param; ?>-result" class="quick-search-result" data-name="<?php echo $param; ?>"></ul>
            
            <script type="text/javascript">
                $(document).ready(function(){
                    var interval<?php echo $param; ?>;
                    $('input[name=<?php echo $param; ?>]').on('keyup', function(){
                        var value = $.trim($(this).val());
                        var old = $('select[name="<?php echo $param ?>s[]"]').select2("val");
                        
                        if( value.length > 2 )
                        {
                            clearTimeout(interval<?php echo $param; ?>);
                            interval<?php echo $param; ?> = setTimeout( function(){
                                $.ajax({
                                    type: "post",
                                    url: "<?php echo admin_url( t('uri')->rsegment(1) . '/search' . $ajax->loader ) ?>",
                                    data: { 
                                        value: value,
                                        old: old,
                                        source: '<?php echo $ajax->source ?>'
                                    },
                                    success: function(output){
                                        var data = $.parseJSON( output );
                                        var rs = '';
                                        $.each(data, function(i, k){
                                            rs += '<li data-value="'+k.id+'">'+ k.<?php echo $values_row[2] ?> +'</li>';
                                        });
                                        $('#quick-<?php echo $param; ?>-result').html(rs);
                                        $('#quick-<?php echo $param; ?>-result').addClass('click-to-hide');
                                        $('#quick-<?php echo $param; ?>-result').show();
                                    }
                                });
                            }, 1500);
                        }
                    });

                    
                    $('.quick-search-result').on('click', 'li', function(){
                        var name = $(this).parent().data('name');
                        var option = '<option value="'+$(this).data('value')+'">'+$(this).text()+'</option>';
                        $('select[name="'+name+'s[]"]').append(option);
                        var value = $('select[name="'+name+'s[]"]').select2("val");
                        value.push( $(this).data('value') );
                        $('select[name="'+name+'s[]"]').select2("val", value);
                        $(this).remove();
                    });
                });
                
            </script>
            </div>


        <?php 
        }

        /* Radio */
        else if ($type == 'radio')
        {
            foreach ($values as $v => $n)
            {
        ?>
                <label class="tcb-inline"><?php
                    echo t('html')->radio($param, $v, form_set_checkbox($v, $value), array_merge(array('class' => 'tc'), $attr));
                    ?>
                    <span class="labels"><?php echo $n; ?></span>
                </label>

                <?php 
                if (count($values) > 2)
                {
                    ?>
                        <div class="clear"></div>
                    <?php 
                }
            }
        }

        else if ($type == 'checkbox')
        {
            foreach ($values as $v => $n)
            {
            ?>
                <label class="tcb-inline">
                <?php
                    echo t('html')->checkbox($param . '[]', $v, form_set_checkbox($v, $value), array_merge(array('class' => 'tc'), $attr));
                ?>
                    <span class="labels"><?php echo $n; ?></span>
                </label>

            <?php 
                if (count($values) > 2) 
                { 
            ?>
                    <div class="clear"></div>
            <?php 
                }
            }
        }

        /* date */
        else if ($type == 'date')
        {
        ?>
            <input name="<?php echo $param; ?>"
                   value="<?php echo (is_numeric($value)) ? get_date($value) : $value; ?>"
                   id="<?php echo $_id; ?>" class="date_picker mask_datess" style="width:100px;"
                   type="text"
                <?php echo t('html')->attr($attr); ?>
                />

        <?php 
        } 

        /* color */
        else if ($type == 'color')
        { 
        ?>
            <div class="color_picker">
                <div></div>
                <span>Choose color...</span>
                <input name="<?php echo $param; ?>" value="<?php echo $value; ?>" type="hidden"/>
            </div>
        <?php 
        }

        /* upload */
        else if (in_array($type, array('file', 'image', 'file_multi', 'image_multi')))
        {
            t('widget')->admin->upload($row['_upload']);
        }

        else if ($type == 'custom')
        {
            echo $row['html'];
        } 

        if($param)
        {
    ?>
            <div class="clear"></div>
            <div name="<?php echo $param; ?>_error" class="error"></div>
    <?php 
        }
       
        if ($desc)
        {
    ?>
            <div class="clear"></div>
            <small><?php echo $desc; ?></small>
    <?php 
        }

        echo $extra; 
    ?>
    </div>
    <div class="clearfix"></div>
</div>
<?php 

    return ob_get_clean();
});


/**
 * Form translate
 */
$this->register('translate', function (array $input) {
    ob_start(); ?>

    <?php
    $action = array_get($input, 'action', current_url(TRUE));
    $attr = array_get($input, 'attr', array());
    $title = array_get($input, 'title', lang('mod_translate'));
    $langs = array_get($input, 'langs', array());
    $btn_submit = array_get($input, 'btn_submit', lang('button_submit'));
    $btn_reset = array_get($input, 'btn_reset', lang('button_reset'));
    ?>
    <div class="portlet">
        <div class="portlet-heading dark">
            <div class="portlet-title">
                <h4>
                    <i class="fa fa-pencil"></i>
                    <?php echo $title ?></h4>
            </div>

        </div>
        <div class="portlet-body ">
            <?php echo t('html')->form($action, array_merge(array('class' => 'form form-horizontal ', 'id' => 'form'), $attr)); ?>
            <div class="tc-tabs">


                <ul class="nav nav-tabs tab-color-dark background-dark white">
                    <?php $i = 1;
                    foreach ($langs as $lang)://pr($lang);
                        ?>
                        <li <?php echo $i == 1 ? ' class="active" ' : '' ?>>
                            <a href="#lang_<?php echo $lang['key']; ?>" data-toggle="tab">
                                <img style="height:16px" class="img-responsive img-responsive pull-left mr5"
                                     src="<?php echo public_url("img/world/{$lang['key']}.gif"); ?>">
                                <span class="mt3"><?php echo $lang['name']; ?></span>
                            </a>
                        </li>
                        <?php $i++; endforeach; ?>

                </ul>
                <div class="tab-content">


                    <?php $i = 1;
                    foreach ($langs as $lang): ?>
                        <div class="tab-pane <?php echo $i == 1 ? 'active' : '' ?> "
                             id="lang_<?php echo $lang['key']; ?>">

                            <?php
                            foreach ($lang['rows'] as $row) {
                                echo $this->macro->row($row);
                            }
                            ?>


                        </div>
                        <?php $i++; endforeach; ?>


                    <div class="form-actions">
                        <div class="form-group formSubmit">
                            <div class="col-sm-offset-2 col-sm-10">
                                <input type="submit" value="<?php echo $btn_submit; ?>" class="btn btn-default"/>
                                <a href='<?php //echo admin_url($mod)?>' class="btn"><?php echo $btn_reset; ?></a>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>

            </div>

            <?php echo t('html')->end('form'); ?>
        </div>
    </div>

    <?php return ob_get_clean();
});


/**
 * Tao select options
 */
$this->register('make_options', function (array $options, $label = '', $value = '') {
    $result = [$value => $label];

    foreach ($options as $k => $v) {
        $result[$k] = $v;
    }

    return $result;
});



/**
 * Form submit
 */
$this->register('submit', function($value = null, $attr = array()){ ob_start(); ?>
    
    <?php 
        $value  = $value ?: lang('button_submit');
        $attr   = array_merge(array('class' => 'btn btn-default'), $attr);
    ?>

    <div class="form-group">
        <div class="col-sm-offset-4 col-sm-9">
            <?php echo t('html')->submit($value, $attr); ?>
        </div>
    </div>

<?php return ob_get_clean(); });