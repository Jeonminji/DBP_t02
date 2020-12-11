function categoryChange(e) {
	var kangnam = ["선택", "신사동", "논현동", "압구정동", "청담동", "삼성동", "대치동", "역삼동", "도곡동", "개포동", "일원동", "수서동", "세곡동"];
	var kangbuk = ["선택", "강일동", "상일동", "명일동", "고덕동", "암사동", "천호동", "성내동", "길동", "둔촌동"];
	var kangseo = ["선택", "염창동", "등촌동", "화곡동", "우장산동", "가양동", "염창동", "내발산동", "외발산동", "공항동", "과해동", "오곡동", "오쇠동", "방화동"];
	var target = document.getElementById("dong");

	if(e.value == "강남구") var d = kangnam;
	else if(e.value == "강북구") var d = kangbuk;
	else if(e.value == "강서구") var d = kangseo;

	target.options.length = 0;

	for (x in d) {
		var opt = document.createElement("option");
		opt.value = d[x];
		opt.innerHTML = d[x];
		target.appendChild(opt);
	}    
}