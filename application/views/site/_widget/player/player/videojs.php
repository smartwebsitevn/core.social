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
<?php $myplayer='myplayer_'.$_id ?>
<video id='<?php echo $myplayer?>' <?php echo isset($height)?' style="height:'.$height.'px !important"':'' ?> class='myplayer video-js vjs-default-skin' controls
       poster='<?php echo $image_url ?>'
    <?php if ($server_cur->key == "youtube") : ?>
        data-setup='{"customControlsOnMobile": true,"language":"vi" }'
        <?php else: ?>
        data-setup='{"customControlsOnMobile": true,"language":"vi"}'

    <?php endif; ?>
    >
    <?php echo $source ?>

        <?php foreach ($subs as $key => $sub): ?>
            <track kind="captions" src="<?php echo $sub; ?>" srclang="<?php echo $key ?>"
                   label="<?php echo lang('sub_' . $key) ?>" <?php echo ($key == 'vi') ? 'default' : ''; ?> >
        <?php endforeach; ?>
    <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a
            href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
</video>

<link href="<?php echo public_url('js'); ?>/player/videojs/video-js.css" rel="stylesheet">
<!--<link href="<?php /*echo public_url('js'); */ ?>/player/videojs/video-quality-selector.css" rel="stylesheet">-->
<link href="<?php echo public_url('js'); ?>/player/videojs/plugins/videojs-resolution-switcher.css" rel="stylesheet">
<!-- Main JS -->
<script type="text/javascript" src="<?php echo public_url('js'); ?>/player/videojs/video.js"></script>
<script type="text/javascript" src="<?php echo public_url('js'); ?>/player/videojs/lang/vi.js"></script>
<!--<script type="text/javascript" src="<?php /*echo public_url('js'); */ ?>/player/videojs/youtube.js"></script>-->
<script type="text/javascript"
        src="<?php echo public_url('js'); ?>/player/videojs/plugins/videojs-resolution-switcher.js"></script>
<!--<script type="text/javascript" src="<?php /*echo public_url('js'); */ ?>/player/videojs/video-quality-selector.js"></script>
-->
<script type="text/javascript">

    videojs.options.flash.swf = "<?php echo public_url('js'); ?>/videojs/video-js.swf"
    var <?php echo $myplayer?> = videojs('<?php echo $myplayer?>', {
        /* plugins: {
         videoJsResolutionSwitcher: {
         default: 'high',
         dynamicLabel: true
         }
         },
         */
    });

    <?php echo $myplayer?>.videoJsResolutionSwitcher()
    $(document).ready(function () {

        /**
         * Play movie
         */
        mfcs['<?php echo $_id?>'].callback_player_play = function (episode) {
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
            <?php echo $myplayer?>.ready(function () {
                //  <?php echo $myplayer?>.src(sources);
                <?php echo $myplayer?>.play();

                //== Events
                if (mfcs['<?php echo $_id?>'].play_mode == 2)//che do interpret
                    $(".vjs-captions-button").hide();

                <?php echo $myplayer?>.on('ended', function () {
                    mfcs['<?php echo $_id?>'].next_movie();
                });
                <?php echo $myplayer?>.on('error', function(e) {
                    //console.log(e);
                    // e.stopImmediatePropagation();
                    var error = this.error();
                    //console.log('error!', error);
                    //alert(error.code +' - '+ error.message + ' - '+error.status);
                    if(error.code == 0 || error.code == 3 || error.code == 43 || error.code == 5){
                        //alert('loi load phim');
                        mfcs['<?php echo $_id?>'].report_error_auto();
                    }

                });
                /*<?php echo $myplayer?>.controlBar.addChild('button', {
                 text: "Press me",
                 });*/

                <?php echo $myplayer?>.on('changeRes', function () {
                    var res = <?php echo $myplayer?>.getCurrentRes()
                    $.each(sources, function (index, value) {
                        //  alert(value.res);
                        if (value.res == res) {
                            //alert(value.src);
                            <?php echo $myplayer?>.src({type: "video/mp4", src: value.src});
                            <?php echo $myplayer?>.play();
                        }
                    });
                    //  alert('Current Res is: ' +  <?php echo $myplayer?>.src());

                });

                // Disable browser context menu on video (khong cho phai chuot vao player)
                <?php echo $myplayer?>.on('contextmenu', function (e) {
                    e.preventDefault();
                });
                <?php echo $myplayer?>.one("loadedmetadata", function () {
                    if (mfcs['<?php echo $_id?>'].movie_sub_number == 2) {
                        var track = this.addTextTrack("captions", "<?php echo lang('sub_vien')?>", "ve");
                        //track.mode = "showing";
                        //nfc.pr(<?php echo $myplayer?>.toJSON())
                        // nfc.pr(<?php echo $myplayer?>.textTracks())

                        //- v 4.x
                        /* var track1 =<?php echo $myplayer?>.textTracks().Va[0].cues.X;
                         var track2 =<?php echo $myplayer?>.textTracks().Va[1].cues.X;

                         //nfc.pr(track1)
                         $.each(track1, function (index, cue) {
                         var cue2=track2[index];
                         track.addCue(new VTTCue(cue.startTime, cue.endTime, cue.text + "\n"+ cue2.text ));//
                         });*/

                        //- v 5.11
                        var track1 = <?php echo $myplayer?>.textTracks().tracks_[0]['cues_'];
                        var track2 = <?php echo $myplayer?>.textTracks().tracks_[1]['cues_'];
                        //nfc.pr(vi_track)
                        $.each(track1, function (index, cue) {
                            var cue2 = track2[index];
                            track.addCue(new VTTCue(cue.startTime, cue.endTime, cue.text + "\n" + cue2.text));//
                        });
                    }

                    /*var trackIndex = <?php echo $myplayer?>.textTracks().length -1;
                     var tt = <?php echo $myplayer?>.textTracks()[trackIndex];
                     // nfc.pr(tt);
                     tt.oncuechange = function() {
                     if(tt.activeCues[0] !== undefined){
                     var dynamicHTML = "id: " + tt.activeCues[0].id + ", ";
                     dynamicHTML += "text: " + tt.activeCues[0].text + ", ";
                     dynamicHTML += "startTime: " + tt.activeCues[0].startTime + ",  ";
                     dynamicHTML += "endTime: " + tt.activeCues[0].endTime;
                     alert('change cap:' + dynamicHTML);
                     }
                     }
                     //end oncuechange*/
                }); //end loadedmetadata


            });// end ready
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
        mfcs['<?php echo $_id?>'].callback_player_pause = function () {
            //jwplayer().setControls(false);
            <?php echo $myplayer?>.pause();

            // var player_pause=true;
        }
        mfcs['<?php echo $_id?>'].callback_player_continue = function () {
            <?php echo $myplayer?>.play();
        }
    });
</script>
