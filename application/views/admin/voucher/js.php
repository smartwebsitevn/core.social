<script type="text/javascript">

    (function ($) {
        $(document).ready(function () {
            $(".select_multi").select2({
                formatResult: function(opt) {
                    if (!opt.id) {
                        return opt.text;
                    }
                    var optimage = $(opt.element).data('image');
                    if(!optimage){
                        return opt.text;
                    } else {
                        var $opt = $(
                            '<span><img src="' + optimage + '"  /> ' + opt.text + '</span>'
                        );
                        return $opt;
                    }},
                formatSelection: function(opt) {
                    if (!opt.id) {
                        return opt.text;
                    }
                    var optimage = $(opt.element).data('image');
                    if(!optimage){
                        return opt.text;
                    } else {
                        var $opt = $(
                            '<span><img src="' + optimage + '"  /> ' + opt.text + '</span>'
                        );
                        return $opt;
                    }}
            });
        });
    })(jQuery);
</script>