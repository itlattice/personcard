function alipayPc(){ //支付宝PC端
    var html=$('#alipayPc').html();
    var state=0;
    if(html=='已启用'){
        state=0;
        $('#alipayPc').html('已禁用').addClass('layui-btn-danger').removeClass('layui-btn-normal');
    } else if(html=='已禁用'){
        state=1;
        $('#alipayPc').html('已启用').removeClass('layui-btn-danger').addClass('layui-btn-normal');
    }
    return payState('alipay','pc',state);
}

function alipayWap(){
    var html=$('#alipayWap').html();
    var state=0;
    if(html=='已启用'){
        state=0;
        $('#alipayWap').html('已禁用').addClass('layui-btn-danger').removeClass('layui-btn-normal');
    } else if(html=='已禁用'){
        state=1;
        $('#alipayWap').html('已启用').removeClass('layui-btn-danger').addClass('layui-btn-normal');
    }
    return payState('alipay','wap',state);
}

function alipayCodePc(){ //支付宝PC端
    var html=$('#alipayCodePc').html();
    var state=0;
    if(html=='已启用'){
        state=0;
        $('#alipayCodePc').html('已禁用').addClass('layui-btn-danger').removeClass('layui-btn-normal');
    } else if(html=='已禁用'){
        state=1;
        $('#alipayCodePc').html('已启用').removeClass('layui-btn-danger').addClass('layui-btn-normal');
    }
    return payState('alipaycode','pc',state);
}

function alipayCodeWap(){
    var html=$('#alipayCodeWap').html();
    var state=0;
    if(html=='已启用'){
        state=0;
        $('#alipayCodeWap').html('已禁用').addClass('layui-btn-danger').removeClass('layui-btn-normal');
    } else if(html=='已禁用'){
        state=1;
        $('#alipayCodeWap').html('已启用').removeClass('layui-btn-danger').addClass('layui-btn-normal');
    }
    return payState('alipaycode','wap',state);
}

function wechatPc(){
    var html=$('#wechatPc').html();
    var state=0;
    if(html=='已启用'){
        state=0;
        $('#wechatPc').html('已禁用').addClass('layui-btn-danger').removeClass('layui-btn-normal');
    } else if(html=='已禁用'){
        state=1;
        $('#wechatPc').html('已启用').removeClass('layui-btn-danger').addClass('layui-btn-normal');
    }
    return payState('wechat','pc',state);
}
function wechatWap(){
    var html=$('#wechatWap').html();
    var state=0;
    if(html=='已启用'){
        state=0;
        $('#wechatWap').html('已禁用').addClass('layui-btn-danger').removeClass('layui-btn-normal');
    } else if(html=='已禁用'){
        state=1;
        $('#wechatWap').html('已启用').removeClass('layui-btn-danger').addClass('layui-btn-normal');
    }
    return payState('wechat','wap',state);
}
function ePc(){
    var html=$('#ePc').html();
    var state=0;
    if(html=='已启用'){
        state=0;
        $('#ePc').html('已禁用').addClass('layui-btn-danger').removeClass('layui-btn-normal');
    } else if(html=='已禁用'){
        state=1;
        $('#ePc').html('已启用').removeClass('layui-btn-danger').addClass('layui-btn-normal');
    }
    return payState('epay','pc',state);
}
function eWap(){
    var html=$('#eWap').html();
    var state=0;
    if(html=='已启用'){
        state=0;
        $('#eWap').html('已禁用').addClass('layui-btn-danger').removeClass('layui-btn-normal');
    } else if(html=='已禁用'){
        state=1;
        $('#eWap').html('已启用').removeClass('layui-btn-danger').addClass('layui-btn-normal');
    }
    return payState('epay','wap',state);
}
function codePc(){
    var html=$('#codePc').html();
    var state=0;
    if(html=='已启用'){
        state=0;
        $('#codePc').html('已禁用').addClass('layui-btn-danger').removeClass('layui-btn-normal');
    } else if(html=='已禁用'){
        state=1;
        $('#codePc').html('已启用').removeClass('layui-btn-danger').addClass('layui-btn-normal');
    }
    return payState('codepay','pc',state);
}
function codeWap(){
    var html=$('#codeWap').html();
    var state=0;
    if(html=='已启用'){
        state=0;
        $('#codeWap').html('已禁用').addClass('layui-btn-danger').removeClass('layui-btn-normal');
    } else if(html=='已禁用'){
        state=1;
        $('#codeWap').html('已启用').removeClass('layui-btn-danger').addClass('layui-btn-normal');
    }
    return payState('codepay','wap',state);
}

function alipayCodeState(){
    var html=$('#alipayCodeState').html();
    var state=0;
    if(html=='已启用'){
        state=0;
        $('#alipayCodeState').html('已禁用').addClass('layui-btn-danger').removeClass('layui-btn-normal');
    } else if(html=='已禁用'){
        state=1;
        $('#alipayCodeState').html('已启用').removeClass('layui-btn-danger').addClass('layui-btn-normal');
    }
    return payState('alipaycode','on',state);
}

function alipayState(){
    var html=$('#alipayState').html();
    var state=0;
    if(html=='已启用'){
        state=0;
        $('#alipayState').html('已禁用').addClass('layui-btn-danger').removeClass('layui-btn-normal');
    } else if(html=='已禁用'){
        state=1;
        $('#alipayState').html('已启用').removeClass('layui-btn-danger').addClass('layui-btn-normal');
    }
    return payState('alipay','on',state);
}
function wechatState(){
    var html=$('#wechatState').html();
    var state=0;
    if(html=='已启用'){
        state=0;
        $('#wechatState').html('已禁用').addClass('layui-btn-danger').removeClass('layui-btn-normal');
    } else if(html=='已禁用'){
        state=1;
        $('#wechatState').html('已启用').removeClass('layui-btn-danger').addClass('layui-btn-normal');
    }
    return payState('wechat','on',state);
}
function epayState(){
    var html=$('#epayState').html();
    var state=0;
    if(html=='已启用'){
        state=0;
        $('#epayState').html('已禁用').addClass('layui-btn-danger').removeClass('layui-btn-normal');
    } else if(html=='已禁用'){
        state=1;
        $('#epayState').html('已启用').removeClass('layui-btn-danger').addClass('layui-btn-normal');
    }
    return payState('epay','on',state);
}
function codeState(){
    var html=$('#codeState').html();
    var state=0;
    if(html=='已启用'){
        state=0;
        $('#codeState').html('已禁用').addClass('layui-btn-danger').removeClass('layui-btn-normal');
    } else if(html=='已禁用'){
        state=1;
        $('#codeState').html('已启用').removeClass('layui-btn-danger').addClass('layui-btn-normal');
    }
    return payState('codepay','on',state);
}

function payState(key,field,state){
    ajax({
        url:'/admin/config/paystateconfig',
        data:{
            field:field,
            key:key,
            state:state
        },
        success:function(res){
            layer.msg(res.msg);
        }
    })
}

layui.use(['laydate', 'form'], function () {
    var laydate = layui.laydate;
    var form = layui.form;

    //执行一个laydate实例
    laydate.render({
        elem: '#start' //指定元素
    });

    //执行一个laydate实例
    laydate.render({
        elem: '#end' //指定元素
    });
});

function payconfig(type){
  layer.open({
    title:'支付通道配置',
    type:2,
    maxmin:false,
    content:'/admin/config/payconfig?type='+type,
    area: ['720px', '550px'],
    end:function(){
        location.reload();
    },
    btn:['确定','取消'],
    yes:function(index){
      var form = window["layui-layer-iframe" + index].form;
      console.log(form);
      var data = form.val('formTest');
      console.log(data);
      ajax({
        url:'/admin/config/payconfighandle?type='+type,
        data:data,
        success:function(res){
          if(res.code==0){
            layer.msg('配置成功',function(){
              layer.closeAll();
            })
          } else{
            layer.msg(res.msg);
          }
        }
      })
    }
  })
}