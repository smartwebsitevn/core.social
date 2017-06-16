<?php if (isset($list) && $list): //pr($list); ?>
    <?php $i = 1;
    foreach ($list as $row):    //pr($row);
        ?>
        <section class="project-block <?php echo ($i % 2 == 0) ? "is-right" : "is-left" ?> is-dark ">
            <a href="#0" class="act-modal" data-url="<?php echo $row->_url_view ?>" data-modal="#modal-blank">

            <div class="image " style="background-image:url(<?php echo $row->image->url ?>)">
                <div class="bg" style="background-image:url(<?php echo $row->image->url ?>)"></div>
            </div>
            <article>
                    <div class="content wow fadeInRight" style="visibility: visible; animation-name: fadeInRight;">
                        <h2 class="display-2"><?php echo $row->name; ?></h2>

                        <div class="hex-divider is-dark"></div>
                        <p><?php echo $row->description; ?></p>
                        Xem thêm
                    </div>
            </article>
            </a>
        </section>
        <?php $i++;endforeach; ?>
<?php else: ?>
    <span class="red"><?php echo lang("have_no_list") ?></span>
<?php endif; ?>
<?php //$this->load->view('site/_widget/movie/display/item/ajax_pagination') ?>