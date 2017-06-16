<div class="bl-bai-giang-noi-bat">
<div class="content row">
<?php foreach ($list as $row): ?>
    <div class="views-row col-xs-12 col-sm-3 col-lg-3">
        <div class="box-wrap">
            <div class="images">
                <a href="<?php echo $row->_url_view; ?>" title="<?php echo $row->name; ?>"><img src="<?php echo $row->image->url_thumb; ?>" /></a>
            </div>
            <div class="info">
                <div class="title">
                    <a href="<?php echo $row->_url_view; ?>" title="<?php echo $row->name; ?>"><?php echo $row->name; ?></a>
                </div>
                <div class="name-gv" ><?php echo lang('price') ?>: <?php echo $row->_price_total?></div>
                <div>  <a class="btn btn-default mt10" href="<?php echo $row->_url_buy?>"><?php echo lang('buy_now')?></a></div>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>
</div>