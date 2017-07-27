<div class="block block-up-img">
	<div class="block-title">
		Hình ảnh giới thiệu về công việc
		<a title="Xóa" class="removes hide_target " data-param="images" style="width: 50px;margin-left:10px ">
			<span class="icon"></span> Xóa
		</a>
	</div>
	<div class="block-content">
		<span class="label-text">Hãy thêm 3 hình ảnh để giới thiệu thêm về môi trường làm việc hoặc văn hóa công ty của bạn</span>

		<div class="row file-up-image">
			<?php for($i=1; $i<=3; $i++){ ?>
				<div class="col-md-4 col-sm-4">
					<div class="item">
						<?php widget('site')->upload(${'upload_image'.$i}, array('temp' => 'tpl::_widget/company/upload_file/company_post_images')) ?>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
