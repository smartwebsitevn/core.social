<?php  if (isset($info->_manufacture) && $manufacture=$info->_manufacture):

    $manufacture = mod('manufacture')->get_info($manufacture->id);
   // pr($manufacture);
    ?>
    <h4>Nhà sản xuất<?php //echo lang("manufacture") ?></h4>
    <hr/>
    <div class="gv">
        <div class="row">
                <div class=" item-gv">
                    <div class="item-photo">
                        <a href="<?php echo $manufacture->_url_view ?>">
                            <img class="instructor__instructor-image" src="<?php echo $manufacture->image->url_thumb ?>">
                        </a>
                    </div>
                    <div class="item-detail">
                        <h4 class="item-name"> <?php echo $manufacture->name ?></h4>
                        <span> <?php echo $manufacture->brief; ?></span>
                        <div class="social">
                            <?php  /*if ($manufacture->email): ?>
                            <a href="<?php echo $manufacture->email ?>" class="sc-link sc-email"><i class="fa fa-emails"
                                                                    aria-hidden="true"></i></a>
                          <?php endif;*/ ?>
                        </div>
                    </div>
                    <div class="item-des">
                        <?php echo macro()->more_block($manufacture->description); ?>
                    </div>
                </div>

        </div>
    </div>

<?php endif; ?>