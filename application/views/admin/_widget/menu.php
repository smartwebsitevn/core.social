<ul id="side" class="nav navbar-nav side-nav">
    <li>
        <a href="<?php echo admin_url() ?>">
            <i class="fa fa-dashboard"></i> <?php echo lang('group_home') ?>
        </a>
    </li>
    <?php
    foreach ($menu as $group => $items):

        if (in_array($group, array("home"))) continue;

        $total_item = count($items);

        $class = '';
        if ($group == $group_cur) $class .= 'open';
        if ($total_item) $class .= ' exp';
        ?>
        <li class="panel <?php echo $class;//echo $group;
        ?>">

            <a data-toggle="collapse" href="javascript:void(0)" class="accordion-toggle">
                <i class="fa fa-<?php echo isset($menu_icon_group[$group]) ? $menu_icon_group[$group] : 'list'; ?>"></i>
                <?php echo (isset($menu_lang[$group]['_'])) ? $menu_lang[$group]['_'] : lang('group_' . $group); ?>
                <!--<span class="badge badge-primary"><?php if ($total_item) echo $total_item; ?></span>-->
                <span class="fa arrow"></span>
            </a>

            <?php if ($total_item): //pr($items,0); ?>
                <ul class="collapse nav">
                    <?php foreach ($items as $item): ?>
                        <?php if ($item == '-'): ?>
                            <li class="separate"></li>
                        <?php else:
                            $href = $menu_url[$group][$item]; ?>
                            <li>
                                <a <?php if ($item == $item_cur) echo 'class="active"'; ?> href="<?php echo $href ?>"
                                    <?php if ($item == 'media'): ?>
                                        onClick="window.open('<?php echo $href ?>','MyWindow',width=600,height=300); return false;";
                                    <?php endif; ?>
                                    >

                                    <i class="fa fa-angle-double-right"></i> <?php echo (isset($menu_lang[$group][$item])) ? $menu_lang[$group][$item] : lang('mod_' . $item); ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>

        </li>
    <?php endforeach; ?>
</ul>
<style type="text/css">
    .navbar-side .side-nav li.panel ul li.separate {
        border-bottom: 1px solid #424242 !important;
    }
</style>