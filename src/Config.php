<?php

declare(strict_types=1);

/**
 * Class Config
 * Config class for storing config values
 */
Class Config
{
    /** @var array static variables storage */
    static private $values = array();

    /**
     * @param null $key
     * @return array|bool|mixed
     */
    public static function get($key = null)
    {
        if (empty($key)) {
            return self::$values;
        }

        return isset(self::$values[$key]) ? self::$values[$key] : false;
    }

    /**
     * @param $key
     * @param $value
     */
    public static function set($key, $value)
    {
        self::$values[$key] = $value;
    }

    /**
     * Initial method which pass config variables to static variable
     */
    public static function init()
    {
        self::$values['supermetrics.api.url'] = 'https://api.supermetrics.com/';
        self::$values['supermetrics.api.client_id'] = 'ju16a6m81mhid5ue1z3v2g0uh';
    }
}