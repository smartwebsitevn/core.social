<?php if (isset($info->_addons) && $info->_addons): ?>
    <?php
    $_data_addon = function ($addon) use($info) {
        //pr($addon);
        ob_start() ?>
        <?php
        //pr($info);
        $data = $addon->_data;
        $price_suffix= $info->price_suffix?'/'.$info->price_suffix:'';

        ?>
        <?php if ($addon->price && !$info->price_is_contact): ?>
            <div class="checkbox ">
            <label>
                <input name="addons[]" value="<?php echo $data->id ?>" type="checkbox">
                <span><?php echo $data->name ?></span>

            </label>
            <?php if (trim($data->description)): ?>
                <i title="<?php echo $data->description ?>" class="fa fa-question-circle mr10" data-toggle="tooltip"
                   data-placement="top"></i>
            <?php endif; ?>
        <span
            class="text-success pull-right f13"><?php echo $addon->price_prefix . ' ' . currency_format_amount($addon->price).$price_suffix ?></span>
        </div>
        <?php else: ?>
        <span><i class="fa fa-check "></i> <?php echo $data->name ?></span>
            <?php if (trim($data->description)): ?>

                <i title="<?php echo $data->description ?>" class="fa fa-question-circle mr10" data-toggle="tooltip"
               data-placement="top"></i>
        <?php endif; ?>
        <?php endif; ?>

        <?php return ob_get_clean();
    };
    ?>

    <ul>
        <?php foreach ($info->_addons as $addon): ?>
            <?php if ($addon->_data): ?>
                <li class=" product-options">
                    <?php echo $_data_addon($addon); ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>