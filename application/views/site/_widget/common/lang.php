<?php if(config('language_multi', 'main')): ?>
    &nbsp; &nbsp;
    <?php foreach ($lang_list as $row): ?>
        <a href="javascript:void(0)" class="item do_action"
           data-url="<?php echo site_url('home/lang/' . $row->id) . '?url=' . $row->_url ?>" rel="nofollow">
            <img class="dInline" alt="<?php echo $row->name ?>" src="<?php echo $row->_img ?>">
        </a>
    <?php endforeach; ?>
<?php endif; ?>
