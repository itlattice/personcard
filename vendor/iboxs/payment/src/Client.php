<?php
/**
 * 支付从这里开始
 * @author  zqu zqu1016@qq.com
 * 
 */
namespace iboxs\payment;

use iboxs\payment\alipay\AlipayService;
use iboxs\payment\chpay\chEPay;
use iboxs\payment\extend\Common;
use iboxs\payment\qqpay\QQPay;
use iboxs\payment\wlpay\weiLanPay;
use iboxs\payment\wxpay\App;
use iboxs\payment\wxpay\WxpayService;

class Client
{
    protected $config=[];

    /**
     * 传入支付配置信息
     * 如果需要支付宝支付就传入支付宝支付的配置信息，需要微信支付就传入微信支付配置信息，QQ支付就传入QQ支付配置信息，均为数组字典，具体格式参考文档及示例程序
     */
    public function __construct($config){
        if(!isset($config['gatewayUrl'])){
            $config['gatewayUrl']="https://openapi.alipay.com/gateway.do";
        }
        $this->config=$config;
    }

    /**
     * QQ钱包支付发起（Native支付）
     * @param array $orderInfo 订单信息（具体构建方式参考文档readme.md）
     * @return mixed 微信返回的信息，可在其中提取到二维码信息
     */
    public function QQPay($orderInfo){
        //$this->config['mchid'] ,$this->config['appid'],$this->config['apiKey']
        $qqArr = [
            "mch_id"     => $this->config['mchid'],//商户号
            "notify_url" => $this->config['notify_url'],//异步通知回调地址
            "key"        => $this->config['apiKey'],//商户key
        ];
        $qqPay = new QQPay($qqArr);
        $param = [
            "out_trade_no"  =>  $orderInfo['out_trade_no'],// 订单号         
            "trade_type"    =>  "NATIVE",// 固定值          
            "total_fee"     =>  $orderInfo['amount']*100,// 单位为分            
            "body"          =>  $orderInfo['order_name'],//订单标题     
        ];
        $unified = $qqPay->unifiedOrder($param);
        return $unified;
    }

    /**
     * 支付宝网页支付（会自动分手机端及pc端支付）
     * @param array $orderInfo 订单信息（具体构建方式参考文档readme.md）
     * 本函数调用后会自动跳转至支付宝内支付，无需做任何处理
     */
    public function AlipayWeb($orderInfo){
        $aliPay = new AlipayService();
        $aliPay->setAppid($this->config['appid']);
        $aliPay->setReturnUrl($this->config['return_url']);
        $aliPay->setNotifyUrl($this->config['notify_url']);
        $aliPay->setRsaPrivateKey($this->config['rsaPrivateKey']);
        $aliPay->setTotalFee($orderInfo['amount']);
        $aliPay->setOutTradeNo($orderInfo['out_trade_no']);
        $aliPay->setOrderName($orderInfo['order_name']);
        $aliPay->setGatewayUrl($this->config['gatewayUrl']);
        $sHtml="";
        if(Common::is_mobile_request()){
            $sHtml = $aliPay->wapPay();
        } else{
            $sHtml = $aliPay->webPay();
        }
        echo $sHtml;
    }

    /**
     * 微蓝码支付
     */
    public function weiLanWeb($orderInfo){
        $parameter = array(
            "pid" => trim($this->config['merid']),
            "type" => $orderInfo['type'],
            "notify_url"	=> $this->config['notify_url'],
            "return_url"	=> $this->config['return_url'],
            "out_trade_no"	=> $orderInfo['order'],
            "name"	=> $orderInfo['name'],
            "money"	=> $orderInfo['amount'],
            "sitename"	=> $this->config['sitename']
        );
        $wlPay=new weiLanPay($this->config['key']);
        $sHtml=$wlPay->webPay($parameter);
        echo $sHtml;
    }

    /**
     * 彩虹易支付
     */
    public function chEWeb($orderInfo){
        $parameter = array(
            "pid" => trim($this->config['merid']),
            "type" => $orderInfo['type'],
            "notify_url"	=> $this->config['notify_url'],
            "return_url"	=> $this->config['return_url'],
            "out_trade_no"	=> $orderInfo['order'],
            "name"	=> $orderInfo['name'],
            "money"	=> $orderInfo['amount'],
        );
        $wlPay=new chEPay($this->config['key']);
        $sHtml=$wlPay->webPay($parameter);
        echo $sHtml;
    }

    /**
     * 支付宝扫码支付获取二维码
     * @param array $orderInfo 订单信息（具体构建方式参考文档readme.md）
     * @return mixed 支付宝返回的信息，可在其中提取到二维码信息后生成二维码
     */
    public function AlipayCode($orderInfo){
        $aliPay = new AlipayService();
        $aliPay->setAppid($this->config['appid']);
        $aliPay->setNotifyUrl($this->config['notify_url']);
        $aliPay->setRsaPrivateKey($this->config['rsaPrivateKey']);
        $aliPay->setTotalFee($orderInfo['amount']);
        $aliPay->setOutTradeNo($orderInfo['out_trade_no']);
        $aliPay->setOrderName($orderInfo['order_name']);
        $aliPay->setGatewayUrl($this->config['gatewayUrl']);
        $result =json_decode($aliPay->codePay(),true);
        return $result['alipay_trade_precreate_response'];
    }

    /**
     * 支付宝支付退款
     * @param array $orderInfo 退款信息（具体构建方式参考文档readme.md）
     * @return mixed 成功返回true，失败返回支付宝返回的数据
     */
    public function AlipayRefund($orderInfo){
        $aliPay = new AlipayService();
        $aliPay->setAppid($this->config['appid']);
        $aliPay->setRsaPrivateKey($this->config['rsaPrivateKey']);
        $aliPay->setTradeNo($orderInfo['tradeNo']);
        $aliPay->setOutTradeNo($orderInfo['out_trade_no']);
        $aliPay->setRefundAmount($orderInfo['refund_amount']);
        $result = $aliPay->doRefund();
        $result = $result['alipay_trade_refund_response'];
        if($result['code'] && $result['code']=='10000'){
            return true;
        }else{
            return $result;
        }
    }

    /**
     * 支付宝Js支付（可用于多个场景，包括APP、小程序、支付宝内网页）
     * @param array $orderInfo 订单信息（具体构建方式参考文档readme.md）
     * @return string 字符串，后面可使用AlipayJSBridge.call("tradePay", {orderStr: "<?php echo $orderStr?>"} 调起支付，具体查看支付宝文档
     */
    public function AlipayJsPay($orderInfo){
        $aliPay = new AlipayService();
        $aliPay->setAppid($this->config['appid']);
        $aliPay->setNotifyUrl($this->config['notify_url']);
        $aliPay->setRsaPrivateKey($this->config['rsaPrivateKey']);
        $aliPay->setTotalFee($orderInfo['amount']);
        $aliPay->setOutTradeNo($orderInfo['out_trade_no']);
        $aliPay->setOrderName($orderInfo['order_name']);
        $orderStr = $aliPay->getOrderStr();
        return $orderStr;
    }

    /**
     * 支付宝条码支付（当面付）
     * @param array $orderInfo 订单信息（具体构建方式参考文档readme.md）
     * @return array 支付宝反馈信息，可取$result['code']：10000支付成功，10003 待用户支付，其他的则根据$result['msg']信息确定
     */
    public function AlipayBarCode($orderInfo){
        $aliPay = new AlipayService();
        $aliPay->setAppid($this->config['appid']);
        $aliPay->setNotifyUrl($this->config['notify_url']);
        $aliPay->setRsaPrivateKey($this->config['rsaPrivateKey']);
        $aliPay->setTotalFee($orderInfo['amount']);
        $aliPay->setOutTradeNo($orderInfo['out_trade_no']);
        $aliPay->setOrderName($orderInfo['order_name']);
        $aliPay->setAuthCode($orderInfo['authCode']);
        $result = $aliPay->barCodePay(isset($orderInfo['store_id'])?$orderInfo['store_id']:null);
        $result = $result['alipay_trade_pay_response'];
        return $result;
    }

    /**
     * 支付宝转账到个人账户
     * @param array $orderInfo 转账信息（具体构建方式参考文档readme.md）
     * @return array 支付宝反馈信息
     */
    public function AlipayTransfer($orderInfo){
        $aliPay = new AlipayService();
        $aliPay->setAppid($this->config['appid']);
        $aliPay->setNotifyUrl($this->config['notify_url']);
        $aliPay->setRsaPrivateKey($this->config['rsaPrivateKey']);
        $result = $aliPay->transfer($orderInfo['account'],$orderInfo['real_name'],$orderInfo['amount'],$orderInfo['remark']);
        return $result;
    }

    /**
     * 支付宝转账状态查询
     * @param array $orderInfo 转账订单信息（具体构建方式参考文档readme.md）
     * @return array 支付宝反馈信息
     */
    public function AlipayTransferQuery($orderInfo){
        $aliPay = new AlipayService();
        $aliPay->setAppid($this->config['appid']);
        $aliPay->setRsaPrivateKey($this->config['rsaPrivateKey']);
        if( (!isset($orderInfo['outBizBo'])) || (!isset($orderInfo['orderId']))){
            return '商户订单号和支付宝转账单据号至少要有一个';
        }
        $arr=[];
        if(isset($orderInfo['outBizBo'])){
            $arr['outBizBo']=$orderInfo['outBizBo'];
        } else{
            $arr['outBizBo']='';
        }
        if(isset($orderInfo['orderId'])){
            $arr['orderId']=$orderInfo['orderId'];
        } else{
            $arr['orderId']='';
        }
        $result = $aliPay->TransferQuery($arr['outBizBo'],$arr['orderId']);
        return $result;
    }

    /**
     * 微信支付获取二维码（一般用于pc端支付），获取的为二维码信息，需将二维码信息转换为二维码图片
     * @param array $orderInfo 订单信息（具体构建方式参考文档readme.md）
     * @return mixed 微信返回的信息，可在其中提取到二维码信息
     */
    public function WxPayCode($orderInfo){
        $wxPay = new WxpayService($this->config['mchid'] ,$this->config['appid'],$this->config['apiKey']);
        $outTradeNo = $orderInfo['out_trade_no'];     //你自己的商品订单号
        $payAmount = $orderInfo['amount'];          //付款金额，单位:元
        $orderName = $orderInfo['order_name'];    //订单标题
        $notifyUrl = $this->config['notify_url'];     //付款成功后的回调地址(不要有问号)
        $payTime = time();      //付款时间
        $arr = $wxPay->NativePay($payAmount,$outTradeNo,$orderName,$notifyUrl,$payTime);
        return $arr;
    }

    /**
     * 微信手机网页端支付（微信内网页可以直接使用微信提供的js调起支付）
     * @param array $orderInfo 订单信息（具体构建方式参考文档readme.md）
     * @return mixed 会自动跳转至微信内完成支付
     */
    public function WxPayWap($orderInfo){
        $wxPay = new WxpayService($this->config['mchid'] ,$this->config['appid'],$this->config['apiKey']);
        $wxPay->setTotalFee($orderInfo['amount']);
        $wxPay->setOutTradeNo($orderInfo['out_trade_no']);
        $wxPay->setOrderName($orderInfo['order_name']);
        $wxPay->setNotifyUrl($this->config['notify_url']);
        $wxPay->setWapUrl($this->config['return_url']);
        $wxPay->setWapName($orderInfo['order_name']);
        $mwebUrl= $wxPay->H5Pay($orderInfo['amount'],$orderInfo['out_trade_no'],$orderInfo['order_name'],$this->config['notify_url']);
        header("Location: {$mwebUrl}");
        exit();
    }
    /**
     * 微信公众号支付
     * @param array $orderInfo 订单信息（具体构建方式参考文档readme.md）
     * @return mixed 返回微信返回的信息
     */
    public function WxJsPay($orderInfo){
        $wxPay = new WxpayService($this->config['mchid'] ,$this->config['appid'],$this->config['apiKey']);
        // $openId = $wxPay->GetOpenid($orderInfo['code']);      //获取openid
        // if(!$openId) exit('获取openid失败');
        $openId=$orderInfo['openid']??null;
        if($openId==null){
            $openId = $wxPay->GetOpenid($orderInfo['code']);      //获取openid
            if(!$openId) exit('获取openid失败');
        }
        $outTradeNo = $orderInfo['out_trade_no'];     //你自己的商品订单号
        $payAmount =$orderInfo['amount'];         //付款金额，单位:元
        $orderName = $orderInfo['order_name'];    //订单标题
        $notifyUrl = $this->config['notify_url'];     //付款成功后的回调地址(不要有问号)
        $payTime = time();      //付款时间
        $jsApiParameters = $wxPay->JsPay($openId,$payAmount,$outTradeNo,$orderName,$notifyUrl,$payTime);
        return $jsApiParameters;
    }

    /**
     * 微信APP支付(获取支付码)
     * @param array $orderInfo 订单信息（具体构建方式参考文档readme.md）
     * @return mixed 返回微信返回的信息
     */
    public function WxJsapiParams($orderInfo,$is_micro_app=false){
        $app=new App();
        $params=array(
            'body'=>$orderInfo['body']??"",
            'out_trade_no'=>$orderInfo['out_trade_no'],
            'total_fee'=>$orderInfo['amount'],
            'trade_type'=>$is_micro_app?'JSAPI':'APP',
            'appid'=>$this->config['appid'],
            'mch_id'=>$this->config['mchid'],
            'nonce_str'=>Common::genRandomString(),
            'notify_url'=>$this->config['notify_url']
        );
        $result=$app->unifiedOrder($params);
        return $result;
    }

    /**
     * 微信支付退款
     * @param array $orderInfo 退款信息（具体构建方式参考文档readme.md）
     * @return mixed 返回微信返回的信息
     */
    public function WxRefund($orderInfo){
        $orderNo = $orderInfo['out_trade_no'];                   //商户订单号（商户订单号与微信订单号二选一，至少填一个）
        $wxOrderNo =  $orderInfo['trade_no'];                    //微信订单号（商户订单号与微信订单号二选一，至少填一个）
        $totalFee =$orderInfo['amount'];                   //订单金额，单位:元
        $refundFee = $orderInfo['refund_amount'];                 //退款金额，单位:元
        $refundNo = $orderInfo['refund_trade_no'];        //退款订单号(可随机生成)
        $desc=$orderInfo['desc'];  //说明
        $wxPay = new WxpayService($this->config['mchid'] ,$this->config['appid'],$this->config['apiKey']);
        $result = $wxPay->doRefund($totalFee, $refundFee, $refundNo, $wxOrderNo,$orderNo,$desc);
        return $result;
    }

    /**
     * 微信支付到零钱
     * @param array $orderInfo 转账信息（具体构建方式参考文档readme.md）
     * @return mixed 返回微信返回的信息
     */
    public function WxTransfers($orderInfo){
        //①、获取当前访问页面的用户openid（如果给指定用户转账，则直接填写指定用户的openid)
        $wxPay = new WxpayService($this->config['mchid'] ,$this->config['appid'],$this->config['apiKey']);
        $openId=$orderInfo['openid']??null;
        if($openId==null){
            $openId = $wxPay->GetOpenid($orderInfo['code']);      //获取openid
            if(!$openId) exit('获取openid失败');
        }
        //②、付款
        $outTradeNo = $orderInfo['out_trade_no'];     //订单号
        $payAmount = $orderInfo['amount'];           //转账金额，单位:元。转账最小金额为1元
        $trueName = $orderInfo['real_name'];         //收款人真实姓名
        $desc=$orderInfo['desc'];
        $result = $wxPay->createJsBizPackage($openId,$payAmount,$outTradeNo,$trueName,$desc);
        return $result;
    }
}