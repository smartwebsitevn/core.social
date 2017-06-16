/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

//CKEDITOR.plugins.addExternal( 'bootstrapTabs', '/ckeditorPlugins/bootstrapTabs/', 'plugin.js' );
//CKEDITOR.plugins.addExternal( 'html5audio');
CKEDITOR.editorConfig = function( config ) {
	//config.extraPlugins = 'widget,lineutils,bootstrapTab,bootstrapTabs';
	//config.extraPlugins = 'html5audio';
	//==
	config.enterMode = CKEDITOR.ENTER_BR;
	var pathCKFinder= admin_url + 'media.html';
	config.resize_dir = 'vertical';
	config.filebrowserBrowseUrl      = pathCKFinder;//+ 'ckfinder.html',
	config.filebrowserImageBrowseUrl = pathCKFinder;//+ 'ckfinder.html?type=Images',
	config.filebrowserFlashBrowseUrl = pathCKFinder;//+ 'ckfinder.html?type=Flash',
	//config.filebrowserUploadUrl =      pathCKFinder+'upload';// 'core/connector/php/connector.php?command=QuickUpload&type=Files',
	//config.filebrowserImageUploadUrl = pathCKFinder+'upload';// 'core/connector/php/connector.php?command=QuickUpload&type=Images',
	//config.filebrowserFlashUploadUrl = pathCKFinder+'upload';// 'core/connector/php/connector.php?command=QuickUpload&type=Flash'



	// %REMOVE_START%
	// The configuration options below are needed when running CKEditor from source files.
	config.plugins = 'dialogui,dialog,about,a11yhelp,dialogadvtab,basicstyles,bidi,blockquote,clipboard,button,panelbutton,panel,floatpanel,colorbutton,colordialog,templates,menu,contextmenu,copyformatting,div,resize,toolbar,elementspath,enterkey,entities,popup,filebrowser,find,fakeobjects,flash,floatingspace,listblock,richcombo,font,forms,format,horizontalrule,htmlwriter,iframe,wysiwygarea,image,indent,indentblock,indentlist,smiley,justify,menubutton,language,link,list,liststyle,magicline,maximize,newpage,pagebreak,pastetext,pastefromword,preview,print,removeformat,save,selectall,showblocks,showborders,sourcearea,specialchar,scayt,stylescombo,tab,table,tabletools,undo,wsc,lineutils,widgetselection,widget,mathjax,oembed,mathedit,html5audio';
	config.skin = 'moono-lisa';
	// %REMOVE_END%

	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	// Define the toolbar buttons you want to have available
	config.toolbar = 'Custom';
	//config.toolbar = 'Custom';



	config.toolbar_Custom = [

		["Image",'Flash',/*"Mathjax",*/'Html5audio','BootstrapTabs', 'BootstrapTabs',"-","Bold","Italic","Underline","Strike","-","NumberedList","BulletedList","-","Outdent","Indent","Blockquote","-","Link","Unlink","-","Table","SpecialChar","-","Cut","Copy","Paste","-","Undo","Redo","-"],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor'],
		['Source'],	['Maximize']
	];

	config.toolbar_Custom_Short = [
		["Image","-","Bold","Italic","Underline","Strike","-","NumberedList","BulletedList","-","Outdent","Indent","Blockquote","-","Link","Unlink","-","Table","SpecialChar","-","Cut","Copy","Paste","-","Undo","Redo"]
	];
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
	config.toolbar_Site =
		[
			['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Scayt'],
			['Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat']
		];
};



