<link href="<?php echo public_url('js'); ?>/jquery/plupload2/jquery.ui.plupload/css/jquery.ui.plupload.css"  media="all" type="text/css" rel="stylesheet"/>
<link href="<?php echo public_url('js'); ?>/jquery/plupload2/jquery.plupload.queue/css/jquery.plupload.queue.css" media="all" type="text/css" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo public_url('js') ?>/jquery/plupload2/plupload.full.min.js"></script>
<script type="text/javascript" src="<?php echo public_url('js') ?>/jquery/plupload2/jquery.ui.plupload/jquery.ui.plupload.js"></script>
<script type="text/javascript" src="<?php echo public_url('js') ?>/jquery/plupload2/jquery.plupload.queue/jquery.plupload.queue.js"></script>
<script type="text/javascript" src="<?php echo public_url('js') ?>/jquery/plupload2/script.js"></script>
<script type="text/javascript" src="<?php echo public_url('site') ?>/theme/js/script.js"></script>

<script type="text/javascript">
    /*window.onload = function() {
     window.addEventListener("beforeunload", function (e) {
     var confirmationMessage = 'Nếu bạn rời khỏi trang này các thay đổi sẽ không được lưu lại. ';

     (e || window.event).returnValue = confirmationMessage; //Gecko + IE
     return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
     });
     };*/
    (function ($) {
        $(document).ready(function () {
            var $main = $('#form');
            var form = {
                init: function () {
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
                        var url=$(this).val()
                        if(!validURL(url))
                            return;
                        $(this).nstUI('loadAjax', {
                            url: "<?php echo current_url(); ?>?_act=load_url&url="+url,
                            field: {load: '_'},
                            datatype: 'html',
                            event_complete: function (data) {
                                $('#form').find('#data_link').html(data);
                            },

                        });
                    });
                    $('#gallery-photo-add').on('change', function () {
                        imagesPreview(this, 'div.gallery');
                    });
                },
            };
            form.init();
        });
    })(jQuery);
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
    function imagesPreview(input) {
        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function (event) {
                    acc(event.target.result)
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    }

    var index =0;
    function acc(src) {
        //alert(_.toLower('ASddf'))
        var $main = $('#form');
        var html = $main.find('#_temp').html();
        html = temp_set_value(html, {src: src});
        html = temp_set_value(html, {index: index++});
        // Cap nhat html
        $('.product-images .owl-carousel').trigger('add.owl.carousel', [html]).trigger('refresh.owl.carousel');
    }
    function del($this) {
        var indexToRemove =$($this).data('index')
        $(".product-images .owl-carousel").trigger('remove.owl.carousel', [indexToRemove]).trigger('refresh.owl.carousel');
    }
    function validURL(str) {
        var regex = /(http|https):\/\/(\w+:{0,1}\w*)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%!\-\/]))?/;
        if(!regex .test(str)) {
            alert("Please enter valid URL.");
            return false;
        } else {
            return true;
        }
    }
</script>