<?php
namespace app\admin\controller;

use app\admin\BaseController;
use app\model\User as ModelUser;
use app\model\UserGroup;

class User extends BaseController{
    public function group(){
        return $this->fetch();
    }

    public function index(){
        return $this->fetch();
    }

    public function list(){
        $state=request()->post('state',-1);
        $keyword=request()->post('keyword','');
        $basic=ModelUser::order('uid','DESC')->with('group');
        if($state>-1){
            $basic=$basic->where('state',$state);
        }
        if($keyword!=''){
            $basic=$basic->where('username|qq|nickname','like',"%{$keyword}%");
        }
        return $this->layJson($basic);
    }

    public function groupuser(){
        $id=request()->get('id',0);
        $this->assign('id',$id);
        $group=UserGroup::get($id);
        if(request()->isGet()){
            $this->assign('groupname',$group['name']);
            return $this->fetch();
        }
        $list=ModelUser::where('gid',$id)->select();
        $count=ModelUser::where('gid',$id)->count();
        return $this->json(0,'操作成功',$list,['count'=>$count]);
    }

    public function grouplist(){
        $list=UserGroup::order('sort')->select()->map(function($item){
            $item['number']=ModelUser::where('gid',$item['gid'])->count();
            return $item;
        });
        return $this->json(0,'获取成功',$list);        
    }

    public function editgroupsort(){
        $id=request()->post('id',0);
        $sort=request()->post('value',0);
        if(!is_numeric($sort)){
            return $this->json(-1,'非法操作');
        }
        UserGroup::where('gid',$id)->update([
            'sort'=>$sort
        ]);
        return $this->json();
    }

    public function addgroup(){
        $id=request()->param('id',0);
        if(request()->isGet()){
            $this->assign('id',$id);
            if($id>0){
                $this->assign('info',UserGroup::get($id));
            }
            return $this->fetch();
        }
        $data=[
            'name'=>request()->post('name',''),
            'sort'=>request()->post('sort')
        ];
        if($id>0){
            UserGroup::where('gid',$id)->update($data);
        } else{
            $info=new UserGroup($data);
            $info->save();
        }
        return $this->json();
    }

    public function moregroup(){
        $id=request()->post('id',0);
        UserGroup::where('is_more',1)->update([
            'is_more'=>0
        ]);
        UserGroup::where('gid',$id)->update([
            'is_more'=>1
        ]);
        return $this->json();
    }

    public function delgroup(){
        $id=request()->post('id',0);
        $group=UserGroup::get($id);
        if($group['is_more']>0){
            return $this->json(-7,'默认分组不可删除');
        }
        $count=ModelUser::where('gid',$group['gid'])->count();
        if($count>0){
            return $this->json(-9,'该分组有用户，不可删除，请先移入其他分组');
        }
        $group->delete();
        return $this->json();
    }

    public function grouplists(){
        $id=request()->param('id',0);
        if(request()->isGet()){
            $this->assign('id',$id);
            return $this->fetch();
        }
        $list=UserGroup::where('gid','<>',$id)->select();
        return $this->json(0,'获取成功',$list);
    }

    public function moveuser(){
        $uid=request()->post('uid');
        $group=request()->post('group');
        if(count($uid)<1){
            return $this->json(-7,'无用户');
        }
        ModelUser::whereIn('uid',$uid)->update([
            'gid'=>$group
        ]);
        return $this->json();
    }

    public function adduser(){
        $id=request()->param('id',0);
        if(request()->isGet()){
            $this->assign('id',$id);
            if($id>0){
                $this->assign('info',ModelUser::get($id));
            }
            return $this->fetch();
        }
        $data=[
            'username'=>request()->post('username'),
            'nickname'=>request()->post('nickname'),
            'qq'=>request()->post('qq'),
            'telephone'=>request()->post('telephone')
        ];
        if($id>0){  //编辑用户
            $password=request()->post('password','');
            if($password!=''){
                $salt=GetRandStr(4);
                $data['salt']=$salt;
                $data['password']=md5(md5($password).$salt);
            }
            ModelUser::where('uid',$id)->update($data);
        } else{
            $password=request()->post('password','');
            $salt=GetRandStr(4);
            $data['salt']=$salt;
            $data['password']=md5(md5($password).$salt);
            $user=new ModelUser($data);
            $user->save();
        }
        return $this->json();
    }

    public function deluser(){
        $id=request()->post('uid');
        $user=ModelUser::get($id);
        if($user){
            $user->delete();
        }
        return $this->json();
    }
}