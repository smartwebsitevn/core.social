<?php
if (!$is_mobile)
    $height = "500";
else
    $height = "300";
$_data_logo = function () use ($movie) {
    $status = mod("product")->setting('ads_logo_status');

    if (isset($movie->options->ads_logo_status)) {
        $status = $movie->options->ads_logo_status;
    }
    if (!$status) return;
    ob_start();
    $logo_uploaded = file_get_image_from_name(setting_get('config-logo'), public_url('site/theme/images/logo.png'));
    $logo = $logo_uploaded->url;

    ?>
    <img class="player_logo" style="position: absolute;top:10px; right: 10px; height: 40px;z-index: 101"
         src="<?php echo $logo ?>"/>

    <?php
    return ob_get_clean();
};
?>
<script src="<?php echo public_url('js/movie/movie.play_popup.js') ?>"></script>
<div id="player-popup-wraper" style="position: relative;z-index: 4">
    <?php
    $this->load->view('site/_widget/player/player_popup/' . $player);
    //echo $_data_logo();
    ?>
</div>
<?php
?>
<script type="text/javascript">
    //nen tang js:n foundation core (nen tang cot loi)
    $(document).ready(function () {
            mpfc.init();
    });

</script>

