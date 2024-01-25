<?php
namespace app\admin\controller;

use app\admin\BaseController;

class Ueditor extends BaseController{
    public function index(){
        //header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
        //header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
        error_reporting(E_ERROR);
        header("Content-Type: text/html; charset=utf-8");
 
        $CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(CONFIG_PATH."/Json/ueditorConfig.json")), true);
        $action = $_GET['action'];
 
        switch ($action) {
            case 'config':
				// echo preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(APP_PATH."/Json/ueditorConfig.json"));
				// dd($CONFIG);
                return json($CONFIG);
                break;
            /* 上传图片 */
            case 'uploadimage':
		        $fieldName = $CONFIG['imageFieldName'];
		        $result = $this->upFile($fieldName);
				return $result;
		        break;
            /* 上传涂鸦 */
            case 'uploadscrawl':
		        $config = array(
		            "pathFormat" => $CONFIG['scrawlPathFormat'],
		            "maxSize" => $CONFIG['scrawlMaxSize'],
		            "allowFiles" => $CONFIG['scrawlAllowFiles'],
		            "oriName" => "scrawl.png"
		        );
		        $fieldName = $CONFIG['scrawlFieldName'];
		        $base64 = "base64";
		        $result = $this->upBase64($config,$fieldName);
				return $result;
		        break;
            /* 上传视频 */
            case 'uploadvideo':
		        $fieldName = $CONFIG['videoFieldName'];
		        $result = $this->upFile($fieldName);
				return $result;
		        break;
            /* 上传文件 */
            case 'uploadfile':
		        $fieldName = $CONFIG['fileFieldName'];
		        $result = $this->upFile($fieldName);
				return $result;
                break;
            /* 列出图片 */
            case 'listimage':
			    $allowFiles = $CONFIG['imageManagerAllowFiles'];
			    $listSize = $CONFIG['imageManagerListSize'];
			    $path = $CONFIG['imageManagerListPath'];
			    $get =$_GET;
			    $result =$this->fileList($allowFiles,$listSize,$get);
				return $result;
                break;
            /* 列出文件 */
            case 'listfile':
			    $allowFiles = $CONFIG['fileManagerAllowFiles'];
			    $listSize = $CONFIG['fileManagerListSize'];
			    $path = $CONFIG['fileManagerListPath'];
			    $get = $_GET;
			    $result = $this->fileList($allowFiles,$listSize,$get);
				return $result;
                break;
            /* 抓取远程文件 */
            case 'catchimage':
		    	$config = array(
			        "pathFormat" => $CONFIG['catcherPathFormat'],
			        "maxSize" => $CONFIG['catcherMaxSize'],
			        "allowFiles" => $CONFIG['catcherAllowFiles'],
			        "oriName" => "remote.png"
			    );
			    $fieldName = $CONFIG['catcherFieldName'];
			    /* 抓取远程图片 */
			    $list = array();
			    isset($_POST[$fieldName]) ? $source = $_POST[$fieldName] : $source = $_GET[$fieldName];
			    foreach($source as $imgUrl){
					$info=$this->saveRemote($config,$imgUrl);
			        array_push($list, array(
			            "state" => $info["state"],
			            "url" => $info["url"],
			            "size" => $info["size"],
			            "title" => htmlspecialchars($info["title"]),
			            "original" => htmlspecialchars($info["original"]),
			            "source" => htmlspecialchars($imgUrl)
			        ));
			    }
 
			    $result = json(array(
			        'state' => count($list) ? 'SUCCESS':'ERROR',
			        'list' => $list
			    ));
				return $result;
                break;
            default:
                $result = json(array(
                    'state' => '请求地址出错'
                ));
				return $result;
                break;
        }
		if(preg_match("/^[\w_]+$/", $_GET["callback"])){
			echo htmlspecialchars($_GET["callback"]).'()';
		}else{
			echo json(array(
				'state' => 'callback参数不合法'
			));
		}
	}
	
	//上传文件
	private function upFile($fieldName){
        $type=request()->param('type','common');
        $file=request()->file($fieldName);
        $info = $file->move(ROOT_PATH.'/upload/'.$type.'/');
        if($info){
            $saveName=$info->getSaveName();
            $data=array(
                'state' => 'SUCCESS',
                'url' => '/upload/'.$type.'/'.$saveName,
                'title' => $info->getFilename(),
                'original' => $info->getFilename(),
                'type' => '.' . $info->getExtension()
            );
            return json($data);
        }else{
            // 上传失败获取错误信息
            // echo $file->getError();
            return $this->json(-1,'上传失败',$file->getError());
        }
	}
 
    //列出图片
	private function fileList($allowFiles,$listSize,$get){
		$dirname = './public/uploads/';
		$allowFiles = substr(str_replace(".","|",join("",$allowFiles)),1);
 
		/* 获取参数 */
		$size = isset($get['size']) ? htmlspecialchars($get['size']) : $listSize;
		$start = isset($get['start']) ? htmlspecialchars($get['start']) : 0;
		$end = $start + $size;
 
		/* 获取文件列表 */
		$path = $dirname;
		$files = $this->getFiles($path,$allowFiles);
		if(!count($files)){
		    return json(array(
		        "state" => "no match file",
		        "list" => array(),
		        "start" => $start,
		        "total" => count($files)
		    ));
		}
 
		/* 获取指定范围的列表 */
		$len = count($files);
		for($i = min($end, $len) - 1, $list = array(); $i < $len && $i >= 0 && $i >= $start; $i--){
		    $list[] = $files[$i];
		}
 
		/* 返回数据 */
		$result = json(array(
		    "state" => "SUCCESS",
		    "list" => $list,
		    "start" => $start,
		    "total" => count($files)
		));
 
		return $result;
	}
 
   	/*
	 * 遍历获取目录下的指定类型的文件
	 * @param $path
	 * @param array $files
	 * @return array
	*/
    private function getFiles($path,$allowFiles,&$files = array()){
	    if(!is_dir($path)) return null;
	    if(substr($path,strlen($path)-1) != '/') $path .= '/';
	    $handle = opendir($path);
			
	    while(false !== ($file = readdir($handle))){
	        if($file != '.' && $file != '..'){
	            $path2 = $path.$file;
	            if(is_dir($path2)){
	                $this->getFiles($path2,$allowFiles,$files);
	            }else{
		            if(preg_match("/\.(".$allowFiles.")$/i",$file)){
		                $files[] = array(
		                    'url' => substr($path2,1),
		                    'mtime' => filemtime($path2)
		                );
		            }
	            }
	        }
	    }
		
	    return $files;
    }
 
    //抓取远程图片
	private function saveRemote($config,$fieldName){
		$lastname='png';
		$savename=str_replace('.','',microtime(true)).GetRandStr(32).'.'.$lastname;
        $path2='/upload/common/'.date('Ymd').'/';
		$path=PUBLIC_PATH.$path2;
		if(is_dir($path)==false){
			mkdir($path,777,true);
			chmod($path,777);
		}
		$savefile=$path.$savename;
		downLoadFile($fieldName,$savefile);
		$data=array(
			'state' => 'SUCCESS',
			'url' => $path2.$savename,
			'title' => '网络图片',
			'original' => 'png',
			'type' => 'png'
		);
		return json($data)->send();
	}
 
    /*
	 * 处理base64编码的图片上传
	 * 例如：涂鸦图片上传
	*/
	private function upBase64($config,$fieldName){
	    $base64Data = $_POST[$fieldName];
	    $img = base64_decode($base64Data);
 
	    $dirname = './public/uploads/scrawl/';
	    $file['filesize'] = strlen($img);
	    $file['oriName'] = $config['oriName'];
	    $file['ext'] = strtolower(strrchr($config['oriName'],'.'));
	    $file['name'] = uniqid().$file['ext'];
	    $file['fullName'] = $dirname.$file['name'];
	    $fullName = $file['fullName'];
 
 	    //检查文件大小是否超出限制
	    if($file['filesize'] >= ($config["maxSize"])){
  		    $data=array(
			    'state' => '文件大小超出网站限制',
		    );
		    return json_encode($data);
	    }
 
	    //创建目录失败
	    if(!file_exists($dirname) && !mkdir($dirname,0777,true)){
	        $data=array(
			    'state' => '目录创建失败',
		    );
		    return json_encode($data);
	    }else if(!is_writeable($dirname)){
	        $data=array(
			    'state' => '目录没有写权限',
		    );
		    return json_encode($data);
	    }
 
	    //移动文件
	    if(!(file_put_contents($fullName, $img) && file_exists($fullName))){ //移动失败
            $data=array(
		        'state' => '写入文件内容错误',
		    );
	    }else{ //移动成功	       
	        $data=array(
			    'state' => 'SUCCESS',
			    'url' => substr($file['fullName'],1),
			    'title' => $file['name'],
			    'original' => $file['oriName'],
			    'type' => $file['ext'],
			    'size' => $file['filesize'],
		    );
	    }
		
	    return json($data);
	}
}