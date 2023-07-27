<?php

namespace Kaixings\Proutine;

class Mysql
{


    protected $host;
    protected $username;
    protected $password;
    protected $database;
    protected $port;

    public function __construct(array $options = [])
    {
        $this->host     = isset($options['host']) ? $options['host'] : null;
        $this->username = isset($options['username']) ? $options['username'] : "";
        $this->password = isset($options['password']) ? $options['password'] : "";
        $this->database = isset($options['database']) ? $options['database'] : "";
        $this->port     = isset($options['port']) ? $options['port'] : "";
    }

    public function queryGen($sql)
    {
        $conn = mysqli_connect($this->host, $this->username, $this->password, $this->database, $this->port);
        $conn->query($sql, MYSQLI_ASYNC);
        $result2 = null;
        $out    = 0;
        do {
            $r_array = $e_array = $reject = [$conn];

            $ret = mysqli_poll($r_array, $e_array, $reject, 0,0);
            yield;
            if($ret){
                foreach ($r_array as $link) {
                    if ($result = $link->reap_async_query()) {
                        $result2 = $result->fetch_all();
                        //释放内存
                        if (is_object($result))
                            mysqli_free_result( $result);
                    }
                    $link->close();
                }
                $out++;
            }
        } while ($out == 0);

        return $result2;
    }
}