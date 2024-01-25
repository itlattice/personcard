
    var offset = 0;
    var limit = 10;
    //依据情况加载
    $(".loading").click(function () {
        load();
    })
    var field={};

    var forma=layui.form;
    layui.use(['form'],function(){
        forma=layui.form;
        forma.on('submit(submitbtn)',function(formdata){
            field=formdata.field;
            load();
        })
    })
    

    function load() {
        var loading = $(".loading").attr("data-loading");
        if (loading == 1) {
            return false;
        }
        $(".loading").attr("data-loading", 1);
        $(".loading").html("加载中...");
        var api = "/index/order/list";

        var data = {
            "offset": offset,
            "limit": limit,
            ...field
        };
        
        $.post(api, data, function (e) {
            if(e.code!=0){
                layer.msg(e.msg);
                $(".loading").attr("data-loading",0)
                return;
            }
            $('#orderlist').hide();
            if(e.count==0){
                $(".loading").hide();
                $("#order-empty").show();
                $(".loading").attr("data-loading",0)
                return;
            }
            var list = e.data;
            var html = "";
            var pagecount=0;
            list.forEach(item => {
                var orderid=item.oid;
                var add_time=item.add_time;
                var state=item.state;
                var statecolor=item.statecolor;
                var ordernum=item.order_num;
                var info_price=item.info_price;
                var num=item.num;
                var goods=item.goods;
                var name='已失效';
                var goodsid=0;
                if(goods!=null){
                    name=item.goods.name
                    goodsid=item.goods.gid;
                }
                var specs=item.specs;
                if(specs!=null){
                    specs=item.specs.name;
                }
                html+=
                    `<div class="layui-card" id="order-box-${orderid}">
                    <div class="layui-card-header">${add_time}<span id="order-status-${orderid}" style="color: ${statecolor}">${item.statetxt}</span></div>
                    <div class="layui-card-body">
                        <p class="order-no"><span>订单号：</span>${ordernum}</p>
                        <p class="goods-name"><span>商品：</span>${name}</p>
                        <p class="goods-name"><span>规格：</span>${specs}</p>
                        <p class="goods-name"><span>数量：</span> &times;${num}</p>
                        <p class="goods-price" style="display: inline-table;"><span>订单金额：</span>￥${info_price}元</p>`;
                if ((item.state == 0)) {
                    html +=
                        `<button type="button" class="layui-btn layui-btn-xs qfk-btn" data-pay_type="${item.pay_type}" data-num="${ordernum}" data-id="${item.oid}">去付款</button>`;
                }
                if (item.state===3) {
                    html +=
                        `<button type="button" class="layui-btn layui-btn-xs goods-btn" data-goods-id="${goodsid}">再次购买</button>`;
                }
                var ordertypes=$('#ordertype').val();

                if ((item.state == 2 || item.state == 3)&&ordertypes=='myorder') {
                    html +=
                        `<button type="button" class="layui-btn layui-btn-normal layui-btn-xs look-cardinfo" data-id="${orderid}">查看卡密</button>`;
                }
                if (item.state == 2 || item.state == 3) {
                    html +=
                        `<button type="button" class="layui-btn layui-btn-normal layui-btn-xs look-details" data-id="${orderid}">订单详情</button>`;
                }
                if (item.state == 2) {
                    html +=
                        `<button type="button" id="sh-btn-${item.oid}" class="layui-btn layui-btn-xs sh-btn" data-id="${item.oid}">确认收货</button>`;
                }
                if (item.state == 0 || item.state ==
                    5) { //订单完成或者未付款或者失效订单
                    html +=
                        `<button type="button" class="layui-btn layui-btn-xs del-btn" data-id="${item.oid}">删除订单</button>`;
                }
                html += `</div></div>`;
                offset++;
                pagecount++;
            });
            $(".loading").attr("data-loading", 0);
            $("#list").append(html)
            if (pagecount < limit) {
                $(".loading").attr("data-loading", 1);
                $(".loading").html("没有更多记录了");
            } else {
                $(".loading").html("点击加载更多");
            }
        }, "json");
    }

    $('body').on('click','.look-cardinfo',function(){
        var order_id = $(this).data('id');
        var href = "/ordercard?id=" + order_id;
        layer.open({
            type: 2,
            title: '查看卡密',
            anim: 5,
            shadeClose: true,
            shade: 0.3,
            skin: 'layui-layer-rim', //加上边框
            maxmin: false, //开启最大化最小化按钮
            area: ['480px', '300px'],
            content: href
        });
    })

    /**
     * 订单详情
     */
    $("body").on("click", ".look-details", function () {
        var order_id = $(this).data('id');
        var href = "/orderview?id=" + order_id;
        layer.open({
            type: 2,
            title: '订单详情',
            anim: 5,
            shadeClose: true,
            shade: 0.3,
            skin: 'layui-layer-rim', //加上边框
            maxmin: false, //开启最大化最小化按钮
            area: [$(window).width() > 480 ? '480px' : '96%', ';max-height:100%'],
            content: href,
            success: function (layero, index) {
                //找到当前弹出层的iframe元素
                var iframe = layui.$(layero).find('iframe');
                //设定iframe的高度为当前iframe内body的高度
                if ((iframe[0].contentDocument.body.offsetHeight) + 30 > $(window)
                    .height()) {
                    iframe.css('height', $(window).height() - 150);
                } else {
                    iframe.css('height', (iframe[0].contentDocument.body
                        .offsetHeight + 30));
                }

                //重新调整弹出层的位置，保证弹出层在当前屏幕的中间位置
                $(layero).css('top', (window.innerHeight - iframe[0].offsetHeight -
                    50) / 2);
            }
        });


    })

    /**
     * 确认收货
     */
    $("body").on("click", ".sh-btn", function () {
        var order_id = $(this).attr("data-id");
        $.post("/index/order/shouhuo.html", {
            id: order_id
        }, function (e) {
            $("#sh-btn-" + order_id).hide(50);
            $("#order-status-" + order_id).html("交易完成")
        }, "json");
    })

    /**
     * 再次购买
     */
    $("body").on("click", ".goods-btn", function () {
        var goods_id = $(this).attr("data-goods-id");
        location.href = "/goods/" + goods_id + ".html";
        // location.href = "/buy_order/" + order_id;
    })

    /**
     * 去付款
     */
    $("body").on("click", ".qfk-btn", function () {
        var ordernum=$(this).attr('data-num');
        location.href=`/order/${ordernum}.html`;
    })

    /**
     * 删除订单
     */
    $("body").on("click", ".del-btn", function () {
        var order_id = $(this).attr("data-id");
        console.log(order_id);
        layer.confirm("确定要删除该订单吗？",function(){
           $.post("/index/order/delorder", {
                "id": order_id
            }, function (e) {
                offset--;
                $("#order-box-" + order_id).hide(500);
            }, "json"); 
        })
    })