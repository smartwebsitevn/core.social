<?php
$settings = $widget->setting;
$content_top = html_entity_decode(handle_content($settings['content_top'], 'output'));
$content_bottom = html_entity_decode(handle_content($settings['content_bottom'], 'output'));
//pr($settings);
?>
<?php echo $content_top; ?>
    <div class="clearfix"></div>
        <div class="tab-service">
            <ul class="nav nav-pills">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <?php if (isset($settings["name" . $i]) && $settings["name" . $i]): ?>
                        <li class="<?php if ($i == 1) echo 'active'; ?>">
                            <a data-toggle="pill" href="#html_tab_<?php echo $i . '_' . $widget->id ?>"
                                ><?php echo $settings["name" . $i]; ?></a>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>

            </ul>
            <div class="tab-content">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <div class="tab-pane fade <?php if ($i == 0) echo 'active in'; ?> "
                         id="html_tab_<?php echo $i . '_' . $widget->id ?>">
                        <?php if (isset($settings["name" . $i]) && $settings["name" . $i]): ?>
                            <?php echo html_entity_decode(handle_content($settings['content' . $i], 'output'));
                            ?>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    <div class="clearfix"></div>
<?php echo $content_bottom; ?>