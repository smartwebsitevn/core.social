<div class="currency_list">
     <?php foreach ($currency_list as $row):?>
         <a href="<?php echo $row->_url_change?>" _url="<?php echo $row->_url_change?>" title="<?php echo $row->name?>" class="change_currency <?php echo ($currency_cur->id == $row->id) ? 'active' : ''?>">
             <?php echo $row->code?>
         </a>
     <?php endforeach;?>  
</div>