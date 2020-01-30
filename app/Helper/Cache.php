<?php
declare(strict_types=1);

namespace App\Helper;

use Hyperf\Utils\ApplicationContext;
use Psr\SimpleCache\CacheInterface;

/**
 * Class Cache
 * @package App\Help
 */
class Cache
{
    /**
     * 类似laravel的缓存remember
     * @param string $name
     * @param int $ttl
     * @param callable|null $default
     * @return mixed
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public static function remember(string $name, int $ttl, callable $default=null)
    {
        $cache = ApplicationContext::getContainer()->get(CacheInterface::class);
        $res = $cache->get($name);
        if ($res) {
            return Str::unserialize($res);
        } else {
            $data = $default();
            $cache->set($name, Str::serialize($data), $ttl);
            return $data;
        }
    }

    /**
     * @return mixed|CacheInterface
     */
    public static function instance()
    {
        return ApplicationContext::getContainer()->get(CacheInterface::class);
    }

}
