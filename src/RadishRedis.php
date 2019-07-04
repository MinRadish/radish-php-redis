<?php
/**
 * Redis 连接对象
 * @authors Radish (1004622952@qq.com)
 * @date    2019-07-01 18:52:31
 */

namespace Radish\Redis;

class RadishRedis
{
    /**
     * 连接IP
     * @var string
     */
    private $host = '127.0.0.1';
    /**
     * 端口
     * @var string
     */
    private $port = '6379'; 
    /**
     * long-长连接 short-短连接
     * @var string
     */
    private $length = 'long'; 
    /**
     * 授权密码
     * @var string
     */
    private $auth = 'Radish';
    /**
     * 选择redis库, 0~15共16个
     * @var int
     */
    private $select;
    /**
     * Redis 连接对象
     * @var object
     */
    private $redis;

    public function __construct(array $options = [])
    {
        foreach ($options as $key => $val) {
            if (isset($this->$key)) {
                $this->$key = $val;
            }
        }
        $this->init();
    }

    private function init()
    {
        $this->redis = new Redis();
        if ($this->length == 'long') {
            $this->redis->pconnect($this->host, $this->port);
        } else {
            $this->redis->open($this->host, $this->port);
        }
        $this->redis->auth($this->auth);
        if ($this->select >= 0 && $this->select <= 15) {
            $this->redis->select($this->select);
        }
    }

    public function __call($funName, $param)
    {
        return call_user_func_array([$this->redis, $funName], $param);
    }
}