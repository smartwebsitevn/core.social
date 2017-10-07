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

            <form  class="ajax_form_filter"  event-hook="moduleCoreFilter"  id="blog-form-search" action="<?php echo $action; ?>" method="get">
                <div class="form-group input-search">

                    <div class="select-search select-search-chosen">
                        <div class="select-container select-container-above select-container-focus2">
                            <ul class="select-rendered">
                                <li class="search-input-remove">
                                    <i class="fa fa-remove"></i>
                                </li>
                                <li class="select-input">
                                    <input type="text" class="select-input-field"
                                           placeholder="Tìm theo tiêu đề"
                                           id="select-input-field" name="name">
                                </li>
                                <li >
                                    <a _submit="1" ><i class="fa fa-search"></i> </a>
                                </li>
                                <?php /* ?>
                            <li class="select-placeholder">
                                <label
                                    for="select-input-field"><span><?php// echo lang("search_skill_or_cat_id_recruit") ?></span></label>
                            </li>
                            <li class="select-all-remove" data-placement="bottom" data-toggle="tooltip"
                                data-original-title="<?php echo lang("remove_all_search") ?>">
                                <span class="select-all-remove"></span>
                            </li>
                             <?php */ ?>

                            </ul>
                        </div>
                        <div class="select-container select-container-dropdown">
                            <div class="select-message">
                                <span><?php echo lang("search_suggestions") ?></span>
                                <!--<a href="#0" class="add-tab" data-placement="bottom" data-toggle="tooltip" data-original-title="<?php /*echo lang("add_cat_recruit")*/ ?>"><?php /*echo lang("add_cat_recruit")*/ ?></a>
                                <a href="#0" calss="copy-tab" data-placement="bottom" data-toggle="tooltip" data-original-title="<?php /*echo lang("copy_cat_from_my_brief")*/ ?>"><?php /*echo lang("copy_cat_from_my_brief")*/ ?></a>-->
                            </div>

                        </div>
                    </div>
                </div>
                <?php echo macro()->filter_dropdown_obj(['value' => $filter['cat_id'], 'values' => $categories, 'param' => 'cat_id', 'name' => lang('cat')]); ?>
            </form>
        </div>

    </div>
</div>
