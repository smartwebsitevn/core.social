<?php if (isset($list) && $list): ?>
    <?php $_data_list = function () use ($list) {
        ob_start() ?>
        <?php foreach ($list as $row):    //pr($row);?>

            <li class="list-group-item">
                <a href="<?php echo $row->url?$row->url:'#0'; ?>">
                    <div class="title "> <?php echo $row->title ?>
                    </div>
                </a>
                <div class="created pull-right"><i class="fa fa-clock-o"></i> Cách đây <?php echo  timespan($row->created,'',1);//$row->_created ?></div>

            </li>
        <?php endforeach; ?>

        <?php return ob_get_clean();
    }
    ?>
    <?php if (isset($load_more) && $load_more): ?>
        <?php echo $_data_list(); ?>
    <?php else: ?>
        <ul class="list-group">
            <?php echo $_data_list() ?>
        </ul>

    <?php endif; ?>
    <?php if (t('input')->is_ajax_request() && isset($pages_config)) : ?>
        <?php widget('user_notice')->display_pagination($pages_config); ?>
    <?php endif; ?>

<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>

