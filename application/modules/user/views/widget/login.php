<?php

if ( ! user_is_login())
{
	echo macro('tpl::user/macros')->login();
}
