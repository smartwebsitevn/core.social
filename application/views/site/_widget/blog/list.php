
<?php if($blogs): ?>
    <?php
    $asset = public_url() ;
    $asset_theme = $asset.'/site/theme/'.$this->_template.'/';

    foreach blogces as $row)
    {
        $row = mod('lessoblogce')->add_info($row);
        ?>
        <li>
            <div class="box-product">
                <div class="img">
                    <a href="<?php echo $row->_url_view ?>" title=""><img src="<?php echo $row->image->url ?>" alt="" /></a>
                    <?php
                    if( $row->price_option == 1 && $row->discount )
                    {
                        ?>
                        <span class="onsale">-<?php echo $row->discount ?>%</span>
                        <?php
                    }
                    ?>
                </div>
                <div class="des">
                    <a href="<?php echo $row->_url_view ?>" class="title"><?php echo $row->name ?></a>

                    <?php
                    if($authors &&  $row->author_id && ! $short && $author = objectExtract( [ 'id' => $row->author_id ], $authors, true ) )
                    {
                        $author = mod('lesson_author')->add_info($author);
                        // pr($author);
                        ?>
                        <div class="author">
                            <img src="<?php echo $author->image->url ?>" class="img-left">
                            <div class="author-right">
                                <a href="<?php echo $author->_url_view ?>.html" title="<?php echo lang('author') ?>">
                                    <span class="name"><?php echo $author->name ?></span>
                                </a>
                                <span class="cv"><?php echo $author->information ?></span>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                    <span class="star">
                    <?php
                    $i = 1;
                    while( $i < $row->rate )
                    {
                        ?>
                        <i></i>
                        <?php
                        $i++;
                    }
                    if( $row->rate - ($i - 1) > 0 )
                    {
                        ?>
                        <i style="width: <?php echo 18 * ($row->rate - ( $i - 1 ) ) ?>px"></i>
                        <?php
                    }
                    ?>
                </span>
                    <span class="cart"><?php echo $row->_price ?></span>
                </div>
            </div>
        </li>
        <?php
    }

    ?>
<?php endif; ?>
