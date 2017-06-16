<form class="form-search" action="<?php echo site_url("aboutus_list") ?>">

    <div class="categori-search  ">
        <select name="cat_id" data-placeholder="Tìm theo danh mucj" class="form-control categori-search-option">
            <option value="">Tim theo danh mục</option>
            <?php
            $filter['show'] = 1;
            $filter['parent_id'] = 0;
            $input["sort"]=[['sort_order', 'asc'],['id','desc']];
            $list_cat = model('lesson_cat')->filter_get_list($filter, $input);
            ?>
            <?php foreach($list_cat as $row): ?>
                <option value="<?php echo $row->id ?>"><?php echo $row->name ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="box-group">
        <input name="name" type="text" class="form-control" placeholder="Tìm khóa học bạn quan tâm">
        <button class="btn btn-search" type="submit"><span>search</span></button>
    </div>
</form>