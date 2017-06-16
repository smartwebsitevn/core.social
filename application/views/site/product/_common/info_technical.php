<?php if ($info->technical):
    $_data_tmp = function () use ($info) {
        ob_start() ?>
        <div class="mb40">
            <h4><?php echo lang("technical") ?></h4>
            <hr/>
            <?php echo $info->technical ?>
        </div>
        <?php return ob_get_clean();
    };
    echo macro()->more_block($_data_tmp());
    ?>
<?php endif; ?>
