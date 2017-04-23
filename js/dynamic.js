/*
$("#3773_CSS").click(function() {
    $(".3773_CSS").show();
    $(".3774_CSS").hide();
    $(".3775_CSS").hide();
    $(".3776_CSS").hide();
});

$("#3774_CSS").click(function() {
    $(".3773_CSS").hide();
    $(".3774_CSS").show();
    $(".3775_CSS").hide();
    $(".3776_CSS").hide();
});

$("#3775_CSS").click(function() {
    $(".3773_CSS").hide();
    $(".3774_CSS").hide();
    $(".3775_CSS").show();
    $(".3776_CSS").hide();
});

$("#3776_CSS").click(function() {
    $(".3773_CSS").hide();
    $(".3774_CSS").hide();
    $(".3775_CSS").hide();
    $(".3776_CSS").show();
});
*/
$("ul#list1 li").click(function() {
    var temp = $(this).attr("id");
    $(".phones").hide()
    $("."+ temp).show();
});

$("#all_phones").click(function() {
    $(".phones").show();
});