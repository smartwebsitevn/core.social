<div class="container">
    <div class="data-wraper">
        <?php echo widget('product')->filter([], "top") ?>
        <div class="block-products-items">
            <div class="block-content ajax-content-product-list">
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        productFilter({'url': '<?php echo site_url('product_list')?>'});
    });
</script>