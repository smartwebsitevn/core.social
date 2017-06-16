<?php
$make_items = function() use ($list)
{
    ob_start();?>

    <div class="list-group">

        <?php foreach ($list as $row): ?>

            <a href="<?php echo $row->_url_view; ?>"
               class="list-group-item"
                ><?php echo $row->title; ?></a>

        <?php endforeach; ?>

    </div>

    <?php return ob_get_clean();
};

echo macro('mr::box')->box([
    'title'   => $name,
    'content' => $make_items(),
]);