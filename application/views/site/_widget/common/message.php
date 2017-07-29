<?php
$_data_message = function ($close = 1) use ($message) {
    $_cls = array();
    $_cls['info'] = 'info';
    $_cls['success'] = 'success';
    $_cls['warning'] = 'warning';
    $_cls['error'] = 'danger';
    ob_start() ?>
    <?php foreach ($message as $t => $ms): ?>
        <div class="alert alert-<?php echo $_cls[$t]; ?>">
            <?php if ($close): ?>
                <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
            <?php endif; ?>
            <?php if (count($ms) == 1): ?>
                <i class="fa fa-tags"></i> <?php echo current($ms); ?>
            <?php elseif (count($ms)): ?>
                <ul>
                    <?php foreach ($ms as $m): ?>
                        <li><?php echo $m; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php return ob_get_clean();
}
?>
<?php if ($message_display == 'text' || $message_display == 'modal'): ?>
    <?php if ($message_display == 'text') : ?>
        <div class="container">
            <div class="mt20">
                <?php echo $_data_message() ?>
            </div>
        </div>
    <?php else: ?>
        <script type="text/javascript">
            jQuery(function ($) {
                $('#myMessage').modal();
            });
        </script>
        <!-- Modal -->
        <div class="modal fade" id="myMessage" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Thông báo</h4>
                    </div>
                    <div class="modal-body">
                        <?php echo $_data_message(0) ?>
                    </div>
                    <div class="modal-footer">
                        <a  data-dismiss="modal"  class="btn btn-default">Ok<?php //echo $this->lang->line('Ok'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php elseif ($message_display == 'toast'): ?>
    <script type="text/javascript">
        jQuery(function ($) {
            $.gritter.removeAll();// go thong bao cu
            var message = <?php echo  json_encode($message)?>;
            $.each(message, function (type, ls) {
                //pr(ls);
                $.each(ls, function (i, ms) {
                    //pr(ms);
                    $.gritter.add({title: 'Thông báo!', text: ms, position:'bottom-right'}
                    );
                });
            });
        });
    </script>
<?php endif; ?>