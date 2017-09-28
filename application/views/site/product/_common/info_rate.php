<?php if (isset($row->rate)): ?>
    <?php
    $_data_rate1 = function () use ($row) {
        ob_start(); ?>
        <?php $i = 1;
        while ($i < $row->rate) {
            ?>
            <i></i>
            <?php
            $i++;
        }
        if ($row->rate - ($i - 1) > 0) {
            ?>
            <i style="width: <?php echo 18 * ($row->rate - ($i - 1)) ?>px"></i>
            <?php
        } ?>

        <?php return ob_get_clean();
    };
    $_data_rate2 = function () use ($row) {
        ob_start();
        $ratio = 5 / 100;
        $percent = round($row->rate / $ratio);
        ?>
        <div class="item-vote">
                <span class="vote-result"><span style="width:<?php echo $percent ?>%;"><span><?php echo $percent ?>
                            %</span></span></span>
                <span class="vote-count"><?php echo $row->rate ?> <span>(<?php echo number_format($row->rate_total) ?>
                        )</span></span>
        </div>
        <?php return ob_get_clean();
    };

   echo $_data_rate2();

    ?>


<?php endif; ?>