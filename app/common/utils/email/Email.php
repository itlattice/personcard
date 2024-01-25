<?php
namespace app\common\utils\email;

use app\Base;
use Exception;
use PHPMailer\PHPMailer\PHPMailer;

class Email{
    /**
     * 发送订单邮件
     * 收件箱
     * 卡密
     */
    public static function sendOrderEmail($email,$card,$order){
        $sysconfig=Base::getSysConfig();
        $webConfig=Base::getWebConfig();
        $tmp=$sysconfig['orderemail'];  //模板
        $subject=$sysconfig['orderemailsubject'];
        // 可用变量【订单号：{order}】、【卡密：{card}】、【发货时间：{goodstime}】、【网站名称：{sitename}】
        $tmp=str_replace('{order}',$order['order_num'],$tmp);
        $tmp=str_replace('{card}',$card,$tmp);
        $tmp=str_replace('{goodstime}',$order['goods_time'],$tmp);
        $tmp=str_replace('{sitename}',$webConfig['title'],$tmp);
        $user=self::getEmailUser();
        return self::SendEmail($user,$subject,$email,$tmp);
    }

    /**
     * 发送邮件验证码
     * @param string $key 模板key
     * @param string $email 邮箱
     * @param string|int $code 验证码
     * @return bool 发送状态
     */
    public static function SendEmailCode($key,$email,$code,$param=[]){
        // $info=self::getEmailTmp($key);
        // if($info==false){
        //     return false;
        // }
        // $subject=$info['subject'];
        // $content=$info['content'];
        // foreach($param as $c=>$d){
        //     $subject=str_replace($c,$d,$subject);
        //     $content=str_replace($c,$d,$content);
        // }
        // $content=str_replace('{code}',$code,$content);
        // $content=str_replace('{date}',date('Y-m-d H:i'),$content);
        // $user=self::getEmailUser('code');
        // return self::SendEmail($info,$user,$email,$subject,$content,$key,$param);
    }

    /**
     * 执行发件
     * @param string $emailInfo 邮件模板信息
     * @param string $user 发件邮箱信息
     * @param string $email 收件箱
     * @param string $subject 邮件主题
     * @param string $text 邮件内容
     */
    public static function SendEmail($user,$subject,$email,$text){
        $mail = new PHPMailer(env('app_debug',false));                              // Passing `true` enables exceptions
        try {
            //服务器配置
            $mail->CharSet ="UTF-8";                     //设定邮件编码
            $mail->SMTPDebug = 0;                        // 调试模式输出
            $mail->isSMTP();                             // 使用SMTP
            $mail->Host = $user['smtp'];                // SMTP服务器
            $mail->SMTPAuth = true;                      // 允许 SMTP 认证
            $mail->Username = $user['email'];                // SMTP 用户名  即邮箱的用户名
            $mail->Password = $user['pwd'];             // SMTP 密码  部分邮箱是授权码(例如163邮箱)
            $mail->SMTPSecure = 'ssl';                    // 允许 TLS 或者ssl协议
            $mail->Port = 465;                            // 服务器端口 25 或者465 具体要看邮箱服务器支持
            $mail->setFrom($user['email'], $user['username']);  //发件人
            $mail->addAddress($email);  // 收件人
            //$mail->addAddress('ellen@example.com');  // 可添加多个收件人
            // $mail->addReplyTo('xxxx@163.com', 'info'); //回复的时候回复给哪个邮箱 建议和发件人一致
            //$mail->addCC('cc@example.com');                    //抄送
            //$mail->addBCC('bcc@example.com');                    //密送
            //发送附件
            // $mail->addAttachment('../xy.zip');         // 添加附件
            // $mail->addAttachment('../thumb-1.jpg', 'new.jpg');    // 发送附件并且重命名
            //Content
            $mail->isHTML(true);                                  // 是否以HTML文档格式发送  发送后客户端可直接显示对应HTML内容
            $mail->Subject = $subject;
            $mail->Body    = $text;
            $mail->AltBody = '请使用支持HTML的客户端查看邮件';
        
            $result=$mail->send();
            if($result==false){
                return array(false,'发送失败，请检查配置是否正确');
            }
            return array($result,'success');
        } catch (Exception $e) {
            return array(false,$e->getMessage());
        }
    }


    public static function getEmailUser(){
        $sysconfig=Base::getSysConfig();
        $user=[
            'smtp'=>$sysconfig['email']['smtp'],
            'email'=>$sysconfig['email']['email'],
            'pwd'=>$sysconfig['email']['pwd'],
            'username'=>$sysconfig['email']['name']
        ];
        return $user;
    }
}
