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
      url: "/admin/goods/list", //数据接口
      page: true, //开启分页
      limit: 20,
      totalRow: true,
      cellMinWidth: 100, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
      cols: [
        [ //表头
          {
            field: 'name',
            title: '商品名称',
            align: 'center',
            width:150
          },
          {
            field: 'goodstype',
            title: '商品分类',
            align: 'center',
            templet:function(res){
              return res.goodstype.name;
            },
            width:120
          },
          {
            field: 'type',
            title: '商品类型',
            align: 'center',
            templet:function(data){
              switch(data.type){
                case 0:return '<span class="layui-badge layui-bg-red">卡密</span>';break;
                case 1:return '<span class="layui-badge layui-bg-blue">链接</span>';break;
                default:return '未知';break;
              }
            }
          },
          {
            field: 'is_head',
            title: '发货方式',
            align: 'center',
            templet:function(data){
              switch(data.is_head){
                case '自动':return '<span class="layui-badge layui-bg-orange">自动</span>';break;
                case '手动':return '<span class="layui-badge layui-bg-green">手动</span>';break;
              }
            }
          },
          {
            field: 'img',
            title: '商品主图',
            align: 'center',
            templet:function(data){
              var icon=data.img;
              if(icon==''||icon==null){
                return '无主图';
              } else{
                return '<img src="'+icon+'" class="img1">';
              }
            }
          },
          {
            field: 'is_sale',
            title: '是否上架',
            align: 'center',
            templet:function(data){
              return data.is_sale>0?'<span class="layui-badge layui-bg-green" id="saleid'+data.gid+'" onclick="goodsSale('+data.gid+',0)">上架</span>':'<span class="layui-badge layui-bg-red" id="saleid'+data.gid+'" onclick="goodsSale('+data.gid+',1)">下架</span>';
            }
          },
          {
            field: 'add_time',
            title: '添加时间',
            align: 'center',
            width: 150
          },
          {
            field: 'price',
            title: '一口价',
            align: 'center',
            sort:true,
            edit:'text',
            width:100
          },
          {
            field: 'stock',
            title: '库存数',
            align: 'center',
            sort:true,
            totalRow: true,
            width:100
          },
          {
            field: 'sale',
            title: '虚拟销量',
            align: 'center',
            sort:true,
            edit:'text',
            totalRow: true,
            width:100
          },
          {
            field: 'real_sale',
            title: '实际销量',
            align: 'center',
            sort:true,
            totalRow: true,
            width:100
          },
          {
            field: 'is_login',
            title: '需要登录',
            align: 'center',
            width:100,
            templet:function(data){
              return data.is_login>0?'<span class="layui-badge layui-bg-orange">需要</span>':'<span class="layui-badge layui-bg-cyan">不需要</span>';
            }
          },
          {
            field: 'is_pwd',
            title: '需要密码',
            align: 'center',
            width:100,
            templet:function(data){
              return data.is_pwd>0?'<span class="layui-badge layui-bg-orange">需要</span>':'<span class="layui-badge layui-bg-cyan">不需要</span>';
            }
          },
          {
            field: 'right',
            title: '操作',
            fixed: 'right',
            align: 'center',
            width:320,
            templet:function(data){
              var html='<div class="layui-btn-group">';
              if(data.is_head=='自动' && data.type==0){
                html+='<button class="layui-btn layui-btn-xs layui-btn-normal" lay-event="addcard">加卡</button>';
                html+='<button class="layui-btn layui-btn-xs layui-btn-normal" lay-event="card">卡密列表</button>';
              } else if(data.is_head=='自动' && data.type==1){
                html+='<button class="layui-btn layui-btn-xs layui-btn-normal" lay-event="updateurl">修改发货内容</button>'
              }
              html+='<button class="layui-btn layui-btn-xs layui-btn-normal" lay-event="shorturl">推广信息</button>';
              html+='<button class="layui-btn layui-btn-xs layui-btn-normal" lay-event="edit">编辑</button>'+
                '<button class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</button></div>';
              return html;
            }
          }
        ]
      ]
    });
  }

  search();

  table.on('edit(datatable)', function(obj){
    console.log(obj);
    var newvalue=obj.value
    var field=obj.field;
    var id=obj.data.gid;
    ajax({
      url:'/admin/goods/updatedata',
      data:{
        val:newvalue,
        field:field,
        id:id
      },
      success:function(res){
        layer.msg(res.msg);
      }
    })
  });

  //监听行工具事件
  table.on('tool(datatable)', function (obj) {
    var data = obj.data;
    var id=data.gid
    if (obj.event === 'del') {
      layer.confirm('确定删除?删除后将不可恢复', {icon: 3, title:'提示'}, function(index){
        ajax({
          url: "/admin/goods/delgoods",
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
    } else if(obj.event==='edit'){
      layer.open({
        title:'编辑商品',
        type:2,
        maxmin:false,
        content:'/admin/goods/editgoods?id='+id,
        area: ['720px', '550px'],
        end:function(){
            location.reload();
        },
        btn:['确定','取消'],
        yes:function(index){
          var form = window["layui-layer-iframe" + index].forms;
          var formdata=form.val('formTest');
          var dataResult=valiteData(formdata);
          if(dataResult==false){
            return false;
          }
          var view = window["layui-layer-iframe" + index].viewObj;
          var price = view.tbData;
          var active=window["layui-layer-iframe" + index].active;
          var priceResult=active.save();
          if(priceResult==undefined||priceResult==false){
            return false;
          }
          var viewinfo=window["layui-layer-iframe" + index].viewinfo;
          formdata.detail=viewinfo.getContent();
          var windowinfo=window["layui-layer-iframe" + index].windowinfo;
          formdata.window=windowinfo.getContent();
          // console.log()
          ajax({
            url:'/admin/goods/editgoodshandle?id='+id,
            data:{
              price:price,
              data:formdata
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
    } else if(obj.event==='addcard'){  //加卡
      layer.open({
        title:'商品加卡',
        type:2,
        maxmin:false,
        content:'/admin/goods/addcard?id='+id,
        area: ['720px', '550px'],
        end:function(){
            location.reload();
        },
        btn:['继续加卡','完成','取消'],
        yes:function(index){
          addCardHandle(index,false);
        },
        btn2: function(index, layero){
          addCardHandle(index,true);
        },
        btn3: function(index){
          layer.closeAll();
        }
      });
    } else if(obj.event==='card'){
      layer.open({
        title:'卡密列表',
        type:2,
        maxmin:false,
        content:'/admin/goods/card?id='+id,
        area: ['720px', '550px'],
        end:function(){
            location.reload();
        }
      });
    } else if(obj.event==='shorturl'){
      layer.open({
        title:'推广信息',
        type:2,
        maxmin:false,
        content:'/admin/goods/shorturl?id='+id,
        area: ['720px', '550px'],
      });
    } else if(obj.event==='updateurl'){  //链接类产品修改发货内容
      layer.open({
        title:'修改发货内容',
        type:2,
        maxmin:false,
        content:'/admin/goods/updateurl?id='+id,
        area: ['720px', '550px'],
        btn:['提交','取消'],
        end:function(){
            location.reload();
        },
        yes:function(index){
          var forma = window["layui-layer-iframe" + index].forma;
          var formdata=forma.val('formTest');
          console.log(formdata);
          if(formdata.url.length<1){
            layer.msg('请把信息填写完整');
            return false;
          }
          ajax({
            url:'/admin/goods/updateurl',
            data:{
              id:formdata.gid,
              specs:formdata.specs,
              url:formdata.url
            },
            success:function(res){
              layer.msg(res.msg);
              if(res.code==0){
                tableIns.reload();
                window["layui-layer-iframe" + index].reloads()
                layer.closeAll();
              }
            }
          })
        }
      })
    }
  });

  function addCardHandle(index,is_complete=false){
    var forma = window["layui-layer-iframe" + index].forma;
    var formdata=forma.val('formTest');
    var card=[];
    var txt=formdata.card;
    card=txt.split('\n');
    var cardArr=[];
    card.forEach(item => {
      if(item!=''&&item!=undefined){
        cardArr.push(item);
      }
    });
    ajax({
      url:'/admin/goods/addcard',
      data:{
        id:id,
        gsid:formdata.specs,
        card:cardArr,
        again:formdata.again
      },
      success:function(res){
        layer.msg(res.msg);
        if(res.code==0){
          if(is_complete){
            layer.closeAll();
          } else{
            window["layui-layer-iframe" + index].reloads();
            tableIns.reload();
          }
        }
      }
    })
  }

  //表头工具栏事件
  table.on('toolbar(datatable)', function (obj) {
    if (obj.event === 'add') {
      layer.open({
        title:'添加商品',
        type:2,
        maxmin:false,
        content:'/admin/goods/addgoods',
        area: ['720px', '550px'],
        end:function(){
            location.reload();
        },
        btn:['确定','取消'],
        yes:function(index){
          var form = window["layui-layer-iframe" + index].forms;
          var formdata=form.val('formTest');
          var dataResult=valiteData(formdata);
          if(dataResult==false){
            return false;
          }
          var view = window["layui-layer-iframe" + index].viewObj;
          var price = view.tbData;
          var active=window["layui-layer-iframe" + index].active;
          var priceResult=active.save();
          if(priceResult==undefined||priceResult==false){
            return false;
          }
          var viewinfo=window["layui-layer-iframe" + index].viewinfo;
          formdata.detail=viewinfo.getContent();
          var windowinfo=window["layui-layer-iframe" + index].windowinfo;
          formdata.window=windowinfo.getContent();

          ajax({
            url:'/admin/goods/addgoodshandle',
            data:{
              price:price,
              data:formdata
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
    }
  });

  function valiteData(data){
    if(data.name.length<1){
      layer.msg('商品名称不能为空');
      return false;
    }
    if(data.sort.length<1){
      layer.msg('商品排序不能为空');
      return false;
    }
    if(data.sale.length<1){
      layer.msg('虚拟销量不能为空');
      return false;
    }
    if(data.price.length<1){
      layer.msg('一口价不能为空');
      return false;
    }
    return true;
  }

  //监听搜索提交
  form.on('submit(sreach)', function (data) {
    let f = data.field;
    search(f);
    return false;
  });
});

function goodsSale(gid,state){
  ajax({
    url:'/admin/goods/updatedata',
    data:{
      val:state,
      field:'is_sale',
      id:gid
    },
    async:false,
    success:function(res){
      layer.msg(res.msg,function(){
        tableIns.reload();
      });
      var info=$('#saleid'+gid).html();
      if(info=='上架'){
        state=0;
      } else{
        state=1;
      }
      if(state==0){  //下架
        $('#saleid'+gid).html('下架').addClass('layui-bg-red').removeClass('layui-bg-green').attr('onclick','goodsSale('+data.gid+',1)');
      } else{
        $('#saleid'+gid).html('上架').removeClass('layui-bg-red').addClass('layui-bg-green').attr('onclick','goodsSale('+data.gid+',0)');
      }
    }
  })
}