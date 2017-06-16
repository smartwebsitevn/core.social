<?php echo macro()->page_heading($combo->name) ?>
<?php echo macro()->page_body_start(); //pr($combo);?>
<form class="form-horizontal">
    <h4 class="text-primary"><?php echo lang('combo_info') ?></h4>

    <div class="form-group ">
        <label class="col-sm-3 text-right"><?php echo lang('combo_package') ?></label>

        <div class="col-sm-9">
            <b class="red"><?php echo '<b class="fontB f16 fontB">' . $combo->name . '</b>' ?></b>
        </div>

    </div>
    <div class="form-group ">
        <label class="col-sm-3 text-right">
            <?php echo lang('price') ?>:
        </label>

        <div class="col-sm-9">
            <b class="red"><?php echo $combo->_price_total ?></b>
        </div>
    </div>
    <?php if ($services["products"]): ?>
        <h4 class="text-primary"><?php echo lang('product_combo') ?></h4>

        <ul class="list-group">
            <?php foreach ($services["products"] as $row): ?>
                <li class="list-group-item "><a href="<?php echo $row->_url_view ?>" target="_blank"> <?php echo $row->name ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if ($services["lessons"]): ?>
        <h4 class="text-primary"><?php echo lang('lesson_combo') ?></h4>
        <ul class="list-group">
            <?php foreach ($services["lessons"] as $row): ?>
                <li class="list-group-item "><a href="<?php echo $row->_url_view ?>" target="_blank"> <?php echo $row->name ?></a>
                </li>
            <?php endforeach; ?>
        </ul>

    <?php endif; ?>
    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <a class="btn btn-default" href="<?php echo $combo->_url_buy ?>"><?php echo lang('buy_combo') ?></a></div>
    </div>

    <div class="clear"></div>

    <p class="text-justify">
        <?php echo html_entity_decode($combo->description); ?>
    </p>
</form>

<?php echo macro()->page_body_end() ?>
