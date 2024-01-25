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
        url: "/admin/user/list", //数据接口
        page: true, //开启分页
        limit: 20,
        cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
        cols: [
          [ //表头
            {
              field: 'username',
              title: '用户名',
              align: 'center'
            },
            {
              field: 'nickname',
              title: '昵称',
              align: 'center'
            },
            {
              field: 'telephone',
              title: '手机号',
              align: 'center'
            },
            {
              field: 'qq',
              title: 'QQ号',
              align: 'center'
            },
            {
              field: 'group',
              title: '用户分组',
              align: 'center',
              templet:function(res){
                return res.group.name;
              }
            },
            {
              field: 'reg_time',
              title: '注册时间',
              align: 'center'
            },
            {
              field: 'login_time',
              title: '上次登录时间',
              align: 'center',
              sort:true
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
      var uid=data.uid
      if (obj.event === 'del') {
        layer.confirm('确定删除?删除后将不可恢复', {icon: 3, title:'提示'}, function(index){
					ajax({
						url: "/admin/user/deluser",
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
        layer.open({
          title:'编辑用户',
          type:2,
          maxmin:false,
          content:'/admin/user/adduser?id='+data.uid,
          area: ['720px', '550px'],
          end:function(){
              location.reload();
          },
          btn:['确定','取消'],
          yes:function(index){
            var form = window["layui-layer-iframe" + index].form;
            console.log(form);
            var data = form.val('formTest');
            if(data.username.length<1||data.nickname.length<1||(data.password.length<6&&data.password.length>0)){
              layer.msg('请把信息填写完整，密码长度不能低于6位');
              return;
            }
            ajax({
              url:'/admin/user/adduser?id='+uid,
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
      } else if(obj.event=='move'){
        layer.open({
          title:'转移用户',
          type:2,
          maxmin:false,
          content:'/admin/user/grouplists?id='+uid,
          area: ['720px', '550px'],
          end:function(){
            var groupid=$('#groupid').val();
            if(groupid<1){
              return false;
            }
            ajax({
              url:'/admin/user/moveuser',
              data:{
                uid:[uid],
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

    //表头工具栏事件
    table.on('toolbar(datatable)', function (obj) {
      if (obj.event === 'add') {
        layer.open({
          title:'添加用户',
          type:2,
          maxmin:false,
          content:'/admin/user/adduser',
          area: ['720px', '550px'],
          end:function(){
              location.reload();
          },
          btn:['确定','取消'],
          yes:function(index){
            var form = window["layui-layer-iframe" + index].form;
            console.log(form);
            var data = form.val('formTest');
            if(data.username.length<1||data.nickname.length<1||data.password.length<6){
              layer.msg('请把信息填写完整，密码长度不能低于6位');
              return;
            }
            ajax({
              url:'/admin/user/adduser',
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