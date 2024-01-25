layui.use(['laydate', 'form', 'table'], function () {
    var table = layui.table;
    var form = layui.form;
  
    var tableIns = {};
  
    function search(where = {}) {
      tableIns = table.render({
        elem: '#datatable',
        toolbar: '#toolbarDemo',
        title: '草稿箱',
        method: 'POST',
        where: where,
        url: "/admin/order/list", //数据接口
        page: true, //开启分页
        limit: 20,
        totalRow: true,
        cellMinWidth: 100, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
        text: {
            none: '无订单' //默认：无数据。
        },
        cols: [
          [ //表头
            {
              field: 'order_num',
              title: '订单号',
              align: 'center',
              width:150
            },
            {
              field: 'user',
              title: '相关用户',
              align: 'center',
              width:120,
              templet:function(row){
                  if(row.user==null) return '游客';
                  return row.user.username;
              }
            },
            {
              field: 'add_time',
              title: '订单时间',
              align: 'center'
            },
            {
              field: 'goods',
              title: '商品/规格',
              align: 'center',
              templet:function(row){
                  var goods=row.goods;
                  if(goods==null){
                      return '商品异常';
                  }
                  goods=goods.name;
                  var specs=row.specs;
                  if(specs==null){
                      return '规格异常';
                  }
                  specs=specs.name;
                  return goods+"/"+specs;
              }
            },
            {
              field: 'num',
              title: '购买数量',
              align: 'center',
              totalRow: true,
            },
            {
              field: 'pay_time',
              title: '支付时间',
              align: 'center',
            },
            {
              field: 'state',
              title: '订单状态',
              align: 'center',
              templet:function(row){
                  var state=row.state;
                  switch(state){
                    case 0:return '<span class="layui-badge layui-bg-orange">待付款</span>';
                    case 1:return '<span class="layui-badge layui-bg-red">待发货</span>';
                    case 2:return '<span class="layui-badge layui-bg-green">已发货</span>';
                    case 3:return '<span class="layui-badge layui-bg-green">交易成功</span>';
                    case 4:return '<span class="layui-badge layui-bg-gray">已退款</span>';
                    case 5:return '<span class="layui-badge layui-bg-gray">订单失效</span>';
                  }
              }
            },
            {
              field: 'pay_type',
              title: '支付方式',
              align: 'center',
              width: 150,
              templet:function(row){
                switch(row.pay_type){
                  case 0:return '支付宝';break;
                  case 1:return '微信支付';break;
                  case null:return '';break;
                  default:return '未知方式';
                }
              }
            },
            {
              field: 'info_price',
              title: '订单总金额',
              align: 'center',
              totalRow: true,
              width: 150
            },
            {
              field: 'right',
              title: '操作',
              fixed: 'right',
              align: 'center',
              width:320,
              templet:function(data){
                var html='<div class="layui-btn-group">';
                if(data.state==1){
                  html+='<button class="layui-btn layui-btn-xs layui-btn-normal" lay-event="sale">去发货</button>';
                }
                if(data.state<4 && data.state>0){
                    html+='<button class="layui-btn layui-btn-xs layui-btn-normal" lay-event="refoud">订单退款</button>';
                }
                html+='<button class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</button></div>';
                return html;
              }
            }
          ]
        ]
      });
    }
  
    search();
    
    //监听行工具事件
    table.on('tool(datatable)', function (obj) {
      var data = obj.data;
      var id=data.oid
      if (obj.event === 'del') {
        layer.confirm('确定删除?删除后将不可恢复', {icon: 3, title:'提示'}, function(index){
          ajax({
            url: "/admin/order/delorder",
            data:{id:id},
            success:function(e){
              layer.msg(e.msg);
              if(e.code==0){
                tableIns.reload();
              }
            }
          })
          layer.close(index);
        });
      } else if(obj.event==='sale'){  //发货
        layer.open({
          title:'订单发货',
          type:2,
          maxmin:false,
          content:'/admin/order/ordersale?id='+id,
          area: ['720px', '550px'],
          end:function(){
              location.reload();
          },
          btn:['发货','取消'],
          yes:function(index){
            var form = window["layui-layer-iframe" + index].forma;
            var formdata=form.val('formTest');
            console.log(formdata.card);
            if(formdata.card.length<1){
                layer.msg('请输入卡密信息');
                return;
            }
            ajax({
                url:'/admin/order/ordersale?id='+id,
                data:{
                    card:formdata.card,
                },
                success:function(res){
                    layer.msg(res.msg);
                    if(res.code==0){
                        tableIns.reload();
                        layer.closeAll();
                    }
                }
            })
          }
        });
      } else if(obj.event==='refoud'){
        layer.confirm('确定退款?这里的退款仅为修改了订单状态，实际退款操作请线下进行', {icon: 3, title:'提示'}, function(index){
            ajax({
                url: "/admin/order/refoudorder",
                data:{id:id},
                success:function(e){
                    layer.msg(e.msg);
                    if(e.code==0){
                        tableIns.reload();
                    }
                }
            })
            layer.close(index);
        });
      }
    });
  
    //监听搜索提交
    form.on('submit(sreach)', function (data) {
      let f = data.field;
      search(f);
      return false;
    });
  });
  