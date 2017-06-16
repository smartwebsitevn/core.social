
<style>
.payment_banking .box-content {
    width: 700px;
    min-height: 300px;
}
.payment_banking .list_banking {
    margin: -10px 0 0 -10px;
}
.payment_banking .list_banking .item {
    float: left;
    margin: 10px 0 0 10px;
    border: 1px solid #e8e8e8;
    border-radius: 3px;
}
.payment_banking .list_banking .item:hover {
    border-color: #01a5e1;
}
.payment_banking .list_banking .item img {
    width: 100px;
    height: 50px;
}
</style>


<div class="t-box payment_banking" id="main_popup">
	<div class="box-title">
		<h1><?php echo lang('payment_banking_notice'); ?></h1>
	</div>
	
	<div class="box-content">
		
		<div class="list_banking">
        
            <?php foreach ($banks as $row): ?>
               	
                <div class="item">
                    <a href="<?php echo $row->_url_pay; ?>" title="<?php echo $row->name; ?>"
				        onclick="lightbox(this); return false;"
                    ><img src="<?php echo $row->image->url; ?>"></a>
                </div>
            
            <?php endforeach; ?>
        
		</div>
		
		<div class="clear"></div>
	</div>
</div>