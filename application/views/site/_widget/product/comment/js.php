<script type="text/javascript">
    nfc.reboot();

    function commentPagination(option) {
        var $wraper = $(option.ele).closest('.comment-list-wraper')

        // load_ajax()
        $('body').append('<div class="loader_mini">Loading...</div>');
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
        $.ajax({
            async: false,
            type: "GET",
            url: url,
            success: function (data) {
                $('body > .loader_mini').remove();
                if (load_more) {
                    // xoa phan trang va nut load more
                    $wraper.find('.page-pagination').remove();
                    $wraper.find('.list-unstyled').append(data);
                }
                else {
                    //alert(2)
                    $wraper.html(data);

                }

                // kiem tra xem co nut next khong, neu co thi hien load more
                if ($wraper.find('.page-pagination .pagination > li:last').hasClass('active')) {
                    $wraper.find('.act-pagination-load-more').parent().hide();
                    return false;
                }
                return true;
            }
        });

    }
</script>
