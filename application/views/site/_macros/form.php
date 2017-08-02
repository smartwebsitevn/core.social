<?php

/**
 * Form
 */
$this->register('form', function (array $input) {
    $action = array_get($input, 'action', current_url(TRUE));
    $attr = array_get($input, 'attr', []);
    $title = array_get($input, 'title');
    $rows = array_get($input, 'rows', []);
    $data = array_get($input, 'data', []);
    $btn_submit = array_get($input, 'btn_submit', lang('button_submit'));
    $btn_reset = array_get($input, 'btn_reset', lang('button_reset'));

    $make_form = function () use ($action, $attr, $rows, $data, $btn_submit, $btn_reset) {
        ob_start(); ?>

        <?php echo t('html')->form($action, array_merge(array('class' => 'form-horizontal form_action'), $attr)); ?>

        <?php
        foreach ($rows as $row) {

            if (is_string($row)) {
                echo $row;
            } else {
                $row['value'] = array_get($row, 'value', array_get($data, $row['param']));

                echo $this->macro->row($row);
            }
        }

        echo $this->macro->submit($btn_submit);
        ?>

        <?php echo t('html')->end('form'); ?>

        <?php return ob_get_clean();
    };

    if (!$title) return $make_form();

    return macro('mr::box')->box([
        'title' => $title,
        'body' => $make_form(),
    ]);
});


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
$this->register('row', function (array $row, $rows = null) {
    ob_start(); ?>

    <?php

    $type = array_get($row, 'type', 'text');
    $param = array_get($row, 'param', '');
    $name = array_get($row, 'name', lang($param));
    $value = array_get($row, 'value');

    ?>
    <?php if ($type == 'hidden'): ?>
        <input type="hidden" name="<?php echo $param ?>" value="<?php echo $value ?>">

    <?php elseif ($type == 'separate'): ?>
        <hr>
        <?php if ($name): ?>
            <h4 class="mb20"><?php echo $name ?></h4>
        <?php endif; ?>
    <?php else: ?>

        <?php
        $refer = array_get($row, 'refer');
        $refer_value = array_get($row, 'refer_value', null);
        $ajax = (array_get($row, 'ajax') ? (object)array_get($row, 'ajax') : null);
        $length_search = array_get($row, 'length_search', null);
        $delay_search = array_get($row, 'delay_search', null);

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
//if($param =="country")		pr($row);
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
        if ($refer && $rows) {
            foreach ($rows as $item) {
                if (array_get($item, 'param', '') == $refer) {
                    $tmp_value = array_get($item, 'value');
                    if ($refer_value !== null) {
                        if (is_array($refer_value)) {
                            if (!in_array($tmp_value, $refer_value)) {
                                $hide = true;
                            }
                        } else if ($tmp_value != $refer_value)
                            $hide = true;
                    } else {
                        if (!$tmp_value)
                            $hide = true;
                    }
                    break;
                }
            }

            ?>
            <script type="text/javascript" defer="defer">
                $('input[name=<?php echo $refer ?>], select[name=<?php echo $refer ?>], select[name="<?php echo $refer ?>[]"]').on('change', function () {
                    if ($(this).attr('type') == 'checkbox')
                        var value = $(this).is(':checked');
                    else
                        var value = $(this).val();

                    <?php
                        if( $refer_value !== null )
                        {
                    ?>
                    if (value == <?php echo $refer_value ?>)
                        <?php
                            }
                            else
                            {
                        ?>
                        if (value)
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
                                success: function (output) {
                                    var data = $.parseJSON(output);
                                    var rs = '<option value="">-=<?php echo lang('select_choice') ?>=-</option>';
                                    $.each(data, function (i, k) {
                                        rs += '<option value="' + k.id + '">' + k.<?php echo $values_row[2] ?> + '</option>';
                                    });
                                    $('.refer_<?php echo $param ?> select').html(rs);
                                    $('.refer_<?php echo $param ?> select').select2("val", "");
                                }
                            })
                            <?php
                        }
                    ?>
                            $('.refer_<?php echo $param ?>').slideDown('250');
                        }
                        else {
                            $('.refer_<?php echo $param ?>').slideUp('250');
                        }
                });
            </script>
            <?php

        }


        ?>
        <div
            class="form-group <?php echo 'param_' . $type; ?>   <?php echo($refer ? 'refer_' . $param : '') ?>" <?php echo($hide ? 'style="display: none;"' : '') ?>>

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
                <select name="<?php echo $param; ?>" class="form-control "
                    <?php echo t('html')->attr($attr); ?>
                    >
                    <?php else: ?>
                    <select name="<?php echo $param; ?>[]" multiple="multiple" class="form-control select_multi"

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

                                $row = isset($row) ? (array)$row : array();
                                ?>
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
                        <input
                            name="<?php echo $param; ?>"
                            value="<?php echo (is_numeric($value)) ? get_date($value) : $value; ?>"
                            id="<?php echo $_id; ?>"
                            class="form-control datepicker"
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
                        <?php t('widget')->site->upload($row['_upload']); ?>


                    <?php elseif ($type == 'captcha'): ?>
                        <div class="input-group form-captcha">

                            <input name="<?php echo $param; ?>" class="form-control" autocomplete="off" type="text"/>

				<span class="input-group-addon" style="padding:3px 12px">

					<img id="<?php echo $_id; ?>" src="<?php echo lib('captcha')->url(); ?>"
                         _captcha="<?php echo $value; ?>" class="captcha">

					<a href="#reset" onclick="change_captcha('<?php echo $_id; ?>'); return false;"
                       title="Reset captcha">
                        <i class="glyphicon glyphicon-repeat"></i>
                    </a>

				</span>

                        </div>


                    <?php elseif ($type == 'number'): ?>
                        <?php echo t('html')->input(
                            $param,
                            $value,
                            array_merge(array('class' => 'form-control input_number'), $attr)
                        ); ?>


                    <?php elseif ($type == 'static'): ?>
                        <div <?php echo t('html')->attr(array_merge(array('style' => 'font-size:16px; font-weight:600; padding-top:5px;'), $attr)) ?>>
                            <?php echo $value ?>
                        </div>


                    <?php elseif ($type == 'custom'): ?>

                        <?php echo $row['html']; ?>

                    <?php endif; ?>


                    <div class="clearfix"></div>

                    <div name="<?php echo $param; ?>_error" class="form-error"></div>

                    <?php if ($desc): ?>
                        <div class="form-help"><?php echo $desc; ?></div>
                    <?php endif; ?>


            </div>
            <div class="clearfix"></div>
        </div>
    <?php endif; ?>
    <?php return ob_get_clean();
});
/**
 * Form row
 */
$this->register('row_', function (array $row) {
    ob_start(); ?>

    <?php
    $_id = '_' . random_string('unique');

    $param = $row['param'];
    $name = array_get($row, 'name', lang($param));
    $type = array_get($row, 'type', 'text');
    $value = array_get($row, 'value');
    $values = array_get($row, 'values', array());
    $req = array_get($row, 'req');
    $desc = array_get($row, 'desc');
    $unit = array_get($row, 'unit');
    $attr = array_get($row, 'attr', array());

    $attr['id'] = $_id;
    ?>

    <div class="form-group <?php echo 'param_' . $type; ?>">

        <label class="col-sm-3 control-label" for="<?php echo $_id; ?>">

            <?php
            $_name = $name;
            if ($unit) $_name .= ' (' . $unit . ')';
            if ($_name) $_name .= ':';

            echo $_name;
            ?>

            <?php if ($req) echo t('html')->span('*', array('class' => 'req')); ?>
        </label>


        <div class="col-sm-9">

            <?php if ($type == 'text'): ?>
                <?php echo t('html')->input(
                    $param,
                    $value,
                    array_merge(array('class' => 'form-control'), $attr)
                ); ?>


            <?php elseif ($type == 'password'): ?>
                <?php echo t('html')->password(
                    $param,
                    $value,
                    array_merge(array('class' => 'form-control'), $attr)
                ); ?>


            <?php elseif ($type == 'textarea'): ?>
                <?php echo t('html')->textarea(
                    $param,
                    $value,
                    array_merge(array('size' => '2', 'class' => 'form-control autosize'), $attr)
                ); ?>


            <?php elseif ($type == 'html'): ?>
                <textarea name="<?php echo $param; ?>" id="<?php echo $_id; ?>"
                          _config='{
						"height": 200
					}'
                    <?php echo t('html')->attr(array_merge(array('class' => 'editor'), $attr)); ?>
                    ><?php echo $value; ?></textarea>


            <?php elseif ($type == 'bool'): ?>

                <?php $values = (empty($values)) ? array(lang('off'), lang('on')) : (array)$values; ?>

                <?php if (count($values) == 1): ?>

                    <label><?php
                        echo t('html')->checkbox($param, 1, form_set_checkbox(1, (int)$value), $attr);
                        echo reset($values);
                        ?></label>

                <?php else: ?>

                    <?php foreach ($values as $v => $n): ?>
                        <label><?php
                            echo t('html')->radio($param, $v, form_set_checkbox((int)$v, (int)$value), $attr);
                            echo $n;
                            ?></label>
                    <?php endforeach; ?>

                <?php endif; ?>


            <?php elseif ($type == 'select' || $type == 'select_multi'): ?>
            <?php if ($type == 'select'): ?>
            <select name="<?php echo $param; ?>"
                <?php echo t('html')->attr(array_merge(array('class' => 'form-control'), $attr)); ?>
                >
                <?php else: ?>
                <select name="<?php echo $param; ?>[]" multiple="multiple" class="left select2"
                    <?php echo t('html')->attr($attr); ?>
                    >
                    <?php endif; ?>
                    <?php foreach ($values as $v => $n): ?>
                        <option value="<?php echo $v; ?>" <?php echo form_set_select($v, $value); ?>
                            ><?php echo $n; ?></option>
                    <?php endforeach; ?>
                </select>


                <?php elseif ($type == 'radio'): ?>
                    <?php foreach ($values as $v => $n): ?>
                        <label><?php
                            echo t('html')->radio($param, $v, form_set_checkbox($v, $value), $attr);
                            echo $n;
                            ?></label>

                        <?php if (count($values) > 2): ?>
                            <div class="clear"></div>
                        <?php endif; ?>
                    <?php endforeach; ?>


                <?php elseif ($type == 'checkbox'): ?>
                    <?php foreach ($values as $v => $n): ?>
                        <label><?php
                            echo t('html')->checkbox($param . '[]', $v, form_set_checkbox($v, $value), $attr);
                            echo $n;
                            ?></label>

                        <?php if (count($values) > 2): ?>
                            <div class="clear"></div>
                        <?php endif; ?>
                    <?php endforeach; ?>


                <?php elseif ($type == 'date'): ?>
                    <input
                        name="<?php echo $param; ?>"
                        value="<?php echo (is_numeric($value)) ? get_date($value) : $value; ?>"
                        id="<?php echo $_id; ?>"
                        class="form-control datepicker"
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
                    <?php t('widget')->site->upload($row['_upload']); ?>


                <?php elseif ($type == 'captcha'): ?>
                    <div class="input-group form-captcha">

                        <input name="<?php echo $param; ?>" class="form-control" autocomplete="off" type="text"/>

				<span class="input-group-addon" style="padding:3px 12px">

					<img id="<?php echo $_id; ?>" src="<?php echo lib('captcha')->url(); ?>"
                         _captcha="<?php echo $value; ?>" class="captcha">

					<a href="#reset" onclick="change_captcha('<?php echo $_id; ?>'); return false;"
                       title="Reset captcha">
                        <i class="glyphicon glyphicon-repeat"></i>
                    </a>

				</span>

                    </div>


                <?php elseif ($type == 'number'): ?>
                    <?php echo t('html')->input(
                        $param,
                        $value,
                        array_merge(array('class' => 'form-control input_number'), $attr)
                    ); ?>


                <?php elseif ($type == 'static'): ?>
                    <div <?php echo t('html')->attr(array_merge(array('style' => 'font-size:16px; font-weight:600; padding-top:5px;'), $attr)) ?>>
                        <?php echo $value ?>
                    </div>


                <?php elseif ($type == 'custom'): ?>

                    <?php echo $row['html']; ?>

                <?php endif; ?>


                <div class="clearfix"></div>

                <div name="<?php echo $param; ?>_error" class="form-error"></div>

                <?php if ($desc): ?>
                    <div class="form-help"><?php echo $desc; ?></div>
                <?php endif; ?>

        </div>

    </div>

    <?php return ob_get_clean();
});


/**
 * Form captcha
 */
$this->register('captcha', function ($args = []) {
    ob_start(); ?>

    <?php
    $args = (!is_array($args)) ? array('url' => $args) : $args;

    $param = array_get($args, 'param', 'security_code');
    $url = array_get($args, 'url', lib('captcha')->url());
    $opts = array_get($args, 'opts', '');

    $layout_opts = array_get($args, 'layout_opts', array());
    $label_col = array_get($layout_opts, 'label_col', 3);
    $input_col = array_get($layout_opts, 'input_col', 9);
    $_id = random_string('unique');
    ?>

    <div class="form-group">

        <label class="col-sm-<?php echo $label_col ?> control-label">
            <?php echo lang($param); ?>:
            <span class="req">*</span>
        </label>

        <div class="col-sm-<?php echo $input_col ?>">

            <div class="input-group form-captcha">

                <input name="<?php echo $param; ?>" class="form-control" autocomplete="off" type="text"/>

				<span class="input-group-addon" style="padding:3px 12px">

					<img id="<?php echo $_id; ?>" src="<?php echo $url; ?>" _captcha="<?php echo $url; ?>"
                         class="captcha">

					<a href="#reset" onclick="change_captcha('<?php echo $_id; ?>'); return false;"
                       title="Reset captcha">
                        <i class="glyphicon glyphicon-repeat"></i>
                    </a>
				</span>

            </div>

            <div name="<?php echo $param; ?>_error" class="form-error"></div>

        </div>

    </div>

    <?php return ob_get_clean();
});


/**
 * Form submit
 */
$this->register('submit', function ($value = null, $attr = array()) {
    ob_start(); ?>

    <?php
    $value = $value ?: lang('button_submit');
    $attr = array_merge(array('class' => 'btn btn-default'), $attr);
    ?>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <?php echo t('html')->submit($value, $attr); ?>
        </div>
    </div>

    <?php return ob_get_clean();
});


/**
 * Row title
 */
$this->register('row_title', function ($title) {
    ob_start(); ?>

    <h4 class="text-primary"><?php echo $title; ?></h4>

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

$this->register('info', function (array $input) {
    ob_start(); ?>

    <?php
    $info = array_get($input, 'info');
    $lang = array_get($input, 'lang', null);
    $act_input = array_get($input, 'act_input', 'act-input-dropdown');// cho phep thay doi cac su ly tao input

    //==
    $type = array_get($input, 'type', 'text');
    $name = array_get($input, 'name');
    $param = array_get($input, 'param');
    $value = array_get($input, 'value', '');// value la dang don
    $value_default = array_get($input, 'value_default', '');// value la dang don

    //==
    $req = array_get($input, 'req');
    $desc = array_get($input, 'desc', '');
    $unit = array_get($input, 'unit', '');
    $placeholder = array_get($input, 'placeholder', '');
    $show_error = array_get($input, 'show_error', true);// value la dang don

    //== holder
    $class = array_get($input, 'class', '');
    $attr = array_get($input, 'attr', array());
    // if($param =='cat_j_type_id')  pr($attr);
    //== input
    $input_class = array_get($input, 'input_class', '');
    $input_attr = array_get($input, 'input_attr', array());

    $linked = array_get($input, 'linked','');// value lien ket
    $can_hide = array_get($input, 'can_hide', 0);
    //neu khong truyen
    /*if (!$linked) {
        if ($type == 'text')
            $linked = 'input-linked';
        elseif ($type == 'select')
            $linked = 'select-linked';
    }*/
    //$data_name ten de link toi input cua lang khac (ten nay dc dung khi thuoc tinh param no khac nhau o cac ngon ngu)
    $data_name = array_get($input, 'data_name', $param);

    // su ly co hien ten (name) cua input hay khong
    $_show_param_name = function ($p = null) use ($param, $lang) {
        $show = false;
        // neu khong truyen lang thi hien tat ca ten
        if (!$lang) $show = true;
        // neu co truyen lang , thi check neu la lang mac dinh moi hien ten
        elseif ($lang->is_default) $show = true;
        if ($show) {
            if ($p) $param = $p;
            echo ' name="' . $param . '" ';
        }

    }


    ?>
    <?php if ($name): ?>
    <div class="form-group  <?php echo $class ?>" <?php echo t('html')->attr($attr) ?> >

            <label>
                <?php echo $name ?><?php echo (!empty($unit) && $type != 'range') ? ' (' . $unit . ')' : ''; ?>
                <?php if ($req): ?><b class="red">*</b><?php endif; ?>
            </label>
            <div class="clearfix"></div>
        <?php endif; ?>


        <?php if ($type == 'text'): ?>
            <input type="text" <?php $_show_param_name() ?> value="<?php echo $value ?>"
                   data-name="<?php echo $data_name ?>"
                   class="form-control <?php echo ($lang) ? $linked : '' ?> <?php echo $input_class ?>"
                   placeholder="<?php echo $placeholder ?>" <?php echo t('html')->attr($input_attr) ?>>

        <?php elseif ($type == 'textarea'): ?>
            <textarea <?php $_show_param_name() ?> data-name="<?php echo $data_name ?>"
                                                   class="form-control <?php echo ($lang) ? $linked : '' ?>  <?php echo $input_class ?>"
                                                   rows="10"
                                                   placeholder="<?php echo $placeholder ?>" <?php echo t('html')->attr($input_attr) ?>><?php echo $value ?></textarea>

        <?php elseif ($type == 'number'): ?>
            <input type="text" <?php $_show_param_name() ?> data-name="<?php echo $data_name ?>"
                   value="<?php echo $value ?>" class="form-control <?php echo $linked ?> <?php echo $input_class ?>"
                   placeholder="<?php echo $placeholder ?>" <?php echo t('html')->attr($input_attr) ?>>
        <?php elseif ($type == 'range'):
            $f = $param . "_from";
            $t = $param . "_to"; ?>

            <input type="text" <?php $_show_param_name($f) ?> value="<?php echo $info[$f] ?>"
                   data-name="<?php echo $f ?>"
                   class="form-control  <?php echo $linked ?> <?php echo $input_class ?>"
                   placeholder="<?php echo $placeholder ?>" <?php echo t('html')->attr($input_attr) ?>>
            -
            <input type="text" <?php $_show_param_name($t) ?> value="<?php echo $info[$t] ?>"
                   data-name="<?php echo $t ?>"
                   class="form-control  <?php echo $linked ?> <?php echo $input_class ?>"
                   placeholder="<?php echo $placeholder ?>" <?php echo t('html')->attr($input_attr) ?>>
            <?php echo !$unit ?: ' (' . $unit . ')'; ?>
        <?php elseif ($type == 'select' || $type == 'select_multi'):
            // values la 1 doi tuong| mang gom key va name
            $values = array_get($input, 'values', array());
            // values la 1 doi tuong| mang gom 1 key
            $values_single = array_get($input, 'values_single', array());
            // values la 1 doi tuong| mang gom nhieu thuoc tinh
            $values_row = array_get($input, 'values_row', array());
            // cac option cua value
            $values_opts = array_get($input, 'values_opts', array());
            if ($values_single)
                $values_single = object_to_array($values_single);
            if ($values_row) {
                $values_row = object_to_array($values_row);
            }

            $not_show_in_value = isset($values_opts['not_show_in_value']) ? $values_opts['not_show_in_value'] : 0;// khong hien thi cac gia tri da chon trong danh s�h

            ?>
            <div class="dropdown search-dropdown <?php echo $input_class ?>" <?php echo t('html')->attr($input_attr) ?>>
                <div class="dropdown-toggle" aria-expanded="true" aria-haspopup="true" data-toggle="dropdown"
                     type="button">
					<span class="search-rendered">
                                                 <?php rendered_value($value, $values,$value_default); ?>

                                                 <?php /* if ($value == ''): ?><?php //echo 'All' //lang("all_u_education")?>
                        <?php else: ?>
                            <?php if ($values): ?><?php echo $values[$value] ?>
                            <?php elseif ($values_single): ?><?php echo $value ?>
                            <?php elseif ($values_row): ?><?php echo $values_row[$value] ?>
                            <?php endif; ?>
                        <?php endif; */
                                                 ?>
					</span>
                    <span class="search-caret"></span>
                </div>
                <span class="search-remove"></span>

                <?php if ($type == 'select'): ?>
                    <ul class="dropdown-menu  ">
                        <?php if ($values): ?>
                            <?php foreach ($values as $v => $n): ?>
                                <?php if ($not_show_in_value && $value == $v) continue; ?>
                                <li class="search-results  <?php echo $act_input ?>   <?php echo $linked ?> <?php echo ($value == $v) ? 'active active_filter' : '' ?>"
                                    data-name="<?php echo $data_name ?>" data-value="<?php echo $v ?>">
                                    <a class="search-results-option " href="#0">
                                        <?php echo lang(array_get($values_opts, 'name_prefix', '') . $n . array_get($values_opts, 'name_suffix', '')) ?>
                                    </a>
                                    <?php if ($value == $v): ?>
                                        <input type="hidden" name="<?php echo $param ?>" value="<?php echo $v ?>"/>
                                    <?php endif; ?>
                                </li>

                            <?php endforeach; ?>
                        <?php elseif ($values_single): ?>
                            <?php foreach ($values_single as $v): ?>
                                <?php if ($not_show_in_value && $value == $v) continue; ?>
                                <li class="search-results  <?php echo $act_input ?>   <?php echo $linked ?> <?php echo ($value == $v) ? 'active active_filter' : '' ?>"
                                    data-name="<?php echo $data_name ?>" data-value="<?php echo $v ?>">
                                    <a class="search-results-option " href="#0">
                                        <?php echo array_get($values_opts, 'text_prefix', '') . lang(array_get($values_opts, 'name_prefix', '') . $v . array_get($values_opts, 'name_suffix', '')) . array_get($values_opts, 'text_suffix', ''); ?>
                                    </a>
                                    <?php if ($value == $v): ?>
                                        <input type="hidden" name="<?php echo $param ?>" value="<?php echo $v ?>"/>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        <?php elseif ($values_row && count($values_row[0]) > 0): ?>
                            <?php foreach ($values_row[0] as $row):
                                $row = isset($row) ? (array)$row : array(); ?>
                                <?php if ($not_show_in_value && in_array($row[$values_row[1]], (array)$value)) continue; ?>

                                <li class="search-results  <?php echo $act_input ?>   <?php echo $linked ?> <?php echo ($value == $row[$values_row[1]]) ? 'active active_filter' : '' ?>"
                                    data-name="<?php echo $data_name ?>"
                                    data-value="<?php echo $row[$values_row[1]] ?>">
                                    <a class="search-results-option " href="#0"><?php echo $row[$values_row[2]] ?></a>
                                    <?php if ($value == $row[$values_row[1]]): ?>
                                        <input type="hidden" name="<?php echo $param ?>"
                                               value="<?php echo $row[$values_row[1]] ?>"/>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                <?php else: ?>
                    <div class="dropdown-menu dropdown-2colums clearfix">
                        <div class="dropdown-menu dropdown-menu-left  slimscroll">
                            <div class="form-group">
                                <input type="text" placeholder="" class="form-control lg searachSelect">
                            </div>

                            <?php if ($values): ?>
                                <?php /*foreach ($values as $v => $n): ?>
                                <li class="search-results  <?php echo $act_input ?>   <?php echo $linked ?> <?php echo ($value == $v) ? 'active' : '' ?>"
                                    data-name="<?php echo $data_name ?>" data-value="<?php echo $v ?>">
                                    <a class="search-results-option " href="#0">
                                        <?php echo lang(array_get($values_opts, 'name_prefix', '') . $n . array_get($values_opts, 'name_suffix', '')) ?>
                                    </a>
                                    <?php if ($value == $v): ?>
                                        <input type="hidden" name="<?php echo $param ?>" value="<?php echo $v ?>"/>
                                    <?php endif; ?>
                                </li>

                            <?php endforeach; */ ?>
                            <?php elseif ($values_single): /*?>
                            <?php foreach ($values_single as $v): ?>
                                <li class="search-results  <?php echo $act_input ?>   <?php echo $linked ?> <?php echo ($value == $v) ? 'active' : '' ?>"
                                    data-name="<?php echo $data_name ?>" data-value="<?php echo $v ?>">
                                    <a class="search-results-option " href="#0">
                                        <?php echo lang(array_get($values_opts, 'name_prefix', '') . $v . array_get($values_opts, 'name_suffix', '')) ?>
                                    </a>
                                    <?php if ($value == $v): ?>
                                        <input type="hidden" name="<?php echo $param ?>" value="<?php echo $v ?>"/>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; */ ?>
                            <?php elseif ($values_row && count($values_row[0]) > 0):
                                $_value = $values_row[1];
                                $_name = $values_row[2];
                                //echo $_value.$_name;

                                ?>
                                <?php foreach ($values_row[0] as $row): // pr($row);
                                $row = isset($row) ? (array)$row : array() ?>
                                <?php $checked = (is_array($value) && in_array($row[$_value], $value)) ? 1 : 0; ?>
                                <?php if ($not_show_in_value && $checked) continue; ?>
                                <div class="search-results checkbox <?php echo $checked ? ' active_filter' : ''; ?> ">
                                    <label>
                                        <input type="checkbox"
                                               name="<?php echo $param ?>[]"
                                               value="<?php echo $row[$_value] ?>" <?php //echo $checked ? 'checked="checked"':'';
                                        ?> >
                                <span class="<?php echo $linked ?>"
                                      data-type="checkbox"><?php echo $row[$_name] ?></span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                            <?php endif; ?>

                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <?php if ($can_hide):
            $hide_target = array_get($input, 'hide_target', $param); ?>
            <a style="width: 50px;margin-left:10px " data-param="<?php echo $hide_target ?>"
               class="removes hide_target " title="Xóa">
                <span class="icon"></span> Xóa
            </a>
        <?php endif; ?>

    <?php if($param && $show_error): ?>
        <div class="clearfix"></div>
        <div name="<?php echo $param; ?>_error" class="error"></div>
    <?php endif; ?>
    <?php if ($desc): ?>
        <small><?php echo $desc; ?></small>
    <?php endif; ?>
    <?php if ($name): ?>
    </div>
    <?php endif; ?>

    <?php return ob_get_clean();
});
$this->register('info_cat_single', function (array $input) {
    ob_start(); ?>

    <?php

    $info = array_get($input, 'info');
    $lang = array_get($input, 'lang');
    $linked = array_get($input, 'linked', 'select-linked');// value lien ket
    $name = array_get($input, 'name');
    $param = array_get($input, 'param');
    $value = array_get($input, 'value', '');// value la dang don
    $values = array_get($input, 'values', array());
    $req = array_get($input, 'req');
    //$_id = '_'.random_string('unique');
    ?>
    <div class="form-group ">
        <?php if ($name): ?>
            <label><?php echo $name ?> <?php if ($req): ?><b class="red">*</b><?php endif; ?></label>
        <?php endif; ?>

        <div class="dropdown search-dropdown">
            <div class="dropdown-toggle" aria-expanded="true" aria-haspopup="true" data-toggle="dropdown" type="button">
										<span class="search-rendered">
                                                                     <?php rendered_value($value, $values); ?>

                                                                     <?php /*if (!isset($info['_' . $param]->name)): ?>
                                                <?php echo 'All' //lang("all_u_education")?>
                                            <?php else: ?>
                                                <?php echo $info['_' . $param]->name ?>
                                            <?php endif; */ ?>
										</span>
                <span class="search-caret"></span>
            </div>
            <span class="search-remove"></span>
            <ul class="dropdown-menu  slimscroll">
                <?php foreach ($values as $row): ?>
                    <li class="search-results act-input <?php echo $linked ?> <?php echo ($value == $row->id) ? 'active' : '' ?>"
                        data-name="<?php echo $param ?>" data-value="<?php echo $row->id ?>">
                        <a class="search-results-option " href="#0"><?php echo $row->_content[$lang->id]->name ?></a>
                        <?php if ($value == $row->id): ?>
                            <input type="hidden" name="<?php echo $param ?>" value="<?php echo $row->id ?>"/>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="clearfix"></div>
        <div name="<?php echo $param; ?>_error" class="error"></div>
    </div>

    <?php return ob_get_clean();
});
$this->register('info_cat_multi', function (array $input) {
    ob_start(); ?>

    <?php
    $info = array_get($input, 'info');
    $lang = array_get($input, 'lang', null);
    $linked = array_get($input, 'linked', 'select-linked');// value lien ket
    $name = array_get($input, 'name');
    $param = array_get($input, 'param');
    $value = array_get($input, 'value', array());// value la dang mang
    $value = (array)$value;
    $values = array_get($input, 'values', array());
    $req = array_get($input, 'req');
    //$_id = '_'.random_string('unique');
    ?>

    <?php if ($name): ?>
        <div class="form-group ">
        <label class="col-sm-3  control-label "><?php echo $name ?><?php if ($req): ?><b
                class="red">*</b><?php endif; ?></label>
        <div class="col-sm-9">
    <?php endif; ?>
    <div class="dropdown search-dropdown">
        <div class="dropdown-toggle" aria-expanded="true" aria-haspopup="true" data-toggle="dropdown" type="button">
				<span class="search-rendered">

											<?php /*if (!isset($info['_' . $param . '_names'])): ?>
                                                <?php echo 'All' //lang("all_u_education")?>
                                            <?php else: ?>
                                                <?php echo $info['_' . $param . '_names'] ?>
                                            <?php endif; */ ?>
                                            <?php rendered_value($value, $values); ?>

				</span>
            <span class="search-caret"></span>
        </div>
        <span class="search-remove"></span>

        <div class="dropdown-menu dropdown-2colums clearfix">
            <div class="dropdown-menu dropdown-menu-left  slimscroll">
                <div class="form-group">
                    <input type="text" placeholder="" class="form-control lg searachSelect">
                </div>
                <?php foreach ($values as $row): ?>
                    <?php $checked = (is_array($value) && in_array($row->id, $value)) ? 1 : 0; ?>
                    <div class="search-results checkbox <?php //echo $checked ? ' active_filter ' : ''; ?> ">
                        <label>
                            <input type="checkbox"
                                   name="<?php echo $param ?>[]"
                                   value="<?php echo $row->id ?>" <?php echo empty($checked) ?: 'checked="checked"'; ?> >
                            <span class="<?php echo $linked ?>" data-type="checkbox"><?php echo $row->name ?></span>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div name="<?php echo $param; ?>_error" class="error"></div>

    <?php if ($name): ?>
        </div>
        </div>

    <?php endif; ?>
    <?php return ob_get_clean();
});
$this->register('info_job', function (array $input) {
    ob_start(); ?>

    <?php
    //$title 		= array_get($input, 'title', lang('title_login'));
    $info = array_get($input, 'info');
    $lang = array_get($input, 'lang');
    $linked = array_get($input, 'linked', 'select-linked');// value lien ket
    $name = array_get($input, 'name');
    $text_all = array_get($input, 'name');
    $param = array_get($input, 'param');
    $value = array_get($input, 'value', '');// value la dang don
    $values = array_get($input, 'values', array());
    //$_id = '_'.random_string('unique');
    ?>
    <div class="form-group ">
        <?php if ($name): ?>
            <label><?php echo $name ?></label>
        <?php endif; ?>

        <div class="dropdown search-dropdown">
            <div class="dropdown-toggle" aria-expanded="true" aria-haspopup="true" data-toggle="dropdown" type="button">
				<span class="search-rendered">
					<?php /*if (!isset($info['_' . $param]->name)): ?>
                        <?php echo $text_all ?>
                    <?php else: ?>
                        <?php echo $info['_' . $param]->name ?>
                    <?php endif; */ ?>
                    <?php rendered_value($value, $values); ?>

                    <span class="search-caret"></span>
            </div>
            <span class="search-remove"></span>

            <ul class="dropdown-menu  slimscroll noparentjob">
                <?php foreach ($values as $cat): ?>
                    <li class="search-results">
                        <a href="#0" data-value="<?php echo $cat->id ?>"><?php echo $cat->name ?></a>
                    </li>
                    <?php if ($cat->jobs): ?>

                        <?php foreach ($cat->jobs as $job): ?>
                            <li class="search-results act-input <?php echo $linked ?> <?php echo ($value == $job->id) ? 'active' : '' ?>"
                                data-name="<?php echo $param ?>" data-value="<?php echo $job->id ?>">
                                <a href="#0" class="search-results-option "><?php echo $job->name ?></a>
                                <?php if ($value == $job->id): ?>
                                    <input type="hidden" name="<?php echo $param ?>" value="<?php echo $job->id ?>"/>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>

                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="clearfix"></div>
        <div name="<?php echo $param; ?>_error" class="error"></div>
    </div>

    <?php return ob_get_clean();
});
$this->register('info_country', function (array $input) {
    ob_start(); ?>

    <?php
    $info = array_get($input, 'info');
    $lang = array_get($input, 'lang');
    $linked = array_get($input, 'linked', 'select-linked');// value lien ket
    $name = array_get($input, 'name');

    $param = array_get($input, 'param');
    $value = array_get($input, 'value', '');
    $values = array_get($input, 'values', array());
    $req = array_get($input, 'req');
    //$_id = '_'.random_string('unique');
    ?>

    <?php if ($name): ?>
        <div class="form-group ">

        <label class="col-sm-3  control-label "><?php echo $name ?> <?php if ($req): ?><b
                class="red">*</b><?php endif; ?></label>
        <div class="col-sm-9">
    <?php endif; ?>

    <div class="dropdown search-dropdown">
        <div class="dropdown-toggle" aria-expanded="true" aria-haspopup="true" data-toggle="dropdown" type="button">
				<span class="search-rendered">
                         <?php rendered_value($value, $values); ?>
                         <?php /*if (!isset($info['_' . $param]->name)): ?>
                                                <?php echo 'All' //lang("all_u_education")?>
                                            <?php else: ?>
                                                <?php echo $info['_' . $param]->name ?>
                                            <?php endif; */ ?>
				</span>
            <span class="search-caret"></span>
        </div>
        <span class="search-remove"></span>

        <div class="dropdown-menu dropdown-2colums clearfix">
            <div class="dropdown-menu dropdown-menu-left  slimscroll ">

                <div class="form-group">
                    <input type="text" placeholder="<?php echo $name ?>"
                           class="form-control lg searachSelect">
                </div>
                <ul>
                    <?php $path = public_url() . '/img/world/'; ?>
                    <?php foreach ($values as $group): ?>
                        <li class="search-results active " data-name="group<?php echo $param ?>"
                            data-value="<?php echo $group->id; ?>">
                            <a class="search-results-option" href="#"><?php echo $group->name; ?></a>
                        </li>
                        <?php foreach ($group->countries as $v):
                            $checked = 0;
                            if (is_array($value))
                                $checked = (in_array($v->id, $value)) ? 1 : 0;
                            else
                                $checked = $v->id == $value ? 1 : 0;

                            ?>
                            <li class="search-results act-input  <?php echo $linked ?> <?php echo $checked ? '  active_filter' : ''; ?>"
                                data-name="<?php echo $param ?>" data-value="<?php echo $v->id; ?>">
                                <a class="search-results-option" href="#" data-value="<?php echo $v->id; ?>">
                                    <img
                                        src="<?php echo $path . strtolower($v->id) . '.gif' ?>"> <?php echo $v->name; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </ul>

            </div>
        </div>


    </div>
    <div class="clearfix"></div>
    <div name="<?php echo $param; ?>_error" class="error"></div>
    <?php if ($name): ?>
        </div>
        </div>
    <?php endif; ?>

    <?php return ob_get_clean();
});
$this->register('info_country_multi', function (array $input) {
    ob_start(); ?>

    <?php
    $linked = array_get($input, 'linked', 'select-linked');// value lien ket
    $name = array_get($input, 'name');

    $param = array_get($input, 'param');
    $value = array_get($input, 'value', '');
    $values = array_get($input, 'values', array());
    $can_hide = array_get($input, 'can_hide', 0);
    //$_id = '_'.random_string('unique');
    ?>


    <?php if ($name): ?>
        <div class="form-group ">

        <label class="col-sm-3  control-label "><?php echo $name ?></label>
        <div class="col-sm-9">
    <?php endif; ?>

    <div class="dropdown search-dropdown">
        <div class="dropdown-toggle" aria-expanded="true" aria-haspopup="true" data-toggle="dropdown"
             type="button">
				<span class="search-rendered">
                    <?php rendered_value($value, $values); ?>
				</span>
            <span class="search-caret"></span>
        </div>
        <span class="search-remove"></span>

        <div class="dropdown-menu dropdown-2colums clearfix">
            <div class="dropdown-menu dropdown-menu-left  slimscroll ">
                <div class="form-group">
                    <input type="text" placeholder="<?php echo $name ?>" class="form-control lg searachSelect">
                </div>


                <?php $path = public_url() . '/img/world/'; ?>
                <?php foreach ($values as $v): ?>
                    <?php $checked = 0;
                    if (is_array($value))
                        $checked = (in_array($v->id, $value)) ? 1 : 0;
                    else
                        $checked = $v->id == $value ? 1 : 0;

                    ?>
                    <div class="search-results checkbox <?php echo $checked ? '  active_filter' : ''; ?>">
                        <label>
                            <input type="checkbox" name="<?php echo $param ?>[]" value="<?php echo $v->id ?>">
                                <span class=" <?php echo $linked ?>" data-type="checkbox">
                                     <img src="<?php echo $path . strtolower($v->id) . '.gif' ?>">
                                    <?php echo $v->name ?>
                                </span>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>


    </div>
    <?php if ($can_hide):
        $hide_target = array_get($input, 'hide_target', $param); ?>
        <a style="width: 50px;margin-left:10px " data-param="<?php echo $hide_target ?>"
           class="  removes hide_target " title="X�a">
            <span class="icon"></span> X�a
        </a>
    <?php endif; ?>
    <div class="clearfix"></div>
    <div name="<?php echo $param; ?>_error" class="error"></div>

    <?php if ($name): ?>
        </div>
        </div>

    <?php endif; ?>

    <?php return ob_get_clean();
});
$this->register('info_city', function (array $input) {
    ob_start(); ?>

    <?php
    //$linked = array_get($input, 'linked', 'select-linked');// value lien ket
    $name = array_get($input, 'name');
    $param = array_get($input, 'param');
    $value = array_get($input, 'value', []);
    $values = array_get($input, 'values', array());
    $can_hide = array_get($input, 'can_hide', 0);
    ?>
    <?php if ($name): ?>
        <div class="form-group ">
        <label class="col-sm-3  control-label "><?php echo $name ?></label>
        <div class="col-sm-9">
    <?php endif; ?>

    <div class="dropdown search-dropdown">
        <div class="dropdown-toggle" aria-expanded="true" aria-haspopup="true" data-toggle="dropdown" type="button">
				<span class="search-rendered">
                    <?php rendered_value($value, $values); ?>
				</span>
            <span class="search-caret"></span>
        </div>
        <span class="search-remove"></span>

        <div class="dropdown-menu p10">
            <div class="form-group">
                <input type="text" placeholder="Nhập tên thành phố muốn tìm<?php //echo lang("city_out_the_country") ?>"
                       class="form-control lg searachSelect">
            </div>
            <div class="slimscroll limit-height">
                <?php
                foreach ($values as $row) {
                    $checked = false;
                    if ($value)
                        $checked = (in_array($row->id, $value)) ? 1 : 0;
                    //pr($checked);
                    ?>
                    <div class="search-results checkbox <?php // echo $checked ? '  active_filter' : ''; ?>">
                        <label>
                            <input id="_<?php echo $param . $row->id ?>" type="checkbox" name="<?php echo $param ?>[]"
                                   value="<?php echo $row->id ?>" <?php echo $checked ? '  checked' : ''; ?> >
                                <span for="_<?php echo $param . $row->id ?>" class=" <?php //echo $linked ?>"
                                      data-type="checkbox"><?php echo $row->name ?></span>
                        </label>
                    </div>
                <?php } ?>
            </div>
        </div>


    </div>

    <?php if ($can_hide):
        $hide_target = array_get($input, 'hide_target', $param); ?>
        <a style="width: 50px;margin-left:10px " data-param="<?php echo $hide_target ?>"
           class="  removes hide_target " title="Xóa">
            <span class="icon"></span> Xóa
        </a>
    <?php endif; ?>
    <div class="clearfix"></div>
    <div name="<?php echo $param; ?>_error" class="error"></div>

    <?php if ($name): ?>
        </div>
        </div>

    <?php endif; ?>

    <?php return ob_get_clean();
});
$this->register('info_city_country', function (array $input) {
    ob_start(); ?>

    <?php
    $info = array_get($input, 'info');
    $lang = array_get($input, 'lang');
    $linked = array_get($input, 'linked', 'select-linked');// value lien ket
    $name = array_get($input, 'name');
    $text_all = array_get($input, 'name');

    // city
    $city_name = array_get($input, 'city_name');
    $city_param = array_get($input, 'city_param');
    $city_value = array_get($input, 'city_value', '');
    $city_values = array_get($input, 'city_values', array());
    // country
    $country_name = array_get($input, 'country_name');
    $country_param = array_get($input, 'country_param');
    $country_value = array_get($input, 'country_value', '');
    $country_values = array_get($input, 'country_values', array());

    //$_id = '_'.random_string('unique');
    ?>
    <div class="form-group ">
        <?php if ($name): ?>
            <label><?php echo $name ?></label>
        <?php endif; ?>

        <div class="dropdown search-dropdown">
            <div class="dropdown-toggle" aria-expanded="true" aria-haspopup="true" data-toggle="dropdown" type="button">
				<span class="search-rendered"> <?php rendered_value($value, $values); ?>
                    <?php /* if (!isset($info['_' . $city_param . '_names'])): ?>
                                                <?php //echo lang("all_address") ?>
                                            <?php else: ?>
                                                <?php echo $info['_' . $city_param . '_names'] ?>
                                            <?php endif; */ ?>
				</span>
                <span class="search-caret"></span>
            </div>
            <span class="search-remove"></span>

            <div class="dropdown-menu dropdown-2colums clearfix">
                <div class="dropdown-menu dropdown-menu-left  slimscroll">
                    <div class="form-group">
                        <input type="text" placeholder="<?php echo $city_name ?>" class="form-control lg searachSelect">
                    </div>
                    <?php

                    foreach ($city_values as $row) {

                        $checked = false;
                        if ($city_value)
                            $checked = (in_array($row->id, $city_value)) ? 1 : 0;
                        //pr($checked);
                        ?>
                        <div class="search-results checkbox <?php echo $checked ? '  active_filter' : ''; ?>">
                            <label>
                                <input <?php //echo $checked ? ' checked = "checked" ':''; ?> type="checkbox"
                                                                                              name="<?php echo $city_param ?>[]"
                                                                                              value="<?php echo $row->id ?>">
                                <span class=" <?php echo $linked ?>"
                                      data-type="checkbox"><?php echo $row->name ?></span>
                            </label>
                        </div>
                    <?php } ?>
                </div>
                <div class="dropdown-menu dropdown-menu-right  slimscroll">

                    <div class="form-group">
                        <input type="text" placeholder="<?php echo $country_name ?>"
                               class="form-control lg searachSelect">
                    </div>
                    <ul>
                        <?php $path = public_url() . '/img/world/'; ?>
                        <?php foreach ($country_values as $group): ?>
                            <li class="search-results active " data-name="group<?php echo $country_param ?>"
                                data-value="<?php echo $group->id; ?>">
                                <a class="search-results-option" href="#"><?php echo $group->name; ?></a>
                            </li>
                            <?php foreach ($group->countries as $v): ?>
                                <li class="search-results act-input  <?php echo $linked ?>"
                                    data-name="<?php echo $country_param ?>" data-value="<?php echo $v->id; ?>">
                                    <a class="search-results-option" href="#" data-value="<?php echo $v->id; ?>">
                                        <img
                                            src="<?php echo $path . strtolower($v->id) . '.gif' ?>"> <?php echo $v->name; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </ul>

                </div>
            </div>


        </div>
        <div class="clearfix"></div>
        <div name="<?php echo $city_param; ?>_error" class="error"></div>
        <div name="<?php echo $country_param; ?>_error" class="error"></div>
    </div>

    <?php return ob_get_clean();
});

/**
 * Danh sach hien thi
 */
$this->register('cat', function ($input = array()) {
    ob_start();
    $info = array_get($input, 'info', null);
    $title = array_get($input, 'title', '');
    $param = array_get($input, 'param');
    $value = array_get($input, 'value', null);
    $req = array_get($input, 'req');
    //$values = array_get($input, 'values');

    $can_rating = array_get($input, 'can_rating', 0);
    $can_addtext = array_get($input, 'can_addtext', 0);
    $style_special = array_get($input, 'style_special', 0);
    $data_multi = array_get($input, 'data_multi', 0);

    //====
    $_id = $param . '_' . random_string('unique');
    $input['_id'] = $_id;
    $url_info_cat = site_url('cancidate_public/info_cat');
    $action = current_url();
    $info = $info ? (array)$info : null;
    $items = $value;
    if (!$items)
        $items = isset($info['_cat_' . $param . '_id']) ? $info['_cat_' . $param . '_id'] : array();
    //if($param =='lang') pr($items);
    ?>
    <div id="<?php echo $_id ?>" class="block <?php echo $style_special ? 'block-vote' : 'block-list' ?>">
        <div class="block-title">
            <?php echo $title ?>
            <?php if ($req): ?><b>*</b><?php endif; ?>
        </div>
        <div class="block-content">
            <?php if ($items): ?>
                <ul class="list-unstyled cat_list_wrap ">
                    <?php foreach ($items as $it): ?>
                        <li class="cat_item_wrap <?php echo !is_numeric($it->id) ? ' request ' : '' ?>"
                            data-id="<?php echo $it->id ?>">
                            <?php if (is_numeric($it->id)): ?>
                                <a href="#0" class=" act-display-cat" data-toggle="tooltip"
                                   data-url="<?php echo $url_info_cat . '?id=' . $it->id ?>"
                                   title="<?php echo lang("view_detail") ?>"><?php echo $it->name ?></a>
                            <?php else: ?>
                                <?php echo $it->name ?>
                            <?php endif; ?>
                            <?php if ($can_rating): ?>

                                <br>
                                <div class="control review-control-vote2">
                                    <?php foreach (range(1, 6) as $vr):
                                        $check = isset($it->rating) ? $it->rating : 3;
                                        ?>
                                        <input <?php echo ($vr == $check) ? ' checked ="checked"' : '' ?>
                                            type="radio"
                                            name="<?php echo $param ?>_<?php echo $it->id ?>_rating"
                                            id="<?php echo $param ?>_<?php echo $it->id ?>_rating_<?php echo $vr ?>"
                                            value="<?php echo $vr ?>"
                                            class="radio do_action" data-loader="_"
                                            data-url="<?php echo $action . '?_act=update_rating&_type=' . $param . '&id=' . $it->id . '&rating=' . $vr ?>"
                                            >
                                        <label title="M?c ?? th�nh th?o <?php echo $vr ?>/6"
                                               for="<?php echo $param ?>_<?php echo $it->id ?>_rating_<?php echo $vr ?>"
                                               class="rating-<?php echo $vr ?>">
                                            <span><?php echo $vr ?> star</span>
                                        </label>
                                    <?php endforeach ?>
                                </div>
                            <?php endif; ?>
                            <!--<span class="edits" title="ch?nh s?a" data-toggle="modal" data-target="#modal-<?php /*echo $param */ ?>">
                                            <span class="icon"></span>
                                        </span>-->
                            <span title="x�a" data-id="<?php echo $it->id ?>" data-type="<?php echo $param ?>"
                                  data-act="del" class="removes act-modal">
                                                <span class="icon"></span>
                                            </span>


                        </li>
                    <?php endforeach ?>
                </ul>
            <?php endif; ?>

            <div class="actions actions1">
                <button class="btn btn-default <?php echo $data_multi ? 'act_datasoure_show' : 'act-modal' ?> "
                        data-target="#<?php echo $_id ?>" data-act="add" data-type="<?php echo $param ?>">
                    <i class="fa fa-plus"></i> Th�m t? th? vi?n
                </button>
                <?php if ($can_addtext): ?>
                    <button class="btn btn-outline act-modal" data-act="add_text" data-type="<?php echo $param ?>">
                        <i class="fa fa-plus"></i> Th�m n?i dung ri�ng
                    </button>
                <?php endif; ?>
            </div>

        </div>
        <!-- Data Soure-->
        <?php
        $modal['id'] = 'modal-' . $param;
        if ($data_multi)
            $modal['class'] = 'modal-edit-ky-nang';
        $modal['name'] = $title;
        echo macro()->modal_start($modal); ?>
        <form class="form_action" method="post" action="<?php echo $action; ?>">
            <input type="hidden" name="_act" value="add">
            <input type="hidden" name="_id" value="">
            <input type="hidden" name="_type" value="<?php echo $param ?>">
            <?php if (!$data_multi): ?>

                <?php echo macro('tpl::form')->cat_datasoure_single($input); ?>
            <?php else: ?>
                <?php echo macro('tpl::form')->cat_datasoure_multi($input); ?>
            <?php endif; ?>
        </form>
        <?php echo macro()->modal_end; ?>

    </div>
    <?php return ob_get_clean();
});
$this->register('cat_datasoure_single', function (array $input) {
    $info = array_get($input, 'info', null);
    $info = $info ? (array)$info : null;
    $param = array_get($input, 'param');
    $values = array_get($input, 'values');
    $can_rating = array_get($input, 'can_rating', 0);
    $select_multi = array_get($input, 'select_multi', 0);

    $ids = $info['_cat_' . $param . '_id_ids'];
    ob_start(); ?>

    <?php

    $_data_info = array(
        'name' => 'Ch?n th�ng tin', 'text_all' => '_', 'type' => $select_multi ? 'select_multi' : 'select',
        'param' => 'id', 'value' => $ids, 'values_row' => array($values, 'id', 'name'),
        'values_opts' => array('not_show_in_value' => 1)
    );
    echo macro('tpl::form')->info($_data_info);
    ?>
    <?php if ($can_rating): ?>
        <div class="form-group">
            <span>Ch?n m?c ?? th�nh th?o</span>

            <div class="control review-control-vote2">
                <?php foreach (range(1, 6) as $v): ?>
                    <input <?php echo form_set_checkbox($v, 3) ?> type="radio" name="rating" value="<?php echo $v ?>"
                                                                  class="radio"
                                                                  id="<?php echo $param ?>_rating_<?php echo $v ?>">
                    <label title="<?php echo $v ?> star" for="<?php echo $param ?>_rating_<?php echo $v ?>"
                           class="rating-<?php echo $v ?>">
                        <span><?php echo $v ?> star</span>
                    </label>
                <?php endforeach ?>
            </div>
        </div>
    <?php endif; ?>
    <button class="btn btn-default" type="submit">Save</button>
    <a class="btn btn-link act-modal-close">Cancel</a>
    <?php return ob_get_clean();
});
$this->register('cat_datasoure_multi', function (array $input) {
    $_id = array_get($input, '_id');
    $info = array_get($input, 'info', null);
    $info = $info ? (array)$info : null;
    $param = array_get($input, 'param');
    $values = array_get($input, 'values');
    $ids = $info['_cat_' . $param . '_id_ids'];
    $items = isset($info['_cat_' . $param . '_id']) ? $info['_cat_' . $param . '_id'] : array();
    ob_start(); ?>

    <div class="row area-act-select">
        <div class="col-md-9 col-sm-8">
            <!--<p class="text-right"><a href="#" class="de-xuat">?? xu?t th�m chuy�n m�n m?i v�o th? vi?n <i class="fa fa-angle-right"></i></a></p>-->
            <div class="table-ky-nang">
                <div class="form-group has-feedback select-search-feedback">
                    <span aria-hidden="true" class="form-control-feedback"></span>
                    <input type="text" placeholder="Nh?p t? kh�a ?? t�m m?t chuy�n m�n"
                           class="form-control lg select-search-field">

                    <div class="select-container select-container-dropdown">
                        <ul class="select-results  slimscroll">
                            <?php
                            foreach ($values as $cat) {
                                foreach ($cat->_subs as $s) {
                                    ?>
                                    <li class="select-results-option" data-placement="bottom" data-toggle="tooltip"
                                        data-original-title=""
                                        data-text="<?php echo $s->name ?>"
                                        data-value="<?php echo $s->id ?>" <?php echo !(is_array($ids) && in_array($s->id, $ids)) ?: 'style="display: none;"' ?>>
                                        <span><?php echo $s->name ?></span>
                                    </li>
                                <?php }
                            } ?>
                        </ul>
                    </div>
                </div>
                <div class="drop-kn clearfix my-tabs">
                    <div class="drop-content nano has-scrollbar">
                        <ul class="list-unstyled nano-content">
                            <?php $i = 1;
                            foreach ($values as $cat): ?>
                                <li class="has-children tabs-nav <?php echo $i == 1 ? 'active' : ''; ?>"
                                    data-target="#tab-content-<?php echo $cat->id ?>"><a
                                        class="  item-drop"><?php echo $cat->name ?></a></li>
                                <?php $i++; endforeach; ?>

                        </ul>

                    </div>
                    <div class="drop-content-right nano has-scrollbar">
                        <?php // pr($ids); ?>
                        <ul class="drop-menu nano-content" tabindex="0" style="right: -17px;">
                            <?php $i = 1;
                            $count = 0;
                            foreach ($values as $cat): ?>
                                <?php if ($cat): ?>

                                    <div id="tab-content-<?php echo $cat->id ?>"
                                         class="tab-content <?php echo $i == 1 ? 'active' : ''; ?>">
                                        <ul class="drop-menu ">
                                            <?php foreach ($cat->_subs as $s): //pr($s)?>
                                                <li class="act-select <?php echo (is_array($ids) && in_array($s->id, $ids)) ? 'active' : '' ?>"
                                                    data-value="<?php echo $s->id ?>"
                                                    data-text="<?php echo $s->name ?>">
                                                    <a href="#0" class="item-drop"><?php echo $s->name ?></a>
                                                </li>
                                                <?php $count++; endforeach; ?>
                                        </ul>

                                    </div>
                                <?php endif; ?>
                                <?php $i++; endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-4 no-padding-left">
            <div class="table-kq">
                <!--<div class="t-head">
                    <span class="count-job">204</span>
                    <span>vi?c l�m</span>
                    <span>Ph� h?p v?i Chuy�n M�n c?a b?n</span>
                </div>-->
                <div class="t-alert">
                    <span><?php echo count($ids) ?></span> c?a <?php echo $count ?> danh m?c ???c ch?n
                </div>
                <div class="nano">
                    <div class="nano-content">
                        <ul class="list-unstyled holder-selected">

                            <?php if (isset($items)):
                                foreach ($items as $it): ?>
                                    <?php //if(!is_numeric($it->id)) continue; ?>
                                    <li title="X�a kh?i danh s�ch" class="act-remove-selected"
                                        data-id="<?php echo $it->id ?>">
                                        <input name='cat_<?php echo $param ?>_id[]' value="<?php echo $it->id ?>"
                                               type="hidden"/>
                                        <?php echo $it->name ?>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </ul>
                        <!-- temp -->
                        <div id="holder-selected-temp" class="hide">
                            <li title="X�a kh?i danh s�ch" data-id="{id_value}" class="act-remove-selected">
                                <input {param_name}="cat_<?php echo $param ?>_id[]" value="{id_value}" type="hidden"/>
                                {text_value}
                            </li>

                        </div>
                    </div>
                </div>

                <p class="text-center">
                    <button type="submit" class="btn btn-default btn-save">Save</button>
                    <a class="btn btn-link act-modal-close">Cancel</a>
                </p>
            </div>
        </div>

    </div>

    <?php return ob_get_clean();
});
$this->register('job', function (array $input) {
    ob_start(); ?>

    <?php
    //$info = array_get($input, 'info', null);
    //$info = $info ? (array)$info : null;
    $title = array_get($input, 'title', '');
    $param = array_get($input, 'param');
    $list = array_get($input, 'list', null);
    $url_info_job = site_url('cancidate_public/info_job');
    $req = array_get($input, 'req');
    $_id = $param . '_' . random_string('unique');
    // pr($values);
    ?>
    <div id="<?php echo $_id ?>" class="block block-list">
        <div class="block-title">
            <?php echo $title ?>
            <?php if ($req): ?><b>*</b><?php endif; ?>
        </div>
        <div class="block-content">
            <ul class="list-unstyled cat_list_wrap ">
                <?php if (isset($list) && $list)
                    foreach ($list as $it): ?>
                        <li class="cat_item_wrap" data-id="<?php echo $it->id ?>">
                            <a href="#0" class=" act-display-job" data-toggle="tooltip"
                               data-url="<?php echo $url_info_job . '?id=' . $it->id ?>"
                               title="<?php echo lang("view_detail") ?>"><?php echo $it->name ?></a>

                            <span class="removes act-modal" title="x�a" data-id="<?php echo $it->id ?>"
                                  data-type="jobs"
                                  data-act="del">
                                <span class="icon"></span>
                            </span>
                        </li>
                    <?php endforeach ?>
            </ul>
            <div class="actions actions1">
                <button class="btn btn-default act_datasoure_show" data-target="#<?php echo $_id ?>">
                    <i class="fa fa-plus"></i> Th�m l?nh v?c
                </button>

            </div>
        </div>
        <!-- Data Soure-->
        <?php echo macro('tpl::form')->job_datasource($input); ?>
    </div>

    <?php return ob_get_clean();
});
$this->register('job_ads', function (array $input) {
    ob_start(); ?>

    <?php
    //$info = array_get($input, 'info', null);
    //$info = $info ? (array)$info : null;
    $title = array_get($input, 'title', '');
    $param = array_get($input, 'param');
    $list = array_get($input, 'list', null);
    $url_info_job = site_url('cancidate_public/info_job');

    $_id = $param . '_' . random_string('unique');
    // pr($values);
    ?>
    <div id="<?php echo $_id ?>">
        <ul class="list-unstyled cat_list_wrap ">
            <?php if (isset($list) && $list)
                foreach ($list as $it): ?>
                    <li style="float: left;" class="cat_item_wrap item-tag-title" data-id="<?php echo $it->id ?>">
                        <a href="#0" class=" act-display-job" data-toggle="tooltip"
                           data-url="<?php echo $url_info_job . '?id=' . $it->id ?>"
                           title="<?php echo lang("view_detail") ?>"><?php echo $it->name ?></a>
                    </li>
                <?php endforeach ?>
        </ul>
        <div class="clearfix"></div>
        <a class="btn btn-outline  btn-sm act_datasoure_show " data-target="#<?php echo $_id ?>">
            <i class="fa fa-plus"></i> Th�m l?nh v?c
        </a>

        <!-- Data Soure-->
        <?php echo macro('tpl::form')->job_datasource($input); ?>
    </div>

    <?php return ob_get_clean();
});
$this->register('job_datasource', function (array $input) {
    ob_start(); ?>

    <?php
    $title = array_get($input, 'title', '');
    $param = array_get($input, 'param');
    $list = array_get($input, 'list', null);
    $value = array_get($input, 'value', null);
    $values = array_get($input, 'values', array());
    $action = current_url(1);
    // pr($values);
    ?>


    <?php
    $modal['id'] = 'modal-' . $param;
    $modal['class'] = 'modal-edit-ky-nang';
    $modal['name'] = $title;
    echo macro()->modal_start($modal); ?>
    <form class="form_action" method="post" action="<?php echo $action; ?>">
        <input type="hidden" name="_act" value="add">
        <input type="hidden" name="_id" value="">
        <input type="hidden" name="_type" value="<?php echo $param ?>">

        <div class="row area-act-select">
            <div class="col-md-9 col-sm-8">
                <!--<p class="text-right"><a href="#" class="de-xuat">?? xu?t th�m l?nh v?c m?i v�o th? vi?n <i class="fa fa-angle-right"></i></a></p>-->
                <div class="table-ky-nang">
                    <div class="form-group has-feedback select-search-feedback">
                        <span aria-hidden="true" class="form-control-feedback"></span>
                        <input type="text" placeholder="Nh?p t? kh�a ?? t�m m?t l?nh v?c"
                               class="form-control lg select-search-field">

                        <div class="select-container select-container-dropdown">
                            <ul class="select-results  slimscroll">
                                <?php
                                foreach ($values as $cat) {
                                    foreach ($cat->jobs as $job) {
                                        ?>
                                        <li class="select-results-option" data-placement="bottom"
                                            data-toggle="tooltip" data-original-title=""
                                            data-text="<?php echo $job->name ?>"
                                            data-value="<?php echo $job->id ?>" <?php echo !(is_array($value) && in_array($job->id, $value)) ?: 'style="display: none;"' ?>>
                                            <span><?php echo $job->name ?></span>
                                        </li>
                                    <?php }
                                } ?>
                            </ul>
                        </div>
                    </div>
                    <div class="drop-kn clearfix my-tabs">
                        <div class="drop-content nano has-scrollbar">
                            <ul class="list-unstyled nano-content ">
                                <?php $i = 1;
                                foreach ($values as $cat): ?>
                                    <li class="has-children tabs-nav   <?php echo $i == 1 ? 'active' : ''; ?>"
                                        data-target="#tab-content-<?php echo $cat->id ?>"><a
                                            class=" item-drop"><?php echo $cat->name ?></a></li>
                                    <?php $i++; endforeach; ?>
                            </ul>
                        </div>
                        <div class="drop-content-right nano has-scrollbar">
                            <div class="nano-content">
                                <?php $i = 1;
                                $count = 0;
                                foreach ($values as $cat): ?>
                                    <?php if ($cat->jobs): ?>

                                        <div id="tab-content-<?php echo $cat->id ?>"
                                             class="tab-content <?php echo $i == 1 ? 'active' : ''; ?>">
                                            <ul class="drop-menu ">
                                                <?php foreach ($cat->jobs as $job): ?>
                                                    <li class="act-select <?php echo !(is_array($value) && in_array($job->id, $value)) ?: 'active' ?>"
                                                        data-value="<?php echo $job->id ?>"
                                                        data-text="<?php echo $job->name ?>">
                                                        <a href="#0" class="item-drop"><?php echo $job->name ?></a>
                                                    </li>
                                                    <?php $count++; endforeach; ?>
                                            </ul>

                                        </div>
                                    <?php endif; ?>
                                    <?php $i++; endforeach; ?>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-4 no-padding-left">
                <div class="table-kq">
                    <!--<div class="t-head">
                        <span class="count-job">204</span>
                        <span>vi?c l�m</span>
                        <span>Ph� h?p v?i l?nh v?c c?a b?n</span>
                    </div>-->
                    <div class="t-alert">
                        <span><?php echo count($value) ?></span> c?a <?php echo $count ?> l?nh v?c ???c
                        ch?n
                    </div>
                    <div class="nano">
                        <div class="nano-content">
                            <ul class="list-unstyled holder-selected">

                                <?php if (isset($list)):
                                    foreach ($list as $job): ?>
                                        <li title="X�a kh?i danh s�ch" class="act-remove-selected"
                                            data-id="<?php echo $job->id ?>">
                                            <input name='jobs[]' value="<?php echo $job->id ?>" type="hidden"/>
                                            <?php echo $job->name ?>
                                        </li>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                            </ul>
                            <!-- temp -->
                            <div id="holder-selected-temp" class="hide">
                                <li title="X�a kh?i danh s�ch" data-id="{id_value}" class="act-remove-selected">
                                    <input {param_name}="jobs[]" value="{id_value}" type="hidden"/>
                                    {text_value}
                                </li>

                            </div>
                        </div>
                    </div>

                    <p class="text-center">
                        <button type="submit" class="btn btn-default btn-save">Save</button>
                        <button type="button" class="btn btn-default btn-link" data-dismiss="modal">Cancel</button>
                    </p>
                </div>
            </div>

        </div>
    </form>
    <?php echo macro()->modal_end; ?>
    <?php return ob_get_clean();
});

function rendered_value($value, $values,$name=null)
{
    if ($value) {
        $selected = [];
        foreach ($values as $row) {
            if (in_array($row->id, $value)) {
                $selected[] = $row->name;
            }
        }
        if ($selected)
            echo implode(',', $selected);
    } else
        if($name)
             echo $name;
        else
            echo 'All';
}