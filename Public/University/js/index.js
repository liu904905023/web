$(function(){
	var name = $("#login-name");
	var phone = $("#login-pass");
	var studentId = $("#login-studentId");
	var num = $("#num");
	var note = $("#login-note");
	var add = $("#add");
	var down = $("#down");
	var num = $("#num");
	var wifi = $("#KDS");
	var money = $("#money");
	var allMoney = $("#allMoney");
	var Total_Money = $("#Total_Money");
	var ProductCount = $("#ProductCount");
	var nums = 1;
	var m = 0;
	var trime;
	var flagName = false;
	allMoney.html(nums*m);
	Total_Money.val(nums*m);
	var reg = /^[\u2E80-\u9FFF]+$/;
	var data = {
		"选择宽带":0,
		"2M-Wifi":30,
		"2M-Wifi（电信）":20,
		"2M":20,
		"6M":50,
		"12M":80,
		"20M":120
	};
	var style1 = {
		"color":"#e74c3c",
		"border":"1px solid #e74c3c"
	};
	var style2 = {
		"color":"#2ecc71",
		"border":"none"
	};
//	name.on("blur",nameFn);
	name.on("input",nameFn);
	function nameFn(){
		if(!reg.test(name.val())){
			flagName = false;
			$(this).css(style1);
			$(".title1").remove();
			$(this).parent().append("<div class='title1'><span class='glyphicon glyphicon-info-sign'></span> 名字格式不正确</div>");
			$(".title1").animate({opacity:1},500);
			$(this).siblings().css("color","#e74c3c");
			setTime();
		}else{
			flagName = true;
			$(this).css(style2);
//			$(".title1").animate({width:"0px",height:"0px"},1000);
			$(".title1").remove();
			$(this).siblings().css("color","#2ecc71");
			clearInterval(trime);
		}
	}
	var flagPhone = false;
//	phone.on("blur",phoneFn);
	phone.on("input",phoneFn);
	function phoneFn(){
		var regPhone = /^(13|15|17|18)[0-9]{9}$/;
		if(!regPhone.test(phone.val())){
			flagPhone = false;
			$(this).css(style1);
			$(".title2").remove();
			$(this).parent().append("<div class='title2'><span class='glyphicon glyphicon-info-sign'></span> 电话格式不正确</div>");
			$(".title2").animate({opacity:1},500);
			$(this).siblings().css("color","#e74c3c");
			setTime();
		}else{
			flagPhone = true;
			$(".title2").remove();
			$(this).css(style2);
			$(this).siblings().css("color","#2ecc71");
			clearInterval(trime);
		}
	}
	var flagStudentId = false;
//	studentId.on("blur",studentIdFn);
	studentId.on("input",studentIdFn);
	function studentIdFn(){
		var regStudentId = /^\d+$/;
		if(!regStudentId.test(studentId.val())){
			flagStudentId = false;
			$(this).css(style1);
			$(".title3").remove();
			$(this).parent().append("<div class='title3'><span class='glyphicon glyphicon-info-sign'></span> 学号格式不正确</div>");
			$(".title3").animate({opacity:1},500);
			$(this).siblings().css("color","#e74c3c");
			setTime();
		}else{
			flagStudentId = true;
			$(".title3").remove();
			$(this).css(style2);
			$(this).siblings().css("color","#2ecc71");
			clearInterval(trime);
		}
	};
	note.on("input",function(){
		$(this).css(style2);
		$(this).siblings().css("color","#2ecc71");
	});
	var n = 1;
	add.on("touchend",function(){
		n++;
		num.html(n);
		nums = num.html();
		allMoney.html(nums*m);
		ProductCount.val(nums);
		Total_Money.val(nums*m);

	});
	down.on("touchend",function(){
		n--;
		if(n<=1){
			n=1;
		}
		num.html(n);
		nums = num.html();
		allMoney.html(nums*m);
		ProductCount.val(nums);
		Total_Money.val(nums*m);
		
	});
	var flagWifi = false;
	wifi.on("input",function(){
		money.html(data[wifi.val()]);
		m = money.html();
		allMoney.html(nums*m);
		Total_Money.val(nums*m);
		if(wifi.val()=="选择宽带"){
			flagWifi = false;
			wifi.css("color","#e74c3c");
			money.parent().css("color","#e74c3c");
			money.parent().siblings().css("color","#e74c3c");
			$(this).parent().siblings().css("color","#e74c3c");
			setTime();
		}else{
			flagWifi = true;
			wifi.css("color","#2ecc71");
			money.parent().css("color","#2ecc71");
			money.parent().siblings().css("color","#2ecc71");
			$(this).parent().siblings().css("color","#2ecc71");
			clearInterval(trime);
		}
	});
	var btn = $("#btn");
	btn.attr("disabled",false);
	btn.on("tap",function(){
		if(flagName&&flagPhone&&flagStudentId&&flagWifi){
//			alert("付款成功");
		}else{
			return false;
		}
	});
	function setTime(){
		trime = setInterval(function(){
			if(flagName&&flagPhone&&flagStudentId&&flagWifi){
			document.getElementById("btn").disabled = false;
			}else{
				btn.attr("disabled",true);
			}
		},1000);
	}
	
});