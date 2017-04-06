$("#checkAll").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
}); 


$(".template-sms").click(function(){
     var id  = this.id;
     var val = $("#" +id).html(); 
     document.getElementById("sms-textarea").value =val;
});

$(".template-sms_m").click(function(){
	var id  = this.id;
	var val = $("#" +id).html();  
	document.getElementById("sms-textarea_m").value =val; 
});

$("#checkAllm").change(function () {
    $("input:checkbox").prop('checked', $(this).prop("checked"));
});
