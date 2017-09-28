<?php t('view')->load('tpl::user_page/_common/public') ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="view_user_info" class="panel">
                <div class="panel-body">
                    <div class="group">
                        <?php $is_user_special = isset($info->user_group_type) && in_array($info->user_group_type, ['user_active', 'user_manager']); ?>
                        <?php if ($is_user_special): ?>
                            <?php if ($info->user_group_type == 'user_manager'): ?>
                                <i class="pe-7s-helm"></i>
                            <?php else: ?>
                                <i class="pe-7s-medal"></i>
                            <?php endif; ?>
                            <br>
                        <?php endif; ?>
                        <span class="group-name"> <?php echo $info->user_group_name; ?></span> <br>

                    </div>
                    <div class="infos">
                        <span class="name"> <?php echo $info->name; ?></span> <br>
                        <span class="profession">
                            <?php echo $info->profession; ?>
                        </span><br>
                        <?php t('view')->load('tpl::_widget/user/display/item/info_attach_name', ['row' => $info]) ?><br>

                        <span class="point"><i class="pe-7s-cup"></i> <span
                                class="value"><?php echo number_format($info->point_total) ?></span> <?php echo lang("count_point") ?></span><br>
                        <span class="place">
                            <i class="pe-7s-map-marker"></i>
                            <span
                                class="value"><?php echo $info->_working_city_name . ', ' . $info->_working_country_name ?></span></span><br>
                        <span> <i class="pe-7s-ribbon"></i>  Tham gia <span
                                class="value"><?php echo $info->_created ?></span>, Số ID - <span
                                class="value"><?php echo $info->_id ?></span></span> <br>

                        <?php t('view')->load('tpl::_widget/user/display/item/info_tags', ['row' => $info]) ?>
                        <span class="phone"> <i class="fa fa-phone "></i> <?php echo $info->phone; ?></span> <br>
                        <span class="email"> <i class="fa fa-envelope "></i> <?php echo $info->email; ?></span> <br>
                        <span class="facebook"> <i class="fa fa-facebook "></i> <?php echo $info->facebook; ?></span>
                        <br>
                        <span class="website"> <i class="fa fa-home "></i> <?php echo $info->website; ?></span> <br>

                    </div>


                    <div class="description">
                        <?php echo $info->desc; ?>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
<?php //widget('site')->footer_navi(); ?>
