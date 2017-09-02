<?php if (isset($row->files) && $row->files): // pr($row->files); ?>
    <div class="mt10 mb10">
            <?php foreach ($row->files as $row): ?>
                <?php if (!empty($row->_url) && file_exists($row->_path)): ?>
                    <?php
                    $file_infos = file_parse($row->_path);
                    ?>
                    <div class=" mb5">

                        <a href="<?php echo $row->_url ?>"
                           target="_blank">
                            <i class="fa fa-<?php echo $file_infos['icon'] ?>"></i>
                            <?php echo $row->_orig_name ?>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
<?php endif; ?>