/**
 * Created by GavinGu on 2016/3/3.
 */
$(function () {
   $('#pro_num').text(parseInt($("#num_box").text()))
});
$(function (){
    var pro_price = parseFloat($('#pro_price').text());
    var xg_num = $('#xg_num').text();
    $("#plus_one").click(function (){
        var buynum = parseInt($("#num_box").text());
        $("#num_box").text(parseInt(buynum+1));
        $('#pro_num').text(parseInt($("#num_box").text()));
        console.log($('#pro_price').text());
        $('#pro_price').text(parseInt($("#num_box").text())*pro_price)
    });
    $("#minus_one").click(function (){
        var buynum = parseInt($("#num_box").text());
        if(buynum > 1){
            $("#num_box").text(parseInt(buynum-1));
            $('#pro_num').text(parseInt($("#num_box").text()))
            $('#pro_num').text(parseInt($("#num_box").text()));
            var a= (parseInt($("#num_box").text())*pro_price);
            var b = Math.round(a*100)/100;

            $('#pro_price').text(b.toFixed(2))
        }
    });
});
function show_withdraw(){
    $('#amount_detail_banner').hide();
    $('#vip_cont_box').hide();
    $('#focus_time').addClass('show');
    $('#withdraw_cont').addClass('show');
}
function open_tabs(){
    $('#index_tabs').toggleClass('show','hide');
}
$(function () {
    var ClientHeight = $(window).height();
   var info_box_h = $('#info-box').height();
    var info_bar_h = $('#info-bar').height();
    var main_box_h = ClientHeight - info_box_h -info_bar_h;
    $('#main-box').css(
        {
            "position":"absolute",
            "width":"100%",
            "height":main_box_h
        }
    );
    window.onscroll = function(){
        var t = document.documentElement.scrollTop || document.body.scrollTop;
        if( t >= 30 ) {
            $('#main-box').css(
                {
                    "position":"absolute",
                    "width":"100%",
                    "height":main_box_h+t,
                    "background":'rgba(255,255,255,0.9)'
                }
            );
        }
    };
});
function confirm_draw(){
    var withdraw_no = $('#inputWithdraw').val();
    var reg =  /^\d+$/g;
    if(reg.test(withdraw_no)&&withdraw_no!=''){
        $('#withdraw_form').submit();
    }else{
        $('.withdraw_box').addClass('show_withdrawbox');
    }
    setTimeout(" $('.withdraw_box').removeClass('show_withdrawbox')",2000)
}
function Buy_action(){
    var reg =  /^\d+$/g;
    var t = setTimeout(" $('.withdraw_box').removeClass('show_withdrawbox')",2000);
    var name = $('#inputName').val();
    var phone_num = $('#inputPhoneNum').val();
    var local_info = $('#inputlocation').val();
    var reg_w =/^[\u4e00-\u9fa5]+$/;
    if(name == ''){
        $('.withdraw_box').addClass('show_withdrawbox');
        $('#name').show();
        t
    }else{
        $('#name').hide();
    }
    var phone_reg = /^1[3|4|5|7|8]\d{9}$/
    if(phone_num == ''|| !phone_reg.test(phone_num)){
        $('.withdraw_box').addClass('show_withdrawbox');
        $('#phone_num').show();
        t
    }else{
        $('#phone_num').hide();
    }
    if(local_info==''||!reg_w.test(local_info)&&!reg.test(local_info)){
        $('.withdraw_box').addClass('show_withdrawbox');
        $('#address').show();
        t
    }else{
        $('#address').hide();
    }
    if(name!=''&& phone_num!=''&&phone_reg.test(phone_num)&&local_info!=''&&reg_w.test(local_info)&&reg.test(local_info)){
        $('#buy_form').submit();
    }

}
$(function () {
    var full_h  = $('.xhq_body').height();
    var bg66_h = full_h-42;
    $('.body_bg66').height(bg66_h+'px');
})