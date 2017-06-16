var Movie = (function() {

	var defaultOptions = {
		'paginate'	: null,
		'qtip'		: null,
		'typeahead'	: null
	}
	// Initialize qtip2 on specified element
	// Default = '.movie-img'
	var _initQtips = function() {

		$(document).on('mouseover', defaultOptions.qtip, function(event) {

			var url = cURL.base + "movie/get_info/" + $(this).data("tooltips");	

			$(this).qtip({
				overwrite: false,
				content: {
					text: function(event, api) {
						$.ajax({
		                    url: url // Use href attribute as URL
		                })
						.then(function(content) {
		                    // Set the tooltip content upon successful retrieval
		                    api.set('content.text', content);
		                }, function(xhr, status, error) {
		                    // Upon failure... set the tooltip content to error
		                    api.set('content.text', status + ': ' + error);
		                });

		                return 'Loading...'; // Set some initial text
		            }
		        },
		        position: {
		        	my: 'center left',
		        	at: 'center right',
		        	effect: false,
		        	viewport: $('body'),
		        	adjust: {
		        		method: 'flip'
		        	}
		        },
		        style: 'qtip-bootstrap',
		        hide: {
		        	fixed: true
		        },
		        show: {
		            event: event.type, // Use the same show event as the one that triggered the event handler
		            ready: true // Show the tooltip as soon as it's bound, vital so it shows up the first time you hover!
		        }
		    }, event);

});
}
	// Boostrap typeahead initialize
	// Defaut = '#search input'
	var _initTypeahead = function() {

		$(defaultOptions.typeahead).typeahead({
			highlight: true, 
			minLength: 2,
			source: function (query, process) {
			    //reset these containers
			    var movies = [];
			    //$.get(cURL.base + 'movie/search', { term: query }, function (res) {
			    $.get(cURL.base + 'movie/search', { term: query }, function (res) {
			    	$.each(res.data, function(key, value) {
			    		movies.push(value.slug+"#"+value.english_title+"#"+value.vietnamese_title+"#"+value.thumb);
			    	});
			        //return the display array
			        process(movies);
			    }, 'json');
			},
			highlighter: function(item) {
				item = item.split("#");
				var template = '' + 
				'<div class="media typeahead-wrapper">' + 
				'<div class="media-body">' +
				'<img src="'+item[3]+'" class="pull-left" style="width:95px;height:auto;margin-right:10px;">'+
				'<h4 class="media-heading">'+ item[1] +'</h4>' +
				'<p>'+ item[2] +'</p>' +
				'</div>' +
				'</div>';
				return template;
			},
			updater: function (item) {
				item = item.split("#");
				window.location.href = cURL.base + 'movie/' + item[0];
			}
		});

		$(document).on('typeahead:opened', function(event, datum) {
			var width = $(event.target).width();
			$('ul.typeahead.dropdown-menu').css('width', width + 'px');
		});

	};

	// Initialize pagination on specified element
	// Defaut ele = '.movies-pagination'
	// Default container = 'ele' prev
	var _initPagination = function() {

		$(defaultOptions.paginate).each(function() {

			var totalPages = $(this).data('total') + 1;
			var href = $(this).data('href');
			var container = $(this).prev();
			var loader = $('<div class="loader-container"></div>');

			$(this).twbsPagination({
				first: false,
				last: 'All',
				prev: '<',
				next: '>',
				totalPages: totalPages,
				visiblePages: 5,
				onPageClick: function (event, page) {
					//url = cURL.base +  href + '&page=' + page;
					url = href + '&page=' + page;
					if (page === totalPages) {
						return window.location.href = _updateQuery('perPage', null, url);
					}
					loader.fadeIn('fast');
					container.addClass('loading').append(loader);
					$.ajax({
						url: url,
						type: 'GET',
						cache: true,
						success: function(response) {
							container.removeClass('loading');
							loader.fadeOut('fast');
							container.html(response);
						}
					});
				}
			});
		});
	};

	var _initAjaxFiltering = function() {

		$(window).on('hashchange', function() {
			if (window.location.hash) {
				var params = jQuery.deparam(window.location.hash.replace('#', ''));
				return console.log(params);
			}
		});
	};

	var _updateQuery = function(key, value, uri) {

		var ret = true;

		if (typeof uri == 'undefined') {
			uri = window.location.href;
			ret = false;
		}

		var params = jQuery.deparam(jQuery.param.querystring(uri));
		if (!value) {
			delete params[key];
		} else {
			params[key] = value;
		}
		delete params['page'];

		if (ret) {
			return jQuery.param.querystring(uri, params, 2);
		}

		window.location.href = jQuery.param.querystring(uri, params, 2);
		return;
	};

	// Add/remove movie to/from wishlist
	var _addToWishlist = function(e, ele) {

		$("body").on(e, ele, function(e) {
			var slug = $(this).data('wishlist') + '/wishlist';
			var btn = $(this).find('i');
			var checked = btn.hasClass('fa-check-square');

			if (checked) {
				btn.removeClass('fa-check-square');
			} else {
				btn.removeClass('fa-square-o');
			}

			// Toggle loader
			btn.addClass('fa-spinner fa-spin');

			$.ajax({
				url: slug,
				type: "POST",
				success: function(data) {
					if (data['errors']) {
						var delay = 2000;
						for (var i = data['msg'].length - 1; i >= 0; i--) {
							alertify.log(data['msg'][i], "error", delay);
							delay = delay + 1000;
						};
					} else {
						btn.removeClass('fa-spin fa-spinner');
						if (checked) {
							btn.addClass('fa-square-o');
						} else {
							btn.addClass('fa-check-square');
						}
					}
				}
			});
		});

	};

	// Favourtie current movie when click on specified element
	var _favouriteMovie = function() {

		var slug = $(this).data('favourite') + '/favourite';
		var btn = $(this).find('i');
		var checked = btn.hasClass('text-danger');

		if (checked) {
			btn.removeClass('fa-check-square');
		} else {
			btn.removeClass('fa-square-o');
		}

		// Toggle loader
		btn.addClass('fa-spinner fa-spin');

		$.ajax({
			url: slug,
			type: "POST",
			success: function(data) {
				if (data['errors']) {
					var delay = 2000;
					for (var i = data['msg'].length - 1; i >= 0; i--) {
						alertify.log(data['msg'][i], "error", delay);
						delay = delay + 1000;
					};
				} else {
					btn.removeClass('fa-spin fa-spinner');
					if (checked) {
						alertify.log("Đã xóa khỏi yêu thích", "error", 2000);
						btn.removeClass('text-danger');
					} else {
						alertify.log("Đã thêm vào yêu thích", "error", 2000);
						btn.addClass('text-danger');
					}
				}
			}
		});
	};

	return {
		// Merge default options
		setOptions: function(options) {
			$.extend(defaultOptions, options);
		},
		initCarousel: function(ele) {
			// Initialize carousel on specified element
			$(ele).carousel();
		},
		updateQueryString: _updateQuery,
		initAjaxFiltering: _initAjaxFiltering,
		initPagination: _initPagination,
		initTypeahead: _initTypeahead,
		initQtips: _initQtips
	}

})();