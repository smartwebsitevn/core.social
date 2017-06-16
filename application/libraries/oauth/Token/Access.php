<?php
/**
 * OAuth2 Token
 *
 * @package    OAuth2
 * @category   Token
 * @author     Phil Sturgeon
 * @copyright  (c) 2011 HappyNinjas Ltd
 */

class OAuth2_Token_Access extends OAuth2_Token
{
	/**
	 * @var  string  access_token
	 */
	protected $access_token;

	/**
	 * @var  int  expires
	 */
	protected $expires;

	/**
	 * @var  string  refresh_token
	 */
	protected $refresh_token;

	/**
	 * @var  string  uid
	 */
	protected $uid;

	/**
	 * Sets the token, expiry, etc values.
	 *
	 * @param   array  $options   token options
	 *
	 * @throws Exception if required options are missing
	 */
	public function __construct(array $options = null)
	{
		if ( ! isset($options['access_token']))
		{
		    $new_options = array();
		    foreach($options as  $key => $value)
		    {
		         $new_options = json_decode($key,true);
		    }
		}else{
			$new_options = $options;
		}
		
	    if ( ! isset($new_options['access_token']))
	    {
	        throw new Exception('Required option not passed: access_token'.PHP_EOL.print_r($new_options, true));
	    }
	
	    // if ( ! isset($options['expires_in']) and ! isset($options['expires']))
	    // {
	    //  throw new Exception('We do not know when this access_token will expire');
	    // }
	
	
	    $this->access_token = $new_options['access_token'];
	
	    // Some providers (not many) give the uid here, so lets take it
	    isset($new_options['uid']) and $this->uid = $new_options['uid'];
	
	    //Vkontakte uses user_id instead of uid
	    isset($new_options['user_id']) and $this->uid = $new_options['user_id'];
	
	    //Mailru uses x_mailru_vid instead of uid
	    isset($new_options['x_mailru_vid']) and $this->uid = $new_options['x_mailru_vid'];
	
	    // We need to know when the token expires, add num. seconds to current time
	    isset($new_options['expires_in']) and $this->expires = time() + ((int) $new_options['expires_in']);
	
	    // Facebook is just being a spec ignoring jerk
	    isset($new_options['expires']) and $this->expires = time() + ((int) $new_options['expires']);
	
	    // Grab a refresh token so we can update access tokens when they expires
	    isset($new_options['refresh_token']) and $this->refresh_token = $new_options['refresh_token'];
	}

	/**
	 * Returns the token key.
	 *
	 * @return  string
	 */
	public function __toString()
	{
		return (string) $this->access_token;
	}

} // End OAuth2_Token_Access