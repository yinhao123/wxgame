function notYetImplemented() {
    layer.open({
        content: "还未实现", 
        skin: "msg", 
        time: 3 
    }); 
}

function userClickRedpacket() {
    $.post('/Wap/Index/hongbaoclick2', function(res) {
            if(res.ret!=0){
                layer.open({
                    content: res.error,
                    time: 3,
                    skin: "msg",  
                }); 
            }else{
                layer.open({
                   content: res.data.tips, 
                   time: 2, 
                   skin: "msg",
                   end: function() {
                        window.location.reload(); 
                   }
                });
            }
    }); 
    return false; 
} 

function renderHongbaoClickItem() {	
    
    $.getJSON('/Wap/Index/hongbaoclick', function(res) {
        if(res.ret == 0){ 
            $("#hongbaoclick").html( res.data.html );
        }else if (res.ret == 1) {
			window.location.reload();
			/*
            layer.open({
                content: "王者尊享礼包！10元游戏红包已入你的账户中", 
                style: 'background-color:#EF595A; color:#fff; border:none;', 
                btn: "我知道了", 
                end: function() {
                    window.location.reload(); 
                }
            }); 
			*/
        }else{
            alert("error"); 
        }
    });  
	
} 

function renderQianDaoClickItem() {	
    
    $.getJSON('/Wap/Index/qiandaoclick', function(res) {
		layer.open({
		   content: res.tips, 
		   time: 2, 
		   skin: "msg",
		   end: function() {
                    window.location.reload(); 
            }
		});
    });  
	
} 

function renderQrPage() {
    layer.open({
        title: [
            '为您服务时间：09:00-20:00', 
            "background-color: #FF4351; color:#fff;"  
        ], 
        content: $("#popKefuQrInfo").html(), 
        btn: "知道了", 
    });  
}

function deleteStart(t){
    $("#start").toggle(); 
}

function removeOpenIdByRedirect() {
    var search = window.location.search;
    if (search.indexOf('openid') >= 0) {
        search = search.slice(1).split("&");
        var new_search = "";
        for(var i=0; i<search.length; i++) {
            var key = search[i].split("=")[0];
            var value = search[i].split("=")[1];
            if(key != "openid") {
                new_search += search[i] + "&";  
            } 
        }
        window.location.href = "http://" + window.location.hostname + window.location.pathname + "?" + new_search; 
    } 
}
removeOpenIdByRedirect(); 
