// JavaScript Document
//by _team_hsk:2014-1110
$(function(){
	//列表页表格背景
  $(".table_tk tr").each(function(i){this.style.backgroundColor  =  ['#fff','#f1f1f1'][i%2]   })   
	//添加页切换
	/*
  $(".1107-tab-add span").each(function(d) {
	  $(this).click(function(){
		 $(".1107-tab-add span").removeClass("hover");
		 $(this).addClass("hover");
		 $(".1107-add").hide();
		 $(".1107-add:eq("+d+")").show();
	});
   }); 
   */
   $(".table_tk tr").each(function(i){
	   $(this).mousemove(function(){
		   $(this).css("background","#d2e8f8")
		   }).mouseout(function(){
			   this.style.backgroundColor  =  ['#fff','#f1f1f1'][i%2] 
			   })
	   })
   //顶部导航
   $(".nav li:last-child").addClass("-nav-nor");
   //关闭添加成功提示框
   $(".blue-success-close").click(function(){
	   $(".add-success-blue").animate({left:"100px",opacity:0});
   })
   $(".yellow-success-close").click(function(){
	   $(".add-success-yellow").animate({left:"100px",opacity:0});
   })
})

window.onresize=function()
{ 
	var he=(document.documentElement.clientHeight)-$(".top").height()-$(".nav").height()-15;
	var wid=$(window).width();
	//var rightWidth=0.89*wid;
	var leftWidth=171;
	var leh=191;
       // $(".right").css("height",(Number(he)+100)+"px");
       //$(".left").css("height",he+"px");
       // $(".left").css("width",leftWidth+"px");
	if(wid<960){
		$(".-main,.top,.nav").width(960);
		//$(".right").width(790);
		$(".right").width(770);
		$(".nav li").width(70);
	}
	else{
		$(".-main,.top,.nav,.nav li").removeAttr("style");
		$(".right").width(wid-leh);
	}
} 

 
window.onload=function(){
	var he=(document.documentElement.clientHeight)-$(".top").height()-$(".nav").height()-15;
	var wid=$(window).width();
	//var rightWidth=0.89*wid;
	var leftWidth=171;
	var leh=191;
   //$(".right").css("height",(Number(he)+100)+"px");
	//$(".left").css("height",he+"px");
	//$(".left").css("width",leftWidth+"px");
	if(wid<960){
		$(".-main,.top,.nav").width(960);
		//$(".right").width(790);
		$(".right").width(770);
		$(".nav li").width(70);
	}
	else{
		$(".-main,.top,.nav,.nav li").removeAttr("style");
		$(".right").width(wid-leh);	
	}
	
} 


// JavaScript Document
//function wh(){
//	var wid=$(window).width()
//	var he=$(window).height()
//	$('.zg_left').height(he)
//}
//window.onload=function(){
//	wh()
//	
//	}
//
//$(function(){
//	$(".right_tab2:first").show()
//	$(".right_nav2 span").each(function(x){
//		$(this).mousemove(function(){
//			$(".right_nav2 span").removeClass("right_nav2_dq")
//			$(this).addClass("right_nav2_dq")
//			$(".right_tab2").hide()
//			$(".right_tab2:eq("+x+")").show()
//			})
//		})
//	})	
//$(function(){
//	var sWidth = $("#slider_name").width();
//	var len = $("#slider_name .silder_panel").length;
//	var index = 0;
//	var picTimer;
//	
//	var btn = "<a class='prev'>Prev</a><a class='next'>Next</a>";
//	$("#hah").append(btn);
//
//	$("#slider_name .silder_nav li").css({"opacity":"0.6","filter":"alpha(opacity=60)"}).mouseenter(function() {																		
//		index = $("#slider_name .silder_nav li").index(this);
//		showPics(index);
//	}).eq(0).trigger("mouseenter");
//
//	
//
//
//	// Prev
//	$("#slider_name .prev").click(function() {
//		index -= 1;
//		if(index == -1) {index = len - 1;}
//		showPics(index);
//	});
//
//	// Next
//	$("#slider_name .next").click(function() {
//		index += 1;
//		if(index == len) {index = 0;}
//		showPics(index);
//	});
//
//	// 
//	$("#slider_name .silder_con").css("width",sWidth * (len));
//	
//	// mouse 
//	$("#slider_name").hover(function() {
//		clearInterval(picTimer);
//	},function() {
//		picTimer = setInterval(function() {
//			showPics(index);
//			index++;
//			if(index == len) {index = 0;}
//		},5000); 
//	}).trigger("mouseleave");
//	
//	// showPics
//	function showPics(index) {
//		var nowLeft = -index*sWidth; 
//		
//		
//		$("#slider_name .silder_con").stop(true,false).animate({"left":nowLeft},300);
//	
//		$("#slider_name .silder_nav li").removeClass("current").eq(index).addClass("current"); 
//		$("#slider_name .silder_nav li").stop(true,false).animate({"opacity":"0.5"},300).eq(index).stop(true,false).animate({"opacity":"1"},300);
//	}
//});	