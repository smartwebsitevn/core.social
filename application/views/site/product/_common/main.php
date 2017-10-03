<div class="product-media block-info">
    <h1 class="page-title">
        <?php echo $info->name ?>
    </h1>
    <?php /* ?>
    <div class="product-meta">
        <p>
            <?php echo view('tpl::_widget/product/display/item/info_rate', array('info' => $info)); ?>
        </p>
    </div>
    <?php */ ?>


    <?php //t('view')->load('tpl::product/_common/info_video') ?>
    <div>
        <?php t('view')->load('tpl::product/_common/info_images') ?>
    </div>
    <?php if($info->link): ?>
    <div class="item-media">
        <?php if (isset($info->link) && $info->link):
            $tags = json_decode($info->link_data, 1);
            ?>
            <div class="media">
                <a href="<?php echo $info->link ?>" target="_blank">

                    <?php if (isset($tags['image'])): ?>
                        <div class="media-left">
                            <img class="media-object" src="<?php echo $tags['image'] ?>">
                        </div>
                    <?php endif; ?>
                    <div class="media-body">
                        <h5 class="media-heading"><?php echo isset($tags['title']) ? $tags['title'] : '' ?></h5>
                        <?php if (isset($tags['description'])): ?>
                            <small><?php echo $tags['description'] ?></small>
                        <?php endif; ?>
                        <?php if(isset($tags['source_name'])): ?>
                            <br>
                            <small class="text-grey">Ngu?n: <a href="<?php echo $tags['source_url'] ?>" target="_blank"><?php echo  $tags['source_name']?></a></small>
                        <?php endif; ?>
                    </div>
                </a>

            </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    <div class="item-actions">
        <div class="item-meta item-action">
            <?php echo widget('product')->action_vote($info) ?>
        </div>
        <div class="item-action">
            <?php echo widget('product')->action_favorite($info) ?>
        </div>
        <div class="item-action">
            <?php widget('product')->action_share($info
            ) ?>
        </div>

    </div>
    <div class="item-overview">
        <?php echo macro()->more_word($info->description,63); ?>
    </div>
    <?php t('view')->load('tpl::product/_common/info_files') ?>

    <?php //t('view')->load('tpl::product/_common/info') ?>

    <?php //t('view')->load('tpl::product/_common/same_cat') ?>
    <?php t('view')->load('tpl::product/_common/info_comment') ?>

</div>
