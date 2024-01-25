<?php
namespace iboxs\payment\libs;
class RemoveVendorGitDir
{
    public static function postUpdate()
    {
        self::del();
    }
    public static function postInstall()
    {
        self::del();
    }

    private static function findGitDir($dir){
        static $gitDirs=[];
        $items = scandir($dir);
        foreach($items as $item){
            if($item!='.' && $item!='..'){
                if(!is_dir($dir.$item)){
                    continue;
                }else{
                    if($item=='.git'){
                        $gitDirs[] = $dir.$item;
                    }else{
                        self::findGitDir($dir.$item.DS);
                    }
                }
            }
        }
        return $gitDirs;
    }

    private static function del(){
        defined('DS')?:define('DS',DIRECTORY_SEPARATOR);
        $baseDir = __DIR__.DS.'..'.DS.'vendor'.DS;
        $gitDirs = self::findGitDir($baseDir);

        if($gitDirs){
            if(strpos(strtoupper(PHP_OS),'WIN')!==false){
                foreach ($gitDirs as $gitDir){
                    exec("rmdir /s /q  ".$gitDir);
                }
            }else{
                foreach ($gitDirs as $gitDir){
                    exec("rm -rf ".$gitDir);
                }
            }
        }
        return true;
    }

}