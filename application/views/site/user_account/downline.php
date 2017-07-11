<script type="text/javascript" src="<?php echo public_url('js') ?>/jquery/colorbox/jquery.colorbox-min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo public_url('js'); ?>/jquery/colorbox/colorbox.css"
      media="screen"/>


<?php
$mr = array();
$mr['info'] = function ($downline_trees, $user_downline, $node_parent, $parent) {
    // pr($user_downline,0);    pr($downline_trees);

    ob_start(); ?>

    <div class="net_wrapper">
        <!--<div style="position:absolute; font-weight:bold;">
            <div> </div>
            <div style="margin-top:190px;">1</div>
            <div class="hidden-xs" style="margin-top:140px;">2</div>
            <div class="hidden-sm hidden-xs" style="margin-top:90px;">3</div>
        </div>-->
        <div >
            <?php //if ($user_downline->id != $parent->id): ?>
                <a class="seemore btn btn-default" href="<?php echo site_url('user/downline/' . $parent->username) ?>"
                   style="width:120px;">Back to top <span class="fa fa-angle-double-up"></span></a>
                <?php if (isset($node_parent->username)): ?>
                    <a class="seemore btn btn-default"
                       href="<?php echo site_url('user/downline/' . $node_parent->username) ?>"
                       style="width:110px; margin-left:10px;">Up 1 Level <span class="fa fa-angle-up"></span></a>
                <?php endif; ?>
            <?php //endif; ?>


        </div>
        <?php
        //echo downline_tree($user_downline->id, $downline_trees, 1, $user_downline->level);
        echo downline_tree_table($user_downline, $downline_trees)
        ?>
    </div>
    <?php return ob_get_clean();
};

echo macro('mr::box')->box([
    'title' => lang('title_downline') . ' - ' . $user_downline->username . '(' . $user_downline->total_children . ')',
    'content' => $mr['info']($downline_trees, $user_downline, $node_parent, $parent),
]);

//echo downline_tree($user_downline->id, $downline_trees, 1, $user_downline->level);


