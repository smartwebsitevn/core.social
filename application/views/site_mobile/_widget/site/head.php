<?php
$asset = public_url();
$asset_theme = $asset . '/site/theme/';
?>
<meta charset="utf-8">

<title><?php echo $_SEO->title; ?></title>

<meta name="title" content="<?php echo $_SEO->title; ?>"/>
<meta name="description" content="<?php echo $_SEO->description; ?>"/>
<meta name="keywords" content="<?php echo $_SEO->keywords; ?>"/>
<?php if($_SEO->robots): ?>
      <meta name="robots" content="<?php echo $_SEO->robots; ?>"/>
<?php endif; ?>
<!-- B_SEO FACE -->
<meta property="og:site_name" content="<?php echo $_SEO->title ?>" >
<meta property="og:url" content="<?php echo $_SEO->url ?>" />
<meta property="og:title" content="<?php echo  $_SEO->title;?>" />
<meta property="og:description" content="<?php echo  $_SEO->title ?>" />
<meta property="og:type" content="Website<?php //echo $_OG['type'] ?>" />
<?php if(isset( $_SEO->image)):?>
      <meta property="og:image" content="<?php echo  $_SEO->image ?>" />
<?php endif;?>
<!-- E_SEO FACE -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php echo  $_SEO->meta_other; ?>

<link href="<?php echo $_SEO->icon ?>" rel="shortcut icon" type="image/x-icon"/>
<!-- Add custom CSS here -->
<link rel="stylesheet" href="<?php echo public_url('site/css/css.css') ?>">
<link rel="stylesheet" href="<?php echo public_url('img/icons/icons.css') ?>">
<link href="<?php echo $asset_theme ?>css/main.css" media="all" type="text/css" rel="stylesheet"/>
<link href="<?php echo $asset_theme ?>css/mobile.css" media="all" type="text/css" rel="stylesheet"/>


<?php if (isset($_ASSET->css) && $_ASSET->css): ?>
      <?php if (is_array($_ASSET->css)): ?>
            <?php foreach ($_ASSET->css as $cs): ?>
                  <link rel="stylesheet" type="text/css" href="<?php echo $asset_theme ?>css/<?php echo $cs ?>.css">
            <?php endforeach; ?>
      <?php else: ?>
            <link rel="stylesheet" type="text/css" href="<?php echo $asset_theme ?>css/<?php echo $_ASSET->css ?>.css">
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
<script src="<?php echo public_url('js/lodash.js') ?>"></script>
<!-- Countdown -->
<script src="<?php echo public_url('js/jquery/countdown/jquery.countdown.js') ?>"></script>
<!-- Cookie -->
<script src="<?php echo public_url('js/jquery/cookie/jquery.cookie.js') ?>"></script>