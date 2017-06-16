<div class="rateit " data-rateit-value="<?php echo $movie->rate?>" data-rateit-ispreset="true" data-rateit-readonly="false" data-url="<?php echo site_url("movie/raty/".$movie->id);?>"></div>
    (<?php echo $movie->rate_count.' '.lang('rate_num')?>) <b><?php echo $movie->rate?></b>
<?php /* ?>
<div class="pull-right">
    <div title="<?php echo $movie->name; ?>" class="star-rating">
     Đánh giá:  <div class="rateit " data-rateit-value="<?php echo $movie->rate?>" data-rateit-ispreset="true" data-rateit-readonly="false" data-url="<?php echo site_url("movie/raty/".$movie->id);?>"></div>
    </div>
    (<?php echo $movie->rate_count.' '.lang('rate_num')?>) <b><?php echo $movie->rate?></b>
</div>
 <?php */ ?>
