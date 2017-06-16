<div class="container" >
    <div class="panel panel-default" >
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-credit-card"></i> <?php echo lang('order_successfully') ?></h3>
        </div>
        <div class="panel-body" > 
            <div class="alert alert-success">
                <p><?php echo lang('congratulate') ?> <strong><?php echo $invoice->info_contact->name ?></strong>,</p>
                <p><?php echo lang('book_successful_order').' '.$invoice->_id ?>.</p>
            </div>
        </div>
        <div class="panel-footer" > 
            <a href="<?php echo base_url() ?>" class="btn btn-default pull-right" ><?php echo lang('home_page') ?></a>
            <div class="clearfix"></div>
        </div>
    </div>
</div>