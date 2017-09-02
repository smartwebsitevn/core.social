<?php
$url = site_url('movie_list');
$action = isset($action)?$action:site_url('movie_list');

$filter = isset($filter)?$filter:null;
$sort_order = isset($sort_order)?$sort_order:null;

$_data_form_filter =function() use($filter,$action ,$cat_type_cat,$cat_type_country,$types,$sort_orders,$sort_order){
    ob_start(); ?>

    <form id="form-ordering" action="<?php echo $action; ?>" method="get">
            <div class="col-md-3 form-group">
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
            <div class="col-md-3 form-group">
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

            <div class="col-md-2 form-group">
                <select class=" form-control" name="type">
                    <option value="" selected="selected">-=<?php echo lang('type'); ?>=-</option>
                    <?php foreach ($types as $v => $name): ?>
                        <option
                            value="<?php echo $v; ?>" <?php if ($filter['type'] == $v) echo 'selected="selected"'; ?>>
                            <?php echo lang('title_movie_' . $name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2 form-group">
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
            <div class="col-md-2 form-group">
                <input type="submit" class="btn btn-default" value="<?php echo lang('filter') ?>"/>
                <a href="<?php echo current_url() ?>" class="btn "><?php echo lang('reset') ?></a>
            </div>
    </form>

    <?php
    return  ob_get_clean();
};
$_id= random_string('unique');
?>
<div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#<?php echo $_id ?>">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </button>
</div>
<ul id="<?php echo $_id ?>"  class="navbar-collapse  collapse toolbar-sorter">
    <li class="active">
        <a href="<?php echo $url.'?order=id|desc' ?>" class="sorter-option"><?php echo lang("filter_movie_day"); ?></a>
        <a class="sorter-action active" href="#"><span></span></a>
    </li>
    <li class="">
        <a href="<?php echo $url.'?order=year|desc' ?>" class="sorter-option"><?php echo lang("filter_movie_year"); ?></a>
    </li>
    <li>
        <a href="<?php echo $url.'?order=imdb|desc' ?>" class="sorter-option"><?php echo lang("filter_movie_imdb"); ?></a>
    </li>
    <li>
        <a href="<?php echo $url.'?order=name|desc' ?>" class="sorter-option"><?php echo lang("filter_movie_name"); ?></a>
    </li>
    <li class="dropdown filter" >
        <a href="javascript:void(0)" role="button" data-toggle="dropdown" ><?php echo lang("filter_submit"); ?>T</a>
        <div class="dropdown-menu">
            <?php echo  $_data_form_filter()?>
            </div>
    </li>
</ul>