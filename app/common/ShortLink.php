<?php
namespace app\common;

class ShortLink{
    /**
     * 生成短链接
     */
    public static function GetShortLink($url){
        $info=new self();
        return $info->create($url);
    }

    const API_URL    = 'https://gz8.co/api/getshort';
    const APP_KEY    = 'Hs015Nsxhau';

    public function create($url)
    {
        $str=GetRandStr(8);
        $time=time();
        $sign=md5(md5('154141241'.$str.urlencode($url).$time).SELF::APP_KEY);
        $res=self::post(SELF::API_URL,[
            'appid'=>'154141241',
            'sign'=>$sign,
            'str'=>$str,
            'time'=>$time,
            'url'=>$url,
            'domain'=>file_get_contents(LOG_PATH.'license.key')
        ]);
        if($res===false){
            return false;
        }
        $json=json_decode($res,true);
        if(!$json){
            return false;
        }
        $code=$json['code'];
        if($code==0){
            $url=$json['data']['url'];
            return $url;
        } else{
            return $json['msg'];
        }
    }

    private static function post($url, $post_data){
        $postdata = http_build_query($post_data);
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
}