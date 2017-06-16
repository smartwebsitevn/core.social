<?php

/**
 * Box
 */
$this->register('box', function(array $args){ ob_start(); ?>

    <?php
    $title 		= array_get($args, 'title');
    $body 		= array_get($args, 'body');
    $content 	= array_get($args, 'content');
    $color		= array_get($args, 'color', 'default'); // default, primary, success, info, warning, danger
    $fa		= array_get($args, 'fa', 'info');
    ?>
    <div class="box panel panel-<?php echo $color ?> ">
        <?php if ($title): ?>
            <div class="panel-heading">
                <h1 class="panel-title"><?php echo $title; ?></h1>
            </div>
        <?php endif ?>
            <div class="panel-body">
            <?php if ($body): ?>
                <?php echo $body; ?>
            <?php endif ?>
            <?php if ($content): ?>
            <?php echo $content; ?>
            <?php endif ?>

            </div>

    </div>
    <?php /* ?>
    <div class="box" style="margin-top:0px">
        <?php if ($title): ?>
            <h2 class="box-title"><?php echo $title ?></h2>
        <?php endif ?>
        <div class="box-content">
        <?php if ($body): ?>
                    <?php echo $body; ?>
        <?php endif ?>
        <?php echo $content; ?>
        </div>

    </div>
    <?php */ ?>


    <?php return ob_get_clean(); });


$this->register('box_widget', function (array $args) {
    ob_start(); ?>

    <?php
    $title = array_get($args, 'title');
    $body = array_get($args, 'body');
    $color = array_get($args, 'color', 'default'); // default, primary, success, info, warning, danger
    ?>
    <div class=" box_widget panel panel-<?php echo $color ?>">
        <?php if ($title): ?>
            <div class=" panel-heading">
                <h2><?php echo $title; ?> </h2>
            </div>
        <?php endif ?>
        <div class="panel-body">
            <?php echo $body; ?>
        </div>
    </div>
    <?php return ob_get_clean();
});

/**
 * panel
 */

$this->register('panel', function(array $args){ ob_start(); ?>

    <?php
    $title 		= array_get($args, 'title');
    $content 	= array_get($args, 'content');
    $color		= array_get($args, 'color', 'default'); // default, primary, success, info, warning, danger
    $fa		= array_get($args, 'fa', 'info');
    ?>

    <div class="panel panel-<?php echo $color ?> ">
        <?php if ($title): ?>
            <div class="panel-heading">
                <h3 class="panel-title"><?php echo $title; ?></h3>
            </div>
        <?php endif ?>
        <?php if ($content): ?>
            <div class="panel-body">
                <?php echo $content; ?>
            </div>

        <?php endif ?>
    </div>
    <?php return ob_get_clean(); });
