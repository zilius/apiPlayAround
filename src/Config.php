<?php

declare(strict_types=1);

Class Config
{
    static private $values = array();

    public static function get($key = null)
    {
        if (empty($key)) {
            return self::$values;
        }

        return isset(self::$values[$key]) ? self::$values[$key] : false;
    }

    public static function set($key, $value)
    {
        self::$values[$key] = $value;
    }

    public static function init()
    {
        self::$values['supermetrics.api.url'] = 'https://api.supermetrics.com/';
        self::$values['supermetrics.api.client_id'] = 'ju16a6m81mhid5ue1z3v2g0uh';
    }
}