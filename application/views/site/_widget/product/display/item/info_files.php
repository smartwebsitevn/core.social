<?php if (isset($row->files) && $row->files): // pr($row->files); ?>
    <div class="mt20">
        <div class="row">
            <?php foreach ($row->files as $row): ?>
                <?php if (!empty($row->_url) && file_exists($row->_path)): ?>
                    <?php
                    $file_infos = file_parse($row->_path);
                    ?>
                    <div class="col-md-12 mb5">

                        <a href="<?php echo $row->_url ?>"
                           target="_blank">
                            <i class="fa fa-<?php echo $file_infos['icon'] ?>"></i>
                            <?php echo $row->_orig_name ?>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>