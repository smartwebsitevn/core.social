<?php
$url = site_url('movie_list');
$_data_form_filter = function () use ($filter, $action, $cat_type_cat, $cat_type_country, $types,$qualitys, $sort_orders, $sort_order) {
    ob_start(); ?>

    <form action="<?php echo $action; ?>" method="get">
        <ul class="content-filter">
            <li class="filter-option">
                <div class="form-group">
                    <select class="orderby form-control" name="cat">
                        <option value="" selected="selected">-=<?php echo lang('cat'); ?>=-</option>
                        <?php foreach ($cat_type_cat as $it): ?>
                            <option
                                value="<?php echo $it->id; ?>" <?php if ($filter['cat'] == $it->id) echo 'selected="selected"'; ?>>
                                <?php echo $it->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </li>
            <li class="filter-option">
                <div class="form-group">
                    <select class=" form-control" name="country">
                        <option value="" selected="selected">-=<?php echo lang('country'); ?>=-</option>
                        <?php foreach ($cat_type_country as $it): ?>
                            <option
                                value="<?php echo $it->id; ?>" <?php if ($filter['country'] == $it->id) echo 'selected="selected"'; ?>>
                                <?php echo $it->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </li>
            <li class="filter-option">
                <div class="form-group">
                    <select name="sound" class="form-control">
                        <option value="">=Âm thanh=</option>
                        <option value="has_subtitle" <?php if ($filter['sound'] == 'has_subtitle') echo 'selected="selected"'; ?>>Phụ đề</option>
                        <option value="has_interpret" <?php if ($filter['sound'] == 'has_interpret') echo 'selected="selected"'; ?>>Thuyết minh</option>
                    </select>
                </div>
            </li>
            <li class="filter-option">
                <div class="form-group">
                    <select class=" form-control" name="type">
                        <option value="" selected="selected">-=Phân loại<?php //echo lang('type'); ?>=-</option>
                        <?php foreach ($types as $v => $name): ?>
                            <option
                                value="<?php echo $v; ?>" <?php if ($filter['type'] == $v) echo 'selected="selected"'; ?>>
                                <?php echo lang('title_movie_' . $name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </li>
            <li class="filter-option">
                <div class="form-group">
                    <select name="quality" class="form-control">
                        <option value="" selected="selected">=Chất lượng=</option>
                        <?php foreach ($qualitys as $v => $name): ?>
                            <option
                                value="<?php echo $v; ?>" <?php if ($filter['quality'] == $v) echo 'selected="selected"'; ?>>
                                <?php echo lang('quality_type_' . $name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </li>
            <li class="filter-option">
                <?php
                $now = getdate();
                ?>
                <div class="form-group">
                    <select name="year" class="form-control">
                        <option value=""  >=Năm sản xuất=</option>
                        <?php foreach(range($now["year"]-5,$now["year"]) as $v): ?>
                        <option value="<?php echo $v ?>" <?php if ($filter['year'] == $v) echo 'selected="selected"'; ?>><?php echo $v ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </li>
            <?php ?>
            <li class="filter-option">
                <div class="form-group">
                    <select class=" form-control" name="order">
                        <option value="" selected="selected">-=<?php echo lang('ordering') ?>=-</option>
                        <?php foreach ($sort_orders as $order):
                            $order_tmp = explode('|', $order) ?>
                            <option
                                value="<?php echo $order; ?>" <?php if ($sort_order == $order) echo 'selected="selected"'; ?>>
                                <?php echo $this->lang->line("ordering_" . $order_tmp[0]); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                </div>
            </li>
             <?php  ?>

        </ul>
        <input type="submit" class="btn btn-default btn-filter" value="<?php echo lang('filter') ?>"/>

    </form>

    <?php
    return ob_get_clean();
};
?>
<div style=" position: relative;">
<div class="filter2 dropdown">
    <a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
       aria-expanded="false"><?php echo lang("filter_movie") ?> <i class="fa fa-angle-down" aria-hidden="true"></i></a>
    <div class="dropdown-menu">
        <?php echo $_data_form_filter() ?>
    </div>
</div>
</div>