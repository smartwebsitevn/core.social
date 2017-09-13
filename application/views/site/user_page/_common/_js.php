<?php // pr($info); ?>
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
                    case 'banner':
                    {
                        banner_handle();
                        break;
                    }
                    default:
                    {
                        break;
                    }


                }
                function banner_handle() {
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
                        filters: [{title: "Files", extensions: g_setting.config_extensions}],
                        multipart : true,
                        multipart_params : {
                            token :csrf_token
                        },
                    });
                    // Khi file duoc chon
                    uploader.bind('FilesAdded', function (up, files) {

                        // Hien thi thong tin file
                        var params = {
                            file_name: files[0].name,
                            file_size: plupload.formatSize(files[0].size),
                            file_progress: '0'
                        };
                        //upload_info.html(temp_set_value(t.find('#temp #upload_info').html(), params)).show();

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
                        //upload_info.find('.progress').css('width', file.percent + '%');
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

                                 if (g_setting.mod == 'banner') {
                                        var html = temp_set_value(t.find('#temp #upload_complete').html(), params);
                                        upload_complete.html(html).fadeIn().find('img').attr('src', data._url);
                                        if (data.id != '0') {
                                            $('#banner_background').addClass('active')
                                            $('#banner_background').css('background-image', 'url(' +  data._url + ')');
                                            t.find('.upload_action #action_del').attr('href', data._url_del).show();
                                        } else {
                                            $('#banner_background').css('background-image', 'none');
                                            $('#banner_background').removeClass('active')

                                            t.find('.upload_action #action_del').hide();
                                        }
                                    }
                                }
                            }
                        });
                    }

                }
            });
        }
    })(jQuery);


</script>