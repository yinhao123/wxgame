 $(function(){
        var my_height=$('.zu_row').length;
        for(i=0;i<my_height;i++){
            var my_nei=$('.zu_row').eq(i).find('.right_box .pand').text();
            var mymy=parseInt(my_nei)
            if(mymy >0){
                $('.zu_row').eq(i).find('.right_box .pand').addClass('jiajia')
            }
        }

        $('.top_box span').click(function(event) {
                $(this).addClass('col').siblings().removeClass('col');
                var my_index=$(this).index();
                $('.game_box').eq(my_index).show().siblings('.game_box').hide();
        });
    })