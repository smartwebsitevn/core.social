<?php

t('lang')->load('site/user_security');

$this->register('form', function($method, array $options = array())
{
	switch ($method)
	{
		case 'password':
		{
			return $this->macro->form_password($options);
		}
		
		case 'pin':
		{
			return $this->macro->form_pin($options);
		}
		
		case 'sms_otp':
	    {
	        return $this->macro->form_sms_otp($options);
	    }
	    
	    case 'sms_odp':
        {
            return $this->macro->form_sms_odp($options);
        }
	}
});

$this->register('form_password', function(array $options = array())
{
	return macro('mr::form')->row(array_merge(array(
		'type' 	=> 'password',
		'name' 	=> lang('password'),
		'desc' 	=> lang('note_security_password'),
		'req' 	=> true,
	), $options));
});


$this->register('form_pin', function(array $options = array())
{
	return macro('mr::form')->row(array_merge(array(
		'type' 	=> 'password',
		'name' 	=> lang('pin'),
		'desc' 	=> lang('note_security_pin'),
		'req' 	=> true,
	), $options));
});

$this->register('form_sms_otp', function(array $options = array())
{
    $notice = lang('note_security_sms_otp');
    /*
    if(isset($options['resend_sms']) && $options['resend_sms'])
    {
        $notice .= ', nếu quên OTP vui lòng click <a href="'.$options['resend_sms_url'].'" class="lightbox" >vào đây</a>';
    }
    */
    return macro('mr::form')->row(array_merge(array(
        'type' 	=> 'text',
        'name' 	=> lang('sms_otp'),
        'desc' 	=> $notice,
        'req' 	=> true,
    ), $options));
});

$this->register('form_sms_odp', function(array $options = array())
{
    $notice = lang('note_security_sms_odp');
    /*
    if(isset($options['resend_sms']) && $options['resend_sms'])
    {
        $notice .= ', nếu quên ODP vui lòng click <a href="'.$options['resend_sms_url'].'"  class="lightbox" >vào đây</a>';
    }
    */
    return macro('mr::form')->row(array_merge(array(
        'type' 	=> 'text',
        'name' 	=> lang('sms_odp'),
        'desc' 	=> $notice,
        'req' 	=> true,
    ), $options));
});

