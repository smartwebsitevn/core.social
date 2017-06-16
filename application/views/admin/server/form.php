<?php
$info = isset($info) ? (array)$info : null;
$_macro = $this->data;
$_macro['form']['data'] = $info;
$_macro['form']['rows'][] = array(
    'param' => 'id', 'type' => 'hidden'
);
$_macro['form']['rows'][] = array(
    'param' => 'name', 'req' => 1,
);
$_macro['form']['rows'][] = array(
    'param' => 'key', 'name' => lang('server'), 'type' => 'select', 'req' => 1,
    'value' => $info['key'], 'values_single' => $server_types, 'values_opts'=>array('name_prefix'=>'server_type_'),
);

$_macro['form']['rows'][] = array(
    'param' => 'player', 'name' => lang('player'), 'type' => 'select', 'req' => 1,
    'value' => $info['player'], 'values_single' => $player_types, 'values_opts'=>array('name_prefix'=>'player_type_'),
);
$_macro['form']['rows'][] = array(
    'param' => 'player_mobile', 'name' => lang('player_mobile'), 'type' => 'select',
    'value' => $info['player_mobile'], 'values_single' => $player_types, 'values_opts'=>array('name_prefix'=>'player_type_'),
    'desc'=>lang('player_mobile_note'),
);
$_macro['form']['rows'][] = array(
    'param' => 'url', 'req' => 1,
);

$_macro['form']['rows'][] = array(
    'type' => 'ob', 'value' => '<div id="key_content"></div>'
);

$_macro['form']['rows'][] = array(
    'param' => 'sort_order',
);
$_macro['form']['rows'][] = array(
    'param' => 'status', 'type' => 'bool_status',
    'value' => $info['status'],
);
echo macro()->page($_macro);

?>
<script type="text/javascript">

    (function ($) {
        $(document).ready(function () {
            var _f = $('#form');
            var form = {
                init : function(){
                    form.toggle_key();
                    _f.find('select[name=key]').change(function () {
                        form.toggle_key()
                    });
                },
                    toggle_key : function() {
                        var key_content = _f.find('#key_content');
                        var id = _f.find('input[name=id]').val();
                        var key = _f.find('select[name=key]').val();
                        if (key == 'dedicated' || key == 'wowza' || key == 'nc') {
                            $.ajax({
                                type: "POST",
                                url: "<?php echo admin_url('server/get_form')?>",
                                data: {'id': id, 'key': key},
                                success: function (data) {
                                    $(key_content).html(data);
                                }
                            });

                        }
                        else {

                            $(key_content).html('');
                        }
                    },
            }
            form.init();
        });
    })(jQuery);
</script>




