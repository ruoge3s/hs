<?php
declare(strict_types=1);
namespace App\Logic;

use App\Helper\AttributeLoader;
use App\Helper\Str;
use Hyperf\Utils\ApplicationContext;

/**
 * accessToken 管理
 * Class AccessToken
 * @package App\Logic
 */
class AccessToken
{
    use AttributeLoader;

    /**
     * @var int
     */
    public $ttl = 7200;

    const TYPE_ADMIN    = 'admin';      // 管理员用户

    protected $payload = [];

    /**
     * AccessToken constructor.
     * @param string $type
     * @param array $data = ['id' => n, xxx= ss...] token中的载荷数据一般包括对应的用户id及相关信息
     */
    public function __construct(string $type, array $data)
    {
        $this->payload = $this->payload($type, $data);
    }

    /**
     * 构造一个token的缓存名称
     * @param string $token
     * @return string
     */
    protected static function key(string $token)
    {
        return 'AccessToken:' . $token;
    }

    /**
     * 构建access token
     * @param \Redis $redis 验证redis中是否已经有这个key
     * @return string
     */
    protected function token($redis = null)
    {
        $str = Str::unique();
        if ($redis && $redis->get(self::key($str))) {
            return $this->token($redis);
        }
        return $str;
    }

    /**
     * 构建载荷数据
     * @param $type
     * @param $data
     * @return array
     */
    public function payload($type, $data)
    {
        return array_merge(['type' => $type], $data);
    }

    /**
     * 生成accessToken
     * @return string|null
     */
    public function generate()
    {
        $redis = ApplicationContext::getContainer()->get(\Redis::class);
        $token = $this->token($redis);
        $redis->set(self::key($token), serialize($this->payload), $this->ttl);
        return $token;
    }

    /**
     * 释放token
     * @param string $token
     * @return int
     */
    public static function free(string $token)
    {
        return ApplicationContext::getContainer()
            ->get(\Redis::class)
            ->del(self::key($token));
    }

    /**
     * 校验accessToken,并获取对应的关联数据
     * @param $token
     * @param array $payload
     * @return bool
     */
    public static function validate($token, &$payload = [])
    {
        $str = ApplicationContext::getContainer()
            ->get(\Redis::class)
            ->get(self::key($token));
        if ($str) {
            $payload = unserialize($str);
            return true;
        }
        return false;
    }

    /**
     * 是否为频繁尝试验证
     * @param $token
     * @param int $ttl
     * @return bool
     */
    public static function attempt($token, $ttl=10)
    {
        $redis = ApplicationContext::getContainer()->get(\Redis::class);
        $name = self::key($token) . 'lock';
        if ($redis->get($name)) {
            return true;
        } else {
            $redis->set($name, 1, $ttl);
            return false;
        }
    }

    /**
     * 获取载荷数据
     * @param $token
     * @return array|mixed|null
     */
    public static function getPayload($token)
    {
        $str = ApplicationContext::getContainer()
            ->get(\Redis::class)
            ->get(self::key($token));
        if ($str) {
            return unserialize($str);
        } else {
            return null;
        }
    }
}
