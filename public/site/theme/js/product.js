var product_nfc = {
    boot: function () {
        this.display.boot();
    },
    display: {
        boot: function () {
            this.common();
        },
        common: function () {
            $(document).ready(function () {
                $('body').on('click', '.item-video-icon', function () {
                    var $parent = $(this).closest('.item');
                    var $player = $parent.find('.item-video-player')
                    if (!$player.length) return;
                    $(this).hide();
                    $parent.find('img').hide();
                    $('<iframe>', {
                        src: '//www.youtube.com/embed/' + $(this).data('youtube') + '?rel=0&autoplay=1',
                        frameborder: 0,
                        scrolling: 'no',
                        allowfullscreen: "allowfullscreen"
                    }).appendTo($player);
                });

                $('body').on('click', '.item-social .act-view-quick', function () {
                    var $modal = $('#modal-social-view');
                    nfc.loader('show');
                    var url = $(this).data('url');
                    $.ajax({
                        type: "GET",
                        url: url,
                        dataType: 'html',
                        success: function (data) {
                            nfc.loader('hide');
                            $modal.find('.modal-body').html(data);
                            // hien thong bao
                            $modal.modal('show')
                        }
                    });

                });

                /*=========== Filter ============*/
                /*$("#form_filter_advance .filter-tick").on("click", function () {
                 $(this).toggleClass('active');
                 if ($(this).hasClass('active')) {
                 html = '<input type="hidden" name="' + $(this).data('name') + '" value="' + $(this).data('value') + '"  />';
                 $(this).append(html);
                 }
                 else {
                 $(this).find('input').remove();
                 }
                 productFilter();
                 });
                 $("#form_filter_advance input,#form_filter_base input").on('keydown', function (e) {
                 if (e.keyCode == '13') {
                 productFilter();
                 }
                 });
                 $("#form_filter_advance input,#form_filter_base input").on('change ', function () {
                 productFilter();
                 });
                 $("#form_filter_advance select,#form_filter_base select").on('change', function () {
                 productFilter();
                 });*/
                /*=========== Pagging Ajax ===============*/
                $("#product-category .pagging-ajax a").bind("click", function () {
                    var page = $(this).attr("href");
                    if (page != undefined) {
                        page = page.replace('/', '');
                        if (page == '')   page = 0;
                        //alert(page);
                        productFilter(page);
                    }
                    return false;
                });
                /*=========== Pagging Ajax ===============*/
                $("#product-category .pagging-ajax a").bind("click", function () {
                    var page = $(this).attr("href");
                    if (page != undefined) {
                        page = page.replace('/', '');
                        if (page == '')   page = 0;
                        //alert(page);
                        productFilter(page);
                    }
                    return false;
                });
                /*=========== Rating ============*/
                /*
                 $.fn.raty.defaults.path = base_url+'/assets/js/raty/img';
                 $('.rating').raty({// hien thi co dinh
                 score: function() {
                 return $(this).attr('data-score');
                 },
                 readOnly  : true,
                 });
                 $('.product-rating-allow .rating').raty({// cho phep rating
                 score: function() {
                 return $(this).attr('data-score');
                 },
                 half    : true,
                 click: function(score, evt) {
                 $.ajax({
                 async: false,
                 type: "POST",
                 url: $(this).attr('data-url'),
                 data: {'score':score},
                 success: function(data)
                 {
                 if(data.complete)
                 {
                 $('.product-rating-allow .rating-total').html(data.total);

                 }
                 alert(data.msg);

                 }
                 });

                 }
                 });
                 */


            });
        },

    },
    filter: function (option) {
        productFilter(option)
    },
}
product_nfc.boot();
/* FUNTIONS SUPPORT HOOK EVENT*/
function productFilter(option) {
    var $target_data = $(".ajax-content-product-list");
    var $target_total = $(".ajax-content-product-total");
    // nfc.loader("show");
    //$target_data.append('<span class="loader_block"></span>');
    $('body').append('<div class="loader_mini">Loading...</div>');

    var matches = 0;
    $("#form_filter_advance .block-filter input[type=hidden]").each(function (i, val) {
        //if ($(this).val() == '1')
        matches++;
    });
    if (matches > 1)
        $('.btn-clear-all').show();
    else
        $('.btn-clear-all').hide();


    //== su ly du lieu submit
    var url = '';
    var load_more = false;
    if (option != undefined) {

        if (option.url != undefined) {
            url = option.url;
        }
        if (option.load_more != undefined) {
            load_more = option.load_more;
        }
    }
    if (url == '')
        url = $('#form_filter_advance').attr('action') + '?' + $('#form_filter_advance').serialize() + "&" + $('#form_filter_base').serialize();

    $('#form_filter_advance').ajaxSubmit({
        url: url,
        dataType: 'json',
        success: function (rs, statusText, xhr, $form) {
            history.pushState('data', '', url);
            //nfc.loader("hide");
            //$target_data.find('span.loader_block').remove()
            $('body > .loader_mini').remove();
            if (rs.status) {
                $(".ajax-filter").html();
                if (rs.filter != undefined) {
                    $(".ajax-filter").html(rs.filter);
                }

                if (load_more) {
                    // xoa phan trang va nut load more
                    $('.page-pagination').remove();
                    $target_data.find('.product-list').append(rs.content);
                }
                else {
                    //alert(2)
                    $target_data.html(rs.content);

                }
                // var go_to = $target.offset().top - 150;
                // $('html, body').animate({scrollTop: go_to}, 500);
                $target_total.html(rs.total);

                // kiem tra xem co nut next khong, neu co thi hien load more
                if ($('.page-pagination .pagination > li:last').hasClass('active')) {
                    $('.act-pagination-load-more').parent().hide();
                    return false;
                }
                return true;
            }
        },

    });
}
