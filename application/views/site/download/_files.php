<h4 ><?php echo lang("title_list_file")?></h4>
<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?php echo lang("file_name")?></th>
                <th><?php echo lang("file_sizes")?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($info->images as $row){ ?>
                <tr>
                    <td>
                        <a href="<?php echo $row->image->url ?>" download title="<?php echo $row->file_name ?>">
                            <?php echo $row->file_name ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $row->_size ?>
                    </td>
                </tr>
            <?php } ?>
            <?php foreach($info->_url_files as $row){ ?>
                <tr>
                    <td>
                        <a href="<?php echo $row['url'] ?>" target="_blank" download title="<?php echo $row['name'] ?>">
                            <?php echo $row['name'] ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $row['size'] ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>