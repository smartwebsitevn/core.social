<script type="text/javascript">
    jQuery(document).ready(function ($) {
        var $form_combo = $('#<?php echo $_id ?>'),
            $form_login = $form_combo.find('#cd-login'),
            $form_signup = $form_combo.find('#cd-signup'),
            $form_forgot_password = $form_combo.find('#cd-reset-password'),

            $form_combo_tab = $form_combo.find('.cd-switcher'),
            $tab_login = $form_combo_tab.children('li').eq(0).children('a'),
            $tab_signup = $form_combo_tab.children('li').eq(1).children('a'),
            $forgot_password_link = $form_login.find('.cd-form-bottom-message a'),
            $back_to_login_link = $form_forgot_password.find('.cd-form-bottom-message a'),
            $main_nav = $('.auth-btn-group');

        login_selected();

        // hien thi modal o nhung vi tri dac biet
        $('.cd-signup').on('click', function (event) {
            $form_combo.show();
            $form_combo.addClass('is-visible');
            signup_selected();
        });
        $('.cd-signin').on('click', function (event) {
            $form_combo.show();

            $form_combo.addClass('is-visible');
            $form_combo.show();

            login_selected();
        });
        //open modal
        $main_nav.on('click', function (event) {

            event.preventDefault();

            if ($(event.target).is($main_nav)) {
                // on mobile open the submenu
                $(this).children('ul').toggleClass('is-visible');
            } else {
                // on mobile close submenu
                $main_nav.children('ul').removeClass('is-visible');
                //show modal layer
                // $form_combo.addClass('is-visible');
                $form_combo.show();

                //show the selected form
                if ($(event.target).is('.cd-signup'))
                    signup_selected();
                else
                    login_selected();
            }

        });

        //close modal
        $('.cd-user-modal').on('click', function (event) {
            if ($(event.target).is($form_combo) || $(event.target).is('.cd-close-form')) {
                //$form_combo.removeClass('is-visible');
                $form_combo.hide();
            }
        });
        //close modal when clicking the esc keyboard button
        $(document).keyup(function (event) {
            if (event.which == '27') {
                // $form_combo.removeClass('is-visible');
                $form_combo.hide();
            }
        });

        //switch from a tab to another
        $form_combo_tab.on('click', function (event) {
            event.preventDefault();
            ( $(event.target).is($tab_login) ) ? login_selected() : signup_selected();
        });

        //hide or show password
        $('.hide-password').on('click', function () {
            var $this = $(this),
                $password_field = $this.prev('input');

            ( 'password' == $password_field.attr('type') ) ? $password_field.attr('type', 'text') : $password_field.attr('type', 'password');
            ( 'Hide' == $this.text() ) ? $this.text('Show') : $this.text('Hide');
            //focus and move cursor to the end of input field
            $password_field.putCursorAtEnd();
        });

        //show forgot-password form
        $forgot_password_link.on('click', function (event) {
            event.preventDefault();
            forgot_password_selected();
        });

        //back to login from the forgot-password form
        $back_to_login_link.on('click', function (event) {
            event.preventDefault();
            login_selected();
        });

        function login_selected() {
            $form_login.addClass('is-selected');
            $form_signup.removeClass('is-selected');
            $form_forgot_password.removeClass('is-selected');
            $tab_login.addClass('selected');
            $tab_signup.removeClass('selected');
        }

        function signup_selected() {
            $form_login.removeClass('is-selected');
            $form_signup.addClass('is-selected');
            $form_forgot_password.removeClass('is-selected');
            $tab_login.removeClass('selected');
            $tab_signup.addClass('selected');
        }

        function forgot_password_selected() {
            $form_login.removeClass('is-selected');
            $form_signup.removeClass('is-selected');
            $form_forgot_password.addClass('is-selected');
        }


    });


</script>