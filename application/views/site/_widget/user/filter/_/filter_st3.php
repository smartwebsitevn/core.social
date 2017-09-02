<?php
$url = site_url('movie_list').'?temp=top&';
/*$action = isset($action) ? $action : site_url('movie_list');
$filter = isset($filter) ? $filter : null;
$sort_order = isset($sort_order) ? $sort_order : null;*/

?>
<div class="menu-custom">
    <span class="nav-menu"></span>

    <div class="content-menu movie_auto_filter_url" data-target=".block-film-top .block-content" data-target-title=".block-film-top .block-title span.title" >
        <div class="box-content">
            <div class="col-menu">
                <a href="javascript:void(0)" class="title"><?php echo lang("title_movie_group_viewest"); ?></a>
                <ul class="sub-menu">
                    <li><a href="javascript:void(0)" data-url="<?php echo  $url.'type=1&order=view_in_day|desc'?>" class="act-filter"><?php echo lang("title_movie_group_viewest_movie_in_day"); ?></a></li>
                    <li><a href="javascript:void(0)" data-url="<?php echo  $url.'type=2&order=view_in_day|desc'?>" class="act-filter"><?php echo lang("title_movie_group_viewest_series_in_day"); ?></a></li>
                    <li><a href="javascript:void(0)" data-url="<?php echo  $url.'type=1&order=view_in_week|desc'?>" class="act-filter"><?php echo lang("title_movie_group_viewest_movie_in_week"); ?></a></li>
                    <li><a href="javascript:void(0)" data-url="<?php echo  $url.'type=2&order=view_in_week|desc'?>" class="act-filter"><?php echo lang("title_movie_group_viewest_series_in_week"); ?></a></li>
                </ul>
            </div>
            <div class="col-menu">
                <a href="javascript:void(0)"  class="title"><?php echo lang("title_movie_group_interest"); ?></a>
                <ul class="sub-menu">
                    <li><a href="javascript:void(0)" data-url="<?php echo  $url.'order=view_total|desc'?>" class="act-filter"><?php echo lang("title_movie_group_interest_view"); ?></a></li>
                    <li><a href="javascript:void(0)" data-url="<?php echo  $url.'order=rate|desc'?>" class="act-filter"><?php echo lang("title_movie_group_interest_rate"); ?></a></li>
                    <li><a href="javascript:void(0)" data-url="<?php echo  $url.'order=comment_count|desc'?>" class="act-filter"><?php echo lang("title_movie_group_interest_comment"); ?></a></li>
                </ul>
            </div>
            <div class="col-menu">
                <a href="javascript:void(0)" class="title"><?php echo lang("title_movie_group_cat"); ?></a>
                <ul class="sub-menu">
                    <?php foreach ($cat_type_cat as $it): ?>
                        <li><a href="javascript:void(0)"  data-url="<?php echo  $url.'cat='.$it->id?>"  class="act-filter"><?php echo lang("movie") ?> <?php echo $it->name; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-menu">
                <a href="javascript:void(0)" class="title"><?php echo lang("title_movie_group_country"); ?></a>
                <ul class="sub-menu">
                    <?php foreach ($cat_type_country as $it): ?>
                        <li><a href="javascript:void(0)"  data-url="<?php echo  $url.'country='.$it->id?>"  class="act-filter"><?php echo lang("movie") ?> <?php echo $it->name; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="col-menu">
                <a href="javascript:void(0)"  class="title"><?php echo lang("title_movie_group_collection"); ?></a>
                <ul class="sub-menu">
                    <?php foreach ($cat_type_collection as $it): ?>
                        <li><a href="javascript:void(0)"  data-url="<?php echo  $url.'collection='.$it->id?>"  class="act-filter"><?php echo lang("movie") ?> <?php echo $it->name; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>