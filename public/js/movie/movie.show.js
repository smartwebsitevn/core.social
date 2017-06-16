$(document).ready(function() {
	/*document.onmousedown=disableclick;
	function disableclick(event)
	{
		if(event.button==2)
		{
			alert("Right Click Disabled");
			return false;
		}
	}*/
	/*
	$('.rateit').rateit({step: 1,max:5});
	$(".rateit").bind('rated', function (event, value) {
		$.ajax({
			async: false,
			type: "POST",
			url: $(this).attr('data-url'),
			data: {'score':value},
			success: function(data)
			{
				nfc.server_response(data);
			}
		});
		$('[name=rate]').val(value);
	});*/

	//== Su ly hien thi trong xem  phim
	//============ Xu ly cac thuoc tinh phu
	$('.remove-ad span.wrap').click(function () {
		$('a.ads_item').fadeOut();
		$('.remove-ad span.wrap').html('T?t qu?ng c�o');
		$('.remove-ad span.wrap').addClass('disabled');

	});
	$('.toggle-autonext span.wrap').click(function () {
		var _selected = $(this).attr('_selected');
		if (_selected == '') {
			$('.toggle-autonext span.wrap').html('AutoNext: Off');
			$('.toggle-autonext span.wrap').attr("_v", 'off');
			$('.toggle-autonext span.wrap').attr('_selected', 'selected');
			return false;
		}
		else {
			$('.toggle-autonext span.wrap').html('AutoNext: On');
			$('.toggle-autonext span.wrap').attr('_selected', '');
			$('.toggle-autonext span.wrap').attr("_v", 'on');
			return false;
		}

	});
	// Hieu ung tat den
	$('.toggle-light').click(function () {
		var text =$(this).find('span');
		var off = $(this).hasClass('light-off');
		if (off == '') {
			text.text($(this).data('title-on'))
			$(this).addClass('light-off');
			$(this).css({
				position: 'relative',
				zIndex: 15
			});
			$("#movie-player-wraper").css({
				position: 'relative',
				zIndex: 15
			});
			$("#light-overlay").show(100);
			return false;
		}
		else {
			text.text($(this).data('title-off'))
			$(this).removeClass('light-off');
			$("#light-overlay").hide(100);
			return false;
		}

	});


	//== hien thi thong tin them cua phim
	//- dang popover

	/*$(".box-movie").popover({
		title: function () {
			var pop_dest = $(this).data("info");
			return $("#"+pop_dest + " .popover-title").html();

		},
		content: function () {
			var pop_dest = $(this).data("info");
			return $("#"+pop_dest + " .popover-content").html();

		},
		placement: 'left auto ',
		trigger: 'hover',
		container: 'body',
		html: true
	});*/
	//- dang tooltip
	/*dw_Tooltip.defaultProps = {
		//content_source: 'ajax'
		content_source: 'class_id',
		//sticky: true,
	}*/

	/*dw_Tooltip.content_vars = {
	 btntip: {
	 url: 'content.text',
	 w: 310
	 }
	 }*/
	//	dw_Tooltip.writeStyleRule();

	//- dang html inline
	$(document).on('click', '.product-film .product-item .action-des', function(){
		var $this =$(this);
		$( ".product-film .product-item .product-item-info" ).removeClass('active');
		$this.parent().toggleClass('active');
		//== dung ajax load
		$this.append('<span class="loader_item"></span>');
		var url = $this.data('url');
		$.post( url, function( data ) {
			$this.find('span').remove()
			if(data){
				$('#movie-data-info').hide();

				//var wraper =$('#movie-data-info');
				var wraper =$this.closest('.block-film');
				var wraper_movie =$(wraper).find('#movie-data-info');
				if ($(wraper_movie).length ==0){
					$(wraper).append('<div id="movie-data-info"></div>');
					 wraper_movie =$(wraper).find('#movie-data-info');

				}
				$(wraper_movie).html(data)
				$(wraper_movie).show();
				//$(wraper).show();
				var go_to = $(wraper_movie).offset().top - 50;
				$('html, body').animate({scrollTop: go_to}, 500);
			}
		});
        //== ko dung ajax load
		/*var info = $(this).data("info");
		$('#movie-data-info').html($(info).html())
		$('#movie-data-info').show();
		var go_to = $('#movie-data-info').offset().top - 50;
		$('html, body').animate({scrollTop: go_to}, 500);*/

	}) ;
	//== Hien Modal Play Trailer
	$(document).on('click', '#video-popup', function(){
		var $this =$(this);
		nfc.loader('show');
		var url = $this.attr('href');
		//url = nfc.addParameterToURL(url, 'act=get_player_popup')
		//alert(url);return false;
		$.ajax({
			url: url,
			dataType: "html",
			type: "get",
			success: function (data) {
				if (data) {
					var $modal = $('#modal-player' );
					//- gan noi dung
					$modal.find('#modal-player-wraper').html(data);
					//- hien thong bao
					$modal.modal('show')
				}
				nfc.loader('hide');
			},
			error: function () {
			}
		});
		return false;

	}) ;

	// tat popup Play video
	$('#modal-player').on('hidden.bs.modal', function (e) {
		$('#modal-player').find('#modal-player-wraper').html('');
	})
	//== Filter Ajax theo url
	$(document).on('click', '.movie_auto_filter_url .act-filter', function(){
		// alert(1)
		var $this = $(this);
		var $parent = $this.closest(".movie_auto_filter_url");
		//var  $content_list =$($parent).find($($parent).data('content'));
		var $target = $($($parent).data('target'));
		var $target_title = $($($parent).data('target-title'));
		if ($target_title.length)
			$target_title.text($this.text());
		//alert($this.data('value'))
		//== dung ajax load
		$target.append('<span class="loader_block"></span>');
		var url = $this.data('url');
		$.ajax({
			url: url,
			dataType: "json",
			type: "get",
			success: function (rs) {
				// alert(2)
				$target.find('span').remove()
				if (rs.status) {
					$target.html(rs.content);
					var go_to = $target.offset().top - 50;
					$('html, body').animate({scrollTop: go_to}, 500);
				}
				else {
				}
			},
			error: function () {
			}
		});
	});

	//== Auto search
	var cache = {}, lastXhr;
	$('.movie_auto_search').each(function()
	{
		var url = $(this).attr('_url');
		var $this =this;


		$(this).autocomplete(// ham nay co trong jquery UI
			{

				minLength: 1,
				source: function(request, response)
				{
					var term = request.term;

					if (term in cache)
					{
						response(cache[term]);
						return;
					}
					url_search  = url;
					var t = $($this).closest('form').find("[name='t']");
					if($(t).length >0)
						url_search = url +'?t='+$(t).val();
					lastXhr = $.getJSON(url_search, request, function(data, status, xhr)
					{
						cache[term] = data;
						if (xhr === lastXhr)
						{
							response(data);
						}
					});
				},
				open: function(event, ui) {
					$(this).autocomplete("widget").css({
						"width": 320,
						"margin-top": 20,
						"z-index": 9999
					});
				},



			})

			.autocomplete( "instance" )._renderItem = function( ul, item ) {

			return $( "<li class='ui-menu-item'>" )
				.append( "<a href='"+item.link+"'><img src='"+item.image+"' />" + item.label + "</a>" )
				.appendTo( ul );
		};

	});


});