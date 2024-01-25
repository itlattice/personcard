layui.use(['laydate', 'form', 'table'], function () {
    var table = layui.table;
    var form = layui.form;
    var util = layui.util;
    var rightpage = layui.rightpage

    var tableIns=table.render({
      elem: '#datatable',
      toolbar: '#toolbarDemo',
      title: '草稿箱',
      url: "/admin/user/groupuser?id="+$('#userid').val(), //数据接口
      method: 'POST',
      defaultToolbar:[],
      page: true, //开启分页
      limit: 15,
      cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
      cols: [
        [ //表头
			    {type:'checkbox',fixed:'left'},
          {
            field: 'username',
            title: '用户名',
            align: 'center'
          },
          {
            field: 'nickname',
            title: '昵称',
            align: 'center',
            edit: 'number'
          },
          {
            field: 'reg_time',
            title: '注册时间',
            align: 'center'
          },
          {
            field: 'state',
            title: '状态',
            align: 'center'
          }
        ]
      ]
    });
    
    table.on('toolbar(datatable)', function (obj) {
      var checkStatus = table.checkStatus(obj.config.id); //获取选中行状态
			var data = checkStatus.data;
      var uidlist=[];
      data.forEach(element => {
        uidlist.push(element.uid);
      });
      if(uidlist.length<1){
        layer.msg('请选择要操作的用户');
        return;
      }
      if (obj.event === 'move') {
        layer.open({
          title:'转移用户',
          type:2,
          maxmin:false,
          content:'/admin/user/grouplists?id='+$('#userid').val(),
          area: ['720px', '550px'],
          end:function(){
            var groupid=$('#groupid').val();
            if(groupid<1){
              return false;
            }
            ajax({
              url:'/admin/user/moveuser',
              data:{
                uid:uidlist,
                group:groupid
              },
              success:function(res){
                if(res.code==0){
                  layer.msg('移动成功',function(){
                    layer.closeAll();
                  })
                  tableIns.reload();
                } else{
                  layer.msg(res.msg);
                }
              }
            })
          }
        })
      }
    });
  });