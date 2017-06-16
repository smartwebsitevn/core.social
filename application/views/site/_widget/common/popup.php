<?php
$site_settings = module_get_setting('site');
$first_popup = get_cookie('state_popup');

 if (user_is_login() && $site_settings['notify_home_status'] && !$first_popup) :
    set_cookie('state_popup', 1, 1 * 24 * 60 * 60);

    ?>
    <script type="text/javascript">
        jQuery(function ($) {
            $('#myNotice').modal();
        });
    </script>
    <!-- Modal -->
    <div class="modal fade" id="myNotice" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Thông báo</h4>
                </div>
                <div class="modal-body">

                    <?php echo $site_settings['notify_home']; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>