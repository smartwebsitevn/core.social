<!-- Register -->
<section class="register">
	<div class="container">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<h2 class="cus-section-title section-title">ĐĂNG KÝ HỌC MIỄN PHÍ</h2>
				<form  class="t-form form_action" action="<?php echo site_url('contact') ?>" method="POST">
					<input name="register" type="hidden" value="1">

					<div class="form-group">
						<input name="name" type="text" class="type-long" placeholder="Họ tên" required="">
						<input name="email" type="email" class="type-short" placeholder="Email" required="">
						<div class="clearfix"></div>
						<div name="name_error" class="error"></div>
						<div name="email_error" class="error"></div>
					</div>
					<div class="form-group">
						<input name="address" type="text" class="type-long" placeholder="Địa chỉ" required>
						<input name="phone" type="tel" class="type-short" placeholder="Số điện thoại" required>
						<div class="clearfix"></div>
						<div name="address_error" class="error"></div>
						<div name="phone_error" class="error"></div>

					</div>
					<div class="form-group">
						<textarea name="message" placeholder="Lời nhắn" required></textarea>
						<div class="clearfix"></div>
						<div name="message_error" class="error"></div>
					</div>
					<button type="submit" class="btn">Đăng ký ngay</button>
				</form>
			</div>
		</div>

	</div>
</section>
