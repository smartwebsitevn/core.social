//####################################################
// jQuery Handle
//####################################################
(function($)
{

	$(document).ready(function()
	{
		// Select2
		$('.select2').select2();

		// Lightbox
		$('.lightbox').nstUI('lightbox');
		$('.load_ajax').nstUI('loadAjax');
		// Form handle
		$('#form, .form_action').each(function()
		{
			var $this = $(this);
			$this.nstUI('formAction', {
				field_load: $this.attr('_field_load'),
				event_error: function(data)
				{
					// Reset captcha
					if (data['security_code'])
					{
						var captcha = $this.find('img[_captcha]').attr('id');
						if (captcha)
						{
							change_captcha(captcha);
						}
					}
				},
			});
		});
		
		// Form update
		$('.form_update').each(function()
		{
			var $this = $(this);
			
			$this.nstUI('formAction', {
				field_load: $this.data('field_load'),
				event_complete: function(data){},
			});
			
			$this.find('[data-autosave=on]').change(function(data)
			{
				$this.submit();
			});
		});

		// Verify action
		$('.verify_action').nstUI('verifyAction');
		$('.need_processing').nstUI('needProcessing');
		// Response action
		$('.response_action').nstUI('responseAction');
		// Toggle action
		$('.toggle_action').nstUI('toggleAction');
		// toggleStatus || select || status
		$('.toggle_status').nstUI('toggleContent');

		// Tooltip
		$('[_tooltip]').nstUI('tooltip');
		// Drop Down
		$('[_dropdownchild]').nstUI({
			method: 'dropdownHasChild'
		});
		// Placeholder
		$('input.placeholder').nstUI('placeholder');
		
		// Accordion
		$('.accordion').nstUI('accordion');


		// Number format
		$('.format_number').autoNumeric('init', {vMin:'0.00000000', vMax:'9999999999999999.99', aPad:false});
	
		// Auto check pages
		$('.auto_check_pages').each(function()
		{
			auto_check_pages($(this));
		});
		
		// Tabs
		// tam tat $('.tabs_content').contentTabs();
		
		// Ckeditor
		call_editor($(document));
		
		// Autocomplete
		call_autocomplete($(document));
		
		// Tags
		$('.tags').each(function()
		{
			var $this = $(this);
			var tagget = $this.attr('_gettag');
			var setting = {
				'width': '100%',
				'defaultText': $this.attr('_text'),
			};

			var ac_url = $this.attr('_url');
			if (ac_url)
			{
				setting.autocomplete_url = ac_url
			}

			$this.tagsInput(setting);
		});


		//====== List handle

		$('table th input:checkbox').on('click' , function(){
			var that = this;
			$(this).closest('table').find('tr > td:first-child input:checkbox')
				.each(function(){
					this.checked = that.checked;
					$(this).closest('tr').toggleClass('selected');
				});

		});



		// Sort col table
		$('table td.sortCol').each(function()
		{
			var $this = $(this);
			var html = '<div>'+$this.html()+'<span></span></div>';
			$this.html(html);
		});





		var $list_filter	= $('.list_filter');
		var $list_item 		= $('.list_item');
		var $list_action 	= $('.list_action');
		
		$list_item.find('.view_of_field').click(function()
		{
			var param = $(this).attr('_param');
			var value = $(this).attr('_value');
			
			$list_filter.find('[name='+param+']').val(value);
			$list_filter.submit();
			
			return false;
		});
		
		$list_action.find('#submit').click(function()
		{
			var action = $list_action.find('[name=action]').val();
			if (!action) return false;
			
			var ids = new Array();
			$list_item.find('[name="id[]"]:checked').each(function()
			{
				ids.push($(this).val());
			});
			
			ids = $.unique(ids);
			if (!ids.length) return false;
			
			$(this).nstUI('loadAjax', {
				url: action,
				data: {'id': ids},
				event_complete: function()
				{
					window.location.reload();
				},
				event_error: function()
				{
					window.location.reload();
				},
			});
			
			return false;
		});
		
		
		// Load uri
		var uri = window.location.href.split('#uri=');
		if (uri[1]) {
			$.colorbox({
				href: admin_url + uri[1],
				opacity: 0.75
			});
		}

		$('.load_uri').click(function () {
			var uri = $(this).attr('_url');
			uri = (!uri) ? $(this).attr('href') : uri;
			uri = uri.replace(admin_url, '');

			window.location.href = '#uri=' + uri;
		});

		/**
		 * Test connect
		 */
		$('.act_test_connect').click(function()
		{
			$(this).nstUI('loadAjax', {
				url: $(this).attr('href'),
				datatype: 'json',
				event_complete: function(result)
				{
					$alert = {
						title: result.status ? 'Success' : 'Error',
						class_name: result.status ? 'bg-success' : 'bg-danger',
					};

					if ( ! result.status)
					{
						$alert.text = result.errors.join('<br>');
					}

					jQuery.gritter.add($alert);
				},
			});

			return false;
		});





		nfc.boot();

	});
})(jQuery);


//####################################################
// Main function
//####################################################
/**
 * Load ajax
 */
function load_ajax($this)
{
	var field = jQuery($this).attr('_field');
	var url = jQuery($this).attr('_url');
	
	jQuery($this).nstUI('loadAjax', {
		url: url,
		field: {load: field+'_load', show: field+'_show'},
	});
	
	return false;
}

/**
 * Gan gia tri cua cac bien vao html
 */
function temp_set_value(html, params)
{
	jQuery.each(params, function(param, value)
	{
		var regex = new RegExp('{'+param+'}', "igm");
		html = html.replace(regex, value);
	});

	return html;
}

/**
 * Copy gia tri giua 2 field
 */
function copy_value(from, to)
{
	jQuery(this).nstUI('copyValue', {
		from: from,
		to: to,
	});
}

/**
 * Hien thi lightbox
 */
function lightbox(t)
{
	jQuery(t).nstUI('lightbox');
}

/**
 * An pages khi ko co chia trang
 */
function auto_check_pages(t)
{
	if (t.find('a')[0] == undefined)
	{
		t.remove();
	}
}

/**
 * Thay doi captcha
 */
function change_captcha(field)
{
	var t = jQuery('#'+field);
	var url = t.attr('_captcha')+'?id='+Math.random();
	t.attr('src', url);
	
	return false;
}

/**
 * Xu ly acc list
 */
function handle_acc_list(main, list_cur)
{
	acc_list(list_cur);
	
	main.find('a.acc_list').click(function()
	{
		var list = jQuery(this).attr('_list');
		acc_list(list);
		
		return false;
	});
	
	function acc_list(list)
	{
		if (list == '')
		{
			return;
		}
		
		main.find('a.acc_list').each(function()
		{
			if (jQuery(this).attr('_list') == list)
			{
				jQuery(this).parent().toggleClass('taskActive');
			}
			else
			{
				jQuery(this).parent().removeClass('taskActive');
			}
		});
		
		main.find('tr[_list='+list+']').toggle('fade', 150);
		main.find('tr[_list][_list!='+list+']').hide();
	}
}

/**
 * Xu ly acc list
 */
function handle_sort_list(main, url_update)
{
	// Sap xep list item
	main.find('table.tc-table').sortable(
	{
		placeholder: 'ui-state-highlight',
		items: 'tbody tr[_list]',
		handle: '.js-sortable-handle',
		forcePlaceholderSize: true,
		helper: function(e, ui) 
		{
			ui.children().each(function()
			{
				$(this).width($(this).width());
			});
			return ui;
		},
		start: function(event, ui) 
		{
			ui.placeholder.html('<td colspan="' + $(this).find('tbody tr:first td').size() + '">&nbsp;</td>');
		},
		stop: function(event, ui)
		{
			var list = ui.item.attr('_list');
			handle_sort_list_update_order(list);
		}
	});
	
	function handle_sort_list_update_order(list)
	{
		var items = new Array();
		main.find('tr[_list='+list+']').each(function()
		{
			var item = $(this).attr('_item');
			items.push(item);
		});
		
		$(this).nstUI('loadAjax', {
			url: url_update,
			data: {items: items.join()},
			field: {load: '_', show: ''},
		});
	}
}

/**
 * Goi editor
 */
function call_editor_($main)
{
	$main.find('.editor').each(function()
	{
		var $this = jQuery(this);
		var id = $this.attr('id');
		
		var config = $this.attr('_config');
		config = (config) ? JSON.parse(config) : {};

		if ( typeof(CKEDITOR.instances[id]) != 'undefined' )
			CKEDITOR.instances[id].destroy(true);

		CKEDITOR.replace(id, config).on("change", function()
		{
			CKEDITOR.instances[id].updateElement();
		});

		CKEDITOR.instances[id].setData(CKEDITOR.instances[id].getData());


	});
}

function call_editor($main) {
	$main.find('.editor').each(function () {
		var $this = jQuery(this);
		var id = $this.attr('id');

		var config = $this.attr('_config');
		config = (config) ? JSON.parse(config) : {};

		if (typeof(CKEDITOR.instances[id]) != 'undefined')
			CKEDITOR.instances[id].destroy(true);

		/*config.extraPlugins = 'html5audio';*/
		// config.extraPlugins = 'mathjax';
		// config.mathJaxLib = 'http://cdn.mathjax.org/mathjax/2.6-latest/MathJax.js?config=TeX-AMS_HTML';
		/* CKEDITOR.replace(id, config).on("change", function () {
		 CKEDITOR.instances[id].updateElement();
		 });
		 CKEDITOR.instances[id].setData(CKEDITOR.instances[id].getData());*/
		// for ( instance in CKEDITOR.instances ) CKEDITOR.instances[instance].updateElement();

		CKEDITOR.replace(id, config);
		CKEDITOR.instances[id].on('change', function() { CKEDITOR.instances[id].updateElement() });

		if (CKEDITOR.env.ie && CKEDITOR.env.version == 8) {
			document.getElementById('ie8-warning').className = 'tip alert';
		}
	});
}

/**
 * Goi autocomplete
 */
function call_autocomplete($main)
{
	var cache = {}, lastXhr;
	$main.find('.autocomplete').each(function()
	{
		var $this = jQuery(this);
		var url_search = $this.attr('_url');
		
		$this.autocomplete(
		{
			minLength: 2,
			source: function(request, response)
			{
				var term = request.term;
				
				if (term in cache)
				{
					response(cache[term]);
					return;
				}
	
				lastXhr = jQuery.getJSON(url_search, request, function(data, status, xhr)
				{
					cache[term] = data;
					if (xhr === lastXhr)
					{
						response(data);
					}
				});
			}
		});
	});
}
/**
 * Goi Crop Anh
 */
function call_image_croper($this) {
	var title='Croper';var w=1024;	var h=600;
	var url = jQuery($this).attr('href');
	var left = (screen.width/2)-(w/2);
	var top = (screen.height/2)-(h/2);
	return window.open(url, title, 'toolbar=yes, location=yes, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);
} 