<?php
namespace iboxs\payment\extend;
class Common{
    public static function is_mobile_request() { 
        $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : ''; 
        $mobile_browser = '0'; 
        if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|ipad|ipod|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) 
            $mobile_browser++; 
        if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false)) 
            $mobile_browser++; 
        if(isset($_SERVER['HTTP_X_WAP_PROFILE'])) 
           $mobile_browser++; 
        if(isset($_SERVER['HTTP_PROFILE'])) 
            $mobile_browser++; 
        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4)); 
        $mobile_agents = array( 
            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac', 
            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno', 
            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-', 
            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-', 
            'newt','noki','oper','palm','pana','pant','phil','play','port','prox', 
            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar', 
            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-', 
            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp', 
            'wapr','webc','winw','winw','xda','xda-'
        ); 
        if(in_array($mobile_ua, $mobile_agents)) 
           $mobile_browser++; 
        if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false) 
            $mobile_browser++;
        if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false) 
            $mobile_browser=0;
        if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false) 
            $mobile_browser++; 
        if($mobile_browser>0)
            return true; 
        else
            return false; 
    }

    /**
    * 产生一个指定长度的随机字符串,并返回给用户
    * @param type $len 产生字符串的长度
    * @return string 随机字符串
    */
    public static function genRandomString($len = 32)
    {
        $chars = array(
        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
        "3", "4", "5", "6", "7", "8", "9"
        );
        $charsLen = count($chars) - 1;
        // 将数组打乱
        shuffle($chars);
        $output = "";
        for ($i = 0; $i < $len; $i++)
        {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }
}