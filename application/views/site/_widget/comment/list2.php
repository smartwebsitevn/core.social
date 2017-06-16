<?php if ( isset($info) ): ?>
    <div class="clearfix"></div>
    <div class="top-box-comments p20" id="rate-box">
        <div id="comment-list" class="media-list comments-list ">
            <?php /*$this->load->view('site/_widget/comment/_list', array(
                'list' => $list,
                'ajax_pagination_total' => $ajax_pagination_total,
                'total' => $total,
                'info' => $info,
                'page_size' => $page_size,
                'page' => 1
            ))*/ ?>
            <?php if (mod("product")->setting('comment_allow')): ?>
                <?php if (!user_is_login()): ?>
                    <h4>
                        <small><?php echo lang("notice_please_login_to_use_function") ?></small>
                    </h4>
                <?php else: ?>
                    <?php  ?>
            <form id="commentForm" class="form_action" accept-charset="UTF-8"
              action="<?php echo site_url('comment/add') ?>" method="POST">
            <input type="hidden" name="table_id" value="<?php echo $info->id ?>"/>
            <input type="hidden" name="table_name" value="product"/>
            <input type="hidden" value="0" name="parent_id">

            <img src="<?php echo isset($user->avatar->url_thumb) ? $user->avatar->url_thumb : public_url('img/user_no_image.png') ?>"></div>

            <div class="form-group text-right">
                                <textarea style="width: 70%;height: 60px;float:left;margin-right:20px" name="content"
                                          placeholder="<?php echo lang("comment") ?>..."
                                          class="form-control"></textarea>
                <input type="submit" value="Bình luận" class="btn btn-default btn-xs pull-left">

                <div class="clear"></div>
                <div name="content_error" class="error "></div>
                <div name="user_error" class="error "></div>
            </div>
            </form>
                <?php  ?>

                <?php endif; ?>

                <hr>

                <div  class="ratings-and-reviews">
                    <div class="ratings-and-reviews__sub-title"><span>Nhận xét (<?php echo count($list) ?>)</span></div>
                    <?php  foreach ($list as $row) :           ?>
                        <div class="ratings-and-reviews__review-container">
                            <div class="ratings-and-reviews__reviewer-details">
                                <div class="ratings-and-reviews__reviewer-avatar">
                                    <img src="<?php echo $row->user->avatar ? $row->user->avatar->url_thumb : public_url('img/user_no_image.png') ?>"></div>
                                <div class="ratings-and-reviews__reviewer-info">
                                    <div class="ratings-and-reviews__reviewer-name ellipsis"> <?php echo $row->user_name ?></div>
        </div>
                            </div>
                            <div class="ratings-and-reviews__review-comment-container">
                                <div class="star-rating--static star-rating--small">
                                <span style="width: <?php echo 18 * ($row->rate ) ?>px;"></span>
                                </div>
                              <div class="ratings-and-reviews__review-created" translate=""><span><?php echo get_date($row->created,"time") ?></span></div>

                                <div class="ratings-and-reviews__review-comment-content">
                                    <p><?php echo html_escape($row->content) ?></p>
                                </div>
                            </div>
                        </div>
                    <?php  endforeach;   ?>

                    <!--<div class="ratings-and-reviews__show-more">
                        <button class="btn btn-default">+ Read more</button>
                    </div>-->
                </div>
                <?php if($ajax_pagination_total>5): ?>
                    <ul class="comments-pagination pagination-sm pull-right pagination"
                        data-total="<?php echo $ajax_pagination_total ?>"
                        >
                        <?php
                        for ($i = 1; $i <= $ajax_pagination_total; $i++) {
                            ?>
                            <li <?php echo $page == $i ? 'class="active"' : '' ?>>
                                <a
                                    href="<?php echo site_url("comment/show/{$info->id}?per_page=$page_size&page=$i") ?>"
                                    data-product="<?php echo $info->id ?>"
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
                <?php endif; ?>
            <?php endif; ?>

            <!-- Facebook comment -->
            <?php /* if (mod("product")->setting('comment_fb_allow')): //pr($info);?>
    <div class="clearfix"></div>
    <div class="fb-comments"
         data-href="<?php echo $info->_url_view ?>"
         data-numposts="5" data-width="100%">
    </div>
<?php endif; */ ?>

        </div>

        <div class="clearfix"></div>
    </div>
    <script type="text/javascript">
        $('#comment-list').on('click', '.comments-pagination a', function(e){
            e.preventDefault();
            var href = $(this).prop('href');
            var perpage = $(this).data('perpage');
            var page = $(this).data('page');

            $.ajax({
                url: href,
                dataType: 'html',
                success: function(output){
                    $('#comment-list').html( output );
                }
            });
        });
    </script>
<?php endif; ?>