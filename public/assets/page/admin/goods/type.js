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
        url: "/admin/goods/typelist", //数据接口
        page: true, //开启分页
        limit: 20,
        cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
        cols: [
          [ //表头
            {
              field: 'name',
              title: '分类名称',
              align: 'center'
            },
            {
              field: 'goods_sort',
              title: '商品排序方式',
              align: 'center'
            },
            {
              field: 'sort',
              title: '排序',
              align: 'center'
            },
            {
              field: 'icon',
              title: '分类图标',
              align: 'center',
              templet:function(data){
                var icon=data.icon;
                if(icon==''||icon==null){
                  return '空';
                } else{
                  return '<img src="'+icon+'" class="img1">';
                }
              }
            },
            {
              field: 'number',
              title: '商品数',
              align: 'center'
            },
            {
              field: 'add_time',
              title: '添加时间',
              align: 'center'
            },
            {
              field: 'right',
              title: '操作',
              fixed: 'right',
              toolbar: '#barDemo',
              align: 'center'
            }
          ]
        ]
      });
    }

    search();

    //监听行工具事件
    table.on('tool(datatable)', function (obj) {
      var data = obj.data;
      console.log(data);
      var uid=data.gtid
      if (obj.event === 'del') {
        layer.confirm('确定删除?删除后将不可恢复', {icon: 3, title:'提示'}, function(index){
            ajax({
                url: "/admin/goods/deltype",
                data:{id:uid},
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
        var gtid=data.gtid;
        layer.open({
          title:'编辑用户',
          type:2,
          maxmin:false,
          content:'/admin/goods/addtype?id='+data.gtid,
          area: ['720px', '550px'],
          end:function(){
              location.reload();
          },
          btn:['确定','取消'],
          yes:function(index){
            var form = window["layui-layer-iframe" + index].form;
            console.log(form);
            var data = form.val('formTest');
            if(data.name.length<1||data.sort.length<1){
              layer.msg('请把信息填写完整');
              return;
            }
            ajax({
              url:'/admin/goods/addtype?id='+gtid,
              data:data,
              success:function(res){
                layer.msg(res.msg,function(){
                  if(res.code==0){
                    tableIns.reload();
                    layer.closeAll();
                  }
                });
              }
            })
          }
        });
      }
    });

    //表头工具栏事件
    table.on('toolbar(datatable)', function (obj) {
      if (obj.event === 'add') {
        layer.open({
          title:'添加用户',
          type:2,
          maxmin:false,
          content:'/admin/goods/addtype',
          area: ['720px', '550px'],
          end:function(){
              location.reload();
          },
          btn:['确定','取消'],
          yes:function(index){
            var form = window["layui-layer-iframe" + index].form;
            console.log(form);
            var data = form.val('formTest');
            if(data.name.length<1||data.sort.length<1){
              layer.msg('请把信息填写完整');
              return;
            }
            ajax({
              url:'/admin/goods/addtype',
              data:data,
              success:function(res){
                layer.msg(res.msg,function(){
                  if(res.code==0){
                    tableIns.reload();
                    layer.closeAll();
                  }
                });
              }
            })
          }
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