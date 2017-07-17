<form class="form-inline mt20" id="form_filter_base" name="form_filter_base" action="<?php echo $action; ?>" method="get">
    <input type="hidden" name="limitstart"/>
    <?php if (isset($filters) && $filters): ?>
        <?php foreach ($filters as $n => $v): ?>
            <input type="hidden" name="<?php echo $n; ?>" value="<?php echo $v ?>"/>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php /* ?>
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
  <?php */?>
    <div class="form-group " style="width: 70%">
        <div class="input-group" style="width: 100%" >
            <input name="name"  style="width: 100%" type="text" class="form-control" placeholder="Tìm khóa học bạn quan tâm">
        </div>
    </div>
    <button type="submit" class="btn btn-default">Tìm kiếm</button>

    <div class="sorter pull-right">
        <div class="control">
            <select style="width:140px" name="order" class="form-control">
                <option value="id|desc">Mới nhất</option>
                <option value="name|asc">Tên khóa</option>
                <option value="count_view|desc">Lượt xem nhiều</option>
                <option value="count_buy|desc">Lượt mua nhiều</option>
                <option value="price|desc">Giá tiền (Giảm)</option>
                <option value="price|asc">Giá tiền (Tăng)</option>
                <!-- <option value="new|desc">Mới</option>-->
                <option value="feature|desc">Nổi bật</option>
                <option value="rate|desc">Yêu thích</option>
            </select>
        </div>
    </div>


    <?php /* ?>
            <div class="col-xs-12 col-sm-6">
                    Hiển thị sp/ 1 trang
                    <select style="width:130px" name="limit" class="form-control select_show">
                        <option value="20">20 tin bài</option>
                        <option value="35">35 tin bài</option>
                        <option value="45">45 tin bài</option>
                        <option value="50">50 tin bài</option>
                    </select>
            </div>
             <?php */ ?>
</form>
<hr>
