ajax=(object)=>{
    object.data.token=$('#token').val();
    let resp={};
    $.ajax({
        url:object.url,
        data:object.data,
        type:'POST',
        async: !!object.async,
        xhrFields: {
            withCredentials:true  //支持附带详细信息
        },
        crossDomain:true,//请求偏向外域
        success:function(res){
            resp=res
            object.success(res);
        },
        fail:function(res){
            layer.msg('错误，请重试');
        }
    })
    return resp;
}

isPhone=(str)=>{
    var myreg=/^[1][3,4,5,6,7,8,9][0-9]{9}$/;
    if (!myreg.test(str)) {
        return false;
    } else {
        return true;
    }
}
isEmail=(str)=>{
    var reg = new RegExp("^[a-z0-9]+([._\\-]*[a-z0-9])*@([a-z0-9]+[-a-z0-9]*[a-z0-9]+.){1,63}[a-z0-9]+$"); //正则表达式
    if(str === ""){ //输入不能为空
        return false
    }else if(!reg.test(str)){ //正则验证不通过，格式不对
        return false;
    }
    return true;
}

isNumber=(val)=>{
    var regPos = /^\d+(\.\d+)?$/; //非负浮点数
    var regNeg = /^(-(([0-9]+\.[0-9]*[1-9][0-9]*)|([0-9]*[1-9][0-9]*\.[0-9]+)|([0-9]*[1-9][0-9]*)))$/; //负浮点数
    if(regPos.test(val) || regNeg.test(val)){
        return true;
    }else{
        return false;
    }
}

var clearCacheIndex=0;

clearCache=()=>{
    if(clearCacheIndex>0){
        layer.msg('2分钟内只可以清除1次，请'+clearCacheIndex+'秒后重试');
        return;
    }
    ajax({
        url:'/admin/index/clearcache',
        data:{},
        success:function(res){
            layer.msg(res.msg);
            clearCacheIndex=120;
            var index=setInterval(function(){
                clearCacheIndex--;
                if(clearCacheIndex<=0){
                    clearCacheIndex=0;
                    clearInterval(index);
                }
            },1000)
        }
    })
}

function updatePwd(){
    layer.open({
        type:2,
        title:'修改密码',
        maxmin:false,
        content:'/admin/info/updatepwd',
        area:["500px",'400px']
    })
}

var newAuthIndex=0;
function newAuth(){
    if(newAuthIndex>0){
        layer.msg('5分钟内只可以刷新1次，请'+newAuthIndex+'秒后重试');
        return;
    }
    ajax({
        url:'/admin/index/newauth',
        data:{},
        success:function(res){
            layer.msg(res.msg);
            if(res.code==0){
                newAuthIndex=300;
                var indexs=setInterval(function(){
                    if(newAuthIndex==298){
                        location.reload();
                    }
                    newAuthIndex--;
                    if(newAuthIndex<=0){
                        newAuthIndex=0;
                        clearInterval(indexs);
                    }
                },1000)
            }
        }
    })
}

function upgrade(){
    layer.open({
        type:2,
        content:'/admin/upgrade/index',
        maxmin:false,
        area:['800px','500px'],
        title:'更新过程中不要关闭本窗口，以免系统异常'
    });
}

var newUpgradeIndex=0;
function checkUpgrade(){
    if(newUpgradeIndex>0){
        layer.msg('5分钟内只可以刷新1次，请'+newUpgradeIndex+'秒后重试');
        return;
    }
    ajax({
        url:'/admin/index/checkupdgrade',
        data:{},
        success:function(res){
            layer.msg(res.msg);
            if(res.code==0){
                newUpgradeIndex=300;
                var indexs=setInterval(function(){
                    newUpgradeIndex--;
                    if(newUpgradeIndex<=0){
                        newUpgradeIndex=0;
                        clearInterval(indexs);
                    }
                },1000)
            } else if(res.code==1){
                setTimeout(function(){
                    upgrade();
                },2000)
            }
        }
    })
}