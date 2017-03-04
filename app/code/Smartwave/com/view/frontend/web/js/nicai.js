<script type="text/javascript">// <![CDATA[
    require([
        'jquery',
        'owl.carousel/owl.carousel.min'
    ], function ($) {
       $("#finder_1_1").css({"border":"1px", "color":"#f00", "border-style":"solid"});
$("#reset").click(function(){
    $("#finder_1_1").css({"border":"1px", "color":"#f00", "border-style":"solid"});
    $('#finder_1_1').attr("disabled",false);
    $('#finder_1_2').attr("disabled",true);
    $('#finder_1_3').attr("disabled",true);
    $("#finder_1_2").empty();
    $("#finder_1_3").empty();
    $("#finder_1_2").append("<option  >"+"Please Select make "+"</option>");
    $("#finder_1_3").append("<option  >"+"Please Select model "+"</option>");
    $("#finder_1_3").css({"border":"", "color":"", "border-style":""});
    });
$("#finder_1_1").change(function(){
    $("#finder_1_1").css({"border":"", "color":"", "border-style":""});
    $("#finder_1_2").css({"border":"1px", "color":"#f00", "border-style":"solid"});
    var year=$("#finder_1_1").val();
    $.post("http://192.168.1.16/magento21/com/index/syear", { "year": year },
    function(data){
    console.log("da: \n" + data );
    for(var i=0,l=data.length;i<l;i++){
    for(var key in data[i]){
    $("#finder_1_2").append("<option value='" +data[i][key]+ "'>" +data[i][key] + "</option>");       $("#finder_1_2").attr("disabled",false);
    }
    }
    });
    });
$("#finder_1_2").change(function(){
    $("#finder_1_2").css({"border":"", "color":"", "border-style":""});
    $("#finder_1_3").css({"border":"1px", "color":"#f00", "border-style":"solid"});
    var year =$("#finder_1_1").find("option:selected").text();
    var make=$("#finder_1_2").find("option:selected").text();
    $.post("http://192.168.1.16/magento21/com/index/smodel", {"year": year,"make": make},
    function(data){
    for(var i=0,l=data.length;i<l;i++){
    for(var key in data[i]){
    $("#finder_1_3").append("<option value='" +data[i][key]+ "'>" +data[i][key] + "</option>");
    $("#finder_1_3").attr("disabled",false);
    }
    }
    });
    }); });
// ]]></script>