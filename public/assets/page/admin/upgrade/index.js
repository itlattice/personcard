window.onload=function(){
    function updateNum(){
         ajax({
            url:'/admin/upgrade/scale',
            data:{},
            success:function(res){
                if(res.code===0){
                    $('#newverid').html(res.count);
                    if(res.count<1){
                        layer.msg('更新成功');
                        clearInterval(upgradeIndex);
                        setTimeout(function(res){
                            parent.layer.closeAll();
                            parent.window.location.reload();
                        },1500)
                    }
                }
            }
        })
    }
   var upgradeIndex=setInterval(function(){
       updateNum();
   },3000);

   function startUpdate(){
        ajax({
            url:'/admin/upgrade/start',
            data:{},
            success:function(res){
            }
        })
   }
   setTimeout(function(){
       startUpdate();
   },5000)
}