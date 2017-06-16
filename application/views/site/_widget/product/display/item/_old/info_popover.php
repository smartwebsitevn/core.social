<div id="movie-data-info-<?php echo $row->id;?>"  style="display:none">
      <div class="popover-title">
          <span class="pull-right hd "><?php echo lang("hd"); ?> <span>720</span></span>
          <span class="name"><?php echo cutText($row->name,120,''); ?></span>
      </div>
      <div class="popover-content">
          <p ><?php echo cutText($row->desc,450); ?></p>
          <hr>
          <b><?php echo lang('movie_imdb')?>: <?php echo $row->imdb?></b>
          <br>

          <div class="start">
          <div class="pull-left"><b><?php echo lang('rate')?>:</b> </div>
           <div class="review pull-left ">
                <div title="<?php echo $row->name; ?>" class="star-rating">
					  <div class="rateit" data-rateit-value="<?php echo $row->rate?>" data-rateit-ispreset="true" data-rateit-readonly="true"></div>
				</div>
           </div>
           <?php /*?><span class="pull-left">(<?php echo $row->rate?> - <?php echo $row->rate_total.lang('rate_num')?>)</span><?php */?>
          </div> <br />

      </div>
</div>
