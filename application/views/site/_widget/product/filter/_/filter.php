<form id="form-filter" action="<?php echo $action; ?>" method="get">
    <div class=" row  mb10">
        <div class="col-md-10">

                <label class="control-label sr-only"><?php echo lang("search") ?></label>
                <input type="text" name="name" value="<?php echo isset($filter['name']) ? $filter['name'] : '' ?>" placeholder="Search"
                       class="form-control">
            </div>
        <div class="col-md-2">

                     <input type="submit" class="btn btn-default" value="<?php echo lang('search') ?>"/>
        </div>
    </div>
    <div class="row  mb20 ">
        <div class="col-md-3">
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
        <div class="col-md-3">
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

        <div class="col-md-2">
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
        <div class="col-md-2">
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
        <div class="col-md-2">
            <input type="submit" class="btn btn-default" value="<?php echo lang('filter') ?>"/>
            <a href="<?php echo current_url() ?>" class="btn "><?php echo lang('reset') ?></a>
        </div>
    </div>
</form>

