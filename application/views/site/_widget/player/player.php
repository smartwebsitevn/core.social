<?php if ($player)://$link_current): ?>

    <script type="text/javascript">
        //nen tang js:n foundation core (nen tang cot loi)
        $(document).ready(function () {
            // su ly play phim
            mfcs['<?php echo $_id?>'] = Object.create(mfc);
            mfcs['<?php echo $_id?>'].url_current = "<?php echo current_url() ?>";
            mfcs['<?php echo $_id?>'].url_save = "<?php echo site_url('lesson/save/'.$lesson->id) ?>";
            <?php /*?>
            mfcs['<?php echo $_id?>'].url_report = "<?php echo site_url('movie/report/'.$movie->id) ?>";
            <?php */?>
            <?php if (isset($auto_play) && $auto_play): ?>
            mfcs['<?php echo $_id?>'].auto_play = true;
            <?php endif; ?>
            <?php if (isset($time_current) && $time_current): ?>
            // alert(<?php echo $time_current; ?>);
            mfcs['<?php echo $_id?>'].time_saved = <?php echo $time_current; ?>;
            <?php endif; ?>
            mfcs["<?php echo $_id?>"].init("#<?php echo $_id ?>");

            $('#<?php echo $_id ?>').on('click', '.movie-play', function () {
                mfcs["<?php echo $_id?>"].play_main();
            });
        });
    </script>
    <div id="<?php echo $_id ?>" style="position:relative">
        <div class="cover-wraper" style="position: relative;z-index: 4">
            <a class="movie-play" href="javascript:void(0)">
                <img src="<?php echo $image_url ?>">
            </a>
        </div>
        <div class="player-wraper" style="display:none">
            <?php
            $this->load->view('site/_widget/player/player/' . $player);
            ?>
        </div>
        <?php //pr($servers); ?>
        <?php if(isset($servers) && count($servers)>1): ?>
        <div class="well text-center mt10 mb10">
            <ul class="list-inline f20">
                <?php foreach ($servers as $s) : //pr($s); ?>
                    <li>
                        <a class="label <?php echo ($s->is_current) ? ' label-danger': 'label-default'?>" href="<?php echo $s->url; ?>" >
                            <?php if($s->is_current): ?>
                                <i class="fa fa-refresh  fa-spin fa-fw" aria-hidden="true"></i>
                            <?php endif; ?>
                            <?php echo $s->name; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php endif; ?>

    </div>
<?php endif; ?>
