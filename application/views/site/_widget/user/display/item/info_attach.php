<?php if ($row->attach_id): //pr($row); ?>

        <a class="file_attach do_action" data-url="<?php echo $row->_url_view_attach ?>">
            <i class="pe-7s-paperclip"></i>
            <?php if (isset($name)): ?>
                Tải file đính kèm
            <?php endif; ?>
        </a>

<?php endif; ?>

