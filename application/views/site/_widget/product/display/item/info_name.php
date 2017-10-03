
<a href="<?php echo $row->_url_view; ?>">
    <?php if ($row->is_feature): ?>
        <i class="pe-7s-star" title="Bài viết mới nổi"></i>
    <?php endif; ?>
    <?php echo $row->name; ?>
    <?php if (isset($row->files) && $row->files): ?>
        <i class="pe-7s-paperclip"></i>
    <?php endif; ?>
</a>