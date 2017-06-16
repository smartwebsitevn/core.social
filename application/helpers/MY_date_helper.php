<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *   $now = getdate();
 * $currentTime = $now["hours"] . ":" . $now["minutes"] . ":" . $now["seconds"];
  $currentDate = $now["mday"] . "." . $now["mon"] . "." . $now["year"];
    $currentWeek = $now["wday"] . ".";
  echo $now["year"];
 // hien thi nam hien tai*/

/**
 * Date Functions
 *
 * Cac function xu ly time
 *
 * @author        ***
 * @version        2016-04-07
 */
function format_date($time = null, $type = null)
{
    $time = $time ?: now();

    $type = $type ? '_' . $type : '';

    $format = config('date_format_display' . $type, 'main');

    return mdate($format, $time);;
}

/**
 * Lay ngay thang tu time
 * @param int $time Timestamp
 * @param string $type Loai format ('' | 'time' | 'full')
 */
function get_date($time = '', $type = '')
{
    $time = (!$time) ? now() : $time;

    if ($type) {
        $type = '_' . $type;
    }
        $format = config('date_format' . $type, 'main');
    $date = mdate($format, $time);
    return $date;
}

/**
 * Lay thong tin cua thoi gian
 * @param int $time Timestamp
 */
function get_time_info($time = '')
{
    $time = (!$time) ? now() : $time;

    $arr = mdate('%d-%m-%Y-%H-%i-%s', $time);
    $arr = explode('-', $arr);

    $info = array();
    foreach (array('d', 'm', 'y', 'h', 'mi', 's') as $i => $p) {
        $info[$p] = intval($arr[$i]);
    }

    return $info;
}

/**
 * Them thoi gian vao 1 thoi diem
 * @param int $start Timestamp bat dau
 * @param array $up Thoi gian muon them
 */
function add_time($start, $up)
{
    $info = get_time_info($start);
    foreach ($info as $p => $v) {
        if (isset($up[$p])) {
            $info[$p] += $up[$p];
        }
    }

    $result = mktime($info['h'], $info['mi'], $info['s'], $info['m'], $info['d'], $info['y']);

    return $result;
}

/**
 * Tinh thoi gian quy ra giay tu ngay thang nam
 * @param string $date Ngay thang nam dau vao
 * @param string $format Format cua $date
 */
function get_time_from_date($date, $format = '')
{
    // Xu ly format
    $format = ($format == '') ? config('date_format', 'main') : $format;
    $format = str_replace(array('%', ' '), '', $format);
    $format = strtolower($format);

    // Phan tich input
    $arr_date = explode('-', $date);
    $arr_format = explode('-', $format);
    if (count($arr_date) != 3 || count($arr_format) != 3) {
        return FALSE;
    }

    // Lay gia tri ngay thang nam
    $time = array();
    foreach ($arr_format as $k => $v) {
        $time[$v] = intval(trim($arr_date[$k]));
    }

    $timestamp = mktime(0, 0, 0, $time['m'], $time['d'], $time['y']);

    return $timestamp;
}

/**
 * Get current mktime
 * @return int
 */
function get_current_mktime()
{
    return mktime(date('H'), date('i'), date('s'), date('m'), date('d'), date('Y'));
}

/**
 * Lay thoi gian bat dau cua tuan
 * @param int $time Timestamp
 */
function get_time_first_week($time = '')
{
    // Mac dinh la time hien tai
    $time = (!$time) ? now() : $time;

    // Lay thoi gian bat dau cua ngay
    $time = get_time_from_date(get_date($time));

    // Tinh ngay dau tuan
    $n = intval(mdate('%N', $time)) - 1;

    // Tinh time cua ngay dau tuan
    $up = array();
    $up['d'] = -$n;
    $time_first_week = add_time($time, $up);

    return $time_first_week;
}

// lay tuan hien tai cua time
function get_weeknum($timestamp = 0)
{
    if (!$timestamp)
        $timestamp = now();
    $maxday = date("t", $timestamp);
    $thismonth = getdate($timestamp);
    $timeStamp = mktime(0, 0, 0, $thismonth['mon'], 1, $thismonth['year']);    //Create time stamp of the first day from the give date.
    $startday = date('w', $timeStamp);    //get first day of the given month
    $day = $thismonth['mday'];
    $weeks = 0;
    $week_num = 0;

    for ($i = 0; $i < ($maxday + $startday); $i++) {
        if (($i % 7) == 0) {
            $weeks++;
        }
        if ($day == ($i - $startday + 1)) {
            $week_num = $weeks;
        }
    }
    return $week_num;
}

/**
 * Lay khoang thoi gian bat dau va ket thuc (tinh ra giay) tu moc thoi gian co dinh
 * @param string $date Thoi gian dau vao (%Y | %m-%Y | %d-%m-%Y) (Neu date la %d-%m-%Y thi phai theo format trong config)
 * @param string $date_output Bien luu dinh dang date tu ket qua
 * @return array($time_start, $time_end)
 */
function get_time_between($date, &$date_output = '')
{
    // Neu khong ton tai date
    if (!$date) {
        return FALSE;
    }

    // Neu date la khoang thoi gian
    if (is_array($date)) {
        $start = $date[0];
        $end = $date[1];

        $date_start = $date_end = array('date' => '');
        $date_start['time_between'] = get_time_between($start, $date_start['date']);
        $date_end['time_between'] = get_time_between($end, $date_end['date']);

        $start = $date_start['date'];
        $end = $date_end['date'];

        if (!$start) {
            return FALSE;
        }

        $time_between = $date_start['time_between'];
        if ($end) {
            $time_between[1] = $date_end['time_between'][1];
        }

        $date_output = array($start, $end);

        return $time_between;
    }

    // Neu date la moc thoi gian co dinh
    $arr = explode('-', $date);
    $count = count($arr);

    $time_start = 0;
    $date_format = '';
    $time_up = array();
    if ($count == 1) {
        $time_start = get_time_from_date('1-1-' . $arr[0], 'd-m-y');
        $date_format = '%Y';
        $time_up['y'] = 1;
    } elseif ($count == 2) {
        $time_start = get_time_from_date('1-' . $arr[0] . '-' . $arr[1], 'd-m-y');
        $date_format = '%m-%Y';
        $time_up['m'] = 1;
    } elseif ($count >= 3) {
        $time_start = get_time_from_date($date);
        $date_format = config('date_format', 'main');
        $time_up['d'] = 1;
    }

    if ($time_start) {
        $time_end = add_time($time_start, $time_up);
        $time_between = array($time_start, $time_end);
        $date_output = mdate($date_format, $time_start);

        return $time_between;
    }

    return FALSE;
}

/**
 * Tinh so ngay con lai so voi thoi diem hien tai
 */
function days_left($end, &$days_left = 0)
{
    $left = $end - now();
    if ($left >= 0) {
        $days_left = $left / (24 * 60 * 60);
        if (get_date(now()) == get_date($end)) {
            $rem = lang('ending_today');
        } else {
            if ($days_left > 1) {
                $rem = round($days_left, 2) . ' ' . lang('days');
            } else {
                $rem = round($days_left, 2) . ' ' . lang('day');
            }
        }

        return $rem;
    } else {
        return lang('closed');
    }
}

/**
 * Tinh so ngay moc 2 > moc 1 bao ngay  (chu y: tinh theo ngay)
 * vd:    date1= 124223245    date3= 124243245
 * <=> date1= 3-5-2014   date2= 5-5-2014    (chu y: ngay o day da la dang giay)
 * kq => 2
 */
function days_compare($date1, $date2)
{
    $date1 = get_time_info($date1);
    $date2 = get_time_info($date2);
    $day = ($date2['d'] - $date1['d']) + 30 * ($date2['m'] - $date1['m']) + 365 * ($date2['y'] - $date1['y']);
    return (int)$day;
}

/**
 * Phan tich so ngay thanh so nam, thang, ngay (Quy dinh 1 nam = 12 thang, 1 thang = 30 ngay)
 * @param    int $days So ngay can phan tich
 * @return    array    Bao gom cac key: y, m, d
 */
function parse_num_day($num_day)
{
    $unit = array();
    $unit['y'] = 12 * 30;
    $unit['m'] = 30;
    $unit['d'] = 1;

    $result = array();
    foreach ($unit as $p => $n) {
        $v = floor($num_day / $n);
        $num_day -= $v * $n;

        $result[$p] = $v;
    }

    return $result;
}

/**
 * Timespan
 *
 * Returns a span of seconds in this format:
 *    10 days 14 hours 36 minutes 47 seconds
 *
 * @access    public
 * @param    integer    a number of seconds
 * @param    integer    Unix timestamp
 * @return    integer
 */
function timespan($seconds = 1, $time = '', $short = false)
{
    $CI =& get_instance();
    $CI->lang->load('date');

    if (!is_numeric($seconds)) {
        $seconds = 1;
    }

    if (!is_numeric($time)) {
        $time = time();
    }

    if ($time <= $seconds) {
        $seconds = 1;
    } else {
        $seconds = $time - $seconds;
    }

    $str = '';
    $years = floor($seconds / 31536000);

    if ($years > 0) {
        $str .= $years . ' ' . $CI->lang->line((($years > 1) ? 'date_years' : 'date_year')) . ', ';

        if ($short) return substr(trim($str), 0, -1);
    }

    $seconds -= $years * 31536000;
    $months = floor($seconds / 2628000);

    if ($years > 0 OR $months > 0) {
        if ($months > 0) {
            $str .= $months . ' ' . $CI->lang->line((($months > 1) ? 'date_months' : 'date_month')) . ', ';
        }

        $seconds -= $months * 2628000;
        if ($short) return substr(trim($str), 0, -1);
    }

    $weeks = floor($seconds / 604800);

    if ($years > 0 OR $months > 0 OR $weeks > 0) {
        if ($weeks > 0) {
            $str .= $weeks . ' ' . $CI->lang->line((($weeks > 1) ? 'date_weeks' : 'date_week')) . ', ';
        }

        $seconds -= $weeks * 604800;

    }

    $days = floor($seconds / 86400);

    if ($months > 0 OR $weeks > 0 OR $days > 0) {
        if ($days > 0) {
            $str .= $days . ' ' . $CI->lang->line((($days > 1) ? 'date_days' : 'date_day')) . ', ';
        }

        $seconds -= $days * 86400;

    }

    $hours = floor($seconds / 3600);

    if ($days > 0 OR $hours > 0) {
        if ($hours > 0) {
            $str .= $hours . ' ' . $CI->lang->line((($hours > 1) ? 'date_hours' : 'date_hour')) . ', ';
        }

        $seconds -= $hours * 3600;
        if ($short) return substr(trim($str), 0, -1);
    }

    $minutes = floor($seconds / 60);

    if ($days > 0 OR $hours > 0 OR $minutes > 0) {
        if ($minutes > 0) {
            $str .= $minutes . ' ' . $CI->lang->line((($minutes > 1) ? 'date_minutes' : 'date_minute')) . ', ';
        }

        $seconds -= $minutes * 60;
        if ($short) return substr(trim($str), 0, -1);
    }


    $str .= $seconds . ' ' . $CI->lang->line((($seconds > 1) ? 'date_seconds' : 'date_second')) . ', ';


    return substr(trim($str), 0, -1);
}

/**
 * Hourspan
 *
 * Returns a span of seconds in this format:
 *    4h:5m:2s
 *
 * @access    public
 * @param    integer    a number of seconds
 * @param    integer    Unix timestamp
 * @return    integer
 */
function timeminspan($t, $format = array(), $empty = array())
{
    if (!$format)
        $format = array('h', 'm', 's',);
    if (!$empty)
        $empty = array('', '0:', '00',);// gia tri mac dinh neu ko co

    $hour = floor($t / (60 * 60));
    $hour = ($hour != 0) ? $hour . $format[0] . ':' : $empty[0];
    $t = $t % (60 * 60);
    $min = floor($t / 60);
    $min = ($min != 0) ? $min . $format[1] . ':' : $empty[1];
    $second = $t % 60;

    if (($second != 0)) {
        $second = ($second < 10) ? '0' . $second : $second;
        $second .= $format[2];
    } else
        $second = $empty[2];

    return $hour . $min . $second;
}

// lay so ngay trong thang
function get_days_in_month($month, $year)
{
    // calculate number of days in a month
    return $month == 2 ? ($year % 4 ? 28 : ($year % 100 ? 29 : ($year % 400 ? 28 : 29))) : (($month - 1) % 7 % 2 ? 30 : 31);
}


/*
	 * Tinh tuan hien tai
	*/
function get_week($date)
{
    $date = explode('-', get_date($date));
    $Y = $date[2];
    $M = $date[1];
    $D = $date[0];

    $date = date($Y . '-' . $M . '-' . $D);
    while (date('w', strtotime($date)) != 1) {
        $tmp = strtotime('-1 day', strtotime($date));
        $date = date('Y-m-d', $tmp);
    }

    $week = date('W', strtotime($date));
    return $week;

}

/*
 * Qui doi ngay thang nam
* @param int	 $date	 so ngay sang dang mang tham so
*/

function convert_day($day = 0)
{

    // Quy doi sang ngay thang nam
    $_days = $day;
    $time['y'] = floor($_days / 360);
    $_days -= $time['y'] * 360;

    $time['m'] = floor($_days / 30);
    $_days -= $time['m'] * 30;

    $time['d'] = $_days;

    return $time;
}

/**
 * Tinh so ngay da troi qua so voi thoi diem hien tai
 */
function days_used($start, &$days_used = 0)
{
    $left = now() - $start;
    if ($left >= 0) {
        if (get_date(now()) == get_date($start)) {
            $rem = lang('today');
        } else {
            $days_used = round($left / (24 * 60 * 60));

            if ($days_used > 1) {
                $rem = number_format($days_used) . ' ' . lang('days');
            } else {
                $rem = number_format($days_used) . ' ' . lang('day');
            }
        }

        return $rem;
    } else {
        return lang('today');
    }
}

// tinh so nam tuoi
function year_age($date)
{
    $birthDate = $date;// "15-1-1983";
    //explode the date to get month, day and year
    $birthDate = explode("-", $birthDate);
    //get age from date or birthdate
    $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[1], $birthDate[0], $birthDate[2]))) > date("md")
        ? ((date("Y") - $birthDate[2]) - 1)
        : (date("Y") - $birthDate[2]));
    return $age;
}