<?php
namespace iboxs\payment\chpay;
class chEPay
{
    protected $key='';

    public function __construct($key)
    {
        $this->key=$key;
    }

    public function webPay($param_tmp, $button='正在跳转'){
        $param = $this->buildRequestParam($param_tmp);

		$html = '<form id="dopay" action="https://pay.xr876.cn/submit.php" method="post">';
		foreach ($param as $k=>$v) {
			$html.= '<input type="hidden" name="'.$k.'" value="'.$v.'"/>';
		}
		$html .= '<input type="submit" value="'.$button.'"></form><script>document.getElementById("dopay").submit();</script>';

		return $html;
    }

    private function buildRequestParam($param){
		$mysign = $this->getSign($param);
		$param['sign'] = $mysign;
		$param['sign_type'] = 'MD5';
		return $param;
	}

	// 计算签名
	private function getSign($param){
		ksort($param);
		reset($param);
		$signstr = '';
	
		foreach($param as $k => $v){
			if($k != "sign" && $k != "sign_type" && $v!=''){
				$signstr .= $k.'='.$v.'&';
			}
		}
		$signstr = substr($signstr,0,-1);
		$signstr .= $this->key;
		$sign = md5($signstr);
		return $sign;
	}

    public function Check($config){
        if(empty($_GET)) return false;
		$sign = $this->getSign($_GET);
		if($sign === $_GET['sign']){
			$signResult = true;
		}else{
			$signResult = false;
		}
		return $signResult;
    }
}