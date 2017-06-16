<?php
/**
 * Login form
 */
$this->register('login', function (array $args = []) {
    $title = array_get($args, 'title', lang('title_login'));
    $action = array_get($args, 'action', site_url('user/login'));
    $btn_submit = array_get($args, 'btn_submit', lang('button_login'));
    $url = mod_url('user', user_get_account_info());

    return macro('mr::meta')->form([

        'action' => $action,
        'title' => $title,
        'btn_submit' => $btn_submit,

        'rows' => [

            '<div name="login_error" class="alert alert-danger" style="display: none;"></div>',

            [
                'param' => 'login_fast',
                'type' => 'hidden',
                'value' => 1
            ],

            [
                'param' => 'email',
                'name' => lang('email'),
                'attr' => [
                    'class' => 'account-input',
                    'placeholder' => lang('placeholder_login_email')
                ],
            ],

            [
                'param' => 'password',
                'type' => 'password',
                'attr' => [
                    'class' => 'account-input',
                    'placeholder' => lang('password')
                ],
            ],

            [
                'param' => 'submit',
                'type' => 'submit',
                'name' => lang('title_login'),
                'attr' => [
                    'class' => 'btn-login'
                ]
            ],

            [
                'param' => '',
                'type' => 'custom',
                'html' => '<div class="col account-forget-pass">
                    <a class="login-lnk forget" href="#" id="hd-lnk-forget">Quên mật khẩu?</a>
                </div>
                <div class="row login-social" style="display: none;">
                    Bạn có thể Đăng nhập bằng <a class="col signin-openID facebook login-lnk" href="#" id="facebook-login-hd">Facebook</a> <a class="col signin-openID google login-lnk" href="#" id="google-login-hd">Google+</a>
                </div>'
            ]

        ],

    ]);
});


/**
 * Header float form
 */
$this->register('form', function(array $input)
{
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

                echo $this->macro->row($row);
            }
        }
        

        echo t('html')->end('form');

        return ob_get_clean();
    };

    if ( ! $title ) return $make_form();

    echo $title;
    echo $make_form();

});




/**
 * Form row
 */
$this->register('row', function(array $row){ ob_start(); ?>
    
    <?php
        $_id    = '_'.random_string('unique');
        
        $param  = $row['param'];
        $name   = array_get($row, 'name', lang($param));
        $type   = array_get($row, 'type', 'text');
        $value  = array_get($row, 'value');
        $values = array_get($row, 'values', array());
        $req    = array_get($row, 'req');
        $desc   = array_get($row, 'desc');
        $unit   = array_get($row, 'unit');
        $attr   = array_get($row, 'attr', array());
        
        $attr['id'] = $_id;
    ?>

    <?php if ($type == 'hidden'): ?>
        <?php echo t('html')->hidden(
            $param, 
            $value, 
            array_merge(array('class' => 'form-control'), $attr)
        ); ?>

    <?php elseif ($type == 'text'): ?>
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
    
        <?php $values = (empty($values)) ? array(lang('off'), lang('on')) : (array) $values; ?>
        
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
            <select name="<?php echo $param; ?>[]" multiple="multiple" class="left select2" style="width:100%;" 
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
                echo t('html')->checkbox($param.'[]', $v, form_set_checkbox($v, $value), $attr);
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
            <div></div><span>Choose color...</span>
            <input name="<?php echo $param; ?>" value="<?php echo $value; ?>" type="hidden" />
        </div>
    
    <?php elseif (in_array($type, array('file', 'image', 'file_multi', 'image_multi'))): ?>
        <?php t('widget')->admin->upload($row['_upload']); ?>
    
    
    <?php elseif ($type == 'captcha'): ?>
        <div class="input-group form-captcha">

            <input name="<?php echo $param; ?>" class="form-control" autocomplete="off" type="text" />

        <span class="input-group-addon" style="padding:3px 12px">

            <img id="<?php echo $_id; ?>" src="<?php echo lib('captcha')->url(); ?>" _captcha="<?php echo $value; ?>" class="captcha">

            <a href="#reset" onclick="change_captcha('<?php echo $_id; ?>'); return false;" title="Reset captcha">
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

    <?php elseif ($type == 'submit'): ?>
        
        <div class="login-submit">
            <?php echo t('html')->submit($name, $attr); ?>
        </div>
        
    <?php endif; ?>
    
    
    <div class="clearfix"></div>

    <div name="<?php echo $param; ?>_error" class="form-error"></div>

    <?php if ($desc): ?>
        <div class="form-help"><?php echo $desc; ?></div>
    <?php endif; ?>


<?php return ob_get_clean(); });



/**
 * Register
 */
$this->register('register', function (array $args = []) {
    $title = array_get($args, 'title', lang('title_register'));
    $action = array_get($args, 'action', site_url('user/register'));
    $btn_submit = array_get($args, 'btn_submit', lang('button_register'));
    $countries = model('country')->get_list();

    return macro('mr::advForm')->form([
        'btn_submit' => $btn_submit,
        'action' => $action,

        'rows' => [

            //t('html')->hidden('email'),

            [
                'param' => 'email',
                'req' => true,
            ],

            [
                'param' => 'username',
                'req' => true,
                /*'attr'    => [
                    'onkeyup' => 'jQuery(this).closest("form").find("[name=email]").val(jQuery(this).val()+"@'.$_SERVER['HTTP_HOST'].'");'
                ],*/
            ],

            [
                'param' => 'password',
                'type' => 'password',
                'req' => true,
            ],

            [
                'param' => 'password_repeat',
                'type' => 'password',
                'req' => true,
            ],
            
            [
                'param' => 'name',
                'name' => lang('full_name'),
                'req' => true,
            ],

            [
                'param' => 'phone',
                'req' => true,
            ],

            [
                'param' => 'address',
                'req' => true,
            ],

            [
                'param' => 'country',
                'type' => 'select2',
                'values_row' => array( $countries, 'id', 'name' ),
                'req' => true,
            ],

            [
                'param' => 'city',
                'type' => 'select2',
                'refer' => 'country',
                'ajax' => array(
                    'loader' => 'City',
                    'source' => 'city'
                ),
                'values_row' => array( null, 'id', 'name' ),
                'req' => true
            ],

            [
                'param' => 'rule',
                'type' => 'bool',
                'name' => '',
                'value' => true,
                'values' => lang('agree_rule'),
            ],
        ],

    ]);
});


/**
 * Status
 */
$this->register('status_color', function($status,$text=''){ ob_start(); ?>

    <?php
    $statuss = array(
                    'on', 'off', 'no', 'yes', // common
                    'success', 'pending', 'failed', 'canceled','fraude',// tran
                    'paid', 'unpaid', 'canceled','overdue', 'partial','draft', // invoice
                    'completed', 'pending', 'canceled','processing', 'failed', 'expired','refunded', 'chargeback',  // order
                    'active', 'inactive', 'canceled','suspended', 'restored', 'deleted','expired','refunded', // service
        );
    if(!$text)
        $text = in_array($status, $statuss) ? $status : 'status_'.$status;
    ?>

    <span class="label label-<?php echo $status; ?>">
        <?php echo lang($text); ?>
    </span>

<?php return ob_get_clean(); });