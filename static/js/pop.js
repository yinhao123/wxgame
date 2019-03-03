$.lxPop = function(){
    var msg = function(text){//提示
        layui.use('layer', function() {
            var layer = layui.layer;
            layer.msg(text,{
                time:2000,
                area:["86%","48px"],
                success:function(layero,index){
                    $(layero).css({
                        "border-radius":"0.05rem",
                        "-webkit-border-radius":"0.05rem"
                    });
                    $(layero).find(".layui-layer-content").css({
                        "font-size":"0.16rem"
                    })
                }
            });
        });
    }
    var loading = function(){//加载
        var loadHtml = '<div class="juhuaLoad" style="position: fixed; width: 100%; height: 100%; top: 0px; left: 0px;z-index:10000;"><div class="shade" style="width: 100%; height: 100%; background-color: rgb(0, 0, 0); opacity: 0.1;"></div><div class="load" style="position: absolute; width: 0.32rem; height: 0.32rem; top: 50%; left: 50%; margin-left: -0.16rem; margin-top: -0.18rem;"><img src="/static/image/loading-2.gif" alt=""></div></div>';
        $("body").append(loadHtml);
    };
    var closeLoad = function(){
        $(".juhuaLoad").remove();
    }
    return{
        msg:msg,
        loading:loading,
        closeLoad:closeLoad
    }
}