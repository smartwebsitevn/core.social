<?php
if ( ! user_is_login())
{
	echo macro('mr::meta')->login();
}
