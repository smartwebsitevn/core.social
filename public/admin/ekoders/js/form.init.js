$(document).ready(function() {

	// Date picker
	/*	$('.datepicker').each(function()
	 {
	 var config_default = {
	 defaultDate: +7,
	 autoSize: true,
	 dateFormat: 'dd-mm-yy',
	 changeMonth: true,
	 changeYear: true,
	 yearRange: 'c-40:c+20'
	 };

	 var config_cur = $(this).attr('_config');
	 config_cur = (config_cur) ? JSON.parse(config_cur) : {};

	 var config = $.extend({}, config_default, config_cur);
	 console.log(config);
	 $(this).datepicker(config);
	 });*/
	$('.date_picker').datepicker({
		format: 'dd-mm-yyyy',
		autoclose: 1,
	})/*.on('changeDate', function(ev){
		$(this).datepicker('hide');
	})*/

	//Bootstrap Datetimepicker
	$('.datetime_picker').datetimepicker({
		//language:  'fr',
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		forceParse: 0,
		showMeridian: 1
	});

	/*$('.form_date').datetimepicker({
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 2,
		minView: 2,
		forceParse: 0
	});

	$('.form_time').datetimepicker({
		weekStart: 1,
		todayBtn:  1,
		autoclose: 1,
		todayHighlight: 1,
		startView: 1,
		minView: 0,
		maxView: 1,
		forceParse: 0
	});*/

	// Color picker
	$('.color_picker').each(function()
	{
		var $this = $(this);
		$this.find('div').css('background-color', '#'+$this.find('input').val());

		$this.colorpicker({
			color: '#'+$this.find('input').val(),
			onShow: function(colpkr)
			{
				$(colpkr).fadeIn();
				return false;
			},
			onHide: function(colpkr)
			{
				$(colpkr).fadeOut();
				return false;
			},
			onChange: function(hsb, hex, rgb)
			{
				$this.find('input').val(hex);
				$this.find('div').css('background-color', '#'+hex);
			}
		});
	});



	//Dual listbox examples  for more information and options please visit http://www.virtuosoft.eu/code/bootstrap-duallistbox/
	/*var demo1 = $('select[name="duallistbox_demo1[]"]').bootstrapDualListbox({infoTextFiltered: '<span class="label label-primary label-lg">Filtered</span>'});
	var container1 = demo1.bootstrapDualListbox('getContainer');*/

	//Select2 examples
	$(".select_multi").select2({
		//placeholder: "Select a Option",
		//width: 375,
		//allowClear: true,
		formatResult: function(opt) {
			if (!opt.id) {
				return opt.text;
			}
			var optimage = $(opt.element).data('image');
			if(!optimage){
				return opt.text;
			} else {
				var $opt = $(
					'<span><img src="' + optimage + '"  /> ' + opt.text + '</span>'
				);
				return $opt;
			}},
		formatSelection: function(opt) {
		if (!opt.id) {
			return opt.text;
		}
		var optimage = $(opt.element).data('image');
		if(!optimage){
			return opt.text;
		} else {
			var $opt = $(
				'<span><img src="' + optimage + '"  /> ' + opt.text + '</span>'
			);
			return $opt;
		}}
	});
	//$("#e5").select2({tags:["red", "green", "blue"]});

	// autosize
	$('.autosize').autosize();

	//Maxilength
	//$('input[maxlength]').maxlength();

	/*$('input.maxL-1').maxlength({
		threshold: 17
	});

	$('input.maxL-2').maxlength({
		alwaysShow: true,
		warningClass: "label label-primary",
		limitReachedClass: "label label-danger",
		separator: ' of ',
		preText: 'You have ',
		postText: ' chars remaining.',
		validate: true,
		threshold: 10
	});

	$('textarea#maxL-3').maxlength({
		alwaysShow: true
	});

	$('input#maxL-4').maxlength({
		alwaysShow: true,
		placement: 'top-left'
	});*/

	// Number format
	$('.input_number').autoNumeric('init', {vMin:'0.00000000', vMax:'9999999999999999.99', aPad:false});

	//Masked Input Uses http://digitalbush.com/projects/masked-input-plugin/
	$.mask.definitions['~']='[+-]';
	$('.mask_date').mask('99-99-9999');
	$('.mask_phone').mask('(999) 999-9999');
	$(".mask_key").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("You typed the following: "+this.val());}});
	$('.mask_eyescript').mask('~9.99 ~9.99 999');


	// Spinners
	$('.spinner').spinner({value:0, min: 0, max: 1000000000000});
	//$('.spinner-3').spinner({value:0, min: 0, max: 10});
	//$('.spinner-4').spinner({value:0, step: 5, min: 0, max: 200});

	// Touchspinners
	$(".touchspin").TouchSpin({
		min: 0,
		max: 100,
		step: 1,
		decimals: 0,
		boostat: 5,
		maxboostedstep: 10,
		postfix: '$'
	});

	/*$(".touchspin-2").TouchSpin({
		min: 0,
		max: 100,
		step: 0.1,
		decimals: 2,
		boostat: 5,
		maxboostedstep: 10,
		buttonup_class: 'btn btn-primary',
		buttondown_class: 'btn btn-primary',
		postfix: '%'
	});*/



});
