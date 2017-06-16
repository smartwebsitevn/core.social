<?php 

return array( 
	
	/**
	 * Storage
	 */
	'storage' => 'Session', 

	/**
	 * Consumers
	 */
	'consumers' => array(

        'Facebook' => array(
            'client_id'     => '1411377835789852',
            'client_secret' => '152676073fe7dfaef4e5cf6c4cb44cf5',
            'scope'         => array('email', 'user_about_me'),
        ),
		
        'Google' => array(
            'client_id'     => '390313436090-fbpuuhmaitltgd0d086un2gsh0674p9d.apps.googleusercontent.com',
            'client_secret' => 'q_9U2Zvy0pjrEtirLtfY_OSC',
            'scope'         => array('userinfo_email', 'userinfo_profile'),
        ),

	),

);