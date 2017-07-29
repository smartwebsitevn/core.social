<link href="<?php echo public_url('js'); ?>/jquery/plupload2/jquery.ui.plupload/css/jquery.ui.plupload.css" media="all"
      type="text/css" rel="stylesheet"/>
<link href="<?php echo public_url('js'); ?>/jquery/plupload2/jquery.plupload.queue/css/jquery.plupload.queue.css"
      media="all" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo public_url('js') ?>/jquery/plupload2/plupload.full.min.js"></script>
<script type="text/javascript"
        src="<?php echo public_url('js') ?>/jquery/plupload2/jquery.ui.plupload/jquery.ui.plupload.js"></script>
<script type="text/javascript"
        src="<?php echo public_url('js') ?>/jquery/plupload2/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<script type="text/javascript">
    /*window.onload = function() {
     window.addEventListener("beforeunload", function (e) {
     var confirmationMessage = 'Nếu bạn rời khỏi trang này các thay đổi sẽ không được lưu lại. ';

     (e || window.event).returnValue = confirmationMessage; //Gecko + IE
     return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
     });
     };*/
    function eventChangeTypeCat($params) {
        var $this = $($params.ele)
        var type_cat = $this.data('value')
        if (!type_cat) {
            return;
        }
        $(this).nstUI('loadAjax', {
            url: "<?php echo current_url(); ?>?_act=load_types&type_cat=" + type_cat + "&p=" + 1,
            field: {load: '_'},
            datatype: 'html',
            event_complete: function (data) {
                $('#form').find('#data_types').html(data);
            },

        });
    }
    function validURL(str) {
        var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
        if (!regex.test(str)) {
            alert("Please enter valid URL.");
            return false;
        } else {
            return true;
        }
    }
    (function ($) {
        $(document).ready(function () {
            var $main = $('#form');
            var form = {
                init: function () {
                    $('.form_action_youtube').nstUI('formActionAdv', {
                        event_complete: function (data) {
                            $('#modal_share_video').modal('hide')
                            load_ajax($('.file_list_media'));
                        },
                    });
                    $main.find('.act-do-submit').bind("click", function () {
                        var $_form = $main.find('form');
                        var draft = $(this).data('draft')
                        if (draft != undefined) {
                            $_form.find('input[name=draft]').val(draft)
                        }

                        // su ly cac truong an
                        var hide_fields = [];
                        $('.more-select-dropdown .search-results').each(function () {
                            if (!($(this).css('display') == 'none')) {
                                var id = $(this).data('id');
                                id = id.replace("more_", "");
                                hide_fields.push(id);
                            }
                        })
                        $_form.find('input[name=hide_fields]').val(hide_fields.toString())
                        $_form.nstUI('formActionAdv', {
                            submit: true,
                        });
                    })

                    $("#upload-media").click(function () {
                        $(".upload-action,.upload-action-data").hide();
                        $("#upload-media-content").show();
                        return false;
                    });
                    $("#upload-link").click(function () {
                        $(".upload-action,.upload-action-data").hide();
                        $("#upload-link-content").show();
                        return false;
                    });
                    $('input[name="link"]').on('change', function () {
                        var url = $(this).val()
                        if (!validURL(url))
                            return;
                        $(this).nstUI('loadAjax', {
                            url: "<?php echo current_url(); ?>?_act=load_url&url=" + url,
                            field: {load: '_'},
                            datatype: 'html',
                            event_complete: function (data) {
                                $('#form').find('#data_link').html(data);
                            },

                        });
                    });
                },
            };
            form.init();
        });
    })(jQuery);
    (function ($) {
        $.fn.pluploadScript = function (user_setting) {
            var g_setting_default = {
                mod: '',
                plugin_path: '',
                url_upload: '',
                url_get: '',
                url_update: '',
                config_extensions: '',
                config_max_size: '',
                config_max_file: 5,
                auto_upload: true
            };
            var g_setting = $.extend({}, g_setting_default, user_setting);

            return $(this).each(function () {
                var t = $(this);
                switch (g_setting.mod) {
                    case 'single':
                    case 'single_image':
                    {
                        single_handle();
                        break;
                    }
                    case 'multi':
                    case 'multi_image':
                    {
                        multi_handle();
                        break;
                    }

                    case 'media':
                    {
                        media_handle();
                        break;
                    }
                    default:
                    {
                        break;
                    }
                }
                /**
                 * Single image
                 */
                function single_handle() {
                    // Update thong tin file hien tai
                    update_file_info();

                    // Khai bao cac bien
                    var upload_info = t.find('.upload_info');
                    var upload_error = t.find('.upload_error');
                    var upload_action = t.find('.upload_action');

                    // Tao id random cho upload_action
                    var action_upload_id = 'action_upload_' + Math.floor((Math.random() * 10000000) + 1);
                    upload_action.find('#action_upload').attr('id', action_upload_id);

                    // Khoi tao plupload
                    var uploader = new plupload.Uploader({
                        runtimes: 'gears,html5,flash,silverlight,browserplus',
                        browse_button: action_upload_id,
                        max_file_size: g_setting.config_max_size + 'mb',
                        url: g_setting.url_upload,
                        multi_selection: false,
                        flash_swf_url: g_setting.plugin_path + '/Moxie.swf',
                        silverlight_xap_url: g_setting.plugin_path + '/Moxie.xap',
                        filters: [{title: "Files", extensions: g_setting.config_extensions}]
                    });
                    // Khi file duoc chon
                    uploader.bind('FilesAdded', function (up, files) {


                        // Hien thi thong tin file
                        var params = {
                            file_name: files[0].name,
                            file_size: plupload.formatSize(files[0].size),
                            file_progress: '0'
                        };
                        upload_info.html(temp_set_value(t.find('#temp #upload_info').html(), params)).show();

                        // An thong tin loi
                        upload_error.hide();

                        // An nut upload
                        upload_action.hide();

                        // Reposition Flash/Silverlight
                        up.refresh();
                    });

                    // Khi file duoc chon xong
                    uploader.bind('QueueChanged', function (up) {
                        // Bat dau upload
                        uploader.start();
                    });

                    // Upload progress
                    uploader.bind('UploadProgress', function (up, file) {
                        // Cap nhat progress
                        upload_info.find('.progress').css('width', file.percent + '%');
                    });

                    // Upload hoan thanh
                    uploader.bind('UploadComplete', function (up, files) {
                        // Chay url update
                        if (g_setting.url_update != '') {
                            $.get(g_setting.url_update);
                        }

                        // Cap nhat thong tin file
                        update_file_info();

                        // An thong tin file upload
                        upload_info.hide();

                        // An thong tin loi
                        upload_error.hide();

                        // Hien thi nut upload
                        upload_action.show();
                    });

                    // Error
                    uploader.bind('Error', function (up, err) {
                        // Hien thi loi
                        var params = {
                            file_error: err.message,
                            file_name: err.file.name,
                            file_size: plupload.formatSize(err.file.size)
                        };
                        upload_error.html(temp_set_value(t.find('#temp #upload_error').html(), params)).show();

                        // An thong tin file
                        upload_info.hide();

                        // Hien thi nut upload
                        upload_action.show();

                        // Reposition Flash/Silverlight
                        up.refresh();
                    });

                    // Khoi dong uploader
                    uploader.init();


                    // Xoa file
                    var is_deleting = false;
                    upload_action.find('#action_del').click(function () {

                        if (is_deleting == false) {
                            is_deleting = true;

                            $(this).nstUI({
                                method: "loadAjax",
                                loadAjax: {
                                    url: $(this).attr('href'),
                                    field: {load: '_', show: ''},
                                    event_complete: function (data) {
                                        is_deleting = false;

                                        // Chay url update
                                        if (g_setting.url_update != '') {
                                            $.get(g_setting.url_update);
                                        }

                                        // Cap nhat thong tin file hien tai
                                        update_file_info();

                                        // An thong bao loi
                                        t.find('.upload_error').hide();
                                    }
                                }
                            });
                        }

                        return false;
                    });

                    // Cap nhat thong tin file da upload
                    function update_file_info() {
                        $(this).nstUI({
                            method: "loadAjax",
                            loadAjax: {
                                url: g_setting.url_get,
                                field: {load: '_', show: ''},
                                datatype: 'json',
                                event_complete: function (data) {
                                    var upload_complete = t.find('.upload_complete');
                                    upload_complete.hide();

                                    var params = {
                                        file_url: data['_url'],
                                        file_name: data['orig_name'],
                                        file_size: data['_size'],
                                        url_download: data['_url_download']
                                    };

                                    if (g_setting.mod == 'company_image' || g_setting.mod == 'company_post_images') {
                                        if (data.id && data.id > 0) {
                                            $(g_setting.object + ' .imagecompany').show();
                                            $(g_setting.object + ' .imagecompany img').attr('src', data._url).show();
                                            $(g_setting.object + ' .imagecompany .removefile').attr('href', data._url_del);
                                        } else {
                                            $(g_setting.object + ' .imagecompany').hide();
                                            $(g_setting.object + ' .imagecompany img').attr('src', '').hide();
                                            $(g_setting.object + ' .imagecompany .removefile').attr('href', '');
                                        }
                                    }

                                    else if (g_setting.mod == 'company_post_files') {
                                        if (data != null) {
                                            if (data.id && data.id > 0) {
                                                var html = temp_set_value(t.find('#temp #upload_complete').html(), params);
                                                upload_complete.html(html).fadeIn().find('img').attr('src', data._url);
                                            }
                                            t.find('.upload_action #action_del').show();
                                            t.find('.upload_action #action_del').attr('href', data._url_del);
                                        }
                                    }

                                    else if (g_setting.mod == 'single') {
                                        if (data != null) {
                                            if (data.id && data.id > 0) {
                                                var html = temp_set_value(t.find('#temp #upload_complete').html(), params);
                                                upload_complete.html(html).fadeIn().find('img').attr('src', data._url);
                                            }
                                            t.find('.upload_action #span_action_del').show();
                                            t.find('.upload_action #action_del').attr('href', data._url_del);
                                        }
                                    }

                                    else if (g_setting.mod == 'single_image') {
                                        var html = temp_set_value(t.find('#temp #upload_complete').html(), params);
                                        upload_complete.html(html).fadeIn().find('img').attr('src', data._url);
                                        if (data.id != '0') {
                                            t.find('.upload_action #span_action_del').show();
                                            t.find('.upload_action #span_action_modify').show();

                                            t.find('.upload_action #action_del').attr('href', data._url_del);
                                            t.find('.upload_action #action_modify').attr('href', data._url_modify);
                                        } else {
                                            t.find('.upload_action #span_action_del').hide();
                                            t.find('.upload_action #span_action_modify').hide();
                                        }
                                    }
                                }
                            }
                        });
                    }

                }
                /**
                 * Multi
                 */
                function multi_handle() {
                    // Tai danh sach file
                    var file_list = t.find('#file_list');
                    load_ajax(file_list);

                    // Tao form file upload
                    create_plupload();
                    // Reset file upload
                    t.find('#reset_file_upload').click(function () {
                        create_plupload();
                    });

                    // Tao form upload
                    function create_plupload() {
                        var uploader = t.find('[id^=file_upload_]').pluploadQueue(
                            {
                                runtimes: 'gears,html5,flash,silverlight,browserplus',
                                url: g_setting.url_upload,
                                max_file_size: g_setting.config_max_size + 'mb',
                                max_file_count: g_setting.config_max_file,
                                flash_swf_url: g_setting.plugin_path + '/Moxie.swf',
                                silverlight_xap_url: g_setting.plugin_path + '/Moxie.xap',
                                filters: [{title: "Files", extensions: g_setting.config_extensions}],
                                // browse_button : 'pickfiles', // you can pass in id...
                                //container: document.getElementById('container'), // ... or DOM Element itself

                                // Post init events, bound after the internal events
                                init: {
                                    FilesAdded: function (up, files) {
                                        var maxfiles = g_setting.config_max_file;
                                        if (up.files.length > maxfiles) {
                                            up.splice(maxfiles);
                                            alert('no more than ' + maxfiles + ' file(s)');
                                        }
                                        if (up.files.length === maxfiles) {
                                            t.find('#uploader_browse').fadeIn("slow");
                                        }

                                    },
                                    FilesRemoved: function (up, files) {
                                        if (up.files.length < g_setting.config_max_file) {
                                            t.find('#uploader_browse').fadeIn("slow");
                                        }
                                    },
                                    // Khi file duoc chon xong
                                    QueueChanged: function (up) {
                                        // Tu dong upload
                                        if (g_setting.auto_upload) {
                                            up.start();
                                        }
                                    },

                                    // Tat ca cac file upload hoan thanh
                                    UploadComplete: function (up, files) {
                                        // Cap nhat danh sach file
                                        load_ajax(file_list);

                                        // Chay url update
                                        if (g_setting.url_update != '') {
                                            $.get(g_setting.url_update);
                                        }

                                        // Reset form
                                        setTimeout(function () {
                                            create_plupload();
                                        }, 1000);
                                    }
                                }
                            });

                        return uploader;
                    }
                }
                /**
                 * Media
                 */
                function media_handle() {
                    // Tai danh sach file
                    var file_list = t.find('#file_list');
                    load_ajax(file_list);

                    // Khai bao cac bien
                    var upload_info = t.find('.upload_info');
                    var upload_error = t.find('.upload_error');
                    var upload_action = t.find('.upload_action');

                    // Tao id random cho upload_action
                    var action_upload_id = 'action_upload_' + Math.floor((Math.random() * 10000000) + 1);
                    upload_action.find('#action_upload').attr('id', action_upload_id);

                    // Khoi tao plupload
                    var uploader = new plupload.Uploader({
                        runtimes: 'gears,html5,flash,silverlight,browserplus',
                        browse_button: action_upload_id,
                        max_file_size: g_setting.config_max_size + 'mb',
                        url: g_setting.url_upload,
                        multi_selection: false,
                        flash_swf_url: g_setting.plugin_path + '/Moxie.swf',
                        silverlight_xap_url: g_setting.plugin_path + '/Moxie.xap',
                        filters: [{title: "Files", extensions: g_setting.config_extensions}]
                    });
                    // Khi file duoc chon
                    uploader.bind('FilesAdded', function (up, files) {
                        // Hien thi thong tin file
                        var params = {
                            file_name: files[0].name,
                            file_size: plupload.formatSize(files[0].size),
                            file_progress: '0'
                        };
                        upload_info.html(temp_set_value(t.find('#temp #upload_info').html(), params)).show();

                        // An thong tin loi
                        upload_error.hide();

                        // Reposition Flash/Silverlight
                        up.refresh();
                    });

                    // Khi file duoc chon xong
                    uploader.bind('QueueChanged', function (up) {
                        // Bat dau upload
                        uploader.start();
                    });

                    // Upload progress
                    uploader.bind('UploadProgress', function (up, file) {
                        // Cap nhat progress
                        upload_info.find('.progress').css('width', file.percent + '%');
                    });

                    // Upload hoan thanh
                    uploader.bind('UploadComplete', function (up, files) {
                        // Chay url update
                        if (g_setting.url_update != '') {
                            $.get(g_setting.url_update);
                        }

                        // Cap nhat thong tin file
                        load_ajax(file_list);

                        // An thong tin file upload
                        upload_info.hide();

                        // An thong tin loi
                        upload_error.hide();

                        // Hien thi nut upload
                        upload_action.show();
                    });

                    // Error
                    uploader.bind('Error', function (up, err) {
                        // Hien thi loi
                        var params = {
                            file_error: err.message,
                            file_name: err.file.name,
                            file_size: plupload.formatSize(err.file.size)
                        };
                        upload_error.html(temp_set_value(t.find('#temp #upload_error').html(), params)).show();

                        // An thong tin file
                        upload_info.hide();

                        // Hien thi nut upload
                        upload_action.show();

                        // Reposition Flash/Silverlight
                        up.refresh();
                    });

                    // Khoi dong uploader
                    uploader.init();


                }
            });
        }
    })(jQuery);


</script>