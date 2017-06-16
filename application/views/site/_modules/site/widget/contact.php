<!-- Register -->
<section id="register-form">
	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<h2 class="title"><?php echo $widget->name ?></h2>
				<div class="intro"><?php echo $widget->setting["intro"] ?></div>
				<form  class="t-form form_action" action="<?php echo site_url('contact') ?>" method="POST">
					<input name="type" type="hidden" value="order">
					<div class=" form-text row">
						<div class="col-md-4">
							<input name="name" value="" placeholder="Họ và Tên " class="form-control" type="text" required>
							<div class="clear"></div>
							<div name="name_error" class="error"></div>
							<input name="email" value="" placeholder="Email" class="form-control" type="text" required>

							<div class="clear"></div>
							<div name="email_error" class="error"></div>
							<input name="phone" value="" placeholder="Điện thoại" class="form-control" type="tel" required>

							<div class="clear"></div>
							<div name="phone_error" class="error"></div>
						</div>
						<div class="col-md-8">
							<textarea name="message" placeholder="Lời nhắn" rows="3" class="form-control" required></textarea>

							<div class="clear"></div>
							<div name="message_error" class="error"></div>
						</div>
						<div class="col-md-12">
							<button type="submit" class="btn btn-default">Gửi yêu cầu</button>
						</div>
					</div>



				</form>
			</div>
		</div>

	</div>
</section>
