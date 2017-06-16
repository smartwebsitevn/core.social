webshim.setOptions('mediaelement', {
	replaceUI: 'auto',
	jme: {
		barTemplate: '<div class="play-pause-container">{{play-pause}}</div>' +
		'<div class="playlist-container"><div class="playlist-box">{{playlist-prev}}{{playlist-next}}</div></div>' +
		'<div class="currenttime-container">{{currenttime-display}}</div>' +
		'<div class="progress-container">{{time-slider}}</div>' +
		'<div class="duration-container">{{duration-display}}</div>' +
		'<div class="mute-container">{{mute-unmute}}</div>' +
		'<div class="volume-container">{{volume-slider}}</div><div class="subtitle-container">' +
		'<div class="subtitle-controls">{{captions}}</div></div>' +
		// Enable dual subtitle plugin
		'<div class="dual-subtitle-container">{{dual-subtitle}}</div>'+
		'<div class="fullscreen-container">{{fullscreen}}</div>'
	}
});

webshim.setOptions('track', {
	override: 'auto'
});

webshim.polyfill('mediaelement track');

webshim.ready('jme', function(){
	//register a plugin named 'jump-10'
	$.jme.registerPlugin('dual-subtitle', {
		structure: '<button class="{%class%}" type="button"><i class="fa fa-exchange"></i></button>',
		_create: function($control, $media, $base) {

			$control.on('click', function() {
				var tracks = $media.prop('textTracks');
				if (!$base.hasClass('dual-sub')) {
					for (var i = tracks.length - 1; i >= 0; i--) {
						if (tracks[i].mode != 'showing') {
							tracks[i].mode = 'showing';
						}
					};
					$base.addClass('dual-sub');
				} else {
					for (var i = tracks.length - 1; i >= 0; i--) {
						tracks[i].mode = 'hidden';
					};
					$base.removeClass('dual-sub');
				}
			});
		}
	});
});

var display = $('#subtitles-container');

var highlightSubtitle = function() {

	var track = $(this).prop('track');
	
	track.oncuechange = function() {
		if (track.activeCues[0]) {
			var cueId = track.activeCues[0].id;
			$(display).find('#cue'+cueId).each(function() {
				$(display).find('.active-cue').removeClass('active-cue');
				$(this).addClass('active-cue');
				$(this).prev().nextAll(':lt(2)').scrollIntoView();
			});
		}
	};
};

$('track').each(function() {

	var track = $(this).prop('track');

	if (track.id == 'vi') {
		track.mode = 'showing';
	} else {
		track.mode = 'hidden';
	}

});

// 'load' event is fired when click on 'cc' button
$('track').on('load', highlightSubtitle);