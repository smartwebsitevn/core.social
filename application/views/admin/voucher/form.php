<?php
$info = isset($info) ? (array)$info : null;
$_macro = $this->data;
$_macro['form']['data'] = $info;

$_macro['form']['rows'][] = array(
    'param' => 'id', 'type' => 'hidden'
);
/*$_macro['form']['rows'][] = array(
    'param' => 'name',   'req' => 1,
);*/
$_macro['form']['rows'][] = array(
    'param' => 'type', 'name' => lang('type'), 'type' => 'select', 'req' => 1,
    'value' => $info['type'], 'values_single' => $types, 'values_opts' => array('name_prefix' => 'voucher_type_'), 'req' => 1,
);
$_macro['form']['rows'][] = array(
    'type' => 'ob', 'value' => '<div id="type_content"></div>'
);

if (!$info) {
    $_macro['form']['rows'][] = array(
        'param' => 'number', 'type' => 'select',
        'values_single' => range(1, 1000),
        'req' => 1,
    );
}
$_macro['form']['rows'][] = array(
    'param' => 'expired', 'type' => 'date', 'req' => 1,
);
if (!$info) {
    $_macro['form']['rows'][] = array(
        'param' => 'pre_voucher',
    );
}
$_macro['form']['rows'][] = array(
    'param' => 'comment', 'type' => 'textarea',
);
/*

$_macro['form']['rows'][] = array(
    'param' => 'user_id', 'name' => lang('apply_for_user'),
    'value' => isset($info['_user']) ? $info['_user']->email : '',
    'attr' => array('class' => 'autocomplete form-control', '_url' => $url_search_user),
    'desc' => lang('apply_for_user_desc')

);
$_macro['form']['rows'][] = array(
    'param' => 'admin_id', 'name' => lang('apply_for_admin'),
    'value' => isset($info['_admin']) ? $info['_admin']->username : '',
    'attr' => array('class' => 'autocomplete form-control', '_url' => $url_search_admin),
    'desc' => lang('apply_for_admin_desc')


);
*/

echo macro()->page($_macro);


?>
<script type="text/javascript">

    (function ($) {
        $(document).ready(function () {
            var _f = $('#form');
            var form = {
                init: function () {
                    form.toggle_key();
                    _f.find('select[name=type]').change(function () {
                        form.toggle_key()
                    });
                },
                toggle_key: function () {
                    var type_content = _f.find('#type_content');
                    var id = _f.find('input[name=id]').val();
                    var type = _f.find('select[name=type]').val();
                    //if (type == 'coupon' || type == 'vip' || type == 'buy') {
                    $.ajax({
                        type: "POST",
                        url: "<?php echo admin_url('voucher/get_form')?>",
                        data: {'id': id, 'type': type},
                        success: function (data) {
                            $(type_content).html(data);
                        }
                    });
                    // }
                    /*else
                    {

                        $(type_content).html('');
                    }*/
                },
            }
            form.init();
        });
    })(jQuery);
</script>