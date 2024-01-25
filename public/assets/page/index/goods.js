layui.use(['form'],function(){
    var form=layui.form;

    form.on('submit(config)',function(data){
        if(data.field.specs<1){
            layer.msg('请先选择规格');
            return;
        }
        console.log(data.field);
        ajax({
            url:'/index/order/submitorder',
            data:data.field,
            success:function(res){
                if(res.code==0){  //下单成功
                    location.href=res.url;
                    return;
                }
                layer.msg(res.msg);
                if(res.url!=undefined){
                    location.href=res.url;
                }
            }
        })
    })

    form.on('checkbox(emailcheck)',function(data){
        if(data.elem.checked==true){
            $('#emailid').show();
           data.elem.checked=false 
        } else{
            $('#emailid').hide();
           data.elem.checked=true 
        }
    })
    form.render();
})

function specsClick(id,item){
    $('.active').removeClass('active');
    $(item).addClass('active');
    $('#specsinfo').val(id);
    getPrice();
}

function getPrice(){
    var id=$('.active').data('id');
    var goodsid=$('#goods_id').val();
    var orderNumber=$('#orderNumber').val();
    ajax({
        url:'/index/goods/getprice',
        data:{
            goodsid:goodsid,
            specs:id,
            num:orderNumber
        },
        success:function(res){
            $('#fromPrice').html('￥'+res.data.form);
            $('#salePrice').html(res.data.sale);
            $('#stockId').html(res.data.stock);
            stock=res.data.stock;
            if(stock>=orderNumber){
                $('#yesStock').show();
                $('#noStock').hide();
            } else{
                $('#yesStock').hide();
                $('#noStock').show();
            }
        }
    })
}
var orderNumber=1;
setInterval(function(data){
    var number=$('#orderNumber').val();
    if(number==orderNumber){
        return;
    }
    orderNumber=number;
    getPrice();
},500)