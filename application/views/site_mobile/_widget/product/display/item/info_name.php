<a href="#0" data-url="<?php echo $row->_url_view ?>" class="act-view-quick">
    <?php if ($row->is_feature): ?>
        <i class="pe-7s-star" title="Bài viết mới nổi"></i>

    <?php endif; ?>
    <?php echo $row->name; ?>
    <?php if (isset($row->files) && $row->files): ?>
        <i class="pe-7s-paperclip"></i>
    <?php endif; ?>
</a>