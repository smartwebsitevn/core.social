<form id="form_filter_base" name="form_filter_base" action="<?php echo $action; ?>" method="get">
    <input type="hidden" name="limitstart"/>
    <?php if (isset($filters) && $filters): ?>
        <?php foreach ($filters as $n => $v): ?>
            <input type="hidden" name="<?php echo $n; ?>" value="<?php echo $v ?>"/>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="sorter ">
        <label >Sắp xếp theo </label>
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
