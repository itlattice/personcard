layui.use(['laydate', 'form', 'table'], function () {
    var table = layui.table;
    var form = layui.form;
    var util = layui.util;
    var rightpage = layui.rightpage

    var tableIns;

    function search(array = {}) {
        tableIns = table.render({
            elem: '#datatable',
            toolbar: '#toolbarDemo',
            title: '草稿箱',
            url: "/admin/goods/card?id=" + $('#goodsid').val(), //数据接口
            method: 'POST',
            // defaultToolbar: [],
            page: true, //开启分页
            where: array,
            limit: 15,
            cellMinWidth: 80, //全局定义常规单元格的最小宽度，layui 2.2.1 新增
            cols: [
                [ //表头
                    {
                        field: 'card',
                        title: '卡号',
                        align: 'center',
                        width:180
                    },
                    {
                        field: 'pwd',
                        title: '卡密',
                        align: 'center',
                        edit: 'number',
                        width:180
                    },
                    {
                        field: 'state',
                        title: '状态',
                        align: 'center',
                        templet:function(row){
                            switch(row.state){
                                case '正常':return '<span class="layui-badge layui-bg-green">正常</span>';break;
                                case '已核销':return '<span class="layui-badge layui-bg-red">已核销</span>';break;
                                case '已售出':return '<span class="layui-badge layui-bg-orange">已售出</span>';break;
                            }
                        },
                        width:100
                    },
                    {
                        field: 'add_time',
                        title: '录入时间',
                        align: 'center',
                        width:150
                    },
                    {
                        field: 'sale_time',
                        title: '售出时间',
                        align: 'center',
                        width:150
                    },
                    {
                        field: 'state',
                        title: '操作',
                        align: 'center',
                        fixed: 'right',
                        width:130,
                        templet:function(row){
                            var html='<div class="layui-btn-group">';
                            var state=row.state;
                            if(state=='正常'){
                                html+='<button class="layui-btn layui-btn-xs layui-btn-normal" lay-event="sale">核销</button>'
                            }
                            html+='<button class="layui-btn layui-btn-xs layui-btn-danger" lay-event="del">删除</button>'
                            html+='</div>'
                            return html;
                        }
                    }
                ]
            ]
        });
    }

    search({
        state: -1,
        gpid: -1
    });

    form.on('submit(sreach)', function (data) {
        let f = data.field;
        search(f);
        return false;
    });

    table.on('tool(datatable)', function (obj) {
        var id=obj.data.cid;
        var event=obj.event;
        var state=0;
        if(event==='sale'){  //卡密核销
            state=2;
        } else if(event==='del') { //删除卡密
            state=-1;
        }
        ajax({
            url:'/admin/goods/cardhandle',
            data:{
                id:id,
                state:state
            },
            success:function(res){
                layer.msg(res.msg);
                if(res.code==0){
                    tableIns.reload();
                }
            }
        })
    });
});