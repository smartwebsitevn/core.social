<div class="block block-up-img mt20 ">
	<div class="block-title">
		Đính kèm file
		<a title="Xóa" class="removes hide_target " data-param="files" style="width: 50px;margin-left:10px ">
			<span class="icon"></span> Xóa
		</a>
	</div>
	<div class="block-content">
		<span class="label-text">Bạn có thể đính kèm file để giới thiệu thêm cho tin tuyển dụng của bạn</span>
		<div class="profile-file " title="Tải profile" >
			<?php widget('site')->upload($upload_files, array('temp' => 'tpl::_widget/company/upload_file/company_post_files')) ?>
		</div>

	</div>
</div>
