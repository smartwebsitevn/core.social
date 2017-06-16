<!DOCTYPE html>
<html lang="en">

<head>
	<?php widget('site')->head(array() ,'site/deposit_card_api/head'); ?>
</head>

<body>

<div class="page-container">

	<div class="container">
		<div class="block-s2">

			<div class="row">
				<div class="col-md-9">
					<?php view('tpl::deposit_card_api/index', $this->data); ?>
				</div>
			</div>

		</div>
	</div>

</div>

<?php view('tpl::deposit_card_api/js') ?>

</body>

</html>
