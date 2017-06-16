<?php
/**
 * Form
 */

$this->register('form', function (array $input) {
    ob_start(); ?>

    <?php
    $mod = array_get($input, 'mod', t('uri')->rsegment(1));
    $title = array_get($input, 'title');
    $action = array_get($input, 'action', current_url(TRUE));
    $attr = array_get($input, 'attr', array());

    $rows = array_get($input, 'rows', array());
    $data = array_get($input, 'data', array());
    $btn_submit = array_get($input, 'btn_submit', lang('button_submit'));
    $btn_reset = array_get($input, 'btn_reset', lang('button_cancel'));
    $_id = '_' . random_string('unique');
    ?>

    <div class="portlet">
        <div class="portlet-heading bg-primary">
            <div class="portlet-title">
                <h4>
                    <i class="fa fa-pencil"></i>
                    <?php echo $title; ?></h4>
            </div>
            <div class="portlet-widgets">
                <span class="divider"></span>
                <a data-parent="#accordion" data-toggle="collapse" href="#<?php echo $_id; ?>"><i
                        class="fa fa-chevron-down"></i></a>
            </div>
        </div>
        <div class="panel-collapse collapse in" id="<?php echo $_id; ?>">
            <div class="portlet-body ">
                <?php echo t('html')->form($action, array_merge(array('class' => 'form form-horizontal ', 'id' => 'form'), $attr)); ?>

                <?php
                foreach ($rows as $row) {
                    if (is_string($row)) {
                        echo $row;
                    } else {
                        if (isset($row['param']))
                            $row['value'] = array_get($row, 'value', array_get($data, $row['param']));
                        else
                            $row['param'] = '';
                        echo $this->macro->row($row,$rows);
                    }
                }
                ?>

                <div class="form-actions">
                    <div class="form-group formSubmit">
                        <div class="col-sm-offset-3 col-sm-9">
                            <input type="submit" value="<?php echo $btn_submit; ?>" class="btn btn-primary"/>
                            <a href='<?php echo admin_url($mod) ?>' class="btn"><?php echo $btn_reset; ?></a>
                        </div>
                    </div>
                </div>
                <?php echo t('html')->end('form'); ?>
            </div>
        </div>
    </div>
    <?php return ob_get_clean();
});


/**
 * Form row
 */

/*
*
* Danh sach cac tuy chon:
* 	'type'			= Loai bien. VD: text.
*   Cac loai bien duoc ho tro:
* 					  ob,separate,static,hidden,text,number, password textarea, html, bool, select, radio, checkbox,
*                    select_multi, spinner, date, color, file, file_multi, image, image_multi
* 	'name'			= Tieu de cua bien
* 	'value'			= Gia tri mac dinh
* 	'values'		= Cac gia tri tuy chon cua bien, ap dung voi type = select, select_multi, radio, checkbox
* 						VD: array('value1' => 'Name1', 'value2' => 'Name2', ...)
*/
$this->register('row', function (array $row,$rows=null) {
    ob_start(); ?>

    <?php

    $type = array_get($row, 'type', 'text');
    $param = array_get($row, 'param', '');
    $name = array_get($row, 'name', lang($param));
    $value = array_get($row, 'value');

    ?>
    <?php if ($type == 'hidden'): ?>
        <input type="hidden" name="<?php echo $param ?>" value="<?php echo $value ?>">

     <?php elseif($type == 'separate'): ?>
            <hr>
        <?php if($name): ?>
            <h4 class="mb20"><?php echo $name ?></h4>
            <?php endif; ?>
    <?php else: ?>

        <?php
        $refer = array_get($row, 'refer');
        $refer_value = array_get( $row, 'refer_value', null );
        $ajax = ( array_get($row, 'ajax') ? (object) array_get($row, 'ajax') : null ) ;
        $length_search = array_get( $row, 'length_search', null );
        $delay_search = array_get( $row, 'delay_search', null );

        $_id = '_' . random_string('unique');


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
        if ($values_row) {
            $values_row = object_to_array($values_row);
        }

        $req = array_get($row, 'req');
        $desc = array_get($row, 'desc');
        $unit = array_get($row, 'unit');
        $extra = array_get($row, 'extra');
        $attr = array_get($row, 'attr', array());
        //$attr_row 	= array_get($row, 'attr_row', array());
        $attr['id'] = $_id;
        ?>



        <?php
        // ??i t??ng hi?n th? ph? thu?c
        $hide = false;
        if( $refer && $rows)
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
                        } else if( $tmp_value != $refer_value )
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
            <script type="text/javascript" defer="defer">
                $('input[name=<?php echo $refer ?>], select[name=<?php echo $refer ?>], select[name="<?php echo $refer ?>[]"]').on('change', function(){
                    if( $(this).attr('type') == 'checkbox' )
                        var value = $(this).is(':checked');
                    else
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



        ?>
        <div class="form-group <?php echo 'param_' . $type; ?>   <?php echo ( $refer ? 'refer_' . $param : '' ) ?>" <?php echo ( $hide ? 'style="display: none;"' : '' ) ?>>

            <?php if ($name): ?>

				<label class="col-sm-3  control-label " for="<?php echo $_id; ?>">

					<?php
					$_name = $name;
					if ($unit) $_name .= ' (' . $unit . ')';
					if ($_name) $_name .= ':';

					echo ucfirst($_name);
					?>

					<?php if ($req) echo t('html')->span('*', array('class' => 'req')); ?>

				</label>

			<?php endif; ?>


            	<div class="<?php echo $name ? 'col-sm-9' : 'col-sm-12'; ?>">

                    <!--<div class=" input-group">-->
                    <?php if ($type == 'ob'): ?>
                        <?php echo $value; ?>

                    <?php elseif ($type == 'static'): ?>
                        <div <?php echo t('html')->attr(array_merge(array('style' => 'font-size:16px; font-weight:600; padding-top:5px;'), $attr)) ?>>
							<?php echo $value ?>
						</div>


                    <?php elseif ($type == 'text'): ?>
                        <?php echo t('html')->input($param, $value, array_merge(array('class' => 'form-control '), $attr)); ?>

                    <?php elseif ($type == 'number'): ?>
                    <?php echo t('html')->input($param, $value, array_merge(array('class' => 'form-control input_number'), $attr)); ?>

                <?php elseif ($type == 'password'): ?>
                        <?php echo t('html')->password($param, null, array_merge(array('class' => 'form-control'), $attr)); ?>

                    <?php elseif ($type == 'password'): ?>
                        <?php echo t('html')->password($param, null, array_merge(array('class' => 'form-control'), $attr)); ?>

                    <?php elseif ($type == 'textarea'): ?>
                        <?php echo t('html')->textarea($param,
                            $value,
                            array_merge(array('size' => '4', 'class' => ' form-control'), $attr)
                        );
                        ?>

                    <?php elseif ($type == 'spinner'): ?>
                        <div class="spinner">
                            <div class="input-group input-medium">
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


                    <?php elseif ($type == 'html'): ?>
                        <textarea name="<?php echo $param; ?>" id="<?php echo $_id; ?>" class="form-control editor"
                                  _config='{
						"height": <?php echo array_pull($attr, 'height', 200); ?>
					}'
                            <?php echo t('html')->attr($attr); ?>
                            ><?php echo $value; ?></textarea>


                    <?php elseif ($type == 'bool' || $type == 'bool_status'): ?>

                        <?php
                        if (empty($values)) {
                            if ($type == 'bool')
                                $values = array(lang('no'), lang('yes'));
                            else
                                $values = array(lang('off'), lang('on'));

                            //$value = is_null($value) ? true : $value; // set valua mac dinh neu khong truyen
                        } else
                            $values = (array)$values; ?>

                        <?php if (count($values) == 1): ?>

                            <label class="tcb-inline"><?php
                                echo t('html')->checkbox($param, 1, form_set_checkbox(1, (int)$value), array_merge(array('class' => 'tc'), $attr));
                                ?>
                                <span class="labels"><?php echo reset($values); ?></span>
                            </label>

                        <?php /* elseif (count($values) == 2): ?>
					 <label  class="mt5">

					 <?php 	echo t('html')->checkbox($param, 1, form_set_checkbox(1, (int)$value), array_merge(array('class' => 'tc tc-switch tc-switch-7'), $attr));?>
						 <span class="labels "></span>
					 </label>
					 <?php */ else: ?>
                            <?php foreach ($values as $v => $n): ?>
                                <label class="tcb-inline "><?php
                                    echo t('html')->radio($param, $v, form_set_checkbox((int)$v, (int)$value), array_merge(array('class' => 'tc'), $attr));
                                    ?>
                                    <span class="labels"><?php echo $n ?></span>
                                </label>
                            <?php endforeach; ?>
                        <?php endif; ?>


                    <?php elseif ($type == 'select' || $type == 'select_multi'): ?>

                    <?php if ($type == 'select'): ?>
                    <select name="<?php echo $param; ?>" class="form-control " style="width:100%;"
                        <?php echo t('html')->attr($attr); ?>
                        >
                        <?php else: ?>
                        <select name="<?php echo $param; ?>[]" multiple="multiple" class="form-control select_multi"
                                style="width:100%;"
                            <?php echo t('html')->attr($attr); ?>
                            >
                            <?php endif; ?>
                            <?php if (!array_get($values_opts, 'value_required', false)): ?>
                                <option value="">-=<?php echo lang('select_choice') ?>=-</option>
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


                        <?php elseif ($type == 'radio'): ?>
                            <?php foreach ($values as $v => $n): ?>
                                <label class="tcb-inline"><?php
                                    echo t('html')->radio($param, $v, form_set_checkbox($v, $value), array_merge(array('class' => 'tc'), $attr));
                                    ?>
                                    <span class="labels"><?php echo $n; ?></span>
                                </label>

                                <?php if (count($values) > 2): ?>
                                    <div class="clear"></div>
                                <?php endif; ?>
                            <?php endforeach; ?>


                        <?php elseif ($type == 'checkbox'): ?>
                            <?php foreach ($values as $v => $n): ?>
                                <label class="tcb-inline"><?php
                                    echo t('html')->checkbox($param . '[]', $v, form_set_checkbox($v, $value), array_merge(array('class' => 'tc'), $attr));
                                    ?>
                                    <span class="labels"><?php echo $n; ?></span>
                                </label>

                                <?php if (count($values) > 2): ?>
                                    <div class="clear"></div>
                                <?php endif; ?>
                            <?php endforeach; ?>


                        <?php elseif ($type == 'date'): ?>
                            <input name="<?php echo $param; ?>"
                                   value="<?php echo (is_numeric($value)) ? get_date($value) : $value; ?>"
                                   id="<?php echo $_id; ?>" class="date_picker mask_datess" style="width:100px;"
                                   type="text"
                                <?php echo t('html')->attr($attr); ?>
                                />


                        <?php elseif ($type == 'color'): ?>
                            <div class="color_picker">
                                <div></div>
                                <span>Choose color...</span>
                                <input name="<?php echo $param; ?>" value="<?php echo $value; ?>" type="hidden"/>
                            </div>


                        <?php elseif (in_array($type, array('file', 'image', 'file_multi', 'image_multi'))): ?>
                            <?php t('widget')->admin->upload($row['_upload']); ?>


                        <?php elseif ($type == 'custom'): ?>

                            <?php echo $row['html']; ?>

<?php

                            /* Search */
                            elseif ($type == 'search'):
                            {
                            ?>

                            <div class="quick-search">
                                <?php
                                echo t('html')->input( "s_$param", '', array_merge( array(
                                    'class' => 'form-control',
                                    'placeholder' => lang('search') . '...'
                                ), $attr ) );
                                ?>
                                <ul id="quick-<?php echo $param; ?>-result" class="quick-search-result"></ul>
                                <div id="quick-<?php echo $param; ?>-choosen" class="quick-search-choosen">
                                    <?php
                                    if($value)
                                    {
                                        ?>
                                        <div class="item">
                                            <img src="<?php echo isset( $value->_avatar->url_thumb ) ? $value->_avatar->url_thumb : $value->_avatar ?>" />
                                            <p><strong><?php echo $value->{$values_row[2]} ?></strong></p>
                                            <?php
                                            if( isset($value->_information) )
                                            {
                                                ?>
                                                <p><?php echo $value->_information ?></p>
                                                <?php
                                            }
                                            ?>
                                            <a href="javascript:;" class="close" data-obj="<?php echo $param ?>" ><i class="fa fa-close"></i></a>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <input type="hidden" name="<?php echo $param ?>" value="<?php  echo $value ? $value->id : '' ?>" />

                                <script type="text/javascript">
                                    $(document).ready(function()
                                    {
                                        var interval<?php echo $param; ?>;
                                        $('input[name=s_<?php echo $param; ?>]').on('keyup', function(){
                                            var value = $.trim($(this).val());
                                            var ids = $('input[name="<?php echo $param ?>"]').val();

                                            if( value.length >= <?php echo $length_search != null ? $length_search : 3 ?> )
                                            {
                                                clearTimeout(interval<?php echo $param; ?>);
                                                interval<?php echo $param; ?> = setTimeout( function(){
                                                    $.ajax({
                                                        type: "post",
                                                        url: "<?php echo admin_url( t('uri')->rsegment(1) . '/search' . $ajax->loader ) ?>",
                                                        data: {
                                                            value: value,
                                                            ids: ids,
                                                            source: '<?php echo $ajax->source ?>'
                                                        },
                                                        success: function(output){
                                                            var data = $.parseJSON( output );
                                                            var rs = '';
                                                            $.each(data, function(i, k){
                                                                rs += '<li data-value="'+k.id+'" data-thumb="'+k._thumb+'" data-information="'+k._information+'">'+ k.<?php echo $values_row[2] ?> +'</li>';
                                                            });
                                                            $('#quick-<?php echo $param; ?>-result').html(rs);
                                                            $('#quick-<?php echo $param; ?>-result').addClass('click-to-hide');
                                                            $('#quick-<?php echo $param; ?>-result').show();
                                                        }
                                                    });
                                                }, <?php echo $delay_search != null ? $delay_search : 1000 ?>);
                                            }
                                        });


                                        $('#quick-<?php echo $param; ?>-result').on('click', 'li', function(){
                                            search_choose( $(this), '<?php echo $param ?>' );
                                        });
                                    });

                                </script>
                            </div>


                            <?php
                        }

                        /* Search */
                        elseif ($type == 'search_multi'):
                        {
                            $owners_id = null;
                            if( $value )
                                $owners_id = array_gets( $value, 'id' );
                            ?>

                            <div class="quick-search">
                                <?php
                                echo t('html')->input( "s_$param", '', array_merge( array(
                                    'class' => 'form-control',
                                    'placeholder' => lang('search') . '...'
                                ), $attr ) );
                                ?>
                                <ul id="quick-<?php echo $param; ?>-result-multiple" class="quick-search-result"></ul>
                                <div id="quick-<?php echo $param; ?>-choosen" class="quick-search-choosen">
                                    <?php
                                    if($value)
                                    {
                                        foreach ($value as $value_info)
                                        {
                                            ?>
                                            <div class="item-multiple">
                                                <img src="<?php echo isset( $value_info->_avatar->url_thumb ) ? $value_info->_avatar->url_thumb : $value_info->_avatar ?>" />
                                                <p><strong><?php echo $value_info->{$values_row[2]} ?></strong></p>
                                                <?php
                                                if( isset($value_info->_information) )
                                                {
                                                    ?>
                                                    <p><?php echo $value_info->_information ?></p>
                                                    <?php
                                                }
                                                ?>
                                                <a href="javascript:;" class="close" data-obj="<?php echo $param ?>" data-id="<?php echo $value_info->id ?>" ><i class="fa fa-close"></i></a>
                                            </div>
                                            <?php
                                        }

                                    }
                                    ?>
                                </div>
                                <input type="hidden" name="<?php echo $param ?>" value="<?php  echo $owners_id ? htmlentities(json_encode($owners_id)) : '' ?>" />

                                <script type="text/javascript">
                                    $(document).ready(function()
                                    {
                                        var interval<?php echo $param; ?>;
                                        $('input[name=s_<?php echo $param; ?>]').on('keyup', function(){
                                            var value = $.trim($(this).val());
                                            var ids = $('input[name="<?php echo $param ?>"]').val();

                                            if( value.length >= <?php echo $length_search != null ? $length_search : 3 ?> )
                                            {
                                                clearTimeout(interval<?php echo $param; ?>);
                                                interval<?php echo $param; ?> = setTimeout( function(){
                                                    $.ajax({
                                                        type: "post",
                                                        url: "<?php echo admin_url( t('uri')->rsegment(1) . '/search' . $ajax->loader ) ?>",
                                                        data: {
                                                            value: value,
                                                            ids: ids,
                                                            <?php
                                                                if( isset($ajax->source) )
                                                                    echo 'source: ' . $ajax->source;
                                                            ?>
                                                        },
                                                        success: function(output)
                                                        {
                                                            var data = $.parseJSON( output );
                                                            var rs = '';
                                                            $.each(data, function(i, k){
                                                                rs += '<li data-value="'+k.id+'" data-thumb="'+k._thumb+'" data-information="'+k._information+'">'+ k.<?php echo $values_row[2] ?> +'</li>';
                                                            });
                                                            $('#quick-<?php echo $param; ?>-result-multiple').html(rs);
                                                            $('#quick-<?php echo $param; ?>-result-multiple').addClass('click-to-hide');
                                                            $('#quick-<?php echo $param; ?>-result-multiple').show();
                                                        }
                                                    });
                                                }, <?php echo $delay_search != null ? $delay_search : 1000 ?>);
                                            }
                                        });


                                        $('#quick-<?php echo $param; ?>-result-multiple').on('click', 'li', function(){
                                            search_multiple_choose( $(this), '<?php echo $param ?>' );
                                        });
                                    });
                                </script>
                            </div>


                            <?php
                        }?>
                        <?php endif; ?>

                        <!--</div>-->

                        <?php if($param): ?>
                            <div class="clear"></div>
                            <div name="<?php echo $param; ?>_error" class="error help-block"></div>
                        <?php endif; ?>
                        <?php if ($desc): ?>
                            <small><?php echo $desc; ?></small>
                        <?php endif; ?>

                        <?php echo $extra; ?>


            </div>
            <div class="clearfix"></div>
        </div>
    <?php endif; ?>
    <?php return ob_get_clean();
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
    $is_ajax = t("input")->is_ajax_request();;
    ?>
    <?php if($is_ajax): ?>
        <script type="text/javascript" src="<?php echo public_url() ?>/js/admin.js" type="text/javascript"></script>
        <?php endif; ?>
    <div class="portlet <?php echo $is_ajax?" container":""; ?> " <?php echo $is_ajax?'style="height: 70%"':'' ?> >
        <div class="portlet-heading bg-primary">
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
                                     src="<?php echo public_url("img/world/".strtolower($lang['key']).".gif"); ?>">
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
                                <input type="submit" value="<?php echo $btn_submit; ?>" class="btn btn-primary"/>
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
 * Row title
 */
$this->register('row_title', function($title){ ob_start(); ?>

    <h4 class="text-primary"><?php echo $title; ?></h4>

<?php return ob_get_clean(); });
