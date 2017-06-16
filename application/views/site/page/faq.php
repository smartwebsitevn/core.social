<link href="<?php echo public_url() ?>/site/style/css/page_faq.css" media="all" type="text/css" rel="stylesheet"/>

<div class="container">
    <?php echo html_entity_decode($page->content); ?>
    <?php if ($cats): ?>
        <div id="accordion-answer" class="panel-group">
            <?php foreach ($cats as $cat): ?>
                <?php if ($cat->faqs): ?>
                    <h3 class="mb10 mt30"><?php echo $cat->name ?></h3>
                    <?php foreach ($cat->faqs as $faq): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading accordion-heading" href="#faq-answer-<?php echo $faq->id ?>" data-parent="#accordion-answer" data-toggle="collapse">
                                <h4 class="panel-title" >
                                    <a >
                                        <?php echo $faq->question ?>
                                    </a>
                                </h4>
                            </div>
                            <div class="panel-collapse collapse" id="faq-answer-<?php echo $faq->id ?>">
                                <div class="panel-body">
                                    <?php echo $faq->answer ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>

        </div>
    <?php endif; ?>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $('.accordion-heading').click(function(){
            var $this =this;
            setTimeout(function(){
                var go_to=$($this).offset().top ;
                $('html, body').animate({scrollTop:go_to},500);},500);

        })

    })
</script>
