<?php use yii\helpers\Url; ?>
var coupon_id_str = "";
var coupon_key_arr;
var coupon_type;

// Create Base64 Object
var Base64={_keyStr:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=",encode:function(e){var t="";var n,r,i,s,o,u,a;var f=0;e=Base64._utf8_encode(e);while(f<e.length){n=e.charCodeAt(f++);r=e.charCodeAt(f++);i=e.charCodeAt(f++);s=n>>2;o=(n&3)<<4|r>>4;u=(r&15)<<2|i>>6;a=i&63;if(isNaN(r)){u=a=64}else if(isNaN(i)){a=64}t=t+this._keyStr.charAt(s)+this._keyStr.charAt(o)+this._keyStr.charAt(u)+this._keyStr.charAt(a)}return t},decode:function(e){var t="";var n,r,i;var s,o,u,a;var f=0;e=e.replace(/[^A-Za-z0-9\+\/\=]/g,"");while(f<e.length){s=this._keyStr.indexOf(e.charAt(f++));o=this._keyStr.indexOf(e.charAt(f++));u=this._keyStr.indexOf(e.charAt(f++));a=this._keyStr.indexOf(e.charAt(f++));n=s<<2|o>>4;r=(o&15)<<4|u>>2;i=(u&3)<<6|a;t=t+String.fromCharCode(n);if(u!=64){t=t+String.fromCharCode(r)}if(a!=64){t=t+String.fromCharCode(i)}}t=Base64._utf8_decode(t);return t},_utf8_encode:function(e){e=e.replace(/\r\n/g,"\n");var t="";for(var n=0;n<e.length;n++){var r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r)}else if(r>127&&r<2048){t+=String.fromCharCode(r>>6|192);t+=String.fromCharCode(r&63|128)}else{t+=String.fromCharCode(r>>12|224);t+=String.fromCharCode(r>>6&63|128);t+=String.fromCharCode(r&63|128)}}return t},_utf8_decode:function(e){var t="";var n=0;var r=c1=c2=0;while(n<e.length){r=e.charCodeAt(n);if(r<128){t+=String.fromCharCode(r);n++}else if(r>191&&r<224){c2=e.charCodeAt(n+1);t+=String.fromCharCode((r&31)<<6|c2&63);n+=2}else{c2=e.charCodeAt(n+1);c3=e.charCodeAt(n+2);t+=String.fromCharCode((r&15)<<12|(c2&63)<<6|c3&63);n+=3}}return t}}

$("document").ready(function() {
coupon_key_arr = coupon_key_arr_str.split('^_^');
console.log(coupon_key_arr);
if (typeof coupon_id !== 'undefined') {
    coupon_id_str = coupon_id;
}

function refresh_perameter_div(){
    $.ajax({
            url:'<?php echo Url::to(['coupons/getcouponview']); ?>'+'?coupon_key='+Base64.encode($("#coupons-coupon_key").val())+"&coupon_id="+coupon_id_str+"&restaurant_id="+$("#restaurant").val(),
            Type:'GET',
            success:function(data){
                if(data){
                    $("#perameters_div").show();
                    $('#perameters_content_div').append('<div>Appended Content</div>');
                    $('#perameters_content_div').html(data);
                }else{
                    $("#perameters_div").hide();
                    $('#perameters_content_div').html('');
                }
            }      
        });
}
//$("#coupons-type").change(function() {
//    if($(this).val() == 'Restaurant'){
//        $("#restaurant_div").show();
//    }else{
//        $("#restaurant_div").hide();
//    }
//});
$("#coupons-type").trigger('change');

$("#coupons-coupon_key").change(function() {
    coupon_type = coupon_key_arr[$(this)[0].selectedIndex - 1];
    $("#coupons-type").val(coupon_type);
    if($("#coupons-type").val() == 'Restaurant'){
        $("#restaurant_div").show();
        if($("#restaurant").val() == ""){
            $("#perameters_div").hide();
            $('#perameters_content_div').html('');
            alert('Please Select restaurant from dropdown below');
        }else{
            refresh_perameter_div();
        }
    }else{
        $("#restaurant_div").hide();
        refresh_perameter_div();
    }
});
if (typeof coupon_id !== 'undefined') {
    $("#coupons-coupon_key").trigger('change');
}
$("#restaurant").change(function() {
    if($("#coupons-coupon_key").val() == ""){
        $("#perameters_div").hide();
        $('#perameters_content_div').html('');
        alert('Please Select coupon key from dropdown');
    }else{
        refresh_perameter_div();
    }
});


});
