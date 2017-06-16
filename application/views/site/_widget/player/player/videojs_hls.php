
<?php $myplayer='myplayer_'.$_id ?>
<video id='<?php echo $myplayer ?>' <?php echo isset($height)?' style="height:'.$height.'px !important"':'' ?> class="myplayer video-js vjs-default-skin" controls>
    <?php foreach ($subs as $key => $sub): ?>
        <track kind="captions" src="<?php echo $sub; ?>" srclang="<?php echo $key ?>"
               label="<?php echo lang('sub_' . $key) ?>" <?php echo ($key == 'vi') ? 'default' : ''; ?> >
    <?php endforeach; ?>
</video>
<?php ?>
<script type="text/javascript" src="<?php echo public_url('js'); ?>/player/videojs_hls/videojs.js"></script>
<script type="text/javascript" src="<?php echo public_url('js'); ?>/player/videojs_hls/videojs-hls.js"></script>
<script type="text/javascript" src="<?php echo public_url('js'); ?>/player/videojs_hls/videojs-viewport.js"></script>
<link href="<?php echo public_url('js'); ?>/player/videojs_hls/videojs.css" rel="stylesheet">

<script type="text/javascript">

    var <?php echo $myplayer?> = videojs('<?php echo $myplayer?>');

    $(document).ready(function () {
        /**
         * Play movie
         */
        mfcs['<?php echo $_id?>'].callback_player_play = function (episode) {
            //nfc.pr(episode);
            var link = '';
            var sub = '';
            // Neu khong ton tai tap
            /*if ( episode == undefined)
             {*/
            link = '<?php echo $link ?>';
            sub = '<?php echo  json_encode($subs) ?>';
            /*}
             else{
             link 	= $(episode).attr('_link');
             sub 	= $(episode).attr('_sub');

             }*/

            //link= link.buildHashPro();

            <?php echo $myplayer?>.ready(function () {
                //  var myPlayer = this;
                <?php echo $myplayer?>.src({type: "application/x-mpegURL", src: link});
                <?php echo $myplayer?>.play();

                if (mfcs['<?php echo $_id?>'].play_mode == 2)//che do interpret
                    $(".vjs-captions-button").hide();
                //=============================
                <?php echo $myplayer?>.on('ended', function () {
                    mfc.next_movie();
                });
                <?php echo $myplayer?>.on('error', function () {
                    mfc.report_error_auto();
                });
                // Disable browser context menu on video
                <?php echo $myplayer?>.on('contextmenu', function (e) {
                    e.preventDefault();
                });
            });
        }

        mfcs['<?php echo $_id?>'].callback_player_get_current_time = function () {
            return <?php echo $myplayer?>.currentTime();
        }
        mfcs['<?php echo $_id?>'].callback_player_set_current_time = function (time) {
            return <?php echo $myplayer?>.currentTime(time);
        }
        /**
         *Tat quang cao va chay phim
         */
        mfcs['<?php echo $_id?>'].callback_player_pause = function (time) {
            //jwplayer().setControls(false);
            <?php echo $myplayer?>.pause();

            // var player_pause=true;
        }
        mfcs['<?php echo $_id?>'].callback_player_continue = function (time) {
            // jwplayer().setControls(true);
            <?php echo $myplayer?>.play();
        }
    });


</script>
