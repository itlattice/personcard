<?php
namespace app\model;

use iboxs\Model;

class Log extends Model
{
    protected $pk="id";

    protected $autoWriteTimestamp = true;
    protected $createTime = 'add_time';
    protected $updateTime = false;

    public static function InsertLog(){
        if(request()->isGet()){
            return;
        }
        self::where('add_time','<',(time()-90*86400))->delete();
        $log=new self([
            'ip'=>request()->ip(),
            'browser'=> $_SERVER['HTTP_USER_AGENT']??'',
            'request'=>request()->url(false),
            'data'=>json_encode(request()->param(),JSON_UNESCAPED_UNICODE)
        ]);
        $log->save();
    }
}