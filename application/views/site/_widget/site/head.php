<?php
$asset = public_url();
$asset_theme = $asset . '/site/theme/';
?>
<meta charset="utf-8">

<title><?php echo $title; ?></title>

<meta name="title" content="<?php echo $title; ?>"/>
<meta name="description" content="<?php echo $description; ?>"/>
<meta name="keywords" content="<?php echo $keywords; ?>"/>
<meta name="robots" content="<?php echo $robots; ?>"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php echo $meta_other; ?>

<link href="<?php echo $icon ?>" rel="shortcut icon" type="image/x-icon"/>
<!-- Add custom CSS here -->
<link rel="stylesheet" href="<?php echo public_url('site/css/css.css') ?>">
<link href="<?php echo $asset_theme ?>css/main.css" media="all" type="text/css" rel="stylesheet"/>

<?php if (isset($css) && $css): ?>
      <?php if (is_array($css)): ?>
            <?php foreach ($css as $cs): ?>
                  <link rel="stylesheet" type="text/css" href="<?php echo $asset_theme ?>css/<?php echo $cs ?>.css">
            <?php endforeach; ?>
      <?php else: ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $asset_theme ?>css/<?php echo $css ?>.css">
      <?php endif; ?>
<?php endif; ?>

<!-- End custom CSS here -->

<!-- Core Js -->
<script src="<?php echo public_url('js/jquery/jquery.min.js') ?>"></script>
<script type="text/javascript" src="<?php echo public_url('js') ?>/jquery/jquery-ui/jquery-ui.min.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo public_url('js'); ?>/jquery/jquery-ui/jquery-ui.theme.min.css"
      media="screen"/>
<script src="<?php echo public_url('js/bootstrap/core/js/bootstrap.min.js') ?>"></script>
<script src="<?php echo public_url('js/angular/angular.min.js') ?>"></script>
<script src="<?php echo public_url('js/angular/angular-ng-modules/angular-ng-modules.js') ?>"></script>

<!-- Countdown -->
<script src="<?php echo public_url('js/jquery/countdown/jquery.countdown.js') ?>"></script>
<!-- Cookie -->
<script src="<?php echo public_url('js/jquery/cookie/jquery.cookie.js') ?>"></script>