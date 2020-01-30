<?php
declare(strict_types=1);
namespace App\Helper;

class Str
{
    /**
     * 生成32位长度唯一字符串
     * @param string $custom
     * @return string
     */
    public static function unique(string $custom='')
    {
        return md5(uniqid(microtime(),true) . $custom);
    }

    /**
     * Serialize the value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public static function serialize($value)
    {
        return is_numeric($value) ? $value : serialize($value);
    }

    /**
     * Unserialize the value.
     *
     * @param  mixed  $value
     * @return mixed
     */
    public static function unserialize($value)
    {
        return is_numeric($value) ? $value : unserialize($value);
    }
}
