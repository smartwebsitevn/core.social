<?php if (isset($row->_job) && $row->_job): ?>
    <ul class="item-list-tag">
        <?php

        $url_filter_cat = site_url('user_list');
        $d=count($row->_job);
        foreach ($row->_job as $it):
            if(!isset($it->name) || !$it->name) continue;
            $url_filter_cat  .='?' . url_build_query(['job'=>$it->id]);

            $class ='item-tag-title ';
            if(is_numeric($it->id))
                $link_cat ='href="'.$url_filter_cat .  '"';
              $class ='item-tag-title  ';
            ?>
            <li class="item-tag">
                <a  class="<?php echo $class ?>" <?php echo $link_cat; ?> ><?php echo $it->name ?></a>
            </li>
        <?php endforeach; ?>
        <?php if ($d > 8): ?>
            <li >
                <a href="javascript:void(0)" class="act_list_all more-tab"   title="<?php // echo lang("view_more_other_cat", 5) ?>"><span><?php echo  number_format($d-8) ?> more...</span></a>
                <a href="javascript:void(0)" class="act_list_short more-tab"   title="<?php //echo lang("view_more_other_cat", 5) ?>"><span>less</span></a>
            </li>
        <?php endif; ?>
    </ul>
<?php endif; ?>