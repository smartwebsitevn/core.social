function createRequestObject() {
    var req = null	
    if(window.XMLHttpRequest) {
        req = new XMLHttpRequest();
    } else if(window.ActiveXObject) {
        req = new ActiveXObject("Microsoft.XMLHTTP");
    }
    return req;
}

function parseSrt(srcUrl, tScale, tOffset, tMargin) {

	var xhr = createRequestObject();
	xhr.open('GET', srcUrl, false);
	xhr.send();

	var srtText = xhr.responseText;
	var tScale = tScale || 1;
	var tOffset = tOffset || 0;

	var lines = srtText.split(/\r?\n/);
	var captions = [];
	var timerex = /^(\d\d|\d):(\d\d):(\d\d),(\d{3}) --\> (\d\d|\d):(\d\d):(\d\d),(\d{3})/;
	var lineslen = lines.length;
	var i = 0;
	var t;
	while (i < lineslen) {
		t = timerex.exec(lines[i]);
		if (t) {
			var tStart = 3600 * t[1] + 60 * t[2] + 1 * t[3] + parseFloat('.' + t[4]);
			var tStop = 3600 * t[5] + 60 * t[6] + 1 * t[7] + parseFloat('.' + t[8]);
			if (i + 1 < lineslen) {

				var j = i + 1;
				var text = "";

				while(!timerex.exec(lines[j]) && lines[j]) {
					text = text + lines[j];
					text = text + '\n';
					j++;
				}

				captions.push({
					'start'	: tStart,
					'stop'	: tStop,
					'id'	: parseInt(lines[i - 1]),
					'text'	: text
				});
			}
		}
		i++;
	}
	return captions;
}

function loadingSubtitle(subContainer) {

	if(!enTrack && !viTrack)
	{
		return;
	}
	enCues = '';
	viCues = '';
	if (enTrack) {
		var enSrc = enTrack.getAttribute('src');
		var enCues = parseSrt(enSrc, 1, 0, 0.1);
	}
	
	if (viTrack) {
		var viSrc = viTrack.getAttribute('src');
		var viCues = parseSrt(viSrc, 1, 0, 0.1);
	}

	var len = Math.max(enCues.length, viCues?viCues.length:0);

	for (var index = 0; index < len; index++) {
		if(viCues[index] != null && enCues[index] != null )
		{
			var container = document.createElement("span");
			container.setAttribute('id', 'cue'+enCues[index].id);
			container.setAttribute('data-start', enCues[index].start);
			
			container.innerHTML = enCues[index].text+(viCues?"<br/><small>"+viCues[index].text+"</small>":"");
			
			subContainer.appendChild(container);
		}
	}
}

var v = document.querySelector('video');
var subContainer = document.getElementById('subtitles-container');
// Parse subtitles
var enTrack = document.getElementById('en');
var viTrack = document.getElementById('vi');
// Loading subtitle
v.textTracks.addEventListener('change', loadingSubtitle(subContainer), false);
