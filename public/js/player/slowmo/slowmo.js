jwplayer().registerPlugin('slowmo', '6.0', function(player, config, slowmo){
	function setup(evt){
		if (player.getRenderingMode() == "html5"){
		var playerWidthPX2 = player.getWidth();
		var playerWidthPX = parseFloat(playerWidthPX2);
		var playerHeightPX2 = player.getHeight();
		var playerHeightPX = parseFloat(playerHeightPX2);
		var c=0;
		var t;
		var timer_is_on=0;
		timedCount = function(){
		document.getElementById(player.id).value=c;
		c=c+1;
		if (config.delay == null){
		if(navigator.userAgent.toLowerCase().indexOf('firefox') > -1){
		t=setTimeout("timedCount()",125);
		} else {
		t=setTimeout("timedCount()",275);
		}
		} else {
		t=setTimeout("timedCount()",config.delay);
		}
		player.play();
		player.setMute(true);
		}	
		stopCount =  function(){
		clearTimeout(t);
		player.play();
		}
		doTimer = function(){
		if (!timer_is_on){
		  timer_is_on=1;
		  timedCount();
		  } else {
		  stopCount();
		  timer_is_on=0;
		  player.setMute(false);
		  }
		}
		player.onComplete(function(evt) {
		clearTimeout(t);
		timer_is_on=0;
		player.setMute(false);
		});
	};
	}
	player.onReady(setup);
	this.resize = function(width, height){
			player.addButton("data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACMAAAAjCAYAAAAe2bNZAAAACXBIWXMAAA7DAAAOwwHHb6hkAAAABGdBTUEAALGOfPtRkwAAACBjSFJNAAB6JQAAgIMAAPn/AACA6QAAdTAAAOpgAAA6mAAAF2+SX8VGAAAO7klEQVR42mL8//8/w2ABAAHExDCIAEAADSrHAASgYVxWEAQCADjrPhSJ0i2CfiWkn4h+tEsf0bWIPeutAiMxqd02L92GGRj1h3VVUSxWHPZHMBapWkI/Zp1KQmsJRER2S9I5wvd8kxfxkzApAt4Lhk6ACJAZKJfgc+T41bOpMGqIz6Ye3Zuoc3h0lFax3W24XxvOzuFOF34CCO6YV69/MBw/egrIAhnxn4FNRkee9bdIuoisurmQhLzJp6/MDF/fPD7w7tah5d/fXdnGwPT7E9BmiGZYsgOlvz8/gfRvJR4R1VAJcX2nP6ws7Kysn3/w8j9+/u7JxY3f3z7ZAFTE8P7dF4Y5MzczREU6Mni7eYK1AwQQ3DH//wFdzAjk/v/LJiirmyFtEl7KK2Ei8/P9LYaPT88x/GcWZlAwDvCT0fbxe3778Ok3N1e0fnt5ZyPE8WADQAQfr7JjpoB+aI64uInMv9/cDLoGbAyvP7xlePacnUHi24uEj/f37np0amXTrw/3jzIwsjHcuPWA4cePD2AjAAKIEZSbJKWNGV48A/qI4Q+vpJH3NAWropjfTNJA7hOGa1vyGL49Wg+U42SQ1MtjEHNuZfgNjDmmFycYrq4rdeFmebSXEeiJz2//Mono+q/V8SkLeP9JioHx+z8GNqaPDPFJggwXr71iOHycg+E/Ez8DNw8DA8uHHW8ubG6J+Pnh2V4GRkYGGSlWBkE+FgaAAGKSUzBkePn8I9CyjwwCSh71Uk5NMZ+5RRjY/gHj/+sfhm/PrzAwApM5I8N3hp8fzjP8Z/zMwPIb6DQJCwYJ65SOP2z/uP78/83AKq3qJ69fHODtIcYQ4/aegYP5M8MvJh6G/afeMty+A4pODgYR/t8MqRG/GHR0nEXkbctmMfCxizIA097T198Yvv38xwAQAI0Acv8D+gAPAPz8+QACCAkWLkNql/z78QzmzJQA+ebGAAIJ/wAF++UA+OznAAADIAAUJUQA+/DbAO7PmwD97t8AJSZFGu7n3uby5+PRJD15rAYSOhTv+fAAEBEIANjgBwD1+QEAKy/8APb1DADp8wQAExj1ABIL9gDOygQABQ4kEfj14Av37Nu4AAII//z+AgACAI0Acv8E+P37AAAG/wAKGiEwBRQdF9OqgAD96a8A7AAFAAYSNwA4S34AGyVnAAEMGgD38+gA1rVfAP4E6gDu2QUAEAcxACYsX2jR/PEMDyFNKPn4+gDb3wUA2OACAAUDBwAEAPIABfr4AAL9BgD/BAYA5O39ALG2/gAB/QcA/wQCACAjYmDu18OC//376fPyAQACUEwGKQjDABBc02CqDWm1VxEvIgiC3/Rx3vxEsESwNU2TBl1/sMzOSGi6kUYIc9hYX2IaPCpCnXhNDE9YtcfqfIJYRrj7g4V3+LJejAmv5NCUx0u7u96k8jBE3ucGjn4xGFQiQbP4WmasFwF55qjZQNVvRBlQ9NtCk1CMH/t34SeAWJj/cjD85RQERoMYNzufPIOosS/DpwuPGXjY/zMIa8QwcHArMnz6DjT7HyMDl7wug4CsK8O3DzcZmGVVGLiUVBh+AYOXjVmAgY/rPYOPlTjD4TMMDO/v/2NgZmNkEBbhYFBSYGPg4+BgeALMUa/eczJwMrMwmFuzMfxg/cdwZdN3Ji4+ES5WYK5jBDoGIIBYWN/eZ/gvKMDA9k3iK+cvoAHG6QwKGl4Mf5/tAZZNfxmYn5xj4Be2YOD495/h9ePHDGJqXgyKEhkMX/n1GD6xyjP8enuC4f+3dwxf3/MyrNjylQGYlBl4WbkZfgNz/LNv/xmWbH3GwPBFnMHYWojh+48/DFdOP2A4spuDgUn4P7BQePf38+eX30BpFhQyAAHEwvjvLxPD2zf/fv9/84vt52+GjzzcDM/ePGF4fGgiw/9PtxnYJfQZjCLmMry7eJThzoEmBgZg9L1UdmFQtrdh4OZkZnj/6gHDy/vnGYQ1+Rh+cANDmfEDME38AqYrNmBocjH8+sQDjDQehp3HPjPoa3xlcLTjYDh9QYjh+XtGhp8fr7B9e/XwKwMo5wIdAxCAojJWQRgGg/BnC1LrKs7i4uA7OPjSrbsPIEoXUQwIao1orC2YYBr/7ndwB8d33TeFKE6p7Wtb6c1lJBD1t65tIZxw2KfCXdeU+xVxkEG2BnPKeLyPAlpNfcix9x2fMiPq1cwmjuWiou8bErmLYCWk+JqQolRMMvBM5z/GwzO6yHJ8ZUQE7Ze/AGICFiD//7GyMPz9/+nuzdON0Yzvrv8TY3MCulEe6DOggxkVGfiZnRiEJA2Bvv7B8BuokUNQh0FOWJbh+ZmJDO/ubQY64jvD7SM1DIzPzzDoSosx/HgLDECgb/8x/mbQ0WBmkOP7zSD8CRhvwFB6+1WQ4dnj7wx3T22+8fza5hgGxn+fgZUd0KLfDAABxMzCKMTwG1SKMXxj+Pv5+YOPX578U1Y1tufj02P6yyHAIGmWwMAgYcfALSPJwMAlyMAp4MGgYZ/P8OP1LYZb+2sYWJnegytI5p8vGL4Ay6O3jM4MD7+KA0t6dqCD3jFEhwkw/Aamt0tP2YDlFjvDq4evGR5d3X7jzsGZRQwMny8DDWBgZP4HxgABBHSMIMMfFlAQgCsHhp9/vh56/fr0XkFBbXUp3XhhThlVtp/MwNTOAHSYhCEDBzsDw+MbSxkenu5jYP3xHKztLxCyA434+vUuw/sXpxmY/75l4AamH0YuAYbX7xgZ3rz+CiwrPjB8fXrq4Zsbq1c8PLM8/d+P12eZeTgZOEDVA8d/BhaguQABxMjBqMTwgx3qGGDQMgvKAMsDYBx+/cnMIcQuI6Rq68L4STL3z++v+l8/XWP48vg6UN0dYDwAE90/UaC+n2BPANsU0ObROyAGlvJcKgx8UmYM7GyCV1j+vd/zh+3ViVd3zmz/zyjwieH3F6D67wysvFwMbBxA/aCCF+gAgACC1NqMUAxubgErQaAoD+env8CS7+Gby0fm/nr7SU5ITVtfUtWAgUkuFFhvAbMw+2uGr8ycwOz5G+iu/wy/mZiA0QJMoMA67Mc/YMHGzM/w//1Dhpf3Dhz+9OFpIYswDzCnAB0LCoI/wNYH4z8GJiYmeUZWbsaff749AJUzAAEErMQVGX4x/4c4BliDM3HwAanfDOz/vzH8+f2HgZNb0cLUu3YDh6yi+HeQz//yM7ADQ+IfMM2B2l7MwKYD098/QDNYGUA+AxfOwBrvL1AtG1A569fnv97c2LX34v4FkUBLPv7nFgJqfM/EI6GcwydlUP713e2FH1+erwKWkgwAAcTyh4UV6AhGSMMI6KJ/33+AA+gHqEj//4OBVUDB/d1/PnG2j58ZBLj5gaUtMASAiQ7UrmL7B4ze/8zAIoIN3rj6y/Qf5AyGfz+BofWDkeH9N1a2/3zSnux8/No/3r09xvD7J4OQtEoRv4Jd929GYIvw7VMmhr/fwS00gABiERTnYHj7mQsYZP8w2qT/gVUFAzun9PMXzxl+vfnJwMvGycDLAWxecAArUWDOYGMHYlaIQ/4AQ+fPn78Mv4EV46/vnxm+f/nC8AmI/wLLDwHOz8BMw6X94//Lq9xC4q5iSib1X/7xAoOVE5iLgFke6EEGIA0QQMCo/Ams87gYvv9khyREFNcAmxCcfCxAXwHDDFhQAtuwnz+9Zfj3+S8wNIAFGjBEYUntP6iJDGwo/f0LKjX/AEMQGNXsQD4LJ9B8YKTxqE+SljLsZpfQ4PvLLsT49wcrAyuwPczEzMYMaTAxMQAEEAuwtQ1uYzz5K4XpGGDyZGTl4mIGGsj0/w8DMyMzAwswShiBhdQ/RiZE+5cRQTMCo+gvOGd9Z/gHTIt/gGmJgZmXQUjdloOXl4/jyy9uYAJnZGAGZn1moIeAZv5h+AHMTcDkAhCAynLJARAGgSjYSRfG+x9VG6vFh7rpigUJeeEzjO7A4dfD1tpsP7d3yycYr6Xk1QDg+UOAGamYtvy+dYZhZS1f4AginXJlbCZUuAPqYjxXJ09tiYZgbOKThUcAgbM2UA8DH+MThm8f/yOFDqil/4eLhcFFg42NF+gAoMF/gdEDdNh/YBnDBM6AjGghw8jAAnQoKLuz/QWVwMCKE+j738CM8PbRFQYePjEGdj5RBk5OoBhQOTsbK8N3FmBdBMpEQAwQQEyQhj0jsGQFmsgK9DnrfwRm+cfFyMqkwMrOD6y7+IHlD7DEBBbbPECfAE0A1klAzIRMg8oZYLsHGKVMwFL9DwtIjB/YngFWCS+v7H5/f28M558rN3gZhRlYmJUY/rGKMPxl/fsPaA8DC7C1CRBATKDewV8gBjqSgYubCRJMLExQ/P/t/z+fNrAAmwOsrKJAzXzAoAVGGbCVz8zOw8DEzoWBmVkFgEWGGLDBJsDwh1sQWG4B6zNw4fhlxa/3t5c+PLko+O+ne+84OIBVDIsoMJq42YGVIwMXMFMBBBDTrVu3QCUhGMuJAC0FFnQMn4CB+BkYMt/////y5PIBjt+PGbjYgW1Zdm6G71zSDF+4ZRh+Ag36xSICLOxEUOjvLBIM31ilgAlXGJijGBn4OYC9hLcX/3x78fjkzzcvGT4+v33t2ulWny+vp+0VZfnEwP5HRIzh91eGj8+uMgAEEDjN3LhxgyEvL4+BBRgar19/YVi9eh/Dz5+/wQn4/fOr6x+eWZQkZ+JpxcElDMx7vKDYBtYt4BqSAV6dQNMOqMRnApbA/5m+AZPrd4ZPj65+vXV4ae2v7++uQspnRoZfX98ev396eSjXN96rbIwff7BwcjOkpKQwAQQQI/KQSFZWFoOrqwvD5MmzGA4cuAIuRyA9Rg4edn5RP2DiM2Rm4eFiZGL+yicjfhtYjf1FLgyAZc//H28/Cv5690HxL+O/P39+ffj85dXz1f//fL0McT1QNScoBzFDHM7AFcLAzPFEQZrhxJ2rRxkAAogRfXxm8eLFDGvWrGa4c+chw9On3xg+fvwF1MUFrmUhDgOGChMrg7SFPQMoI/xD0s8MLI3f3rrK8BlUs4NrKqAeRk6IPmCO4hdgZxCV4gCW1v+ApfMvYCn9nUFMWIBBVoqT8fDh4/8BAohxMA0WAQTQoBqfAQgwAD4ObjSG2ssWAAAAAElFTkSuQmCC", 
            "Slowmo", 
            function() { doTimer(); 
			}, 
            "slowmodock");
	};
}, './slowmo.swf');