<?php

/* * *
 *      _____            _  _
 *     |  __ \          | |(_)
 *     | |__) | ___   __| | _  ___
 *     |  _  / / _ \ / _` || |/ __|
 *     | | \ \|  __/| (_| || |\__ \
 *     |_|  \_\\___| \__,_||_||___/
 *
 *    A libarary having normal functions of php and redis
 *    While written I used redis 4.* version
 *    fx -: means function in this file
 *    follow link https://www.hugeserver.com/kb/install-redis-centos/ for installation
 *    http://webd.is/ for many things 
 */

Class Redis_magic {

    public $redis = 0;
    protected $redis_config = array(
        "session" => array(
            "scheme" => "tcp",
            "host" => CONFIG_REDIS_HOST,
            "port" => CONFIG_REDIS_PORT
        ),
        "data" => array(
            "scheme" => "tcp",
            "host" => CONFIG_REDIS_HOST,
            "port" => CONFIG_REDIS_PORT
        )
    );

    public function __construct($conn) {
        $host = $_SERVER['HTTP_HOST'] ?? "";
        //if ($host && $host != "localhost" && PROJECT_MODE == "pre-prod") {
            if ($host && $host != "localhost") {
            $this->redis = new Redis();
            $this->redis->connect($this->redis_config[$conn]['host'], $this->redis_config[$conn]['port']);
        } else {//for localhost only
            require_once APPPATH . "third_party/predis/autoload.php";
            Predis\Autoloader::register();

            $this->redis = new Predis\Client($this->redis_config["session"]);
        }
    }

    /* fx to set a value  for respcetive key */

    public function SET($key, $value) {
        try {
        	$key  .= ((defined("APP_ID") && APP_ID)?"_".APP_ID:'');
            $result = $this->redis->SET($key, $value);
        } catch (Exception $e) {
            $result = "";
        }
        return $result;
    }

    /* fx to get a value from key */

    public function GET($key) {
        try {
        	$key  .= ((defined("APP_ID") && APP_ID)?"_".APP_ID:'');
            $result = $this->redis->GET($key);
        } catch (Exception $e) {
            $result = "";
        }
        return $result;
    }

    /* set key with expiry in seconds */

    public function SETEX($key, $seconds, $value) {
        try {
        	$key  .= ((defined("APP_ID") && APP_ID)?"_".APP_ID:'');
            $result = $this->redis->SETEX($key, $seconds, $value);
        } catch (Exception $e) {
            $result = "";
        }
        return $result;
    }

    /* check variables living time */

    public function TTL($key) {
        try {
        $key  .= ((defined("APP_ID") && APP_ID)?"_".APP_ID:'');
            $result = $this->redis->TTL($key);
        } catch (Exception $e) {
            $result = "";
        }
        return $result;
    }

    /* expire variables living time */

    public function EXPIRE($key, $seconds) {
        try {
        $key  .= ((defined("APP_ID") && APP_ID)?"_".APP_ID:'');
            $result = $this->redis->EXPIRE($key, $seconds);
        } catch (Exception $e) {
            $result = "";
        }
        return $result;
    }

    /* Redis sets
     * https://redis.io/commands/hexists
     */
    /* set an array */

  
    public function HASHSET($key, $field, $data) {
    $key  .= ((defined("APP_ID") && APP_ID)?"_".APP_ID:'');
        $result = $this->redis->HSET($key, $field, $data);
        return $result;
    }

    public function HASHGET($key, $field) {
    $key  .= ((defined("APP_ID") && APP_ID)?"_".APP_ID:'');
        $result = $this->redis->HGET($key, $field);
        return $result;
    }

    public function HASHGETALL($key) {
    $key  .= ((defined("APP_ID") && APP_ID)?"_".APP_ID:'');
        $result = $this->redis->HGETALL($key);
        return $result;
    }

    public function HASHDEL($key) {
    $key  .= ((defined("APP_ID") && APP_ID)?"_".APP_ID:'');
        $result = $this->redis->DEL($key);
        return $result;
    }



    public function HDEL($table, $key) {
        try {
        $key  .= ((defined("APP_ID") && APP_ID)?"_".APP_ID:'');
            $result = $this->redis->HDEL($table, $key);
        } catch (Exception $e) {
            $result = "";
        }
        return $result;
    }

    public function EXPIRE_HMSET_KEY($table, $u_id, $seconds) {
        try {	
            $result = $this->redis->EXPIRE($table . ':' . $u_id, $seconds);
        } catch (Exception $e) {
            $result = "";
        }
        return $result;
    }

    /* Publish a message */

    public function PUBLISH($channel_name, $message) {
        try {
            $result = $this->redis->PUBLISH($channel_name, $message);
        } catch (Exception $e) {
            $result = "";
        }
        return $result;
    }

    /* Publish a message */

    public function LPUSH($list_name, $value) {
        try {
            if ($value && is_array($value)) {
                $pipeline = $this->redis->pipeline();
                foreach ($value as $val) {
                    $pipeline->LPUSH($list_name, json_encode($val));
                }
                return $pipeline->execute();
            } else {
                return $this->redis->LPUSH($list_name, $value);
            }
        } catch (Exception $e) {
            $result = "";
        }
        return $result;
    }

    public function RPUSH($list_name, $value) {
        try {
            $result = $this->redis->RPUSH($list_name, $value);
        } catch (Exception $e) {
            $result = "";
        }
        return $result;
    }

    public function LRANGE($list_name, $limit, $offset) {
        try {
            $result = $this->redis->LRANGE($list_name, $limit, $offset);
        } catch (Exception $e) {
            $result = "";
        }
        return $result;
    }

    public function LLEN($list_name) {
        try {
            $result = $this->redis->LLEN($list_name);
        } catch (Exception $e) {
            $result = "";
        }
        return $result;
    }

    public function DEL($key) {
        try {
        $key  .= ((defined("APP_ID") && APP_ID)?"_".APP_ID:'');
            $result = $this->redis->DEL($key);
        } catch (Exception $e) {
            $result = "";
        }
        return $result;
    }


    public function HGETALL($table, $index) {
        $result = "";
        try {
            if ($index && is_array($index)) {
                $pipeline = $this->redis->pipeline();
                foreach ($index as $val) {
                    $pipeline->HGETALL($table . ":" . $val);
                }
                return array_combine($index, $pipeline->execute());
            } else if (is_string($index) || is_int($index))
                return $this->redis->HGETALL($table . ':' . $index);
        } catch (Exception $e) {
            $result = "";
        }
        return $result;
    }

    public function HMSET($table, $u_id, $data) {
        try {
            if (is_array($data))
                $result = $this->redis->HMSET($table . ':' . $u_id, $data);
            else
                $result = $this->redis->HSET($table, $u_id, $data);
        } catch (Exception $e) {
            $result = "";
        }
        return $result;
    }



//     public function HGETALL($table, $index)
//     {
//         $index_name = $index;
//         if (DEVICE_ID)
//             $index_name = $index . ':' . DEVICE_ID;
//         try {
//             if ($index_name && is_array($index_name)) {
//                 $pipeline = $this->redis->pipeline();
//                 foreach ($index_name as $val) {
//                     $pipeline->HGETALL($table . ":" . $val);
//                 }
//                 return array_combine($index_name, $pipeline->execute());
//             } else if (is_string($index_name) || is_int($index_name))
//                 return $this->redis->HGETALL($table . ':' . $index_name);
//         } catch (Exception $e) {
//             $result = "";
//         }
//         return $result;
//     }


// public function HMSET($table, $u_id, $data)
//     {
//         $table_name = $table . ':' . $u_id;
//         if (DEVICE_ID)
//             $table_name = $table . ':' . $u_id . ':' . DEVICE_ID;
//         try {
//             if (is_array($data))
//                 $result = $this->redis->HMSET($table_name, $data);
//             else
//                 $result = $this->redis->HSET($table_name, $u_id, $data);
//         } catch (Exception $e) {
//             $result = "";
//         }
//         return $result;
//     }


}