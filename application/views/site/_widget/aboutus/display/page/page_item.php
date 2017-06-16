<?php $_data_row=function($i,$type='') use($list){
  ob_start();?>
<?php if(isset($list[$i])):
        $row = $list[$i];
        $img =thumb_img($row->image);
        ?>
    <div class="box-img <?php echo $type?>" style="background-image: url(<?php echo $img?>)">
        <a href="<?php echo $row->_url_view; ?>" >
            <img src="<?php echo $img?>" alt="img">
            <span class="title"><?php echo $row->name; ?></span>
        </a>
    </div>
<?php endif; ?>

<?php return ob_get_clean();
}; ?>
<div class="block-categoriesImage">
    <div class="block-title heading-opt1">
        <strong class="title"><?php echo $widget->name ?></strong>
    </div>
    <div class="block-content">
        <div class="row">
            <div class="col-lg-4 col-md-12">
                <div class="row">
                    <div class="col-lg-12 col-md-4 col-sm-4">
                        <?php echo $_data_row(0) ?>
                    </div>
                    <div class="col-lg-12 col-md-4 col-sm-4">
                        <?php echo $_data_row(1) ?>

                    </div>
                    <div class="col-lg-12 col-md-4 col-sm-4">
                        <?php echo $_data_row(2) ?>

                    </div>
                </div>

            </div>
            <div class="col-lg-8 col-md-12">
                <?php echo $_data_row(3,'box-img-lg') ?>

                <div class="row">
                    <div class="col-md-4 col-sm-4">
                        <?php echo $_data_row(4) ?>

                    </div>
                    <div class="col-md-4 col-sm-4">
                        <?php echo $_data_row(5) ?>

                    </div>
                    <div class="col-md-4 col-sm-4">
                        <?php echo $_data_row(6) ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>