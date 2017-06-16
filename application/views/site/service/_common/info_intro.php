<?php if ($info->description):
    $_data_tmp = function () use ($info) {
        ob_start() ?>
        <div class="mb40">
            <h4>
                <?php echo lang("description") ?>
            </h4>
            <?php echo $info->description ?>
        </div>
        <?php return ob_get_clean();
    };
    echo macro()->more_block($_data_tmp());
    ?>
<?php endif; ?>
<?php
/*
$_data_tmp=function()use($info){
    ob_start()*/ ?><!--
   <ul>
       <li class="item">itemfsdfs</li>
       <li class="item">itemfsdfs</li>
       <li class="item">itemfsdfs</li>
       <li class="item">itemfsdfs</li>
       <li class="item">itemfsdfs</li>
       <li class="item">itemfsdfs</li>
       <li class="item">itemfsdfs</li>
       <li class="item">itemfsdfs</li>
   </ul>
    --><?php /*return ob_get_clean();
};
echo macro()->more_list($_data_tmp());*/

?>
