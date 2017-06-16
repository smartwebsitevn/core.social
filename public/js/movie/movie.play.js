var mfcs=[];

var mfc = {
    //======= Cac bien dc set tu ngoai
    notices: [],
    //
    player: '',
    movie_id: "",
    movie_sub_number:0,
    episode_current: null,
    play_mode: 0,// che do xem link hay interpret
    //
    url_current:'',
    url_save: '',
    url_order_movie: '',
    url_demo: '',
    url_report: '',

    //
    auto_play: null,
    has_demo: null,
    has_interpret: null,
    is_login: null,
    is_login_to_watch: null,
    is_can_watch: null,
    is_can_watch_full: null,
    // kiem tra xem che do co phai play nhieu link
    _is_link_multi: null,
    // luu thoi gian lan chay truoc
    time_saved: null,
    // Names
    name_light_overlay: "#light-overlay",

    //======= Cac bien dc set trong lib
    //is_first_play: true,
    auto_next: true,
    //is_pause: false,
    //have_error: false,// co loi khi load video
    reported: false,
    //======= Chay quang cao
    ads_player: null,
    ads_clocker: null,
    ads_status: null,
    ads_url: null,// link video quang cao
    ads_popup_url: null, // link popup khi click vao video
    ads_time_total: null, // tong thoi gian quang cao
    ads_time_skip: null, // thoi gian toi tieu cho phep bo qua quang cao


    /*===============================================
     * Cac ham Callback API
     * ===============================================*/
    callback_player_play:null,
    callback_player_pause:null,
    callback_player_continue:null,
    callback_player_set_current_time:null,
    callback_player_get_current_time:null,
    player_play: function (episode) {
        if (typeof this.callback_player_play == "function") {
            return this.callback_player_play.call(this, episode);
        }
    },

    player_pause: function () {
        if (typeof this.callback_player_pause == "function") {
            return this.callback_player_pause.call(this);
        }
    },
    player_continue: function () {
        if (typeof this.callback_player_continue == "function") {
            return this.callback_player_continue.call(this);
        }
    },
    player_set_current_time: function (time) {
        if (typeof this.callback_player_set_current_time == "function") {
            return this.callback_player_set_current_time.call(this, time);
        }
    },
    player_get_current_time: function () {
        if (typeof this.callback_player_get_current_time == "function") {
           return this.callback_player_get_current_time.call(this);
        }
    },


    /*===============================================
     * Cac ham play va dieu huong
     * ===============================================*/
    init: function ($key) {
        this.player =   $($key);
        if(this.auto_play){
            this.play();
        }

    },

    /**
     * Kiem tra xem phim co the xem
     */
    play: function () {

        this.goto_player()
        this.player_play();
        if (this.time_saved >0){
            this.continue_movie();
        }
        this.save_movie();
        /*
         // chay quang cao
         setTimeout(function () {
         this.ads_run()
         }, 1 * 1000);*/
    },
    play_main: function () {
        /*if (!this.is_can_watch_full) {
            this.notify();
            return false;
        }*/
       // alert(this.player.attr("id"))
   //     nfc.pr(mfcs)
       // return false;
        this.load_player(nfc.addParameterToURL(this.url_current, 'auto_play=1'))
        return false;
    },


    /**
     * Luu lai thoi diem dang play
     */
    save_movie: function () {
        var $this =this
        // if(!this.is_login) return;
        setInterval(function () {
            var curr = $this.player_get_current_time();
            //alert(curr)
            $.ajax({
                type: "POST",
                url: $this.url_save,
                data: "time=" + curr,
            });//ajax

            // console.log(' - curr=' +curr);
        }, 60 * 1000)// 1 phut gui 1 lan

    },
    /**
     * Chay tiep thoi diem da luu
     */
    continue_movie: function () {
        var $this =this
        setTimeout(function () {
            if ($this.time_saved > 0) {
                $this.player_set_current_time($this.time_saved)
            }
        }, 1 * 1000);
    },


    /*===============================================
    * Cac ham ho tro
    * ===============================================*/
    load_player: function (url) {
      //  location.href = url;     return;
       // url = nfc.addParameterToURL(url, 'act=get_player')
       // $('#movie-player-wraper').append('<span class="loader_block"></span>');
        // an cac thong bao neu co
       // this.message_hide();
        // tam dung player neu dang cahy
        this.player_pause();
        // chuyen len player
        this.goto_player(0)
        this.play();
       // $('#movie-player-wraper').find('span.loader_block').remove()

    },
    /**
     * Di chuyen toi player
     */
    goto_player: function (show_player) {
        if(typeof show_player == 'undefined' || show_player){
           // alert(1)
            this.player.find('.cover-wraper').hide();
            this.player.find('.player-wraper').show();
        }
        else{
            this.player.find('.cover-wraper').show();
            this.player.find('.player-wraper').hide();
        }

        if(  this.player.length){
             var go_to =this.player.offset().top - 50;
            $('html, body').animate({scrollTop: go_to}, 500);
        }


    },
    /*===============================================
     * Cac ham su ly chay quang cao
     * ===============================================*/
    ads_run: function () {
        if(!this.ads_status)
            return;
        this.player_pause();

        $('#player_ads_wraper').show();
        $('#myplayer').hide();
        var src ="https://www.youtube.com/embed/"+this.ads_url+"?autoplay=1&controls=0&autohide=1&showinfo=0&fs=1&modestbranding=1&iv_load_policy=3&rel=0&version=2&hd=0&fs=0&enablejsapi=1&playerapiid=ytplayer";
        $(this.ads_player).prop('src',src )
        // this.ads_player.playVideo();
        // $('#player_ads_timer_wraper').show();
        var d=0;
        this.ads_clocker=setInterval( function (){
            this.ads_time_total--;
            d++;
            $('#player_ads_timer').html(this.ads_time_total);
            if(d >=this.ads_time_skip)
                $('#player_ads_close').show();

            if ( this.ads_time_total <= 0 ) {
                this.ads_close();
            }

        },1000);


    },
    ads_close: function () {
        $('#player_ads_wraper').remove();
        $('#myplayer').show();
        window.clearInterval(this.ads_clocker);
        this.player_continue();

    },
    ads_remove: function () {
        $('#player_ads_wraper').remove();
    },
    ads_open_popup: function () {
        if(this.ads_popup_url !="")
            window.open(this.ads_popup_url,'MyWindow')
    },


    /**
     * Thong bao trong player
     */
    message: function (content) {
        /*var $modal = $('#modal-system-notify' );
         $modal.find('.modal-body').html($('#movie-notify').html());
         $modal.modal('show');*/
        if ($('#player-message').length){
            $('#player-message').show();
            $('#player-message .player-message-body').html(content);
           // var go_to = $('#player-message').offset().top - 100;
           // $('html, body').animate({scrollTop: go_to}, 100);

        }

    },
    message_hide: function (content) {
        $('#player-message').hide();
    },
    /**
     * Thong bao
     */
    notify: function () {
        /*var $modal = $('#modal-system-notify' );
        $modal.find('.modal-body').html($('#movie-notify').html());
        $modal.modal('show');*/
        if ($('#movie-notify').length){
           // alert(1);
            var go_to = $('#movie-notify').offset().top - 100;
            $('html, body').animate({scrollTop: go_to}, 100);
        }

    },

}
