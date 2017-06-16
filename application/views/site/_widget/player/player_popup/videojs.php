<?php
//pr($link);
//pr($movie_cur,false);
//pr($server_cur);
//pr($subs);
//echo '<br>'.$link;
/*if ($server_cur->key == "picasa") {
    $link = picasa_direct_2($movie_cur->url_decode);
    // mahoa link
    $link = movie_encode_link($link);
}*/
if (!$is_mobile)
    $height = "500";
else
    $height = "300";
?>
<style>
    <!--
    #myplayer_popup {
        z-index: 100;
        width: 100%;
        height: <?php echo $height?>px !important;
    }
    }
    -->
</style>

<?php
$source = '';
$res_default = '';
/*$link ='http://picasaweb.google.com/107381407253905053463/DungNhanTroChoiMaQuai?authkey=Gv1sRgCPWZvt73l8u8jwE#6220341408683541154';
$link = 'https://www.youtube.com/embed/8rODHN_IR1c';
$link = 'https://lh3.googleusercontent.com/Oe_wnPdnFcIPDZjB_yTFiyL0PeHZkvpHSrniULi2dlOPNkyqWI34O8UVht6QuV2weCy1BiYWOg=m18';
$link = 'http://r2---sn-8pxuuxa-i5oe7.googlevideo.com/videoplayback?ip=116.107.197.123&pl=20&source=youtube&dur=3142.472&mv=m&lmt=1429800184787073&ms=au&mm=31&mn=sn-8pxuuxa-i5oe7&id=o-AEDVQCZ6kDx0M8T_4b3yWKELZxzwSYucL7L9uAE6XueH&key=yt6&signature=BC115701814A891B96C63D00EFD25C55BEA988E3.2825F19A1765C846C3F2FCB41E203F0B428B4EF7&upn=2dxhsA7uBic&sparams=dur,id,initcwndbps,ip,ipbits,itag,lmt,mime,mm,mn,ms,mv,pl,ratebypass,source,upn,expire&expire=1466608636&mime=video/mp4&sver=3&itag=18&ipbits=0&fexp=9416126,9416891,9419451,9422596,9428398,9431012,9433096,9433380,9433386,9433653,9433946,9435526,9435580,9435876,9436084,9436618,9437066,9437553,9437815,9438764,9439652&mt=1466586696&ratebypass=yes&initcwndbps=1225000&signature=';*/
//pr($link);
if (is_array($link)) {
    foreach ($link as  $it) {
        $source .= '<source src="' . $it->file . '" type="video/mp4" label="' . $it->label . '" ></source>';
    }
} else {
    $source .= '<source src="' . $link . '" type="video/mp4"></source>';
}
?>

<video id='myplayer_popup' class='video-js vjs-default-skin' width='100%' height='<?php echo $height ?>' controls
       poster='<?php echo $movie->banner->url ?>'
    <?php if ($server_cur->key == "youtube") : ?>
        data-setup='{"customControlsOnMobile": true,"language":"vi" }'
        <?php else: ?>
        data-setup='{"customControlsOnMobile": true,"language":"vi"}'

    <?php endif; ?>
    >
    <?php echo $source ?>

    <?php if ($play_mode != 2): ?>
        <?php foreach ($subs as $key => $sub): ?>
            <track kind="captions" src="<?php echo $sub; ?>" srclang="<?php echo $key ?>"
                   label="<?php echo lang('sub_' . $key) ?>" <?php echo ($key == 'vi') ? 'default' : ''; ?> >
        <?php endforeach; ?>
    <?php endif; ?>
    <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a
            href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
</video>


<!-- Javascript -->
<script type="text/javascript">
    var site_name = '<?php echo $site_name; ?>';
</script>

<link href="<?php echo public_url('js'); ?>/player/videojs/video-js.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo public_url('js'); ?>/player/videojs/video.js"></script>
<script type="text/javascript" src="<?php echo public_url('js'); ?>/player/videojs/lang/vi.js"></script>
<script type="text/javascript">
    videojs.options.flash.swf = "<?php echo public_url('js'); ?>/videojs/video-js.swf"
    var myPlayerPopup = videojs('myplayer_popup', {
    });

    $(document).ready(function () {
        mpfc.callback_player_play = function (episode) {
            var have_error = false;
            var link = '';
            var sub = '';

            <?php if(is_array($link)): ?>
            link = <?php echo json_encode($link) ?>;
            <?php else: ?>
            link = '<?php echo $link ?>';
            <?php endif;?>
            sub = '<?php echo  json_encode($subs) ?>';
            //alert(typeof link)
            var sources = [];
            if (typeof link == 'string') {
                //link = link.buildHashPro();
                sources.push({type: "video/mp4", src: link});
            }
            else {
                $.each(link, function (index, value) {
                    // value = value.buildHashPro();
                    sources.push({type: "video/mp4", src: value, res: index});
                });
            }

            // nfc.pr(sources);
            myPlayerPopup.ready(function () {
                myPlayerPopup.play();
                // Disable browser context menu on video (khong cho phai chuot vao player)
                myPlayerPopup.on('contextmenu', function (e) {
                    e.preventDefault();
                });
            });// end ready
        }
    });
</script>

