$(window).ready(function() {

	$('body').on('click', '.reply-btn', function(e) {

		var btn = $(this);
		var loader = $('<i class="fa fa-spinner fa-spin"></i>').hide();
		var repliesContainer = btn.parent().next();

		if (!btn.hasClass('replies-loaded')) {	
			$.ajax({
				url: btn.data('href'),
				type: 'GET',
				dataType: 'html',
				beforeSend: function() {
					loader.appendTo(btn).fadeIn('fast');
				},
				success: function(data) {
					repliesContainer.append(data);
				}
			}).done(function() {
				btn.addClass('replies-loaded');
				loader.fadeOut('fast');
			});
		}

	});

	$('body').on('click', '.like-btn', function(e) {

		var btn = $(this);

		$.ajax({
			url: btn.data('href'),
			type: 'POST',
			success: function(data) {
				btn.find('.number').text(data);
			}
		});

	});


	$('.comments-pagination').each(function() {

		var totalPages = $(this).data('total');
		var href = $(this).data('href');
		var container = $(this).prev('ul.comments-list');

		$(this).twbsPagination({
			prev: '<',
			next: '>',
			totalPages: totalPages,
			onPageClick: function (event, page) {
				$.ajax({
					url: href + '&page=' + page,
					type: 'GET',
					cache: true,
					beforeSend: function() {
						container.addClass('loading');
					},
					success: function(response) {
						container.html(response);
					}
				}).done(function() {
					container.removeClass('loading');
				});
			}
		});
	});
	
});