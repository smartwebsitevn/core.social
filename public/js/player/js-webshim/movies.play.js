$(document).ready(function() {
	
	var $player = $('video');
	var $subContainer = $('#subtitles-container');
	var $hideSub = $('#hideSub');
	var $subOverlay = $('#subtitles-overlay');
	var $undo = $('#undo');
	var start;

	$subContainer.css({'height': $player.height()});

	$player.on('loadedmetadata', function() {
		start = new Date();
	});

	$(".speed-controller").each(function() {

		var speedValue = $(this).find(".speed-value");
		var downBtn = $(this).find('button.speed-decrease');
		var upBtn = $(this).find('button.speed-increase');

		downBtn.click(function() {

			var playbackRate = $player.prop('playbackRate');

			var value = parseFloat(playbackRate) - 0.1;
			value = value.toFixed(1);

			if (playbackRate > 0 && value >= 0.25) {

				$player.prop('playbackRate', value);
				speedValue.text(value);

			};
		});

		upBtn.click(function() {

			var playbackRate = $player.prop('playbackRate');

			var value = parseFloat(playbackRate) + 0.1;
			value = value.toFixed(1);

			if (playbackRate <= 2 && value <= 2) {
				$player.prop('playbackRate', value);
				speedValue.text(value);
			};

		});
	});
	
	
	 $(document).keydown(function(evt) {
		    if (evt.keyCode == 16) {
		    	evt.preventDefault();
				if ($player.prop('paused')) {
					$player.play();
				} else {
					$player.pause();
				}
			}
   });
	
	/*
	$(document).on('keydown', null, 'space', function(e) {
		e.preventDefault();
		if ($player.prop('paused')) {
			$player.play();
		} else {
			$player.pause();
		}
		return false;
	});
	

    
	$(document).bind('keydown', 'space', function () {
		    e.preventDefault();
			if ($player.prop('paused')) {
				$player.play();
			} else {
				$player.pause();
			}
			return false;
	 });
	*/
    /*
	$(document).on('keydown', null, 'tab', function(e) {
		e.preventDefault();
		var value = $('#undoValue').val();
		$player.prop('currentTime', $player.prop('currentTime') - value);
		return false;
	});
	*/

	
	$player.on('timeupdate', function() {

		if ($('.playable').length > 0) {

			var end = Date.now() - start;
			
			if (end >= 5 * 60 * 1000) {
				$player.pause();
				$('.playable').removeClass('hide');
			}
		}

		var textTracks = $(this).prop('textTracks');

		$(textTracks).each(function() {
			var mode = $(this).prop('mode');
			if (mode == 'disabled') {
				$(this).prop('mode', 'hidden');
			}
		});

	});

	$player.on('play', function() {
		if ($hideSub.is(':checked')) {
			$subContainer.attr('class', 'hide-sub');
			$subOverlay.attr('class', 'show-overlay');
		} else {
			$subContainer.attr('class', 'show-sub');
			$subOverlay.attr('class', 'hide-overlay');
		}

	});

	$player.on('pause', function() {
		$subContainer.attr('class', 'show-sub');
		$subOverlay.attr('class', 'hide-overlay');
	});

	$hideSub.on('click', function() {

		if ($(this).is(':checked')) {
			$subContainer.attr('class', 'hide-sub');
			$subOverlay.attr('class', 'show-overlay');
		} else {
			$subContainer.attr('class', 'show-sub');
			$subOverlay.attr('class', 'hide-overlay');
		}

	});

	// 
	$undo.on('click', function(e) {

		var value = $('#undoValue').val();
		$player.prop('currentTime', $player.prop('currentTime') - value);

	});

	$subContainer.on('click', '> *', function(e) {
		$player.prop('currentTime', $(this).data('start'));
	});

});
