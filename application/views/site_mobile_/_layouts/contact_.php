<!DOCTYPE html>
<html>
<head>
    <?php widget('site')->head(); ?>
</head>
<body  >
<?php
get_time_between()
$map_x =module_get_setting('site','google_map_x');
$map_y =module_get_setting('site','google_map_y');
$map_key =module_get_setting('site','google_api_key');
?>
<div class="wrapper">
    <?php echo $header; ?>
    <!-- MAIN -->
    <div id="main">
<?php  ?>
        <div id="map" style="width: 100%; height: 300px;">Google Map</div>
 <?php  ?>

        <div class="container">
            <?php echo $content_top; ?>
            <?php echo $content; ?>
            <?php echo $content_bottom; ?>
        </div>
    </div>
    <?php echo $footer; ?>
</div>
<?php view('tpl::_widget/site/js') ?>
<?php /* ?>
<script  src="https://maps.googleapis.com/maps/api/js?key=<?php echo $map_key ?>&sensor=true&v=3"></script>
<script type="text/javascript" src="<?php echo public_url('js') ?>/jquery/gmaps/gmaps.min.js"></script>
<script type="text/javascript">

    $(document).ready(function(){
        map = new GMaps({
            el: '#map',
            lat: 34.043333,
            lng: -78.028333

        });
        map.addMarker({
            lat: 34.042,
            lng: -78.028333,
            title: 'Marker with InfoWindow',
            infoWindow: {
                content: '<p>Your Content</p>'
            }
        });
    });
</script>

<?php
*/
$map_x =module_get_setting('site','google_map_x');
$map_y =module_get_setting('site','google_map_y');
$map_key =module_get_setting('site','google_api_key');
?>
<script>
    function initMap() {
        var uluru = {lat: -25.363, lng: 131.044};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 4,
            center: uluru
        });
        var marker = new google.maps.Marker({
            position: uluru,
            map: map
        });
    }
    function MAP_initialize() {
        var default_pos=new google.maps.LatLng(20.85037,106.690804);
        var mapOptions = {
            center:  default_pos,
            zoom: 15,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoomControl: false,
            panControl: false,
        };
        map = new google.maps.Map(document.getElementById("map"),  mapOptions);
        google.maps.event.addListener(map, 'click', function( event)
        {
            // N?u ?ã ??t icon r?i thì ph?i MAP_cancelMarker
            if (!marker_placed)
            {
                MAP_placeMarker(event.latLng);
                marker_placed = true;
            }
        });
    }//end intialize
</script>

<script  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAIgxQ8wvt9UPQRlQYIdbGyvyRo7TtDR6Y&sensor=true&v=3"></script>


<?php ?>
</body>
</html>

