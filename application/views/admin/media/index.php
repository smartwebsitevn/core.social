<!DOCTYPE html>
<html lang="vi">
    <head>
	<?php $this->load->view('admin/media/_common/head',$this->data); ?>
    </head>
    <body>
      <div id="container">
            <div id="menu">
                <?php if (!$fckeditor): ?>
                    <a class="button" style=" background-image: url('<?php echo $path_assets ?>js/jquery/filemanager/images/home.png');" href="<?php echo admin_url() ?>"><?php echo lang('home') ?></a>
                <?php endif; ?>
                <a id="create" class="button" style="background-image: url('<?php echo $path_assets ?>js/jquery/filemanager/images/folder.png');">
                    <?php echo $button_folder; ?>
                </a>
                <a id="delete" class="button" style="background-image: url('<?php echo $path_assets ?>js/jquery/filemanager/images/edit-delete.png');">
                    <?php echo $button_delete; ?>
                </a>
                <a id="move" class="button" style="background-image: url('<?php echo $path_assets ?>js/jquery/filemanager/images/edit-cut.png');">
                    <?php echo $button_move; ?>
                </a>
                <a id="copy" class="button" style="background-image: url('<?php echo $path_assets ?>js/jquery/filemanager/images/edit-copy.png');">
                    <?php echo $button_copy; ?>
                </a>
                <a id="rename" class="button" style="background-image: url('<?php echo $path_assets ?>js/jquery/filemanager/images/edit-rename.png');">
                    <?php echo $button_rename; ?>
                </a>
                <a id="modify"  onclick="button_image_croper(); return false;"   class="button" style="background-image: url('<?php echo $path_assets ?>js/jquery/filemanager/images/edit-rename.png');">
                   <?php echo $button_modify; ?>
                </a>
                <a id="upload" class="button" style="background-image: url('<?php echo $path_assets ?>js/jquery/filemanager/images/upload.png');">
                    <?php echo $button_upload; ?>
                </a>
                <a id="refresh" class="button" style="background-image: url('<?php echo $path_assets ?>js/jquery/filemanager/images/refresh.png');">
                    <?php echo $button_refresh; ?>
                </a>
               <!-- <div class="status">status...</div>-->
                <a id="copy_url" class="button" style="padding-left: 4px; color: blue; overflow: hidden; width: 520px; ">  </a>

            </div>

            <div class="clear"></div>
            <div id="column-left"></div>

            <div id="column-right">

            </div>
			
        </div>

        <script type="text/javascript">
      
        
            function printObject(o) {
                var out = '';
                for (var p in o) {
                    out += p + ': ' + o[p] + '\n';
                }
                alert(out);
            }

            $(document).ready(function() {
              
                setInterval(function() {

                    $('.status').text("status...");

                }, 5000);

                (function() {
                    var special = jQuery.event.special,
                            uid1 = 'D' + (+new Date()),
                            uid2 = 'D' + (+new Date() + 1);
                    special.scrollstart = {
                        setup: function() {
                            var timer,
                                    handler = function(evt) {
                                var _self = this,
                                        _args = arguments;
                                if (timer) {
                                    clearTimeout(timer);
                                } else {
                                    evt.type = 'scrollstart';
                                    jQuery.event.handle.apply(_self, _args);
                                }
                                timer = setTimeout(function() {
                                    timer = null;
                                }, special.scrollstop.latency);
                            };
                            jQuery(this).bind('scroll', handler).data(uid1, handler);
                        },
                        teardown: function() {
                            jQuery(this).unbind('scroll', jQuery(this).data(uid1));
                        }
                    };
                    special.scrollstop = {
                        latency: 300,
                        setup: function() {
                            var timer,
                                    handler = function(evt) {
                                var _self = this,
                                        _args = arguments;
                                if (timer) {
                                    clearTimeout(timer);
                                }
                                timer = setTimeout(function() {
                                    timer = null;
                                    evt.type = 'scrollstop';
                                    jQuery.event.handle.apply(_self, _args);
                                }, special.scrollstop.latency);
                            };
                            jQuery(this).bind('scroll', handler).data(uid2, handler);
                        },

                        teardown: function() {
                            jQuery(this).unbind('scroll', jQuery(this).data(uid2));
                        }
                    };
                })();
                $('#column-right').bind('scrollstop', function() {
                    $('#column-right a').each(function(index, element) {
                        var height = $('#column-right').height();
                        var offset = $(element).offset();
                        if ((offset.top > 0) && (offset.top < height) && $(element).find('img').attr('src') === '<?php echo $no_image; ?>') {
                            $.ajax({
                                url: '<?php echo $media_url_image?>?image=' + encodeURIComponent($(element).find('input[name=\'image\']').attr('value')),
                                dataType: 'html',
                                success: function(html) {
                                    //Thumbnail (load anh)

                                    //TaiPV edit

                                    //Lay phan mo rong cua anh

                                    var ext = html.substring(html.length - 3);

                                    //alert(ext);

                                    if (ext === 'mp3') {

                                        html = "<?php echo $path_assets ?>js/jquery/filemanager/images/media/audio.png";

                                    }

                                    else if (ext === 'mp4' || ext === 'avi' || ext === 'mpg' || ext === 'avi' || ext === 'flv') {

                                        html = "<?php echo $path_assets ?>js/jquery/filemanager/images/media/video.png";

                                    }

                                    else if (ext === 'zip' || ext === 'rar') {

                                        html = "<?php echo $path_assets ?>js/jquery/filemanager/images/media/compress.png";

                                    }

                                    else if (ext === 'doc' || ext === 'ocx') {

                                        html = "<?php echo $path_assets ?>js/jquery/filemanager/images/media/document.png";

                                    }

                                    else if (ext === 'swf') {

                                        html = "<?php echo $path_assets ?>js/jquery/filemanager/images/media/flash.png";

                                    }

                                    else if (ext === 'pdf') {

                                        html = "<?php echo $path_assets ?>js/jquery/filemanager/images/media/pdf.png";

                                    }

                                    else if (ext === 'txt') {

                                        html = "<?php echo $path_assets ?>js/jquery/filemanager/images/media/txt.png";

                                    }

                                    $(element).find('img').replaceWith('<img class="lazy" src="' + html + '" alt="' + ext + '" title="" />');

                                }

                            });

                        }

                    });

                });



                $('#column-left').tree({

                    data: {

                        type: 'json',

                        async: true,

                        opts: {

                            method: 'post',

                            url: '<?php echo $media_url_directory?>'

                        }

                    },

                    selected: 'top',

                    ui: {

                        theme_name: 'classic',

                        animation: 100

                    },

                    types: {

                        'default': {

                            clickable: true,

                            creatable: false,

                            renameable: false,

                            deletable: false,

                            draggable: false,

                            max_children: -1,

                            max_depth: -1,

                            valid_children: 'all'

                        }

                    },

                    callback: {

                        beforedata: function(NODE, TREE_OBJ) {

                            if (NODE === false) {

                                TREE_OBJ.settings.data.opts.static = [

                                    {

                                        data: '<?php echo $entry_files ?>',

                                        attributes: {

                                            'id': 'top',

                                            'directory': ''

                                        },

                                        state: 'closed'

                                    }

                                ];



                                return {'directory': ''}

                            } else {

                                TREE_OBJ.settings.data.opts.static = false;



                                return {'directory': $(NODE).attr('directory')}

                            }

                        },

                        onselect: function(NODE, TREE_OBJ) {

                            $.ajax({

                                url: '<?php echo $media_url_files?>',

                                type: 'post',

                                data: 'directory=' + encodeURIComponent($(NODE).attr('directory')) +'&token='+ csrf_token,

                                dataType: 'json',

                                success: function(json) {

                                    html = '<div>';



                                    if (json) {

                                        for (i = 0; i < json.length; i++) {
											//alert(json[i]['filename']);
                                            html += '<a>';

                                            html += '<img src="<?php echo $no_image; ?>" alt="..." title="" /><br />' +

                                                    ((json[i]['filename'].length > 15) ? (json[i]['filename'].substr(0, 15) + '..') : json[i]['filename']) +

                                                    '<br />' + json[i]['size'];

                                            html += '<input class="url" type="hidden" name="image" value="' + json[i]['file'] + '" />';

                                            html += '</a>';

                                        }

                                    }



                                    html += '</div>';



                                    $('#column-right').html(html);



                                    $('#column-right').trigger('scrollstop');

                                },

                                error: function(xhr, ajaxOptions, thrownError) {

                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                                }

                            });

                        }

                    }

                });



                $('#column-right a').live('click', function() {

                    url = $(this).find('.url').attr("value");

                    url = "<?php echo $path_upload?>" + url;

                    if ($(this).attr('class') === 'selected') {

                        $(this).removeAttr('class');

                    }

                    else {

                        $('#column-right a').removeAttr('class');



                        $(this).attr('class', 'selected');

                    }

                    var new_url = url;

                    var url_len = 55;

                    if (new_url.length > url_len) {

                        new_ulr = "..." + url.substring(url.length - url_len, url.length);

                    }

                    $('#copy_url').attr("href",url);

                    $('#copy_url').text(new_url);

                });

                

                $('#column-right a').live('dblclick', function() {

                    //Xet truong hop dac biet neu trang nay duoc goi tu trang form?widget thi add anh luon ko show ra chkeditor nua

                    //cai nay them vao de su ly show incon trong phan icon cua widget

//                    alert(window.opener.location.indexOf('form?widget'));

//                    var url_parent = window.opener.location;

//                    var i_widget = url_parent.indexOf("local");

//                    alert(window.opener.location);

////                  alert(i_widget);

//                    var str="http://localhost/saobacdau/admin/widget/form?widget=visitcounter_display";

//                    var n=str.indexOf("form?widget");

//                    alert(n);

//                    alert("parent is WIDGET..." + window.opener.location);

                    if (window.opener.location == "<?php echo base_url() ?>admin/widget" ||

                        window.opener.location == "<?php echo base_url() ?>admin/widget/form?widget=visitcounter_display" ||

                        window.opener.location == "<?php echo base_url() ?>admin/widget/form?widget=customer_comment_display" ||

                        window.opener.location == "<?php echo base_url() ?>admin/widget/form?widget=common_html" ||

                        window.opener.location == "<?php echo base_url() ?>admin/widget/form?widget=news_search" ||

                        window.opener.location == "<?php echo base_url() ?>admin/widget/form?widget=document_search" ||

                        window.opener.location == "<?php echo base_url() ?>admin/widget/form?widget=image_slideshow" ||

                        window.opener.location == "<?php echo base_url() ?>admin/widget/form?widget=news_category" ||

                        window.opener.location == "<?php echo base_url() ?>admin/widget/form?widget=supportonline_display") {

//                        alert("parent is WIDGET..." + window.opener.location);

//                        Gan duong dan anh icon vao txt cua thang cha

                        var url = $(this).find('.url').attr("value");

                        url = "<?php echo $path_upload?>" + url;

                        //Gan cho txt an

                        window.opener.$("#txt_icon_link").val(url);

                        //Gan cho thang anh

                        window.opener.$("#news_icon").attr("src", url);

                        self.close();

                    }

                    

                    

                    <?php if ($fckeditor) { ?>

                        window.opener.CKEDITOR.tools.callFunction(<?php echo $fckeditor; ?>, '<?php echo $directory; ?>' + $(this).find('input[name=\'image\']').attr('value'));

                        self.close();

                    <?php } else { ?>

                        parent.$('#<?php echo $field; ?>').attr('value',  $(this).find('input[name=\'image\']').attr('value'));

                        parent.$('#dialog').dialog('close');

                        parent.$('#dialog').remove();

                    <?php } ?>

                });

                

//My edit-----------------------------------------------------------

                $('#column-right a').live('_dblclick_', function() {

                    //printObject(window.opener);

<?php if ($fckeditor) { ?>

                        //Neu no duoc mo tu phan soan thao tin tuc thi mo     CKEDITOR

                        if (window.opener.location == "<?php echo base_url() ?>admin/news" ||

                                window.opener.location == "<?php echo base_url() ?>admin/page") {

    //                            alert("parent is News | Page");

                            window.opener.CKEDITOR.tools.callFunction(<?php echo $fckeditor; ?>, '<?php echo $directory; ?>' + $(this).find('input[name=\'image\']').attr('value'));

                            self.close();

                        }

                        else if (window.opener.location == "<?php echo base_url() ?>admin/widget" ||

                                window.opener.location == "<?php echo base_url() ?>admin/widget/form?widget=news_display") {

    //                          alert("parent is WIDGET..." + window.opener.location);

                            //cai nay them vao de su ly show incon trong phan news widget

                            //Gan duong dan anh icon vao txt cua thang cha

                            var url = $(this).find('.url').attr("value");

                            url = "<?php echo $path_upload?>" + url;

                            //Gan cho txt an

                            window.opener.$("#txt_icon_link").val(url);

                            //Gan cho thang anh

                            window.opener.$("#news_icon").attr("src", url);

                            self.close();

                        }

                        else if (window.opener.location == "<?php echo base_url() ?>admin/setting#") {

    //                            alert("parent is WIDGET..." + window.opener.location);

                            var url = $(this).find('.url').attr("value");

                            url = "<?php echo $path_upload?>" + url;

                            //Gan cho txt an

                            window.opener.$("#txt_banner_image").val(url);

    //                            window.opener.$("#txt_logo_image").val(url);

                            //Gan cho thang anh

    //                            window.opener.$("#news_icon").attr("src",url);

                            self.close();

                        }

                        else if (window.opener.location == "<?php echo base_url() ?>admin/news#") {

                            var url = $(this).find('.url').attr("value");

                            url = "<?php echo $path_upload?>" + url;

    //                            window.opener.$("#news_param_content").val(url);

                            //url cu cua #textarea_param_content

//                            window.opener.$("#textarea_param_content").removeClass('editor');

//                            old_url = window.opener.$("#textarea_param_content").text();

                            str = "<textarea name=\"content\" style=\"margin: 0px 0px 10px; height: 40px; width: 530px;\">";

//                            str += old_url + url;

                            str += url;

                            str += "</textarea>";

                            window.opener.$("#textarea_param_content").html(str);

                            self.close();

                        }

    //                        else{

    //                            window.opener.CKEDITOR.tools.callFunction(<?php // echo $fckeditor;  ?>, '<?php // echo $directory;  ?>' + $(this).find('input[name=\'image\']').attr('value'));

    //                            self.close();

    //                        }

<?php } else { ?>

                        parent.$('#<?php echo $field; ?>').attr('value', $(this).find('input[name=\'image\']').attr('value'));

                        parent.$('#dialog').dialog('close');

                        parent.$('#dialog').remove();

<?php } ?>

                });

//End my edit-----------------------------------------------------------



                $('#column-right a').live('_dblclick_cai_nay_minh_them_vao', function() {

                    $('#dialog').remove();

                    var url = $(this).find('.url').attr("value");

                    name = url;

                    tmp = url.split("/");

                    if (tmp.length > 1)

                        name = tmp[tmp.length - 1];





                    url = "<?php echo $path_upload?>" + url;

                    var flag = false;

                    var icon = '';

                    //Neu ko phai la anh thi in ra bieu tuong va duong link cua no

                    //TaiPV edit

                    //Lay phan mo rong cua anh

                    var ext = url.substring(url.length - 3);

                    //alert(ext);

                    if (ext === 'mp3') {

                        icon = "<?php echo $path_assets ?>js/jquery/filemanager/images/media/audio.png";

                        flag = true;

                    }

                    else if (ext === 'mp4' || ext === 'avi' || ext === 'mpg' || ext === 'avi' || ext === 'flv') {

                        icon = "<?php echo $path_assets ?>js/jquery/filemanager/images/media/video.png";

                        flag = true;

                    }

                    else if (ext === 'zip' || ext === 'rar') {

                        icon = "<?php echo $path_assets ?>js/jquery/filemanager/images/media/compress.png";

                        flag = true;

                    }

                    else if (ext === 'doc' || ext === 'ocx') {

                        icon = "<?php echo $path_assets ?>js/jquery/filemanager/images/media/document.png";

                        flag = true;

                    }

                    else if (ext === 'swf') {

                        icon = "<?php echo $path_assets ?>js/jquery/filemanager/images/media/flash.png";

                        flag = true;

                    }

                    else if (ext === 'pdf') {

                        icon = "<?php echo $path_assets ?>js/jquery/filemanager/images/media/pdf.png";

                        flag = true;

                    }

                    else if (ext === 'txt') {

                        icon = "<?php echo $path_assets ?>js/jquery/filemanager/images/media/txt.png";

                        flag = true;

                    }

                    if (flag) {

                        html = '<div id="dialog">';

                        html += "<a class='mylink' href='" + url + "'>";

                        html += "<img src='" + icon + "' style='max-width: 200px'/> ";

                        html += "</a>";

                        html += "<br><br>" + name + "</br>";

                        html += '</div>';

                        $('#column-right').prepend(html);



                        $('#dialog').dialog({

                            title: 'Chi tiết tệp tin',

                            //                        resizable: false

                            resizable: true,

                            width: 400

                        });

                    }

                    else {

                        html = '<div id="dialog">';

                        html += "<a class='mylink' href='" + url + "'>";

                        html += "<img src='" + url + "' style='max-width: 800px'/> ";

                        html += "</a>";

                        html += '</div>';

                        $('#column-right').prepend(html);



                        $('#dialog').dialog({

                            title: 'Chi tiết ảnh',

                            //                        resizable: false

                            resizable: true,

                            width: 800

                        });

                    }

                });



                $('#create').bind('click', function() {

                    var tree = $.tree.focused();



                    if (tree.selected) {

                        $('#dialog').remove();



                        html = '<div id="dialog">';

                        html += '<?php echo $entry_folder; ?> <input type="text" name="name" value="" /> <input type="button" value="<?php echo $button_submit; ?>" />';

                        html += '</div>';



                        $('#column-right').prepend(html);



                        $('#dialog').dialog({

                            title: '<?php echo $button_folder; ?>',

                            resizable: false

                        });



                        $('#dialog input[type=\'button\']').bind('click', function() {

                            $.ajax({

                                url: '<?php echo $media_url_create ?>',

                                type: 'post',

                                data: 'directory=' + encodeURIComponent($(tree.selected).attr('directory')) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()) +'&token='+ csrf_token,

                                dataType: 'json',

                                success: function(json) {

                                    if (json.success) {

                                        $('#dialog').remove();

                                        tree.refresh(tree.selected);

                                        //alert(json.success);

//                                        $('.status').text(json.success);

                                        $('.status').text('Object Created');

                                    }

                                    else {

                                        alert(json.error);

                                    }

                                },

                                error: function(xhr, ajaxOptions, thrownError) {

                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                                }

                            });

                        });

                    } else {

                        alert('<?php echo $error_directory; ?>');

                    }

                });



                $('#delete').bind('click', function() {

                    if (!confirm('<?php echo $confirm_delete ?>'))

                        return false;

                    //========

                    path = $('#column-right a.selected').find('input[name=\'image\']').attr('value');



                    if (path) {

                        $.ajax({

                            url: '<?php echo $media_url_delete?>',

                            type: 'post',

                            data: 'path=' + encodeURIComponent(path) +'&token='+ csrf_token,

                            dataType: 'json',

                            success: function(json) {

                                if (json.success) {

                                    var tree = $.tree.focused();

                                    tree.select_branch(tree.selected);

//                                    alert(json.success);

//                                    $('.status').text(json.success);

                                    $('.status').text('Object deleted');

                                }



                                if (json.error) {

                                    alert(json.error);

                                }

                            },

                            error: function(xhr, ajaxOptions, thrownError) {

                                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                            }

                        });

                    } else {

                        var tree = $.tree.focused();



                        if (tree.selected) {

                            $.ajax({

                                url: '<?php echo $media_url_delete ?>',

                                type: 'post',

                                data: 'path=' + encodeURIComponent($(tree.selected).attr('directory')) +'&token='+ csrf_token,

                                dataType: 'json',

                                success: function(json) {

                                    if (json.success) {

                                        tree.select_branch(tree.parent(tree.selected));

                                        tree.refresh(tree.selected);

//                                        alert(json.success);

//                                        $('.status').text(json.success);

                                        $('.status').text('Object deleted');

                                    }



                                    if (json.error) {

                                        alert(json.error);

                                    }

                                },

                                error: function(xhr, ajaxOptions, thrownError) {

                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                                }

                            });

                        } else {

                            alert('<?php echo $error_select; ?>');

                        }

                    }

                });



                $('#move').bind('click', function() {

                    $('#dialog').remove();



                    html = '<div id="dialog">';

                    html += '<?php echo $entry_move; ?> <select name="to"></select> <input type="button" value="<?php echo $button_submit; ?>" />';

                    html += '</div>';



                    $('#column-right').prepend(html);



                    $('#dialog').dialog({

                        title: '<?php echo $button_move; ?>',

                        resizable: false

                    });



                    $('#dialog select[name=\'to\']').load('<?php echo $media_url_folders?>');



                    $('#dialog input[type=\'button\']').bind('click', function() {

                        path = $('#column-right a.selected').find('input[name=\'image\']').attr('value');



                        if (path) {

                            $.ajax({

                                url: '<?php echo $media_url_move?>',

                                type: 'post',

                                data: 'from=' + encodeURIComponent(path) + '&to=' + encodeURIComponent($('#dialog select[name=\'to\']').val()) +'&token='+ csrf_token,

                                dataType: 'json',

                                success: function(json) {

                                    if (json.success) {

                                        $('#dialog').remove();

                                        var tree = $.tree.focused();

                                        tree.select_branch(tree.selected);

                                        //alert(json.success);

//                                        $('.status').text(json.success);

                                        $('.status').text('Object moved');

                                    }



                                    if (json.error) {

                                        alert(json.error);

                                    }

                                },

                                error: function(xhr, ajaxOptions, thrownError) {

                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                                }

                            });

                        } else {

                            var tree = $.tree.focused();



                            $.ajax({

                                url: '<?php echo $media_url_move?>',

                                type: 'post',

                                data: 'from=' + encodeURIComponent($(tree.selected).attr('directory')) + '&to=' + encodeURIComponent($('#dialog select[name=\'to\']').val()) +'&token='+ csrf_token,

                                dataType: 'json',

                                success: function(json) {

                                    if (json.success) {

                                        $('#dialog').remove();

                                        tree.select_branch('#top');

                                        tree.refresh(tree.selected);

                                        //alert(json.success);

//                                        $('.status').text(json.success);

                                        $('.status').text('Object moved');

                                    }



                                    if (json.error) {

                                        alert(json.error);

                                    }

                                },

                                error: function(xhr, ajaxOptions, thrownError) {

                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                                }

                            });

                        }

                    });

                });



                $('#copy').bind('click', function() {

                    $('#dialog').remove();



                    html = '<div id="dialog">';

                    html += '<?php echo $entry_copy; ?> <input type="text" name="name" value="" /> <input type="button" value="<?php echo $button_submit; ?>" />';

                    html += '</div>';



                    $('#column-right').prepend(html);



                    $('#dialog').dialog({

                        title: '<?php echo $button_copy; ?>',

                        resizable: false

                    });



                    $('#dialog select[name=\'to\']').load('<?php echo $media_url_folders?>');



                    $('#dialog input[type=\'button\']').bind('click', function() {

                        path = $('#column-right a.selected').find('input[name=\'image\']').attr('value');



                        if (path) {

                            $.ajax({

                                url: '<?php echo $media_url_copy?>',

                                type: 'post',

                                data: 'path=' + encodeURIComponent(path) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()) +'&token='+ csrf_token,

                                dataType: 'json',

                                success: function(json) {

                                    if (json.success) {

                                        $('#dialog').remove();

                                        var tree = $.tree.focused();

                                        tree.select_branch(tree.selected);

                                        //alert(json.success);

//                                        $('.status').text(json.success);

                                        $('.status').text('Object Copied');

                                    }



                                    if (json.error) {

                                        alert(json.error);

                                    }

                                },

                                error: function(xhr, ajaxOptions, thrownError) {

                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                                }

                            });

                        } else {

                            var tree = $.tree.focused();



                            $.ajax({

                                url: '<?php echo $media_url_copy?>',

                                type: 'post',

                                data: 'path=' + encodeURIComponent($(tree.selected).attr('directory')) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()) +'&token='+ csrf_token,

                                dataType: 'json',

                                success: function(json) {

                                    if (json.success) {

                                        $('#dialog').remove();

                                        tree.select_branch(tree.parent(tree.selected));

                                        tree.refresh(tree.selected);

                                        //alert(json.success);

//                                        $('.status').text(json.success);

                                        $('.status').text('Object copied');

                                    }



                                    if (json.error) {

                                        alert(json.error);

                                    }

                                },

                                error: function(xhr, ajaxOptions, thrownError) {

                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                                }

                            });

                        }

                    });

                });



                $('#rename').bind('click', function() {

                    $('#dialog').remove();



                    html = '<div id="dialog">';

                    html += '<?php echo $entry_rename; ?> <input type="text" name="name" value="" /> <input type="button" value="<?php echo $button_submit; ?>" />';

                    html += '</div>';



                    $('#column-right').prepend(html);



                    $('#dialog').dialog({

                        title: '<?php echo $button_rename; ?>',

                        resizable: false

                    });



                    $('#dialog input[type=\'button\']').bind('click', function() {

                        path = $('#column-right a.selected').find('input[name=\'image\']').attr('value');



                        if (path) {

                            $.ajax({

                                url: '<?php echo $media_url_rename?>',

                                type: 'post',

                                data: 'path=' + encodeURIComponent(path) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()) +'&token='+ csrf_token,

                                dataType: 'json',

                                success: function(json) {

                                    if (json.success) {

                                        $('#dialog').remove();

                                        var tree = $.tree.focused();

                                        tree.select_branch(tree.selected);

                                        //alert(json.success);

//                                        $('.status').text(json.success);

                                        $('.status').text('Object renamed');

                                    }



                                    if (json.error) {

                                        alert(json.error);

                                    }

                                },

                                error: function(xhr, ajaxOptions, thrownError) {

                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                                }

                            });

                        } else {

                            var tree = $.tree.focused();



                            $.ajax({

                                url: '<?php echo $media_url_rename?>',

                                type: 'post',

                                data: 'path=' + encodeURIComponent($(tree.selected).attr('directory')) + '&name=' + encodeURIComponent($('#dialog input[name=\'name\']').val()) +'&token='+ csrf_token,

                                dataType: 'json',

                                success: function(json) {

                                    if (json.success) {

                                        $('#dialog').remove();

                                        tree.select_branch(tree.parent(tree.selected));

                                        tree.refresh(tree.selected);

                                        //alert(json.success);

//                                        $('.status').text(json.success);

                                        $('.status').text('Object renamed');

                                    }



                                    if (json.error) {

                                        alert(json.error);

                                    }

                                },

                                error: function(xhr, ajaxOptions, thrownError) {

                                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                                }

                            });

                        }

                    });

                });



                
    

				
                new AjaxUpload('#upload', {

                    action: '<?php echo $media_url_upload?>',

                    name: 'file',

                    autoSubmit: false,

                    responseType: 'json',

                    onChange: function(file, extension) {

                        var tree = $.tree.focused();



                        if (tree.selected) {

                            this.setData({'directory': $(tree.selected).attr('directory')});

                        } else {

                            this.setData({'directory': ''});

                        }



                        this.submit();

                    },

                    onSubmit: function(file, extension) {

                        $('#upload').append('<img src="<?php echo $path_assets ?>js/jquery/filemanager/images/loading.gif" class="loading" style="padding-left: 5px;" />');

                    },

                    onComplete: function(file, json) {

                        if (json.success) {

                            var tree = $.tree.focused();

                            tree.select_branch(tree.selected);

//                            $('.status').text(json.success);

                            $('.status').text('Object uploaded');

                        }



                        if (json.error) {

                            alert(json.error);

                        }



                        $('.loading').remove();

                    }

                });



                $('#refresh').bind('click', function() {

                    var tree = $.tree.focused();



                    tree.refresh(tree.selected);

                });

            });
            function button_image_croper($this){
                
           	 path = $('#column-right a.selected').find('input[name=\'image\']').attr('value');
        	var url = '<?php echo $media_url_modify?>?image='+path;
     		var title='Croper';var w=1024;	var h=600;
    		var left = (screen.width/2)-(w/2);
    		var top = (screen.height/2)-(h/2);
    		return window.open(url, title, 'toolbar=yes, location=yes, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, copyhistory=no, width='+w+', height='+h+', top='+top+', left='+left);

            }
     

        </script>

    </body>

</html>