<?php if (isset($pages_config['total_rows']) && isset($pages_config['per_page']) && $pages_config['total_rows'] > $pages_config['per_page']): ?>
    <?php //pr($pages_config) ?>
    <nav class="page-pagination text-center" event-hook="moduleCoreFilter">
        <div class="text-center mt20 mb20">
            <a href="" id="act-pagination-load-more" class="btn btn-default">Xem thêm</a>
        </div>
        <?php $this->widget->site->pages($pages_config); ?>
    </nav>
<?php endif; ?>