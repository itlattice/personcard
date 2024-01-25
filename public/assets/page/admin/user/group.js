layui.use(['laydate', 'form', 'table'], function () {
    var table = layui.table;
    var form = layui.form;
    var util = layui.util;
    var rightpage = layui.rightpage

    var tableIns=table.render({
      elem: '#datatable',
      toolbar: '#toolbarDemo',
      title: '草稿箱',
      url: "/admin/user/grouplist", //数据接口
      method: 'POST',
      page: true, //开启分页
      limit: 15,
      cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
      cols: [
        [ //表头
          {
            field: 'name',
            title: '会员组名称',
            align: 'center'
          },
          {
            field: 'sort',
            title: '排序',
            align: 'center',
            edit: 'number'
          },
          {
            field: 'add_time',
            title: '创建时间',
            align: 'center'
          },
          {
            field: 'is_more',
            title: '是否默认分组',
            align: 'center',
            templet:function(res){
              if(res.is_more==0){
                return '<button class="layui-btn layui-btn-xs layui-btn-normal" lay-event="more">设为默认</button>'
              } else{
                return '是';
              }
            }
          },
          {
            field: 'number',
            title: '分组人数',
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

    //监听行工具事件
    table.on('tool(datatable)', function (obj) {
      var data = obj.data;
      if (obj.event === 'del') {
        layer.confirm('确定删除?删除后将不可恢复', {icon: 3, title:'提示'}, function(index){
					ajax({
						url: "/admin/user/delgroup",
						data:{id:data.gid},
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
          title:'编辑用户组',
          type:2,
          maxmin:false,
          content:'/admin/user/addgroup?id='+data.gid,
          area: ['720px', '550px'],
          end:function(){
              location.reload();
          },
          btn:['确定','取消'],
          yes:function(index){
            var form = window["layui-layer-iframe" + index].form;
            console.log(form);
            var data = form.val('formTest');
            ajax({
              url:'/admin/user/addgroup?id='+data.gid,
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
        return;
      } else if(obj.event==='more'){  //设为默认分组
        layer.confirm('确定设为默认，原默认分组将失效?', {icon: 3, title:'提示'}, function(index){
					$.ajax({
						url: "/admin/user/moregroup",
						data:{id:data.gid},
						success:function(e){
							layer.msg(e.msg);
							if(e.code==0){
								tableIns.reload();
							}
						}
					})
					layer.close(index);
				});
      } else if(obj.event==='list'){  //用户组用户列表
        layer.open({
          title:'用户列表',
          type:2,
          maxmin:false,
          content:'/admin/user/groupuser?id='+data.gid,
          area: ['720px', '550px'],
          end:function(){
              tableIns.reload();
          },
        })
      }
    });

    //表头工具栏事件
    table.on('toolbar(datatable)', function (obj) {
      if (obj.event === 'add') {
        layer.open({
          title:'添加用户组',
          type:2,
          maxmin:false,
          content:'/admin/user/addgroup',
          area: ['720px', '550px'],
          end:function(){
              location.reload();
          },
          btn:['确定','取消'],
          yes:function(index){
            var form = window["layui-layer-iframe" + index].form;
            console.log(form);
            var data = form.val('formTest');
            ajax({
              url:'/admin/user/addgroup',
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

    table.on('edit(datatable)', function(obj){
      var value = obj.value; //得到修改后的值
      var id=obj.data.gid;
      console.log(value,id);
      ajax({
        url:'/admin/user/editgroupsort',
        data:{
          value:value,
          id:id
        },
        success:function(res){
          layer.msg(res.msg);
        }
      })
    });

    //监听搜索提交
    form.on('submit(sreach)', function (data) {
      let f = data.field;
      tableIns.reload({
        where: {
          keywords: f.keywords,
          start_time: f.start_time,
          end_time: f.end_time
        },
        page: {
          curr: 1
        }
      });
      return false;
    });
  });