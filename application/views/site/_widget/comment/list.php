<?php if (isset($info)): ?>
    <div class="clearfix"></div>
    <div class="top-box-comments" id="rate-box">

        <div id="comment-list" class="media-list comment-list">
            <?php if (1/*mod("product")->setting('comment_allow')*/): ?>

                <?php if (!user_is_login()): ?>
                    <h4>
                        <small><?php echo lang("notice_please_login_to_use_function") ?></small>
                    </h4>
                <?php else: ?>
                    <form id="commentForm" class="form_action" accept-charset="UTF-8"
                          action="<?php echo site_url('comment/add') ?>" method="POST">
                        <input type="hidden" name="table_id" value="<?php echo $info->id ?>"/>
                        <input type="hidden" name="table_name" value="<?php echo $type ?>"/>
                        <input type="hidden" value="0" name="parent_id">

                        <!--<img src="<?php /*//echo !$user->avatar?$user->avatar->url_thumb:public_url('site/layout/img/default-avatar.png')*/ ?>" class="media-object user-avatar pull-left">-->

                        <div class="row mt10">
                            <div class="col-md-12 mb20">
                                <strong>(<?php echo $total ?>) Bình luận </strong>
                                <hr/>

                            </div>
                            <div class="col-md-1">
                                <i class="fa fa-user fa-3x" aria-hidden="true"></i>
                            </div>
                            <div class="col-md-11">
                                <textarea name="content"
                                          placeholder="<?php echo lang("comment") ?>"
                                          class="form-control"></textarea>
                                <a  _submit="true" class="mt10 pull-right">Post</a>
                                <div class="clear"></div>
                                <div name="content_error" class="error "></div>
                                <div name="user_error" class="error "></div>
                            </div>

                        </div>
                    </form>
                    <ul class="list-unstyled">
                        <?php
                        foreach ($list as $row) {
                            ?>
                            <li>
                                <?php echo widget('comment')->builder_html($row);//$_comment($row); ?>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                    <?php /* ?>
                        <ul class="comments-pagination pagination-sm pull-right pagination"
                            data-total="<?php echo $ajax_pagination_total ?>"
                            >
                            <?php
                            for ($i = 1; $i <= $ajax_pagination_total; $i++) {
                                ?>
                                <li <?php echo $page == $i ? 'class="active"' : '' ?>>
                                    <a
                                        href="<?php echo $ajax_pagination_url;//site_url("comment/show/{$info->id}?per_page=$page_size&page=$i") ?>"
                                        data-perpage="<?php echo $page_size ?>"
                                        data-page="<?php echo $i ?>"

                                        >
                                        <?php echo $i ?>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                        </ul>
                     <?php */ ?>
                    <?php $this->widget->site->pages($pages_config); ?>

                <?php endif; ?>

            <?php endif; ?>


        </div>

        <div class="clearfix"></div>
    </div>
    <script type="text/javascript">
        $('#comment-list').on('click', '.comments-pagination a', function (e) {
            e.preventDefault();
            var href = $(this).prop('href');
            var perpage = $(this).data('perpage');
            var page = $(this).data('page');

            $.ajax({
                url: href,
                dataType: 'html',
                success: function (output) {
                    $('#comment-list').html(output);
                }
            });
        });
    </script>
<?php endif; ?>