<?php echo macro()->page_heading(lang('message_view')) ?>
<?php echo macro()->page_body_start() ?>
<?php view('tpl::message/_menu', ['current' => 'sended']); ?>

    <h4><?php echo $message->title ?></h4>
    <p><i class="fa fa-calendar" aria-hidden="true"></i> <?php echo lang('created') ?>
        : <?php echo $message->_created_time; ?></p>

    <div>
        <?php echo $message->content; ?>
    </div>
    <div class="clear"></div>
    <hr>
    <h3><?php echo lang('receive') ?></h3>

<?php foreach ($receives as $receive): ?>
    <?php $status = ($receive->readed > 0) ? 'readed' : 'not_readed' ?>
    <?php $title = ($receive->readed > 0) ? lang('readed') . ' lúc ' . get_date($receive->readed, 'full') : lang('not_readed') ?>

    <a data-toggle="tooltip" title="<?php echo $title ?>"><b><i aria-hidden="true"
                                                                class="fa fa-user"></i> <?php echo $receive->receive_username ?>
        </b>: <span class="<?php echo $status ?>"><?php echo lang($status) ?></span></a> |
<?php endforeach; ?>
    <div class="clear"></div>
<?php echo macro()->page_body_end() ?>