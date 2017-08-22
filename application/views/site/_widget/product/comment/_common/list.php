<ul class="list-unstyled list-comment-0">
    <?php
    foreach ($list as $row) {
        ?>
        <li>
            <?php echo widget('comment')->builder_html($row, ['url_reply' => $info->_url_comment_reply, 'field_load' => $info->id . '_comment_load', 'info' => $info]);//$_comment($row); ?>
        </li>
        <?php
    }
    ?>
</ul>

