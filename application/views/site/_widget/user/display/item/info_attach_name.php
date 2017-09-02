<?php if ($row->attach_id): //pr($row); ?>
    <?php $attach= $row->attach?>
        <?php if (!empty($attach) && file_exists($attach->path)): ?>
            <?php
            $file_infos = file_parse($attach->path);
            ?>
            <div class=" mb5">

                <a href="<?php echo $attach->url ?>"
                   target="_blank">
                    <i class="fa fa-<?php echo $file_infos['icon'] ?>"></i>
                    <?php echo $attach->name ?>
                </a>
            </div>
        <?php endif; ?>

<?php endif; ?>

