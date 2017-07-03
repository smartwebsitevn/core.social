<?php if (isset($list) && $list): ?>
     <ol>
        <?php foreach ($list as $row):    //pr($row);?>
            <li >
                    <a href="<?php echo $row->_url_view; ?>" >
                                <?php echo $row->name; ?></a>
            </li>
        <?php endforeach; ?>
    </ol>
<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>