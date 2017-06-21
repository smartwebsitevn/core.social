<?php
$_data_link = function($list) use($url_info_cat){
    ob_start(); ?>
    <?php if ($list): ?>
        <?php foreach ($list as $it):
            if(!isset($it->name) || !$it->name) continue;
            $link_cat='';
            if(is_numeric($it->id))
                $link_cat ='href="javascript:void(0)"   class=" act-display-cat"  data-url="'.$url_info_cat . '?id=' . $it->id. '"';
            ?>
            <a <?php echo $link_cat; ?>  ><?php echo ucfirst($it->name) ?></a>,
        <?php endforeach; ?>
    <?php endif; ?>

    <?php return ob_get_clean();
};
?>

    <div class="item-decaption limit-height">
        <?php
        if($row->desc )
        echo $row->desc .','?>
        <?php // if($row->user_id ==1) pr($row->cat_u_specialize_id);
        foreach(array('_cat_u_specialize_id','_cat_u_meetwork_id','_cat_u_quality_id') as $f){
            if(isset($row->$f))
                echo $_data_link($row->$f);
        }
        ?>
    </div>

<?php if (isset($row->_cat_u_skill_id)): ?>
    <ul class="item-list-tag">
        <?php
        $d=count($row->_cat_u_skill_id);
        foreach ($row->_cat_u_skill_id as $it):
            if(!isset($it->name) || !$it->name) continue;

            $class ='item-tag-title ';
            if(is_numeric($it->id))
                $link_cat ='href="javascript:void(0)"   data-url="'.$url_info_cat . '?id=' . $it->id. '"';
              $class ='item-tag-title  act-display-cat';
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