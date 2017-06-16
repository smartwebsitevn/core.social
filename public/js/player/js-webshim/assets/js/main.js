jQuery(document).ready(function($) {

	var $notification = $('.nav-notification');

	if ($notification.length) {

		$('body').css('padding-top', 50 + $notification.height() + 'px');

		$notification.find('button').click(function()
		{
			$('body').css('padding-top', '50px');
		});
	}

	var $form_modal = $('.cd-user-modal'),
		$form_login = $form_modal.find('#cd-login'),
		$form_signup = $form_modal.find('#cd-signup'),
		$form_change_password = $form_modal.find('#cd-change-pwd'),
		$form_forgot_password = $form_modal.find('#cd-reset-password'),
		$form_modal_tab = $('.cd-switcher'),
		$tab_login = $form_modal_tab.children('li').eq(0).children('a'),
		$tab_signup = $form_modal_tab.children('li').eq(1).children('a'),
		$forgot_password_link = $form_login.find('.cd-form-bottom-message a'),
		$back_to_login_link = $form_forgot_password.find('.cd-form-bottom-message a'),
		$main_nav = $('.auth-btn-group');

	//open modal
	$main_nav.on('click', function(event) {

		event.preventDefault();

		if( $(event.target).is($main_nav) ) {
			// on mobile open the submenu
			$(this).children('ul').toggleClass('is-visible');
		} else {
			// on mobile close submenu
			$main_nav.children('ul').removeClass('is-visible');
			//show modal layer
			$form_modal.addClass('is-visible');	
			//show the selected form
			if ($(event.target).is('.cd-signup')) signup_selected();
			else if ($(event.target).is('.change-pwd')) change_password_selected();
			else login_selected();
		}

	});

	//close modal
	$('.cd-user-modal').on('click', function(event) {
		if( $(event.target).is($form_modal) || $(event.target).is('.cd-close-form') ) {
			$form_modal.removeClass('is-visible');
		}	
	});
	//close modal when clicking the esc keyboard button
	$(document).keyup(function(event) {
    	if(event.which=='27') {
    		$form_modal.removeClass('is-visible');
	    }
    });

	//switch from a tab to another
	$form_modal_tab.on('click', function(event) {
		event.preventDefault();
		( $(event.target).is( $tab_login ) ) ? login_selected() : signup_selected();
	});

	//hide or show password
	$('.hide-password').on('click', function() {
		var $this= $(this),
			$password_field = $this.prev('input');
		
		( 'password' == $password_field.attr('type') ) ? $password_field.attr('type', 'text') : $password_field.attr('type', 'password');
		( 'Hide' == $this.text() ) ? $this.text('Show') : $this.text('Hide');
		//focus and move cursor to the end of input field
		$password_field.putCursorAtEnd();
	});

	//show forgot-password form 
	$forgot_password_link.on('click', function(event) {
		event.preventDefault();
		forgot_password_selected();
	});

	//back to login from the forgot-password form
	$back_to_login_link.on('click', function(event) {
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

	function change_password_selected() {
		$form_login.removeClass('is-selected');
		$form_signup.removeClass('is-selected');
		$form_change_password.addClass('is-selected');

		var alert = function(msg, status) {
			return '' +
			'<div class="alert alert-'+status +'">'+
				'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
				msg +
			'</div>';
		}

		$form_change_password.find('form').submit(function(e) {
			
			e.preventDefault();

			var data = $(this).serializeArray();

			console.log(data);

			var xhr = $.ajax({
				url: $(this).attr('action'),
				type: 'POST',
				data: data,
				beforeSend: function() {

				},
				success: function(res) {

					if (res.errors)
					{
						var messages = '';
						$.each(res.msg, function(key, value)
						{
							messages += value + ' ';
						});

						$(alert(messages, 'danger')).hide().prependTo($form_change_password).slideDown().delay(3000).slideUp();
						return false;
					}

					$(alert('Đổi mật khẩu thành công!', 'success')).hide().prependTo($form_change_password).slideDown().delay(3000).slideUp();
				}
			}).done(function() {
				$form_change_password.find('input').val('');
			});

			return false;
		})
	}

});


//credits http://css-tricks.com/snippets/jquery/move-cursor-to-end-of-textarea-or-input/
jQuery.fn.putCursorAtEnd = function() {
	return this.each(function() {
    	// If this function exists...
    	if (this.setSelectionRange) {
      		// ... then use it (Doesn't work in IE)
      		// Double the length because Opera is inconsistent about whether a carriage return is one character or two. Sigh.
      		var len = $(this).val().length * 2;
      		this.setSelectionRange(len, len);
    	} else {
    		// ... otherwise replace the contents with itself
    		// (Doesn't work in Google Chrome)
      		$(this).val($(this).val());
    	}
	});
};

jQuery(document).ready(function($) {
	// browser window scroll (in pixels) after which the "back to top" link is shown
	var offset = 300,
		//browser window scroll (in pixels) after which the "back to top" link opacity is reduced
		offset_opacity = 1200,
		//duration of the top scrolling animation (in ms)
		scroll_top_duration = 700,
		//grab the "back to top" link
		$back_to_top = $('.cd-top');
		$navbar = $("nav.navbar-fixed-top");

	//hide or show the "back to top" link
	$(window).scroll(function() {
		if ($(this).scrollTop() > offset) {
			$back_to_top.addClass('cd-is-visible');
		} else {
			$back_to_top.removeClass('cd-is-visible cd-fade-out');

		}
		if( $(this).scrollTop() > offset_opacity ) { 
			$back_to_top.addClass('cd-fade-out');
		}
	});

	//smooth scroll to top
	$back_to_top.on('click', function(event) {
		event.preventDefault();
		$('body,html').animate({
			scrollTop: 0 ,
		 	}, scroll_top_duration
		);
	});

	// Enable numberify on class number
	$(".number").number(true);
});

function dismissCookie(cookie)
{

}

function updateQueryString(key, value, uri) {
	var params = jQuery.deparam(jQuery.param.querystring(uri));
	if (!value) {
		delete params[key];
	} else {
		params[key] = value;
	}
	delete params['page'];
	return jQuery.param.querystring(uri, params, 2);
};