<?php
namespace app\admin\controller;

use app\admin\BaseController;

class Common extends BaseController{
    public function upload(){
        $type=request()->param('type','');
        $file=request()->file('file');
        $info = $file->move(PUBLIC_PATH.'/upload/'.$type.'/');
        if($info){
            // 成功上传后 获取上传信息
            // 输出 jpg
            // echo $info->getExtension();
            // // 输出 20160820/42a79759f284b767dfcb2a0197904287.jpg
            // echo $info->getSaveName();
            // // 输出 42a79759f284b767dfcb2a0197904287.jpg
            // echo $info->getFilename(); 
            $saveName=$info->getSaveName();
            return $this->json(0,'上传成功',['src'=>'/upload/'.$type.'/'.$saveName]);
        }else{
            // 上传失败获取错误信息
            // echo $file->getError();
            return $this->json(-1,'上传失败',$file->getError());
        }
    }
}