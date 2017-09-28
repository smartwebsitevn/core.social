<?php if (isset($pages_config['total_rows']) && isset($pages_config['per_page']) && $pages_config['total_rows'] > $pages_config['per_page']): ?>
    <?php // pr($pages_config)
    /*
     [total_rows] => 28
    [per_page] => 1
    [cur_page] => 5
    */
    $total_rows =$pages_config['total_rows'];
    $per_page =$pages_config['per_page'];

    $num_page=round($total_rows/$per_page);
    $cur_page =round($pages_config['cur_page']/$per_page);

    ?>
    <nav class="page-pagination text-center" event-hook="moduleCoreFilter">
        <div class="text-center mt20 mb20">
            <a href="#0" class="act-pagination-load-more btn btn-default btn-rounded">Tải thêm trang <?php echo ($cur_page+2) .'/'.($num_page);// .' - '. $total_rows ?></a>
        </div>
        <a  role="button" data-toggle="collapse" href=".pagination-wraper"  >Chuyển đến một trang khác</a>
        <div class="pagination-wraper collapse mt10">
            <?php $this->widget->site->pages($pages_config); ?>
        </div>
    </nav>
<?php endif; ?>
