<div class="row">
    <?php foreach ($list as $row):?>
        <div class=" col-lg-12 col-md-4 col-sm-4 col-xs-12">
            <div class="ads ads-1 ">
                <div class="field-img">
                    <a href="<?php echo $row->_url_buy?>"><img  src="<?php echo $row->image->url; ?>" alt="images"/></a>
                </div>
                <?php /* ?>
                <div class="title">
                    <a href="<?php echo $row->_url_buy?>"><?php echo $row->name?></a>
                </div>
                 <?php */ ?>
            </div>
        </div>
    <?php endforeach;?>
</div>
<?php /* ?>
                    <div class="row">
                        <div class=" col-lg-12 col-md-4 col-sm-4 col-xs-4">
                            <div class="ads ads-1 ">
                                <?php $page_youtube = mod('product')->get_info("9");
                                if($page_youtube)
                                    $page_youtube = $page_youtube->_url_view;
                                ?>

                                <div class="field-img">

                                    <a href="<?php echo $page_youtube ?>"><img
                                            src="<?php echo public_url('site/theme/images/ads1.jpg'); ?>" alt="images"/></a>
                                </div>
                                <div class="title">
                                    <a href="<?php echo $page_youtube ?>">Th? vi?n</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-4 col-sm-4 col-xs-4">
                            <div class="ads ads-2">
                            <?php $page_face = mod("page")->get_info(6);

                             if($page_face)
                                 $page_face = $page_face->_url_view;
                            ?>
                            <div class="field-img">
                                <a href="<?php echo $page_face ?>"><img
                                        src="<?php echo public_url('site/theme/images/ads2.png'); ?>" alt="images"/></a>
                            </div>
                            <div class="title">
                                <a href="<?php echo $page_face ?>">H?c viên Dungmori</a>
                            </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-4 col-sm-4 col-xs-4">
                                <div class="ads ads-3">
                            <div class="field-img">
                                <a href="<?php echo site_url("author") ?>"><img
                                        src="<?php echo public_url('site/theme/images/ads3.png'); ?>" alt="images"/></a>
                            </div>
                            <div class="title">
                                <a href="<?php echo site_url("author")//widget('site')->info("youtube") ?>">Gi?ng
                                    viên</a>
                            </div>
                        </div>
                            </div>
                    </div>
                    <?php */ ?>