<div class="block-slide">
    <div class="carousel-once">
        <?php foreach ($slides as $row): ?>
            <div class="item" style="background-image: url('<?php echo $row->_url ?>');">
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8  col-sm-offset-2 text-center">
                            <div class="des">
                                <div class="box-content ">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
