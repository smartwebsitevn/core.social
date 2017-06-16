<?php
/*if (is_array($link)) {
    $i = 0;
    foreach ($link as $h => $url) {
        $res_default = $h;
        $source .= '<source src="' . ($url) . '" type="video/mp4" label="' . $h . '" ></source>';
    }
} else {
    $source .= '<source src="' . ($link) . '" type="video/mp4"></source>';
}*/
if (!$is_mobile)
    $height = "500";
else
    $height = "300";
?>

<?php $myplayer='myplayer_'.$_id ?>
<div id='<?php echo $myplayer?>' class="myplayer">Loading the player...</div>
<?php /*?>
	         <video id="myplayer" height="500px" controls autoplay="" preload="none">
                 <?php echo $source ?>
                 <?php if($play_mode != 2): ?>
		             <?php  foreach ($subs as $key => $sub):?>
	                   <track <?php echo ($key == 'vi') ? 'default=""' : '';?> type="text/vtt" label="<?php echo lang('movie_sub_'.$key)?>" srclang="<?php echo $key?>" src="<?php echo $sub;?>" id="<?php echo $key;?>" ></track>
	                 <?php endforeach;?>
                 <?php endif; ?>
	          </video>
<?php  */ ?>

<!-- Javascript -->
<script type="text/javascript">
    var height = '<?php echo $height; ?>';
</script>
<script type="text/javascript" src="<?php echo public_url('js'); ?>/player/jwplayer_6/jwplayer.js"></script>

<script type="text/javascript">
    var _0x21a6 = ["\x6B\x65\x79", "\x44\x48\x73\x41\x7A\x65\x55\x59\x72\x69\x68\x4E\x34\x78\x4D\x75\x33\x53\x61\x7A\x43\x74\x46\x56\x75\x49\x6F\x4E\x71\x53\x6A\x56\x43\x66\x43\x79\x61\x54\x6C\x6E\x66\x47\x30\x3D"];
    jwplayer[_0x21a6[0]] = _0x21a6[1];
</script>
<script type="text/javascript">
    var <?php echo $myplayer?> = jwplayer("<?php echo $myplayer?>");
    $(document).ready(function () {
        /**
         * Play movie
         */
        mfcs['<?php echo $_id?>'].callback_player_play =  function(episode) {
            var link ='';var sub ='';
            // Neu khong ton tai tap

            link = '<?php echo $link ?>';
            //sub = '<?php echo  json_encode($subs) ?>';

            // link= link.buildHashPro();
            //alert(link);
            // Tao config cua jwplayer
            var config = {
                'abouttext': site_name,
                'aboutlink': site_url,
                /*logo: {
                 file: '/assets/blenderLogo.png',
                 link: 'http://www.blender.org/foundation/'
                 },*/

                'flashplayer': public_url + 'js/player/jwplayer_6/jwplayer.flash.swf',
                'skin': public_url + 'js/player/jwplayer_6/skins/stormtrooper.xml', //beelden|bekle|five|glow|roundster|six|stormtrooper|vapor

                'width': '100%',
                'height': height,
                'autostart': 'true',
                'stretching': 'uniform',
                // 1 file
                'file': link,
                <?php /*?>
                // nhieu file (ho tro nhieu do phan giai)
                sources: [
                         { file: 'http://151.80.99.22:1935/hdfilme/_definst_/mp4:sample.mp4/playlist.m3u8', label: "720 Hd",  "default":true },
                         { file: 'http://151.80.99.22:1935/hdfilme/_definst_/mp4:kino/2015/American_Sniper_2014_German-muxed.mp4/playlist.m3u8', label: "360 Hd"},

                        ],
                <?php */?>
                // subs
                tracks: [
                    <?php  foreach ($subs as $key => $sub):?>
                    {
                        file: "<?php echo $sub;?>",
                        label: "<?php echo lang('sub_'.$key)?>",
                        kind: "captions",
                        "default": <?php echo ($key == 'vi') ? 'true' : 'false';?>
                    },
                    <?php endforeach;?>
                ],
                'modes': [
                    {type: 'flash', src: public_url + 'js/player/jwplayer_6/jwplayer.flash.swf'},
                    {
                        type: 'html5', config: {
                        'file': link,
                        provider: 'video'
                    }
                    }
                ],
                'plugins': {
                    // Time slider
                    '<?php echo public_url('js'); ?>/player/jwplayer_6/plugins/timeslider/timeslidertooltipplugin-3.js': {
                        'preview': {
                            'enabled': false
                        }
                    },
                },
                'events': {
                    onComplete: function (event) {
                        mfcs['<?php echo $_id?>'].next_movie();
                    },
                    onError: function (event) {
                        mfcs['<?php echo $_id?>'].report_error_auto();
                    }
                }
            }

            // Xu ly voi tung loai provider
            var provider = link.split('://').shift().toLowerCase();
            switch (provider) {
                case 'rtmp':
                {
                    config['provider'] = 'rtmp';
                    config['rtmp'] = {bufferlength: 9};
                    break;
                }
                case 'rtmps':
                {
                    config['provider'] = 'rtmps';
                    config['rtmp'] = {bufferlength: 9};
                    break;
                }
                default:
                {
                    config['provider'] = 'http';
                    break;
                }
            }
            // Khoi tao jwplayer
            <?php echo $myplayer?>.setup(config);
            //=============================
            /*  save_movie();
             if(mfcs['<?php echo $_id?>'].is_first_run)
             continue_movie()*/
        }

        mfcs['<?php echo $_id?>'].callback_player_get_current_time = function () {
            return <?php echo $myplayer?>.getPosition();
        }
        mfcs['<?php echo $_id?>'].callback_player_set_current_time =  function(time) {
            return   <?php echo $myplayer?>.seek(time);
        }

        /**
         *Tat quang cao va chay phim
         */
        mfcs['<?php echo $_id?>'].callback_player_pause =  function() {
            // <?php echo $myplayer?>.setControls(false);
            <?php echo $myplayer?>.stop();

            // var player_pause=true;
        }
        mfcs['<?php echo $_id?>'].callback_player_continue =  function() {
            // <?php echo $myplayer?>.setControls(true);
            <?php echo $myplayer?>.play();
        }

    });



</script>
