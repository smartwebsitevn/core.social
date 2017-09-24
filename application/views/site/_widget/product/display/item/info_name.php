<a href="<?php echo $row->_url_view; ?>">
    <?php echo $row->name; ?>
    <?php if (isset($row->files) && $row->files): ?>
        <i class="pe-7s-paperclip"></i>
    <?php endif; ?>
</a>