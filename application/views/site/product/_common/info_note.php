<?php if ($info->note):
    $_data_tmp = function () use ($info) {
        ob_start() ?>
        <div class="mb40">
            <h4>Ghi chú<?php //echo lang("note") ?></h4>
            <hr/>
            <?php echo $info->note ?>
        </div>
        <?php return ob_get_clean();
    };
    echo macro()->more_block($_data_tmp());
    ?>
<?php endif; ?>
