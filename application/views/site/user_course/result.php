<?php //echo macro()->page_heading(lang('title_test_result'))?>
<?php //echo macro()->page_body_start()?>
    <div id="page" class="panel panel-default mt20">
        <div class="page-heading panel-heading">
            <h1 class="panel-title"><?php echo lang('title_test_result') ?></h1>
        </div>
        <div class="page-body panel-body">

            <div id="accordion" class="panel-body">
                <?php if ($results): ?>
                    <h4><?php echo lang('result_of_finished_test') ?></h4>
                    <hr>
                    <?php foreach ($lessons as $lesson): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading" role="tab" id="heading-<?php echo $lesson->id ?>e">
                                <h4 class="panel-title" role="button" data-toggle="collapse" data-parent="#accordion"
                                    href="#collapse-<?php echo $lesson->id ?>" aria-expanded="true"
                                    aria-controls="collapse-<?php echo $lesson->id ?>">
                                    <?php echo lang('lesson') ?>: <?php echo $lesson->name ?>
                                </h4>
                            </div>
                            <div id="collapse-<?php echo $lesson->id ?>" class="panel-collapse collapse" role="tabpanel"
                                 aria-labelledby="heading-<?php echo $lesson->id ?>">
                                <div class="panel-body">
                                    <?php
                                    foreach ($results as $result) {
                                        if ($lesson->id != $result->lesson_id)
                                            continue;

                                        ?>
                                        <div class="alert <?php echo $result->passed ? 'bg-success' : 'bg-warning' ?>">
                                            <p><?php echo lang('do_on_the_time') ?>
                                                <b><?php echo date('H:i', $result->created) ?></b>
                                                <?php echo lang('day') ?>
                                                <b><?php echo date('d/m/Y', $result->created) ?></b>
                                            </p>

                                            <p><?php echo lang('total_grade') ?>: <b><?php echo $result->grade ?></b> /
                                                <b><?php echo $result->total_grade ?></b></p>

                                            <p><?php echo lang('result') ?>:
                                                <b><?php echo $result->passed ? lang('passed') : lang('not_passed') ?></b>
                                            </p>
                                            <a href="javascript:void(0)" title='<?php echo lang('view_the_test') ?>'
                                               class="btn btn-info pull-right do_action"
                                               data-url="<?php echo site_url("lesson/result_examination/" . $result->id) ?>">
                                                <?php echo lang('view_the_test') ?></a>

                                            <div class="clearfix"></div>
                                        </div>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p><?php echo lang('no_result_test') ?>!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php //echo macro()->page_body_end()  ?>