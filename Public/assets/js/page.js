$("#first").click(function(){
		NowPage = parseInt($("#NowPage").text());
		if(NowPage>1){
			Page = 0;
		infoview(Page,PageSize);
		}else{
		$(this).addClass("disabled");
		}
		
		
	})
	$("#last").click(function(){
	
		LastPage = parseInt($("#TotalPage").text());
		NowPage = parseInt($("#NowPage").text());
		if(LastPage==NowPage){
		
		}else{
		Page = LastPage-1;
		infoview(Page,PageSize);
		}
	})
	$("#prev").click(function(){
		
		NowPage = parseInt($("#NowPage").text());
		Total = parseInt($("#TotalPage").text());
		Page = NowPage-2;
		if(Page<0){
			$(this).addClass("disabled");
		}else{
		$(this).removeClass();
		infoview(Page,PageSize);
		}
	
	})
	$("#next").click(function(){
	
		NowPage = parseInt($("#NowPage").text());

		Total = parseInt($("#TotalPage").text());
		Page = NowPage;
		if(Page>=Total){
			$(this).addClass("disabled");
		}else{
			$(this).removeClass();
			infoview(Page,PageSize);
		}
		
		
	})
	$('#SelectNo').change(function(){
	var Page = parseInt($(this).children('option:selected').val())-1;
	infoview(Page,PageSize);
	
	})