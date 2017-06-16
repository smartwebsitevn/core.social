<div class="row">
	<div class="col-md-12">
		<h3 class="gv-title"><?php echo lang('author_introduce') ?></h3>
	</div>
	<div class="col-md-12">

	<div class="check-box">
            <div class="check-box-information">
                <a href="<?php echo $author->_url_view ?>" class="pull-left" title="">
                    <img src="<?php echo $author->avatar->url ?>" alt="" width="150px" />
                </a>
                <p class="name"><big><?php echo $author->name ?></big></p>
                <p><i><small><?php echo $author->profession ?></small></i></p>
                <?php echo $author->desc ?>
            </div>
            <div class="clear"></div>
	</div>
	</div>
</div>



<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('my_products') ?></h3>
	</div>
	<div class="panel-body">
		<?php
		if( $products )
		{
			?>
				<?php  widget('product')->display_list($products); ?>
			<?php
		}
		else
		{
			?>
			<p><?php echo lang('i_have_no_products') ?></p>
			<?php
		}
		?>
	</div>
</div>
<?php /* ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo lang('my_lessons') ?></h3>
	</div>
	<div class="panel-body">
		<?php
		if( $lessons )
		{
			?>
			<ul class="list-product">
				<?php echo widget('lesson')->lesson_list( $lessons, $authors, true ) ?>
			</ul>
			<?php
		}
		else
		{
			?>
			<p><?php echo lang('i_have_no_lessons') ?></p>
			<?php
		}
		?>
	</div>
</div>
 <?php */ ?>

<br class="clear" />