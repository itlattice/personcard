$('#sendtestemail').click(function(){
    var smtp=$('#smtp').val();
    var pwd=$('#smtppwd').val();
    var usr=$('#smtpusr').val();
    var email=$('#smtpemail').val();
    var send=$('#smtpsend').val();
    if(smtp==''||pwd==''||usr==''||email==''||send==''){
        layer.msg('请把信息填写完整');
        return false;
    }
    ajax({
        url:'/admin/config/sendtestemail',
        data:{
            smtp:smtp,
            pwd:pwd,
            usr:usr,
            email:email,
            send:send
        },
        success:function(res){
            if(res.code==0){
                layer.msg('发送成功');
            } else{
                layer.msg(res.msg);
            }
        }
    })
})

layui.use(['form', 'layedit', 'laydate'], function () {
    var form = layui.form;
    form.render();
    var upload=layui.upload;

    form.on('submit(config)', function (data) {
        console.log(data);
        var sumit = data.field;
        sumit.indexwindows = indexwindows.getContent();
        sumit.goodswindows = goodswindows.getContent();
        sumit.notice = notice.getContent(); 
        sumit.orderemail=orderemail.getContent();

        ajax({
            url: '/admin/config/submit',
            data: data.field,
            success: function (res) {
                if (res.code == 0) {
                    layer.msg('操作成功');
                } else {
                    layer.msg(res.msg);
                }
            }
        })
        return false;
    })

    upload.render({
        elem: '#uploadlogo'
        ,url: '/admin/common/upload?type=logo' //此处配置你自己的上传接口即可
        ,accept: 'file' //普通文件
        ,exts: 'png|jpg|jpeg|bng'
        ,done: function(res){
            layer.msg('上传成功');
            console.log(res);
            $('#weblogo').attr('src',res.data.src).show();
            $('#weblogodiv').show();
            $('#weblogoinput').val(res.data.src);
        }
    });
})
var goodswindows = UE.getEditor('goodswindows');
goodswindows.ready(function () {
    goodswindows.setHeight(400);
});
var indexwindows = UE.getEditor('indexwindows');
indexwindows.ready(function () {
    indexwindows.setHeight(400);
});
var notice = UE.getEditor('notice');
notice.ready(function () {
    notice.setHeight(400);
});
var orderemail = UE.getEditor('orderemail');
orderemail.ready(function () {
    orderemail.setHeight(400);
});