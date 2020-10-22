<?php
/**
 * Created by PhpStorm.
 * Date: 21.09.14
 * Time: 18:13
 */

namespace HelperManager\Helper;

use DateTime;

class DateTimeHelper
{
    const SEC_MINUTE = 60;
    const SEC_HOUR = 3600;
    const SEC_DAY = 86400;
    const SEC_YEAR = 31536000;
    /**
     * @param $str
     * @param null $format
     * @return bool|DateTime
     */
    public static function getDateFromString($str = '', $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        if (!empty($str)) {
            return DateTime::createFromFormat($format, $str);
        }

        return new DateTime();
    }

    /**
     * @param null $date
     * @param null $format
     * @return string
     */
    static public function getDateString($date = null, $format = null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';
        $date = $date ? $date : new DateTime();

        return $date->format($format);
    }

    /**
     * @param $str
     * @param null $format
     * @return false|string
     */
    static public function getDateTimestamp($str, $format=null)
    {
        $format = $format ? $format : 'Y-m-d H:i:s';

        return date($format, $str);
    }
    static public function getDateStringLog($time = 'd')
    {
        if($time == 'h'){
            $timeFormat = 'Y-m-d H';
        }elseif ($time == 'i'){
            $timeFormat = 'Y-m-d H:i';
        }elseif ($time == 'm'){
            $timeFormat = 'Y-m';
        }else{
            $timeFormat = 'Y-m-d';
        }
        $out = static::getDateString($date = null, $timeFormat);

        return $out;
    }

    /**
     * @param $seconds
     * @return array
     */
    static public function seconds2times($seconds)
    {
        $times = array();
        $count_zero = false;
        $periods = array(static::SEC_MINUTE, static::SEC_HOUR, static::SEC_DAY, static::SEC_YEAR);

        for ($i = 3; $i >= 0; $i--)
        {
            $period = floor($seconds/$periods[$i]);
            if (($period > 0) || ($period == 0 && $count_zero))
            {
                $times[$i+1] = $period;
                $seconds -= $period * $periods[$i];

                $count_zero = true;
            }
        }

        $times[0] = $seconds;

        return $times;
    }

    /**
     * @param $second
     * @return string
     */
    static public function s2t($second)
    {
        $times_values = array('сек.','мин.','час.','д.','лет');
        $times = static::seconds2times($second);
        for ($i = count($times)-1; $i >= 0; $i--)
        {
            return $times[$i] . ' ' . $times_values[$i] . ' ';
        }
    }
}