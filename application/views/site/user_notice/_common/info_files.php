<?php if (isset($info->files) && $info->files): ?>
    <div class="mt20">
        <h4>Tệp đính kèm<?php //echo lang("file_title") ?></h4>
        <hr/>
        <div class="row">
            <?php foreach ($info->files as $row): ?>

                <?php if (!empty($row->_url) && file_exists($row->_path)): ?>
                    <?php
                    $file_infos = file_parse($row->_path);
                    ?>
                    <div class="col-md-6 mb10">

                        <a href="<?php echo $row->_url ?>"
                           target="_blank">
                            <img width="30px"
                                 src="<?php echo $file_infos['icon'] ?>">
                            <?php echo $row->_orig_name ?>
                        </a>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>