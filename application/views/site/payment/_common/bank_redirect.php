<style >
.form-banking{
  padding:0 20px;
  background: #fff;
}


.form-banking h2{
  text-transform:uppercase;
  font-size: 17px;
}


.transfer_tut{
	margin:10px 0;
	line-height: 1.5em;
}
.tut_content{
	padding:0 20px;
}
ul.list2 li {
    background:none;
    border: 0 none;
    line-height: 16px;
    margin: 5px 0;
    padding: 0 0 0 10px;
    display:block;
    width:100%
}
ul.list2 li span {
    float: left;
    width: 200px;
}
</style>

<div class="t-box">
	<div class="box-title">
		<h1><?php echo lang('payment_'.$bank['code'].'_notice')?></h1>
	</div>
	
	<div class="box-content">
		<div class="form-banking">
<?php //pr($tran);?>			
	<div class="transfer_tut">
		<div class="tut_title blue">1. <?php echo lang('bank_hint_login_your_account',lang('payment_'.$bank['code']))?>:</div>
		<div class="tut_content link">
			<a class="fontB f14" target="_blank" href="<?php echo $bank['url_bank']?>">
				<?php echo $bank['url_bank']?>
			</a>
		</div>
	</div>
	
	<div class="transfer_tut">
		<div class="tut_title blue">2. <?php echo lang('bank_hint_tranfer_to')?>:</div>
		<div class="tut_content">
			<ul class="list2">
				<li>
					<span><?php echo lang('bank_hint_tranfer_to_acc_num')?>:</span>
					<font class="fontB f13 green"><?php echo $bank['acc']?></font>
					<div class="clear"></div>
				</li>
				<li>
					<span><?php echo lang('bank_hint_tranfer_to_acc_name')?>:</span>
					<font class="fontB f13 green"><?php echo $bank['acc_name']?></font>
					<div class="clear"></div>
				</li>
				<li>
					<span><?php echo lang('bank_hint_tranfer_to_amount')?>:</span>
					<font class="fontB f13 green"><?php echo $amount?></font>
					<div class="clear"></div>
				</li>
				<li>
					<span style="width:auto;"><?php echo lang('bank_hint_tranfer_to_content')?>:</span>
					<div class="clear"></div>
				</li>
			</ul>
			<div class="textC mt20">
				<input type="text" value="<?php echo $bank['content_tranfer']?>" onclick="this.select();" style="width:50%; color:#f60;font-weight:bold;height:35px;border:1px solid #ececec" class="textC">
			</div>
		</div>
	</div>
	<div class="transfer_tut">
		<div class="tut_title blue">3. <?php echo lang('bank_hint_confirm')?>:</div>
		<div class="tut_content"> <?php echo lang('bank_hint_confirm_do')?></div>
	</div>
    	<div class="textL">
	    <font class="fontB f13" style="color:red">Lưu ý: Phí người chuyển trả</font>
	</div>
	<div class="clear"></div>
	<div class="textC">
		<a class="button button-border medium green f" href="<?php echo $bank['url_payment']?>"><?php echo lang('bank_tran_confirm')?></a>
	</div>
</div>
	</div>
</div>
