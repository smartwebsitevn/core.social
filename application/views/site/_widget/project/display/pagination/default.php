<?php if (isset($pages_config['total_rows']) && isset($pages_config['per_page']) && $pages_config['total_rows'] > $pages_config['per_page']): ?>
    <div class="clearfix"></div>
    <?php //pr($pages_config) ?>
    <nav class="page-pagination text-center">

        <ul class="pagination ">
            <?php $this->widget->site->pages($pages_config); ?>
        </ul>
    </nav>
<?php endif; ?>