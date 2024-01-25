<?php
namespace iboxs\payment\wlpay;
class weiLanPay
{
    protected $key='';

    public function __construct($key)
    {
        $this->key=$key;
    }

    public function webPay($para_temp, $method='POST', $button_name='正在跳转')
    {
        $para = $this->buildRequestPara($para_temp);
        
        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='http://api.ka5.co/submit.php' method='".$method."'>";
        
        foreach ($para as $key=>$val) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }

        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";
        
        $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";
        
        return $sHtml;
    }

    public function Check(){
        if(empty($_GET)) {//判断POST来的数组是否为空
			return false;
		}
		else {
			//生成签名结果
			$isSign = $this->getSignVeryfy($_GET, $_GET["sign"]);
			//获取支付宝远程服务器ATN结果（验证是否是支付宝发来的消息）
			$responseTxt = 'true';
			//if (! empty($_POST["notify_id"])) {$responseTxt = $this->getResponse($_POST["notify_id"]);}
			
			//验证
			//$responsetTxt的结果不是true，与服务器设置问题、合作身份者ID、notify_id一分钟失效有关
			//isSign的结果不是true，与安全校验码、请求时的参数格式（如：带自定义参数等）、编码格式有关
			if (preg_match("/true$/i",$responseTxt) && $isSign) {
				return true;
			} else {
				return false;
			}
		}
    }

    private function getSignVeryfy($para_temp, $sign) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter =$this-> paraFilter($para_temp);
		
		//对待签名参数数组排序
		$para_sort =$this-> argSort($para_filter);
		
		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr =$this->  createLinkstring($para_sort);
		
		$isSgin = false;
		$isSgin =$this->md5Verify($prestr, $sign, $this->key);
		
		return $isSgin;
	}

    private function md5Verify($prestr, $sign, $key) {
        $prestr = $prestr . $key;
        $mysgin = md5($prestr);
    
        if($mysgin == $sign) {
            return true;
        }
        else {
            return false;
        }
    }

    private function buildRequestPara($para_temp)
    {
        //除去待签名参数数组中的空值和签名参数
        $para_filter = $this->paraFilter($para_temp);

        //对待签名参数数组排序
        $para_sort = $this->argSort($para_filter);

        //生成签名结果
        $mysign = $this->buildRequestMysign($para_sort);
       
        //签名结果与签名方式加入请求提交参数组中
        $para_sort['sign'] = $mysign;
        $para_sort['sign_type'] = strtoupper('MD5');
       
        return $para_sort;
    }

    private function buildRequestMysign($para_sort)
    {
        //把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
        $prestr =$this->createLinkstring($para_sort);
    
        $mysign = $this->md5Sign($prestr, $this->key);

        return $mysign;
    }

    private function md5Sign($prestr, $key) {
        $prestr = $prestr . $key;
        return md5($prestr);
    }

    private function createLinkstring($para)
    {
        $arg  = "";
        foreach($para as $key=>$val){
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg, 0, strlen($arg)-1);
        
        return $arg;
    }

    private function argSort($para)
    {
        ksort($para);
        reset($para);
        return $para;
    }

    private function paraFilter($para)
    {
        $para_filter = array();
        foreach ($para as $key=>$val) {
            if ($key == "sign" || $key == "sign_type" || $val == "") {
                continue;
            } else {
                $para_filter[$key] = $para[$key];
            }
        }
        return $para_filter;
    }
}