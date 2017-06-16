<?php
$setting = isset($setting) ? (array)$setting : null;
foreach (array('ip', 'user', 'password', 'target_folder') as $p) {
    echo macro('mr::form')->row(array(
        'param' => $p, 'name' => lang('dedicated_' . $p),
        'type' => ($p != 'password') ? 'text' : 'password',
        'value' => $setting[$p], 'req' => true,

    ));
}
?>

<!-- secret -->
<div id="secret_content">
    <?php echo macro('mr::form')->row(array(
        'param' => 'secret_status', 'name' => lang('dedicated_secret_status'),
        'type' => 'bool',
        'value' => $setting[$p]

    )); ?>

    <div id="secret_status_content">
        <?php echo macro('mr::form')->row(array(
            'param' => 'secret_type', 'name' => lang('dedicated_secret_type'),
            'req' => true,
            'type' => 'select',
            'value' => $setting['secret_type'],
            'values_single' => $secret_types,
            'values_opts' => array('name_prefix' => 'dedicated_secret_type_')
        )); ?>

        <?php
        foreach (array('secret', 'expire', 'uri_prefix', 'port') as $p) {
            echo macro('mr::form')->row(array(
                'param' => $p, 'name' => lang('dedicated_' . $p),
                'req' => true, 'type' => 'text',
                'value' => $setting[$p],

            ));
        }
        ?>
    </div>
</div>
<!-- end secret -->

<script type="text/javascript">
    (function ($) {
        $(document).ready(function () {
            var main = $('#form');
            // Toggle upload_server_status
            toggle_status_content('secret_status', 'secret_status_content');

            /**
             * Hien thi content theo param value
             */
            function toggle_status_content(param, content) {
                toggle_status_content_handle(param, content);

                main.find('input[name=' + param + ']').change(function () {
                    toggle_status_content_handle(param, content);
                });
            }

            function toggle_status_content_handle(param, content) {
                var status = (main.find('input[name=' + param + ']:checked').val() == '1') ? true : false;
                var content = main.find('#' + content);

                if (status) {
                    content.slideDown(function () {
                        $(this).show();
                    });
                }
                else {
                    content.slideUp(function () {
                        $(this).hide();
                    });
                }
            }

        });
    })(jQuery);
</script>