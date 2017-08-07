<?php $setting= $widget->setting; ?>

<?php $type = $setting['style']; ?>
<?php if ($type == 'style1'): ?>
    <div class="work-ad work-ad1">
        <div class="container">
            <div class="heading"><?php echo $widget->name ?></div>
            <?php if(isset($setting['content_top']) &&$setting['content_top']): ?>
            <div class="description"><?php echo $setting['content_top'] ?></div>
            <?php endif; ?>
            <div class="owl-carousel ">
                <?php foreach ($banners as $item): ?>
                    <div class="item">
                        <div class="image">
                            <a href="<?php echo $item->url ?>"><img alt="images"
                                                                    src="<?php echo $item->image->url; ?>"/></a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if(isset($setting['content_bottom']) &&$setting['content_bottom']): ?>
                <div class="description"><?php echo $setting['content_bottom'] ?></div>
            <?php endif; ?>
        </div>
    </div>
<?php elseif ($type == 'style2'): ?>
    <div class="work-ad work-ad2">
        <div class="container">
            <div class="heading"><?php echo $widget->name ?></div>
            <?php if(isset($setting['content_top']) &&$setting['content_top']): ?>
                <div class="description"><?php echo $setting['content_top'] ?></div>
            <?php endif; ?>
            <div class="row">
                <?php foreach ($banners as $item): ?>
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <div class="box">
                            <div class="image">
                                <a href="<?php echo $item->url ?>"><img alt="images"
                                                                        src="<?php echo $item->image->url; ?>"/></a>
                            </div>
                            <div class="content mt10  fontB ">
                                <?php echo $item->name ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if(isset($setting['content_bottom']) &&$setting['content_bottom']): ?>
                <div class="description"><?php echo $setting['content_bottom'] ?></div>
            <?php endif; ?>
        </div>
    </div>
<?php elseif ($type == 'style3'): ?>
    <div class="work-ad work-ad3">
        <div class="container">
            <div class="heading"><?php echo $widget->name ?></div>
            <?php if(isset($setting['content_top']) &&$setting['content_top']): ?>
                <div class="description"><?php echo $setting['content_top'] ?></div>
            <?php endif; ?>
            <div class="row">
                <?php foreach ($banners as $item): ?>
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <div class="box">
                            <div class="image">
                                <a href="<?php echo $item->url ?>"><img alt="images"
                                                                        src="<?php echo $item->image->url; ?>"/></a>
                            </div>
                            <div class="content ">
                                <div class="mt10  textC fontB">
                                    <?php echo $item->name ?>
                                </div>
                                <div class="mt10">
                                    <?php echo handle_content($item->content, 'output'); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if(isset($setting['content_bottom']) &&$setting['content_bottom']): ?>
                <div class="description"><?php echo $setting['content_bottom'] ?></div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

