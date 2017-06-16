<?php
//pr($link);
//pr($movie_cur,false);
//pr($server_cur);
//pr($subs);
//echo '<br>'.$link;

if(!$is_mobile)
    $height ="500";
else
    $height ="300";
?>
<style>
    <!--
    #myplayer {
        width: 100%;
        height: <?php echo $height?>px;
        z-index:100;

    }
    /* do not show minimal timeline on mouseout */
    .youtubeplayer .fp-ratio {
        padding:0 !important
    }
    -->
</style>

<div id="myplayer">
    <?php /* ?>
    <iframe id="video" width="100%" height="<?php echo $height?>" frameborder="0" allowfullscreen="" src="https://www.youtube.com/embed/<?php echo $link_current->link_main->link ?>?&autohide=1&fs=1&modestbranding=1&iv_load_policy=3&rel=0&showinfo=0&version=2&hd=0&fs=0&enablejsapi=1&playerapiid=ytplayer&autoplay=1&loop=1" ></iframe>
    <?php */ ?>
    <iframe id="video" width="100%" height="<?php echo $height?>" src="//www.youtube.com/embed/<?php echo $link_current->link  ?>?rel=0" frameborder="0" allowfullscreen></iframe>
</div>



<script type="text/javascript">

    var youtube_src = "//www.youtube.com/embed/<?php echo $link_current->link ?>?rel=0";

    $(document).ready(function () {
        /**
         * Play movie
         */
        mpfc.callback_player_play =   function () {
            //$("#video")[0].src += "&autoplay=1";
            $("#video")[0].src";
        }

    });

</script>