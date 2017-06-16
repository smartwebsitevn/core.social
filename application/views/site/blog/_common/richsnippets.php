<!--  Rich Snippets-->
<div itemscope itemtype="http://schema.org/Recipe" class="hide">
    <span itemprop="name"><?php echo $info->name?></span>
    <img itemprop="image" src="<?php echo $info->image->url_thumb?>" alt="<?php echo $info->name?>">
    <div itemprop="aggregateRating" itemscope="" itemtype="http://schema.org/AggregateRating">
        <span itemprop="ratingValue"><?php echo $info->rate?></span>/<span itemprop="bestRating">10</span>
        <span itemprop="ratingCount">(<?php echo $info->rate_count.lang('rate_num')?>)</span>
    </div>
</div>