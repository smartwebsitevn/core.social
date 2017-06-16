var mpfc = {
    /*===============================================
     * Cac ham Callback API
     * ===============================================*/
    callback_player_play:null,
    callback_player_pause:null,
    callback_player_continue:null,
    callback_player_set_current_time:null,
    callback_player_get_current_time:null,

    player_play: function (episode) {
        if (typeof mpfc.callback_player_play == "function") {
            return mpfc.callback_player_play.call(this, episode);
        }
    },

    player_pause: function () {
        if (typeof mpfc.callback_player_pause == "function") {
            return mpfc.callback_player_pause.call(this);
        }
    },
    player_continue: function () {
        if (typeof mpfc.callback_player_continue == "function") {
            return mpfc.callback_player_continue.call(this);
        }
    },
    player_set_current_time: function (time) {
        if (typeof mpfc.callback_player_set_current_time == "function") {
            return mpfc.callback_player_set_current_time.call(this, time);
        }
    },
    player_get_current_time: function () {
        if (typeof mpfc.callback_player_get_current_time == "function") {
           return mpfc.callback_player_get_current_time.call(this);
        }
    },


    /*===============================================
     * Cac ham play va dieu huong
     * ===============================================*/
    /**
     * Kiem tra xem phim co the xem
     */
    play: function () {
        mpfc.player_play();
    },

    /*===============================================
    * Cac ham ho tro
    * ===============================================*/

    init: function () {
            mpfc.play();
    },
}
