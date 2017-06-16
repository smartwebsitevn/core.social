<?php if (isset($info->_author) && $info->_author): ?>
    <?php
    //pr($info->_author);
    $_data_author =function($author){
       // pr($author);
        ob_start()?>
            <div class="instructor__body usertracker-command">
                <div class="instructor__left images-master">
                    <a class="instructor__instructor-image-container js-discover-tracker-elm ud-discover-tracker"
                       href="<?php echo $author->_url_view ?>">
                        <img class="instructor__instructor-image" src="<?php echo $author->avatar->url ?>">
                    </a>
                    <table class="instructor__stats">
                        <tbody>
                        <?php /* ?>
                    <tr class="instructor__stat-row">
                        <td class="instructor__stat-icon"><i class="udi udi-star"></i></td>
                        <td>
											<span class="instructor__stat">
											4,7
											<span class="instructor__stat-description">/5</span>
											</span>
                            Average rating
                        </td>
                    </tr>
                    <tr class="instructor__stat-row">
                        <td class="instructor__stat-icon"><i class="udi udi-comment"></i></td>
                        <td>
                            <span class="instructor__stat">9&nbsp;997</span> Reviews
                        </td>
                    </tr>
                    <tr class="instructor__stat-row">
                        <td class="instructor__stat-icon"><i class="udi udi-user"></i></td>
                        <td>
                            <span class="instructor__stat">43&nbsp;636</span> Students
                        </td>
                    </tr>
                    <tr class="instructor__stat-row">
                        <td class="instructor__stat-icon"><i class="udi udi-play-circle"></i></td>
                        <td>
                            <span class="instructor__stat">1</span> product
                        </td>
                    </tr>
                    <?php */ ?>

                        <?php if ($author->email): ?>
                            <tr class="instructor__stat-row">
                                <td class="instructor__stat-icon"><i class="fa fa-envelope lakita" aria-hidden="true"></i> </td>
                                <td>
                                    <span class="instructor__stat"><?php echo $author->email ?></span>
                                </td>
                            </tr>
                        <?php endif;
                        if ($author->facebook) { ?>
                            <tr class="instructor__stat-row">
                                <td class="instructor__stat-icon"><i class="fa fa-facebook" aria-hidden="true"></i> </td>
                                <td>
                                    <span class="instructor__stat"><?php echo $author->facebook ?></span>
                                </td>
                            </tr>
                        <?php }
                        if ($author->twitter) { ?>
                            <tr class="instructor__stat-row">
                                <td class="instructor__stat-icon"><i class="fa fa-twitter" aria-hidden="true"></i> </td>
                                <td>
                                    <span class="instructor__stat"><?php echo $author->twitter ?></span>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>

                </div>
                <div class="instructor__right">
                    <div class="instructor__titles ud-discover-tracker">
                        <a href="<?php echo $author->_url_view ?>" class="instructor__title js-discover-tracker-elm">
                            <?php echo $author->name ?>
                        </a>
                        <div class="instructor__job-title">
                            <?php echo $author->profession;//$author->information ?>
                        </div>
                    </div>
                    <div class="instructor__description ud-discover-tracker">
                        <div class="instructor__expand-description">
                            <?php
                                echo macro()->more_block($author->desc);
                            ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php return ob_get_clean();
    } ;
    ?>
    <h2>
        <?php echo lang("author") ?>
    </h2>
    <div class="detail-area-content instructor" id="instructor-author">
    <?php foreach($info->_author as $author): ?>
        <?php echo  $_data_author($author);?>
        <?php endforeach; ?>
    </div>

<?php endif; ?>