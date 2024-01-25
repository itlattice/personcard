window.onload = function () {
    //点击商品
    $(".goods-box").click(function () {
        var goods_id = $(this).data('id');
        location.href = "/goods/" + goods_id + ".html";
    })

    //点击分类
    $('.cate-box').click(function () {
        $('.cate-box').removeClass('cate-box-select');
        $(this).addClass("cate-box-select");
        var key = $(this).data("key");
        $(".goods-list").hide();
        $(".goods-list-" + key).show();


        var div = document.getElementById('cateBox');
        var width = div.scrollWidth;
        var mrg = $(this)[0].offsetLeft;
        // console.log(mrg)
        $('#cateBox').animate({
            scrollLeft: mrg - 120 + 'px'
        });
        // console.log(width)
    })



    $("#search").on("input", function (e) {
        var txt_pc = $("#search").val();
        if ($.trim(txt_pc) != "") {
            $(".product").hide().filter(":contains('" + txt_pc + "')").show();
        } else {
            $(".product").show();
        }
    });


}