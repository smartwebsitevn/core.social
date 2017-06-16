<?php if(isset($ajax_pagination) && $ajax_pagination):?>
<ul class="movies-pagination pagination-sm pull-right" data-total="<?php echo $ajax_pagination_total?>" data-href="<?php echo $ajax_pagination_url?>">
</ul>
<div class="clearfix"></div>
<?php endif;?>