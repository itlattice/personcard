<?php
namespace app\install\controller;

use app\common\Config;
use app\model\Admin;
use Exception;
use mysqli;
use PDO;
use iboxs\Controller;
use iboxs\Db;
use iboxs\facade\Cache;

class Index extends Controller
{
    public function index(){
        /**
         * PHP版本检查
         * 安装环境检查（目录） 
         */
        $lock=APP_PATH.'install/install.lock';
        if(file_exists($lock)){
           die('lock'); 
        }
        if (version_compare("7.2", PHP_VERSION, ">")) {
            die("PHP版本过低，最低要求PHP7.2版本");
        }
        $query0=substr(sprintf('%o', fileperms(ROOT_PATH)), -4);
        $query1=substr(sprintf('%o', fileperms(ROOT_PATH.'app/')), -4);
        $query2=substr(sprintf('%o', fileperms(ROOT_PATH.'runtime/')), -4);
        if($query0!='0777'){
            die("目录权限错误，需777权限：".ROOT_PATH);
        }
        if($query1!='0777'){
            die("目录权限错误，需777权限：".ROOT_PATH.'app/');
        }
        if($query2!='0777'){
            die("目录权限错误，需777权限：".ROOT_PATH.'runtime/');
        }
        return $this->fetch();
    }

    public function start(){
        try{
            $data=request()->post();
            $text=file_get_contents(ROOT_PATH.'/envexam');
            $ver=file_get_contents(ROOT_PATH.'ver.txt');
            $text=str_replace('{appver}',$ver,$text);
            $text=str_replace('{host}',$data['mysqlHostname'],$text);
            $text=str_replace('{data}',$data['mysqlDatabase'],$text);
            $text=str_replace('{usr}',$data['mysqlUsername'],$text);
            $text=str_replace('{pwd}',$data['mysqlPassword'],$text);
            file_put_contents(ROOT_PATH.'.env',$text);
            /**********执行初始化数据库************/
            $sqlFile=APP_PATH.'install/data/sql.sql';
            if(!file_exists($sqlFile)){
                return json([
                    'code'=>-4,
                    'msg'=>'数据库文件出错'
                ]);
            }
            $sql = file_get_contents($sqlFile);
            $mysqlHostname=$data['mysqlHostname'];
            $mysqlUsername=$data['mysqlUsername'];
            $mysqlPassword=$data['mysqlPassword'];
            $mysqlDatabase=$data['mysqlDatabase'];
            $mysqlHostport=3306;

            $salt=GetRandStr(4);
            $password=md5(md5($data['adminPassword']).$salt);
            $username=$data['adminUsername'];
            $sql.="insert into gz_admin(`username`,`password`,`salt`,`top`) values('{$username}','{$password}','{$salt}',1);";
            try {
                $pdo = new PDO("mysql:host={$mysqlHostname}" . ($mysqlHostport ? ";port={$mysqlHostport}" : ''), $mysqlUsername, $mysqlPassword);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->query("CREATE DATABASE IF NOT EXISTS `{$mysqlDatabase}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
                $instance = Db::connect([
                    'type' => "mysql", 'hostname' => "{$mysqlHostname}", 'hostport' => "{$mysqlHostport}", 'database' => "{$mysqlDatabase}", 'username' => "{$mysqlUsername}", 'password' => "{$mysqlPassword}",'prefix' => ""
                ]);
                $sql=str_replace('{database}',$mysqlDatabase,$sql);
    
                // 查询一次SQL,判断连接是否正常
                $instance->execute("SELECT 1");
                $_mysqli = new mysqli($data['mysqlHostname'],$data['mysqlUsername'],$data['mysqlPassword'],$data['mysqlDatabase']);
                if (mysqli_connect_errno()) {
                    return json([
                        'code'=>-4,
                        'msg'=>'链接数据库出错'
                    ]);
                }
                //执行sql语句
                $_mysqli->multi_query($sql);
                $_mysqli->close();
            } catch (\PDOException $e) {
                if(strstr($e->getMessage(), '(using password: YES)')){
                    throw new Exception('数据库连接失败，数据库密码错误');
                }else{
                    throw new Exception($e->getMessage());
                }
            }
            /*********新增管理员信息和配置数据写入*****************/
            // Config::set('installInfo',$data);
            Cache::clear();
            file_put_contents(APP_PATH.'install/install.lock','');
            return json([
                'code'=>1,
                'msg'=>'安装成功',
                'data'=>[
                    'adminName'=>'admin'
                ]
            ]);
        } catch(Exception $ee){
            return json([
                'code'=>-4,
                'msg'=>'安装错误'.$ee->getMessage()
            ]);
        }
    }
}