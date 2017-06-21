<div class="item-infos">
    <div class="clearfix">
        <a href="<?php echo $row->_url_view ?>" target="_blank" class="item-title" data-toggle="tooltip" class=""
           title="Xem chi tiết"><?php echo $row->_content->title ?>
            <?php if (isset($row->_profile_applyed) && $row->_profile_applyed): ?>
                <span class="label label-info">Đã ứng tuyển</span>
            <?php endif; ?>
            <?php if($row->attach_id): ?>
                <span class="file-dinh"></span>
            <?php endif; ?>
        </a>
        <?php if ($user && $user_mode == mod('user')->config('user_type_cancidate')): ?>
            <?php $caculate_relevance = mod('cancidate')->caculate_relevance($user->id, $row) ?>
            <span data-toggle="tooltip" class="item-phu-hop"
                  title="Phù hợp với hồ sơ của bạn <?php echo $caculate_relevance ?>%"><?php echo $caculate_relevance ?>
                %</span>
        <?php endif; ?>

    </div>


    <div class="item-metas">
        <span data-toggle="tooltip" class="dia-chi-job"
              title=""><?php echo $row->_citys_name . ', ' . $row->_countrys_name ?> </span>|&nbsp;&nbsp;
        <span data-toggle="tooltip" class="price-job" title="">
           	<?php view('tpl::_widget/recruit/info/info_salary',array('row'=>$row)) ?> &nbsp;&nbsp;|&nbsp;&nbsp;
        <span data-toggle="tooltip" class="kinh-nghiem-job" title=""><?php echo $row->experience_from ?>
            -<?php echo $row->experience_to ?> <?php echo lang("year_kn") ?></span> &nbsp;&nbsp;|&nbsp;&nbsp;
        <span data-toggle="tooltip" class="thoi-gian-job"
              title="Full time"><?php echo $row->_cat_j_type_id->name ?></span>
    </div>
</div>