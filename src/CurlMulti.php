<?php

namespace Kaixings\Proutine;

class CurlMulti
{

    public function curl(array $item,&$mh=null) :\Generator
    {
        $mh = curl_multi_init();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $item['url']);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, isset($item['headers']) && is_array($item['headers']) ? $item['headers'] : []);

        if (empty($item['https'])) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        if ((isset($item['method']) ? $item['method']:'get') == 'post') {
            curl_setopt($ch, CURLOPT_POST, true);
            $params = $item['params'];
            if (is_array($item['params'])) {
                $flag = true;
                foreach ($params as $key => $val) {
                    if (strpos($val, '@') === 0) {
                        $flag = false;
                        break;
                    }
                }
                if ($flag) {
                    $params = http_build_query($params);
                }
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_multi_add_handle($mh, $ch);


        do {
            curl_multi_exec( $mh, $running );
            yield;
        } while( $running > 0 );

        $content = curl_multi_getcontent( $ch );
        curl_multi_remove_handle($mh, $ch);
        curl_multi_close($mh);
        return $content;
    }
}