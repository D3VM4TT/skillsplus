$(function(){
  var onClass = "on";
  var showClass = "show";
  
  $("input").bind("checkval",function(){
    var label = $(this).prev("label");
    if(this.value !== ""){
      label.addClass(showClass);
    } else {
      label.removeClass(showClass);
    }
  }).on("keyup",function(){
    $(this).trigger("checkval");
  }).on("focus",function(){
    $(this).prev("label").addClass(onClass);
  }).on("blur",function(){
      $(this).prev("label").removeClass(onClass);
  }).trigger("checkval");


$(".confirm-password-showhide .trigger-password, .password-showhide .trigger-password").click(function() {
  var c = $(this).parent().attr("class").replace("-showhide", "");
  var obj = $("#" + (c.indexOf("confirm") > -1 ? "confirmPassword" : "password"));
  obj.attr("type", obj.attr("type") == "text" ? "password" : "text");
  $(this).text($(this).text() == "Hide" ? "Show" : "Hide");
});


});