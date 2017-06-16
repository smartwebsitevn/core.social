<?php
$product_order_quick= $this->config->item('product_order_quick','main');// che do dat hang nhanh

$info = isset($data['info_delivery'])?$data['info_delivery']:NULL;
//pr($data);
?>

<?php if(!$product_order_quick):?>
<ul class="checkout-steps">
    <li class="step1 <?php echo ($step==1)?'current':''?>  begin">
        <span class="number">1</span>
        <a href="<?php echo ($step>1)?site_url('cart'):'#';?>"><span class="name">Giỏ hàng</span></a>
    </li>
    <li class="step2 <?php echo ($step==2)?'current':''?>  ">
        <span class="number">2</span>
        <a href="<?php echo ($step>2 || isset($info['name']))?site_url('checkout').'?modify=1':'#';?>"><span class="name">Thông tin tài khoản</span></a>
    </li>
    <li class="step3 <?php echo ($step==3)?'current':'#'?> ">
        <span class="number">3</span>
        <a href="<?php echo ($step>3 || isset($info['name']))?site_url('checkout/confirm'):'#';?>"> <span class="name">Xác nhận thông tin</span></a>
    </li>
    <li class="step4 <?php echo ($step==4)?'current':'#'?> finish">
        <span class="number">4</span>
        <a href="#"> <span class="name">Hoàn thành</span></a>
    </li>
  </ul>
<?php else:?>
 <ul class="checkout-steps">
    <li class="step1 <?php echo ($step==1)?'current':''?>  begin">
        <span class="number">1</span>
        <a href="<?php echo ($step>1)?site_url('cart'):'#';?>"><span class="name">Giỏ hàng</span></a>
    </li>
    <li class="step2 <?php echo ($step==2)?'current':''?>  ">
        <span class="number">2</span>
        <a href="<?php echo ($step>2 || isset($info['name']))?site_url('checkout').'?modify=1':'#';?>"><span class="name">Thông tin tài khoản</span></a>
    </li>
    <li class="step3 <?php echo ($step==3)?'current':'#'?> finish">
        <span class="number">3</span>
        <a href="#"> <span class="name">Hoàn thành</span></a>
    </li>
 </ul>
<?php endif;?>
