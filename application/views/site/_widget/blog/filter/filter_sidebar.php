<?php
$filter =isset($filter)?$filter:[];
?>
<div class="sidebar sidebar-top ">
    <div class="block block-search-blog">
        <div class="block-title clearfix">
            <div class="pull-left">Tìm kiếm bài viết<?php //echo lang("search_blog")?></div>
            <div class="pull-right"><span
                    class="resultTotalList ajax-content-total"><?php echo $total_rows?></span> Kết quả<?php //echo lang("result")?></div>
        </div>
        <div class="block-content">
            <form id="blog-form-search" action="<?php echo $action; ?>" method="get">
                <div class="form-group input-search">
                    <div class="select-search">
                        <div class="">

                            <ul class="select-rendered">
                                <li class="select-input" style="display: block">
                                    <input name="name" class="form-control" placeholder="Tìm tiêu đề, nội dung, tác giả" value="<?php echo isset($filter['name']) ? $filter['name'] : '' ?>" type="text">
                                    <button type="submit" class="btn btn-search-blog"></button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php echo macro()->filter_dropdown_obj(['value' => $filter['cat_id'], 'values' => $categories, 'param' => 'cat_id', 'name' => lang('cat')]); ?>
            </form>
        </div>

    </div>
</div>
