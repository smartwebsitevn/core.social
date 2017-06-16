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
/* do not show minimal timeline on mouseout */
.youtubeplayer .fp-ratio {
   padding:0 !important
}
-->
</style>
<?php
//pr($link_current->link_main->link);
/*$parse_url = parse_url ($movie_cur->url_decode );
$array = explode ( "&", $parse_url ['query'] );
$param = explode ( "=", $array [0] );*/

//$link ="https://www.youtube.com/embed/".$link_current->link_main->link."?autoplay=0&controls=1&autohide=1&showinfo=0&fs=1&modestbranding=1&iv_load_policy=3&rel=0&version=2&hd=0&fs=0&enablejsapi=1&playerapiid=ytplayer";

?>
<div class="myplayer">
    <script type="text/javascript" src="<?php echo public_url('js'); ?>/player/youtube/swfobject.js"></script>
    <script type="text/javascript">
       // $(document).ready(function () {
            var params = {allowScriptAccess: "always"};
            var atts = {id: "myytplayer"};
            swfobject.embedSWF("http://www.youtube.com/v/<?php echo $link_current->link_main->link ?>?enablejsapi=1&playerapiid=ytplayer&version=3",
                "ytapiplayer", "100%", "<?php echo $height?>", "8", null, null, params, atts);
            function onYouTubePlayerReady(playerId) {
               var ytplayer = document.getElementById("myytplayer");
               // ytplayer.playVideo();

            }
            /*function onytplayerStateChange(newState) {
               // alert("Player's new state: " + newState);
            }*/

       // })
    </script>
    <div id="ytapiplayer"  width="100%" <?php echo isset($height)?' style="height:'.$height.'px !important"':'' ?>>
        You need Flash player 8+ and JavaScript enabled to view this video.
    </div>
 <!-- <iframe width="100%" height="<?php /*echo $height*/?>" frameborder="0" allowfullscreen="" src="<?php /*echo $link*/?>" ></iframe>
    -->
</div>
