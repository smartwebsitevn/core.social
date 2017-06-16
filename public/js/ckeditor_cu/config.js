/*
 Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
 For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.plugins.addExternal('onchange');

CKEDITOR.editorConfig = function(config)
{
	config.extraPlugins = 'onchange';
	config.resize_dir = 'vertical';
	config.enterMode = CKEDITOR.ENTER_BR;

	var pathCKFinder= admin_url + 'media.html';
	config.extraPlugins = 'onchange';
	config.resize_dir = 'vertical';
	config.filebrowserBrowseUrl      = pathCKFinder;//+ 'ckfinder.html',
	config.filebrowserImageBrowseUrl = pathCKFinder;//+ 'ckfinder.html?type=Images',
	config.filebrowserFlashBrowseUrl = pathCKFinder;//+ 'ckfinder.html?type=Flash',
	//config.filebrowserUploadUrl =      pathCKFinder+'upload';// 'core/connector/php/connector.php?command=QuickUpload&type=Files',
	//config.filebrowserImageUploadUrl = pathCKFinder+'upload';// 'core/connector/php/connector.php?command=QuickUpload&type=Images',
	//config.filebrowserFlashUploadUrl = pathCKFinder+'upload';// 'core/connector/php/connector.php?command=QuickUpload&type=Flash'



	config.toolbar = 'Custom';

	config.toolbar_Full = [
		['Source','-','Save','NewPage','Preview','-','Templates'],
		['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
		['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
		//'/',
		['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink','Anchor'],
		['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		//'/',
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Maximize', 'ShowBlocks','-','About']
	];

	config.toolbar_Custom = [
		["Image",'Flash',"-","Bold","Italic","Underline","Strike","-","NumberedList","BulletedList","-","Outdent","Indent","Blockquote","-","Link","Unlink","-","Table","SpecialChar","-","Cut","Copy","Paste","-","Undo","Redo","-"],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Source'],	['Maximize']
	];

	config.toolbar_Custom_Short = [
		["Image","-","Bold","Italic","Underline","Strike","-","NumberedList","BulletedList","-","Outdent","Indent","Blockquote","-","Link","Unlink","-","Table","SpecialChar","-","Cut","Copy","Paste","-","Undo","Redo"]
	];

};