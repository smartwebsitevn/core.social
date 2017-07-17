<?php


/**
 * Page
 */
$this->register('page', function(array $input = array()){
    ob_start(); 

    $mod    = array_get($input, 'mod', t('uri')->rsegment(1));
    $act    = array_get($input, 'act', t('uri')->rsegment(2));
    $breadcrumbs    = array();
    $message        = array_get($input, 'message', get_message());
    $contents       = array_get($input, 'contents');

    $toolbar = array_get($input, 'toolbar', array(
                array(
                    'url'   => admin_url($mod.'/add'),
                    'title' => lang('add'),
                    'icon' => 'plus',
                    'attr'=>array('class'=>'btn btn-danger'),
                ),
                array(
                    'url'   => admin_url($mod),
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
                <?php if (!empty($breadcrumbs)): //pr($breadcrumbs);
                    t('widget')->admin->breadcrumbs($breadcrumbs); ?>
                <?php else: ?>
                    <ul class="breadcrumb">
                        <li>
                            <a href="<?php echo admin_url() ?>">Home</a>
                        </li>
                        <li><a href="<?php echo admin_url($mod) ?>"><?php echo lang('mod_' . $mod) ?></a></li>
                        <?php if ($act != 'index'): ?>
                            <li class="active"><?php echo lang($act) ?></li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>

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
            <?php echo macro()->page_setting(); ?>
            <?php if (!empty($toolbar_sub)): ?>
                <?php echo macro()->toolbar_sub($toolbar_sub); ?>
            <?php endif; ?>


        </div>
        <!-- /.col-lg-12 -->
    </div><!-- /.row -->
    <!-- END PAGE HEADING ROW -->


    
        
    <!-- Message -->
    <?php if ( ! empty($message)):?>
        <?php t('widget')->admin->message($message); ?>
    <?php endif; ?>
    
    <?php if ( ! empty($form) || ! empty($form_translate) || ! empty($table)) :?>
    <!-- Content -->
    <div class="row">
        <div class="col-lg-12">
        
        <?php if ( ! empty($form)) echo macro('mr::advForm')->form($form); ?>
        
        <?php if ( ! empty($form_translate)) echo macro('mr::advForm')->translate($form_translate); ?>
        
        <?php if ( ! empty($table)) echo macro('mr::advTable')->table($table); ?>
        
        <?php echo $contents; ?>
        </div>
    </div>
    <?php endif;?>
<?php return ob_get_clean(); });






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
    ob_start();

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

                        echo $this->macro->row($row, $rows);
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
    $tooltips = array_get($row, 'tooltips');
    $tooltips_attr = '';
    if (isset($tooltips)) {
        $tooltips_attr .= ' data-toggle="tooltip"';
        if (isset($tooltips['data-placement'])) $tooltips_attr .= ' data-placement="'.$tooltips['data-placement'].'"';
        if (isset($tooltips['title'])) $tooltips_attr .= ' title="'.$tooltips['title'].'"';
    }

    if ($type == 'hidden')
    {
?>
        <input type="hidden" name="<?php echo $param ?>" value="<?php echo $value ?>">
<?php 
        return ob_get_clean();
    }
    

    $refer = array_get($row, 'refer');
    $refer_value = array_get( $row, 'refer_value', null );
    $ajax = ( array_get($row, 'ajax') ? (object) array_get($row, 'ajax') : null ) ;
    $length_search = array_get( $row, 'length_search', null );
    $delay_search = array_get( $row, 'delay_search', null );
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
    if($rows)
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

<div class="form-group <?php echo 'param_' . $type; ?> <?php echo ( $refer ? 'refer_' . $param : '' ) ?>" <?php echo ( $hide ? 'style="display: none;"' : '' ) ?>>
<?php 
    if ($name) 
    {
    
?>
        <label class="col-sm-3  control-label " for="<?php echo $_id; ?>">
            <?php
                $_name = $name;
                if ($unit) $_name .= ' (' . $unit . ')';
                if ($_name) $_name .= ':';

                echo ucfirst($_name);
            
                if ($req) 
                    echo t('html')->span('*', array('class' => 'req')); 
                // Tooltips
                if (isset($tooltips)) {
                    echo '<i class="fa fa-question-circle" aria-hidden="true" '.$tooltips_attr.'></i>';
                }
            ?>
        </label>
        <div class="col-sm-9">
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
    $class = ' form-control select_multi ' ;
    if($attr && isset($attr['class'])){
        $attr['class'] =   $attr['class']. $class;
    }else{
        $attr['class'] = $class;
    }
    ?>
        <select name="<?php echo $param; ?>" style="width:100%;" <?php echo t('html')->attr($attr); ?> >
           
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

            <?php if ($type == 'select'):
             $class = ' form-control' ;
    if($attr && isset($attr['class'])){
        $attr['class'] =   $attr['class']. $class;
    }else{
        $attr['class'] = $class;
    }
    ?>
                <select name="<?php echo $param; ?>" style="width:100%;" <?php echo t('html')->attr($attr); ?> >
            <?php else:
              $class = ' form-control select_multi ' ;
    if($attr && isset($attr['class'])){
        $attr['class'] =   $attr['class']. $class;
    }else{
        $attr['class'] = $class;
    }
             ?>
                <select name="<?php echo $param; ?>[]" multiple="multiple"  style="width:100%;" <?php echo t('html')->attr($attr); ?> >
            <?php endif; ?>
               
            <?php if (!array_get($values_opts, 'value_required', false)): ?>
                <option value="">-=<?php echo lang('select_choice') ?>=-</option>
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
            <?php 
                echo t('html')->input( "s_$param", '', array_merge( array(
                    'class' => 'form-control',
                    'placeholder' => lang('search') . '...'
                ), $attr ) ); 
            ?>
            <ul id="quick-<?php echo $param; ?>-result" class="quick-search-result"></ul>
            <div id="quick-<?php echo $param; ?>-choosen" class="quick-search-choosen">
                <?php 
                    if($value){

                    $image =isset( $value->_avatar)?$value->_avatar:$value->image;
                        ?>
                        <div class="item">
                            <img src="<?php echo isset( $image->url_thumb ) ? $image->url_thumb : '' ?>" />
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
    else if ($type == 'search_multi')
    {
        $owners_id = null;
       // pr($value);
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
                             $image="";
                            if(isset($value_info->avatar->url_thumb)){
                                 $image =$value_info->avatar->url_thumb;
                            }
                           /* if(isset($value_info->_avatar->url_thumb)){
                                 $image =$value_info->_avatar->url_thumb;
                            }*/
                            elseif(isset($value_info->image->url_thumb)){
                                $image =$value_info->image->url_thumb;
                            }
                             ?>
                            <div class="item-multiple">
                                <img src="<?php echo $image ?>" />
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
                                                echo 'source: "' . $ajax->source.'"';
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

    else if ($type == 'datetime')
    {
    ?>
        <input 
            type="text"   
            name="<?php echo $param ?>_datetime[hour]" 
            value = "<?php echo ( is_numeric($value) ? date( 'H:i', $value ) : $value[0] ) ?>"
            style = "width: 68px;"
        />
        <input 
            type="text"   
            name="<?php echo $param ?>_datetime[day]" 
            class="date_picker mask_datess" 
            value = "<?php echo ( is_numeric($value) ? date( 'd-m-Y', $value ) : $value[1] ) ?>"
            style = "width: 160px;"
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
            <div name="<?php echo $param; ?>_error" class="error help-block"></div>
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
