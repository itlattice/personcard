<?php
// +----------------------------------------------------------------------
// | iboxsframe [ WE CAN DO IT JUST iboxs ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://iboxsframe.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

use app\common\Cache;
use app\model\Goods;
use app\model\GoodsType;
use iboxs\facade\Cookie;

function is_serialized($data)
{
    if (!is_string($data)) {
        return false;
    }
    $data = trim($data);
    if ('N;' == $data) {
        return true;
    }
    if (!preg_match('/^([adObis]):/', $data, $badions)) {
        return false;
    }
    switch ($badions[1]) {
        case 'a':
        case 'O':
        case 's':
            if (preg_match("/^{$badions[1]}:[0-9]+:.*[;}]\$/s", $data)) {
                return true;
            }
            break;
        case 'b':
        case 'i':
        case 'd':
            if (preg_match("/^{$badions[1]}:[0-9.E-]+;\$/", $data)) {
                return true;
            }
            break;
    }
    return false;
}

function isEmail($str){
    $result = trim($str);
    if (filter_var($result, FILTER_VALIDATE_EMAIL)){
        return true;
    }
    return false;
}

function isPhone($str){
    if(preg_match("/^1[34578]{1}\d{9}$/",$str)){
        return true;
    }else{
        return false;
    }
}

function token(){
    $token=md5(GetRandStr(31));
    // Cache::set('webtoken-'.$token,1,20);
    return $token;
}

function GetRandStr($length){
    //字符组合
    $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $len = strlen($str)-1;
    $randstr = '';
    for ($i=0;$i<$length;$i++) {
     $num=mt_rand(0,$len);
     $randstr .= $str[$num];
    }
    return $randstr;
}

function downLoadFile($file_url, $save_to){
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_POST, 0); 
	curl_setopt($ch,CURLOPT_URL,$file_url); 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	$file_content = curl_exec($ch);
	curl_close($ch);
	$downloaded_file = fopen($save_to, 'w');
	fwrite($downloaded_file, $file_content);
	fclose($downloaded_file);
}

function GetGoodsList($typeid){
    $goods_sort=GoodsType::where('gtid',$typeid)->find()->getData('goods_sort');
    $goods=Goods::where('gtid',$typeid)->where('is_sale',1);
    if($goods_sort>0){
        switch($goods_sort){
            case 1:$goods=$goods->order('add_time','DESC');break;
            case 2:$goods=$goods->order('add_time','ASC');break;
            case 3:$goods=$goods->order('price','DESC');break;
            case 4:$goods=$goods->order('price','ASC');break;
            case 5:$goods=$goods->order('sale','DESC');break;
            case 6:$goods=$goods->order('sale','ASC');break;
        }
    }
    $list=$goods->select();
    return $list;
}

function deldir($path){
    if(is_dir($path)){
        $p = scandir($path);
        foreach($p as $val){
            if($val !="." && $val !=".."){
                if(is_dir($path.$val)){
                    deldir($path.$val.'/');
                    @rmdir($path.$val.'/');
                }else{
                    unlink($path.$val);
                }
            }
        }
    }
}

function checkCookie($key){
    $info=Cookie::get('window'.$key);
    if($info==null){
        return true;
    }
    return false;
}

function PostUpdate($url, $data_string) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Content-Length: ' . strlen($data_string))
    );
    ob_start();
    curl_exec($ch);
    $return_content = ob_get_contents();
    ob_end_clean();
    $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    return array($return_code, $return_content);
}

function dd(...$vars){
    header('Access-Control-Allow-Origin:*');
    header('Access-Control-Allow-Headers:*');
    header('Access-Control-Allow-Methods:GET, POST, PATCH, PUT, OPTIONS');
    foreach ($vars as $v) {
        print_r($v);
    }
    die(1);
}