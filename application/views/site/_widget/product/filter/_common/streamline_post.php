<?php
$stream = model('product')->get_streamline_post($user->id);
if ($stream->min):

    $month_selected= t('input')->get('month');
    $year_selected= t('input')->get('year');

    //pr_db($stream);
    $min = \Carbon\Carbon::createFromTimestamp($stream->min);
    if ($stream->max)
        $max = \Carbon\Carbon::createFromTimestamp($stream->max);
    else        $max = \Carbon\Carbon::now();
    //pr($max);
    ?>
    <?php
    //$input['limit'] = array(0,2);
    $filter = array();
    $filter['show'] = 1;
    $filter['user_id'] = $user->id;
    //pr_db($users);
   // pr_db($min->year);

    ?>
    <div class="slimscroll">
        <ul class="list-group  timeline">
            <?php if ($min->year < $max->year): ?>


                <li class="list-group-item"><b>Năm <?php echo $max->year ?></b></li>
                <?php $i=1; for ($m = $max->month; $m >= 1; $m--) : ?>
                    <?php
                    $filter['created'] = $max->copy()->startOfMonth()->timestamp;
                     $filter['created_to'] = $max->copy()->endOfMonth()->timestamp;
                     $total= model('product')->filter_get_total($filter);
                    $max->subMonth(1);

                    // echo '<br>-m='.$max->subMonth(1);
                   // pr_db($max->month,0);
                    if(!$total) continue;
                    ?>
                    <li class="list-group-item act-filter " data-name="created" data-value="<?php echo $filter['created'].'|'.$filter['created_to'] ?>"><a href="#0"> - Tháng <?php echo $m  ?> : <?php echo number_format($total) ?>posts </a></li>

                <?php endfor; ?>

                <?php foreach (range($max->year-1, $min->year, -1) as $y): ?>
                    <?php
                    $max->subYear(1);
                    $filter['created'] = $max->copy()->startOfYear()->timestamp;
                    $filter['created_to'] = $max->copy()->endOfYear()->timestamp;
                    $total= model('product')->filter_get_total($filter);
                    //pr_db($max->year,0);
                    if(!$total) continue;

                    ?>
                    <li class="list-group-item act-filter " data-name="created" data-value="<?php echo $filter['created'].'|'.$filter['created_to'] ?>"><a href="#0"><b>Năm <?php echo $y  ?></b> : <?php echo number_format($total) ?>posts </a></li>

                <?php endforeach; ?>
            <?php else: ?>
                <li class="list-group-item"><b>Năm <?php echo $max->year ?></b></li>
                <?php for ($m = $max->month; $m >= $min->month; $m--) : ?>
                    <?php
                    $filter['created'] = $max->copy()->startOfMonth()->timestamp;
                    $filter['created_to'] = $max->copy()->endOfMonth()->timestamp;
                    $total= model('product')->filter_get_total($filter);
                    $max->subMonth(1);

                    if(!$total) continue;

                    ?>
                    <li class="list-group-item act-filter " data-name="created" data-value="<?php echo $filter['created'].'|'.$filter['created_to'] ?>"><a href="#0"> - Tháng <?php echo $m  ?> : <?php echo number_format($total) ?>posts </a></li>

                <?php endfor; ?>
            <?php endif; ?>

        </ul>

    </div>
<?php endif; ?>