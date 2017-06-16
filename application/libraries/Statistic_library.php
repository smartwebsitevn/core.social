<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistic_library {
	
	var $CI = '';

	function __construct()
	{
		$this->CI =& get_instance();

	}


    /**
     * HANDER - THONG KE LUOT TRUY CAP
     **/
    function counter(){
      	/* ------------------------------------------------------------------------------------------------ */
       	// From minutes to seconds
    	$sessiontime	=	10 * 60;  // thoi gian de xac dinh la 1 phien, neu qua thi tinh la 1 luot view moi
		// Detect Guest's IP Address and Insert new records
		$ip = "0.0.0.0";
		if(!empty($_SERVER['REMOTE_ADDR']))
            $ip = $_SERVER['REMOTE_ADDR'];

		// Now we are checking if the ip was logged in the database.
		// Depending of the value in minutes in the sessiontime variable.
		// Check session time, insert new record if timeout
		$this->insertVisitor($sessiontime, $ip, now() );

		/* ------------------------------------------------------------------------------------------------ */
    }

    function getVisitorsToday(){
        $today= get_time_between(get_date());
        $lasttime =	$today[0] ;
        //echo '<br>today:'.get_date($lasttime,'full');
        return 	$this->getVisitors($lasttime);
	}
	 function getVisitorsYesterday(){
        $today= get_time_between(get_date());
        $lasttime =	strtotime( "-1 day",$today[0] ) ;
        //echo '<br>ystery1:'.get_date($today[0],'full');
        //echo '<br>ystery2:'.get_date($lasttime,'full');
        return 	$this->getVisitors($lasttime,$today[0]);
	}
    function getVisitorsWeek(){
        $today= get_time_between(get_date());
        $lasttime =	strtotime( "-1 week",$today[0] ) ;
        
        return 	$this->getVisitors($lasttime);
	}
    function getVisitorsMonth(){
        $today= get_time_between(get_date());
        $lasttime =	strtotime( "-1 month",$today[0] ) ;
        return 	$this->getVisitors($lasttime);
	}

	/* ------------------------------------------------------------------------------------------------ */

	/*
	** Get Total of Visisitors Until $time
	*/
	/* ------------------------------------------------------------------------------------------------ */
	function getAllVisitors(){
        // Total visitors
    	$this->CI->db->from('visit_counter');
		return $this->CI->db->count_all_results();

	}
	/* ------------------------------------------------------------------------------------------------ */



	/*
	** Get Number of Visisitors from $timestart to $timestop
	*/
	/* ------------------------------------------------------------------------------------------------ */
	function getVisitors( $timestart = 0, $timestop = 0 ){
        // echo '<br>query $timestart='.$timestart;
		$visitors	=	0;

//		if ( $timestart < BIRTH_DAY_JOOMLA ) $timestart = 0;
//		if ( $timestop < BIRTH_DAY_JOOMLA ) $timestop = 0;

		$query			=	' SELECT COUNT(*) as num FROM visit_counter ';

		if ( !$timestart ){
			if ( !$timestop ) return 0;
			$query		.= ' WHERE tm < ' . $this->CI->db->escape( $timestop );
		}
		else{
			if ( !$timestop ){
				$query		.= ' WHERE tm >= ' . $this->CI->db->escape( $timestart );
			}
			else{

				if ( $timestop == $timestart ){
					$query		.= ' WHERE tm = ' . $this->CI->db->escape( $timestart );
				}

				if ( $timestop > $timestart ){
					$query		.= ' WHERE tm >= ' . $this->CI->db->escape( $timestart );
					$query		.= ' AND tm < ' . $this->CI->db->escape( $timestop );
				}

				if ( $timestop < $timestart ){
					$query		.= ' WHERE tm >= ' . $this->CI->db->escape( $timestop );
					$query		.= ' AND tm < ' . $this->CI->db->escape( $timestart );
				}
			}
		}

        $row=$this->CI->db->query($query);
        $row=$row->row_array();
        // echo $this->CI->db->last_query();
        if($row)
            $visitors= $row['num'];
        return $visitors;

	}


    /*
	** Insert New Visisitor
	*/
	/* ------------------------------------------------------------------------------------------------ */
	function insertVisitor( $sessiontime, $ip, $time ){
		// Check session time, insert new record if timeout
		$query			=	' SELECT COUNT(*) as count FROM visit_counter ';
		$query			.=	' WHERE ip=' .  $this->CI->db->escape ($ip);
		$query			.=	' AND (tm + ' . $sessiontime  . ') > ' .  $time;
        $query= $this->CI->db->query($query);
        $overtime =$query->row_array();
        // echo  $this->CI->db->last_query();
	   //	$overtime= $query->count_all_results();
         //   pr($overtime);
	  if ($overtime['count']==0)
		{
			$query = " INSERT INTO visit_counter (id, tm, ip) VALUES ('', " . $this->CI->db->escape ($time) . ", " . $this->CI->db->escape ( $ip ) . ")";
			$this->CI->db->query($query);
            //echo  $this->CI->Mvisitcounter->db->last_query();
	   	}
	}
	/* ------------------------------------------------------------------------------------------------ */



	/* ------------------------------------------------------------------------------------------------ */
	/*
	** Remove Visisitors from $timestart to $timestop
	*/
	/* ------------------------------------------------------------------------------------------------ */
	function delVisitors( $timestart = 0, $timestop = 0 ){

	   //	if ( $timestart < BIRTH_DAY_JOOMLA ) $timestart = 0;
	   //	if ( $timestop < BIRTH_DAY_JOOMLA ) $timestop = 0;

		$query			=	' DELETE FROM visit_counter ';

		if ( !$timestart ){
			if ( !$timestop ) return 0;
			$query		.= ' WHERE tm < ' . $this->CI->db->escape( $timestop );
		}
		else{
			if ( !$timestop ){
				$query		.= ' WHERE tm >= ' . $this->CI->db->escape( $timestart );
			}
			else{

				if ( $timestop == $timestart ){
					$query		.= ' WHERE tm = ' . $this->CI->db->escape( $timestart );
				}

				if ( $timestop > $timestart ){
					$query		.= ' WHERE tm >= ' . $this->CI->db->escape( $timestart );
					$query		.= ' AND tm < ' . $this->CI->db->escape( $timestop );
				}

				if ( $timestop < $timestart ){
					$query		.= ' WHERE tm >= ' . $this->CI->db->escape( $timestop );
					$query		.= ' AND tm < ' . $this->CI->db->escape( $timestart );
				}
			}
		}

		$this->CI->db->query($query);
	}


	
}
?>