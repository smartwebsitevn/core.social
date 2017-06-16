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
                /*=========== CART ============*/
                $('body').on('click', 'a.product-empty-cart', function (e) {
                    if (confirm('Bản có muốn xóa toàn bộ sản phẩm trong giỏ hàng?')) {
                        $.ajax({
                            type: "POST", dataType: "JSON", async: false, url: $(this).attr('data_url'),
                            success: function (data) {
                                productUpdateCart(data);
                            }
                        });
                        return false;
                    }
                    return false;
                });
                $('body').on('click', 'a.product-delete-cart', function (e) {
                    //$("a.product-delete-cart").on("click",function(){
                    if (confirm('Bản có muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                        var product_key = $(this).attr("data_product_key");
                        $.ajax({
                            type: "POST", async: false, url: $(this).attr('data_url'),
                            data: {"rowid": product_key},dataType: "JSON",
                            success: function (data) {
                                productUpdateCart(data);

                            }
                        });
                    }
                });
                $('body').on('click', 'a.product-update-cart', function (e) {
                    //  $("a.product-update-cart").on("click",function(){

                    var product_key = $(this).attr("data_product_key");
                    var qty = $("#product-qty-key-" + product_key).val();
                    // neu co so luong thi kiem tra xem so co hop le
                    if (isNaN(qty)) {
                        alert(qty + "Số lượng sản phẩm không hợp lệ");
                        return false;
                    }
                    $.ajax({
                        type: "POST",
                        dataType: "JSON",
                        async: false,
                        url: $(this).attr('data_url'),
                        data: {"rowid": product_key, "qty": qty},
                        success: function (data) {
                            productUpdateCart(data);

                        }
                    });
                });
                $('body').on('click', 'a.product-apply-coupon-cart', function (e) {
                    //$("a.product-apply-coupon-cart").on("click",function(){
                    var code = $("#product-coupon-cart").val();
                    // neu co so luong thi kiem tra xem so co hop le
                    if (isNaN(code)) {
                        AC_messageShow("Mã không hợp lệ", 'notice')
                        return false;
                    }
                    $.ajax({
                        type: "POST", dataType: "JSON", async: false, url: $(this).attr('data_url'), data: {"code": code},
                        success: function (rs) {
                            if (rs.complete) {
                                AC_messageClear();
                                $('#cart-info.page-content').html(rs.data);
                            }
                            else
                                AC_messageShow(rs.message, 'notice')
                        }
                    });
                });
                $('body').on('click', '.quantity-up-down-select .up', function () {
                    if( $(this).hasClass("disable")) return;
                    up_down_quantity(1)
                    $('.quantity-up-down-select .down').removeClass("disable")
                });
                $('body').on('click', '.quantity-up-down-select .down', function () {
                    if( $(this).hasClass("disable")) return;
                    $('.quantity-up-down-select .up').removeClass("disable")
                    up_down_quantity(0)
                });
                $('body').on('change', '.quantity-up-down-select .quantity-select', function () {
                    up_down_quantity(2)
                });
                function up_down_quantity(type){
                    var  $select = $(".quantity-up-down-select .quantity-select");
                    var total = $select.find("option").length
                    var current =  $select.prop('selectedIndex') +1;
                    if(type == 0)
                        current--
                    else if(type == 1)
                        current++
                    if(current <= total && current>=1)
                    {
                        $select.val(current);
                    }

                    if(current == total)
                    {
                        $('.quantity-up-down-select .up').addClass("disable")
                        $('.quantity-up-down-select .down').removeClass("disable")
                    }
                    if(current == 1)
                    {
                        $('.quantity-up-down-select .down').addClass("disable")
                        $('.quantity-up-down-select .up').removeClass("disable")
                    }

                    if(current > 1 && current<total)
                    {
                        $('.quantity-up-down-select .down').removeClass("disable")
                        $('.quantity-up-down-select .up').removeClass("disable")
                    }
                }
                // thay doi

                $('#product_form_action').nstUI('formActionAdv', {
                    event_complete: function (data) {
                        productUpdateCart(data);

                    }
                });

                /*=========== CHANGE PRICE BY OPTION AND QUANTITY */
                $("#product_form_action .product-options select").on('change', function () {
                    //alert(2);
                    productChangeOptionPrice();

                });
                $("#product_form_action .product-options :input[type=checkbox]").on('click', function () {
                    productChangeOptionPrice();
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
                /*=========== Compare ============*/
                $("a.add-to-compare ").bind("click", function () {
                    var compare = $(this).attr('data-compare');
                    $.ajax({
                        async: false,
                        type: "POST",
                        url: $(this).attr('data-url'),
                        success: function (data) {
                            if (data.count_compare > 1)
                                $.colorbox({width: '90%', height: '90%', href: compare});
                            else
                                alert(data.msg);

                        }
                    });
                });
                $('#compare-info select[class="product-name"]').on('change', function () {
                    var id_new = this.value;
                    var id_old = $(this).attr('data-id');
                    var id_col = $(this).attr('data-col');
                    if (id_new) {
                        $.ajax({
                            async: false,
                            type: "POST",
                            url: $('#compare-info').attr('data-url'),
                            data: {'id_new': id_new, 'id_old': id_old, 'id_col': id_col},
                            success: function (data) {
                                $("#_tmp").html(data);
                                $('#product-compare #compare-info').html($("#_tmp").find('#product-compare #compare-info').html());
                                $("#_tmp").html('');
                            }
                        });
                    }
                });
            });
        },

    },
    filter: function (option) {
        productFilter(option)
    },
}
product_nfc.boot();

/* FUNTIONS SUPPORT HOOK EVENT*/
function productUpdateCart(data) {
    var $modal_cart = $('#modal-cart')
    if (data.cart_mini != undefined) {
        $('#product-cart-mini').html(data.cart_mini);
    }
    if (data.cart != undefined) {
        $modal_cart.find('.modal-dialog').css("width",'90%');
        $modal_cart.find('.modal-body').html(data.cart);
        // hien thong bao
        $modal_cart.modal('show')
    }
    else if (data.checkout != undefined) {
        $modal_cart.find('.modal-dialog').css("width",'40%');
        $modal_cart.find('.modal-title').html("Thông tin đặt hàng");
        $modal_cart.find('.modal-body').html(data.checkout);
        // hien thong bao
        $modal_cart.modal('show')
    }
    else{
        $modal_cart.find('.modal-dialog').css("width",'40%');
        $modal_cart.modal('hide')
    }
    /* $('html, body').animate({ scrollTop: 0 }, 'slow');  */
    //$.colorbox({width:'75%',height:'90%',href:$('#cart #shopping-cart-mini a').attr('href')});
    //$.colorbox({width:'85%',height:'90%',href:$('#shopping-cart-mini').attr('href')});
    //  window.parent.location = $('#shopping-cart-mini').attr('href');
    //window.parent.location =base_url+'checkout';
    nfc.server_response(data);

}
function productChangeOptionPrice() {
    $('#product_form_action #price-total-wraper').append('<span class="loader_block"></span>');
    $('#product_form_action').ajaxSubmit({
       // url: $('#product_form_action').attr('_action_sub'),
        data: {'_submit': true,'update_price':true},
        dataType: 'json',
        success: function (data, statusText, xhr, $form) {
           // alert(data.total);
            $('#product_form_action #price-total-wraper').find('span.loader_block').remove()
            if (data.total) {
                $('#product_form_action #price-total').html(data.total);
            }

        },

    });
}
function productFilter(option) {
    //alert(option.url)
    var $target_data = $(".ajax-content-product-list");
    var $target_total = $(".ajax-content-product-total");
    // nfc.loader("show");
    //$target_data.append('<span class="loader_block"></span>');
    $('body').append('<div class="loader_mini">Loading...</div>');

    var matches = 0;
    $("#form_filter_advance .block-filter input[type=hidden]").each(function(i, val) {
        //if ($(this).val() == '1')
        matches++;
    });
    if(matches>1)
        $('.btn-clear-all').show();
    else
        $('.btn-clear-all').hide();


    //== su ly du lieu submit
    var url='';
    var load_more =false;
    if (option != undefined) {

        if (option.url != undefined) {
            url =option.url;
        }
        if (option.load_more != undefined) {
            load_more =option.load_more;
        }
    }
    if(url == '')
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
                if(load_more){
                    // xoa phan trang va nut load more
                    $('.page-pagination').remove();
                    // alert(1)
                    $target_data.find('.product-list').append(rs.content);
                }
                else{
                    //alert(2)
                    $target_data.html(rs.content);

                }
                // var go_to = $target.offset().top - 150;
                // $('html, body').animate({scrollTop: go_to}, 500);
                $target_total.html(rs.total);

                // kiem tra xem co nut next khong, neu co thi hien load more
                if($('.page-pagination .pagination > li:last').hasClass('active')){
                    $('#act-pagination-load-more').parent().hide();
                    return false;
                }
                return true;
            }
        },

    });
}