<style>
    <!--
    /* do not show minimal timeline on mouseout */
    .youtubeplayer .fp-ratio {
        padding:0 !important
    }
    -->
</style>

<div class="myplayer" <?php echo isset($height)?' style="height:'.$height.'px !important"':'' ?>>
    <?php /* ?>
    <iframe id="video" width="100%" height="<?php echo $height?>" frameborder="0" allowfullscreen="" src="https://www.youtube.com/embed/<?php echo $link_current->link ?>?&autohide=1&fs=1&modestbranding=1&iv_load_policy=3&rel=0&showinfo=0&version=2&hd=0&fs=0&enablejsapi=1&playerapiid=ytplayer&autoplay=1&loop=1" ></iframe>
    <?php */ ?>
    <iframe class="video"  <?php echo isset($height)?' style="height:'.$height.'px !important"':'' ?> src="//www.youtube.com/embed/<?php echo $link_current->link ?>?rel=0" frameborder="0" allowfullscreen></iframe>
</div>

<script type="text/javascript">

    var youtube_src_<?php echo $_id?> = "//www.youtube.com/embed/<?php echo $link_current->link ?>?rel=0";

    $(document).ready(function () {
        /**
         * Play movie
         */
        mfcs['<?php echo $_id?>'].callback_player_play =   function () {
            $('#<?php echo $_id ?>').find(".video")[0].src += "&autoplay=1";
        }
        mfcs['<?php echo $_id?>'].callback_player_get_current_time = function () {
            return 0;
        }
        mfcs['<?php echo $_id?>'].callback_player_set_current_time =  function (time) {
            return 0;
        }
        /**
         *Tat quang cao va chay phim
         */
        mfcs['<?php echo $_id?>'].callback_player_pause =  function(time) {
            $('#<?php echo $_id ?>').find(".video")[0].src = youtube_src_<?php echo $_id?>;
        }
        mfcs['<?php echo $_id?>'].callback_player_continue =  function(time) {
            $('#<?php echo $_id ?>').find(".video")[0].src += "&autoplay=1";
        }
    });

</script>