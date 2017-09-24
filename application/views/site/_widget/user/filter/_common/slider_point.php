<div class="block-slider">
    <input name="point" class="act-filter-slider" id="slider_point_hander" type="hidden"
           data-provide="slider"
           data-slider-min="0"
           data-slider-max="100"
           data-slider-step="10"
           data-slider-value="0" data-slider-tooltip="hide"/>
    <div class="clearfix"></div>
    <span id="slider_point">Trên <span id="slider_point_value">0</span> point</span>

</div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#slider_point_hander").slider();
        $("#slider_point_hander").on("slide", function (slideEvt) {
            $("#slider_point_value").text(slideEvt.value);
        });
    })
</script>