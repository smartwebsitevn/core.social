<?php
	$mr = [];

	
	$mr['withdraw'] = function() use ($renewtv, $mr)
	{
		$invoice_order = $renewtv->invoice_order;

		$body = macro()->info([
			lang('id')               => $invoice_order->id,
		    lang('code')             => $renewtv->code,
			lang('type')             => lang('renewtv_type_'.$renewtv->type),
		    lang('time')             => $renewtv->time .' '. lang('month'),
		     
			lang('amount')           => $invoice_order->{'format:amount'},

			lang('order_status')     => macro()->status_color($renewtv->status, lang('order_status_' . $renewtv->status)),
			
			lang('created')          => $invoice_order->{'format:created, full'},
			lang('message')          => $renewtv->message,
		]);


		return macro('mr::box')->box([
			'title'   => lang('title_plan_order'),
			'content' => $body,
		]);
	};

?>

<div class="row">

	<div class="col-md-12">

		<?php echo $mr['withdraw'](); ?>

	</div>

</div>