layui.use(['laydate', 'form', 'table'], function () {
    var table = layui.table;
    var form = layui.form;
    var util = layui.util;
    var rightpage = layui.rightpage

    var tableIns=table.render({
      elem: '#datatable',
      toolbar: '#toolbarDemo',
      title: '草稿箱',
      url: "/admin/user/grouplists?id="+$('#userid').val(), //数据接口
      method: 'POST',
      defaultToolbar:[],
      page: true, //开启分页
      limit: 15,
      cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
      cols: [
        [ //表头
          {
            field: 'name',
            title: '分组名称',
            align: 'center'
          },
          {
            field: 'sort',
            title: '排序',
            align: 'center',
            edit: 'number'
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
    
    table.on('tool(datatable)', function (obj) {
      if (obj.event === 'list') {
        console.log(obj);
        var id=obj.data.gid;
        parent.$('#groupid').val(id);
        parent.layer.closeAll();
      }
    });
  });