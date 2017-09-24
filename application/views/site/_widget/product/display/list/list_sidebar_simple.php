<?php if (isset($list) && $list): ?>
     <ol>
        <?php foreach ($list as $row):    //pr($row);?>
            <li >
                <?php t('view')->load('tpl::_widget/product/display/item/info_name', ['row' => $row]) ?>
            </li>
        <?php endforeach; ?>
    </ol>
<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>