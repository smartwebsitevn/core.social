<?php

	$banks = array(
		'VCB' => 'Vietcombank',
		'DAB' => 'Ngan Hang Dong A',
		'VTB' => 'Vietinbank',
		'TCB' => 'Techcombank',
		'SGB' => 'Ngan hang Sai Gon Cong Thuong',
		'AGB' => 'Agribank',
		'BIDV' => 'BIDV',
		'EIB' => 'Eximbank',
		'ACB' => 'Ngan hang A Chau (ACB)',
		'SCB' => 'Sacombank',
		'MRTB' => 'Maritime Bank',
		'VIB' => 'Ngan hang Quoc Te',
		'MB' => 'Ngan Hang Quan Doi',
		'VPB' => 'VPBANK- Ngan Hang Viet Nam Thinh Vuong',
		'OCEB' => 'Ngan Hang TMCP dai Duong',
		'HDB' => 'HD Bank',
		'VAB' => 'Viet A',
		'NVB' => 'Ngan Hang thuong mai co phan Nam Viet',
		'ABB' => 'Ngan hang An Binh',
		'GPB' => 'GPBank - Ngan Hang Thuong Mai Co Phan Toan Cau',
		'NAB' => 'Ngan hang Nam A',
		'PGB' => 'Ngan hang TMCP Xang Dau Petrol Limex',
		'OCB' => 'Ngan Hang Phuong Dong',
	);
	
?>


<link rel="stylesheet" href="<?php echo public_url('site/pay123/css.css'); ?>" type="text/css" />


<div class="pay123">

	<div class="pay123_box">
		<h4 class="pay123_banks_title">Thẻ ATM nội địa/Internet Banking</h4>
		
		<div class="pay123_banks">
		
			<?php foreach ($banks as $id => $name): ?>
			
				<div class="item">
				
					<a href="<?php echo $payment->url.'?bank=123P'.$id; ?>"
						title="<?php echo $name; ?>"
					><img src="<?php echo public_url('site/pay123/banks/'.strtolower($id).'.png'); ?>"></a>
				
				</div>
			
			<?php endforeach; ?>
			
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>
	
</div>
