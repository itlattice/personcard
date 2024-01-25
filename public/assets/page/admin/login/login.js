$(function() {
    layui.use('form', function(){
      var form = layui.form;
      //监听提交
      form.on('submit(login)', function(data){
        data.field.password=hex_md5(data.field.password);
        ajax({
          url:'/admin/login/loginpost',
          data:data.field,
          success:function(res){
            if(res.code<0){
              layer.msg(res.msg);
              $('#code').val('');
              $('#codeimg').attr('src','/captcha?'+Math.random());
              return false;
            } else{
              layer.msg('登录成功',function(){
                location.href='/admin/';
              })
              return false
            }
          }
        })
        return false;
      });
    });
  })