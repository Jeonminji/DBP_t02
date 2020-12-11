<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title> DBP final </title>
    <link rel="stylesheet" href="./css/style.css?after">
    <style>
    @import url(http://fonts.googleapis.com/earlyaccess/amstelvaralpha.css);
	</style>
</head>
<body>
<div class="center">
    <div class="inner">
    <h1>아파트 매매 <br>실거래가 조회 시스템</h1><tab> 
    <form action="search.php" method="POST" name="area" onsubmit="return chk();">
    <!-- 예산 range slider -->
        <div slider id="slider-distance">
            <div>
                <div inverse-left style="width:70%;"></div>
                <div inverse-right style="width:70%;"></div>
                <div range style="left:30%;right:30%;"></div>
            <span thumb style="left:30%;"></span>
            <span thumb style="left:70%;"></span>
            <div sign style="left:30%;">
            <span id="value">30</span>
            </div>
            <div sign style="left:70%;">
            <span id="value">70</span>
            </div>
        </div>
        <input type="range" tabindex="0" value="30" max="100" min="0.1" step="0.1" oninput="
            this.value=Math.min(this.value,this.parentNode.childNodes[5].value-1);
            var value=(100/(parseInt(this.max)-parseInt(this.min)))*parseInt(this.value)-(100/(parseInt(this.max)-parseInt(this.min)))*parseInt(this.min);
            var children = this.parentNode.childNodes[1].childNodes;
            children[1].style.width=value+'%';
            children[5].style.left=value+'%';
            children[7].style.left=value+'%';children[11].style.left=value+'%';
            children[11].childNodes[1].innerHTML=this.value;" name="minBudget"/>
        <input type="range" tabindex="0" value="70" max="100" min="0.1" step="0.1" oninput="
            this.value=Math.max(this.value,this.parentNode.childNodes[3].value-(-1));
            var value=(100/(parseInt(this.max)-parseInt(this.min)))*parseInt(this.value)-(100/(parseInt(this.max)-parseInt(this.min)))*parseInt(this.min);
            var children = this.parentNode.childNodes[1].childNodes;
            children[3].style.width=(100-value)+'%';
            children[5].style.right=(100-value)+'%';
            children[9].style.left=value+'%';children[13].style.left=value+'%';
            children[13].childNodes[1].innerHTML=this.value;" name="maxBudget"/>
        </div>
        예산 선택 (단위 : 억)
         <!-- 평수 range slider -->
        <div slider id="slider-distance">
            <div>
                <div inverse-left style="width:70%;"></div>
                <div inverse-right style="width:70%;"></div>
                <div range style="left:30%;right:30%;"></div>
            <span thumb style="left:30%;"></span>
            <span thumb style="left:70%;"></span>
            <div sign style="left:30%;">
            <span id="value">30</span>
            </div>
            <div sign style="left:70%;">
            <span id="value">70</span>
            </div>
        </div>
        <input type="range" tabindex="0" value="30" max="100" min="1" step="1" oninput="
            this.value=Math.min(this.value,this.parentNode.childNodes[5].value-1);
            var value=(100/(parseInt(this.max)-parseInt(this.min)))*parseInt(this.value)-(100/(parseInt(this.max)-parseInt(this.min)))*parseInt(this.min);
            var children = this.parentNode.childNodes[1].childNodes;
            children[1].style.width=value+'%';
            children[5].style.left=value+'%';
            children[7].style.left=value+'%';children[11].style.left=value+'%';
            children[11].childNodes[1].innerHTML=this.value;" name="minA"/>
        <input type="range" tabindex="0" value="70" max="100" min="1" step="0.1" oninput="
            this.value=Math.max(this.value,this.parentNode.childNodes[3].value-(-1));
            var value=(100/(parseInt(this.max)-parseInt(this.min)))*parseInt(this.value)-(100/(parseInt(this.max)-parseInt(this.min)))*parseInt(this.min);
            var children = this.parentNode.childNodes[1].childNodes;
            children[3].style.width=(100-value)+'%';
            children[5].style.right=(100-value)+'%';
            children[9].style.left=value+'%';children[13].style.left=value+'%';
            children[13].childNodes[1].innerHTML=this.value;" name="maxA"/>
        </div>
        평수 선택 <br><br>
        <!-- 카테고리 -->
        <select name="gu" id= "gu" onchange="categoryChange(this)">
            <option>지역 선택</option>
            <option value="강남구">강남구</option>
            <option value="강동구">강동구</option>
            <option value="강북구">강북구</option>
            <option value="강서구">강서구</option>
            <option value="관악구">관악구</option>
            <option value="광진구">광진구</option>
            <option value="구로구">구로구</option>
            <option value="금천구">금천구</option>
            <option value="노원구">노원구</option>
            <option value="도봉구">도봉구</option>
            <option value="동대문구">동대문구</option>
            <option value="동작구">동작구</option>
            <option value="마포구">마포구</option>
            <option value="서대문구">서대문구</option>
            <option value="서초구">서초구</option>
            <option value="성동구">성동구</option>
            <option value="성북구">성북구</option>
            <option value="송파구">송파구</option>
            <option value="양천구">양천구</option>
            <option value="영등포구">영등포구</option>
            <option value="용산구">용산구</option>
            <option value="은평구">은평구</option>
            <option value="종로구">종로구</option>
            <option value="중구">중구</option>
            <option value="중랑구">중랑구</option>
        </select>
        <select name="dong" id="dong">
        <option>지역을 먼저 선택하세요.</option>
        </select><br><br>
        <input type="submit" value="선택 완료" class="button"><br>
        </form>
    
 </div>
 </div>
<script>
  function categoryChange(e) {
    var kangnam = ["선택", "개포동", "논현동", "대치동", "도곡동", "삼성동", "세곡동", "수서동", "신사동",  "압구정동", "역삼동", "율현동", "일원동", "자곡동", "청담동"];   
    var kangdong = ["선택", "강일동", "고덕동", "길동", "둔촌동", "명일동", "상일동", "성내동", "암사동", "천호동"];
    var kangbuk = ["선택", "미아동", "번동", "수유동", "우이동"];
    var kangseo = ["선택", "가양동", "개화동", "공항동", "과해동","내발산동", "등촌동", "마곡동", "방화동", "염창동", "오곡동", "오쇠동", "외발산동", "화곡동"]; 
    var gwanak = ["선택", "남현동", "봉천동", "신림동"];
    var gwangjin = ["선택", "광장동", "구의동", "군자동", "능동", "자양동", "중곡동", "화양동"];
    var guro = ["선택", "가리봉동", "개봉동", "고척동", "구로동", "궁동", "신도림동", "오류동", "온수동",  "천왕동", "항동"];
    var geumcheon = ["선택", "가산동", "독산동", "시흥동"];
    var nowon = ["선택", "공릉동", "상계동", "월계동", "중계동", "하계동"];
    var dobong = ["선택", "도봉동", "방학동", "쌍문동", "창동"];
    var dongdaemun = ["선택","답십리동","신설동", "용두동", "이문동", "장안동", "전농동", "제기동", "청량리동","회기동", "휘경동"]; 
    var dongjak = ["선택", "노량진동","대방동", "동작동", "본동", "사당동", "상도1동", "상도동", "신대방동", "흑석동"];  
    var mapo = ["선택", "공덕동", "구수동", "노고산동", "당인동", "대흥동", "도화동", "동교동", "마포동", "망원동" , "상수동", "상암동", "서교동", "성산동", "신공덕동" ,"신수동", "신정동", "아현동", "연남동", "염리동","용강동","중동", "창전동","토정동", "하중동","합정동", "현석동"];
    var seodaemun = ["선택", "남가좌동","냉천동", "대신동", "미근동", "봉원동", "북가좌동", "북아현동", "신촌동", "연희동", "영천동", "옥천동", "창천동", "천연동", "충정로2가", "충정로3가", "합동","현저동", "홍은동","홍제동"];
    var seocho = ["선택", "내곡동", "반포동", "방배동", "서초동", "신원동", "양재동", "염곡동", "우면동", "원지동", "잠원동"];
    var seongdong = ["선택", "금호동1가", "금호동2가", "금호동3가", "금호동4가", "도선동", "마장동", "사근동", "상왕십리동", "성수동1가", "성수동2가", "송정동", "옥수동", "용답동", "응봉동", "하왕십리동", "행당동", "홍익동"];
    var seongbukgu = ["선택", "길음동", "돈암동", "동선동1가", "동선동2가", "동선동3가", "동선동4가", "동선동5가", "동소문동1가","동소문동2가","동소문동3가","동소문동4가", "동소문동5가", "동소문동6가", "동소문동7가", "보문동1가", "보문동2가", "보문동3가", "보문동4가", "보문동5가", "보문동6가", "보문동7가", "삼선동1가", "삼선동2가", "삼선동3가", "삼선동4가", "삼선동5가", "상월곡동", "석관동", "성북동", "성북동1가", "안암동1가", "안암동2가", "안암동3가", "안암동4가", "안암동5가", "장위동", "정릉동", "종암동", "하월곡동"];
    var songpagu = ["선택", "가락동","거여동", "마천동", "문정동","방이동","삼전동", "석촌동", "송파동", "신천동", "오금동","잠실동", "장지동", "풍납동"];
    var yangcheon = ["선택", "남현동", "봉천동", "신림동"];
    var yeongdeungpo = ["선택","당산동", "당산동1가", "당산동2가", "당산동3가", "당산동4가","당산동5가", "당산동6가","대림동","도림동","문래동1가","문래동2가","문래동3가","문래동4가","문래동5가","문래동6가","신길동","양평동","양평동1가","양평동2가","양평동3가","양평동4가","양평동5가","양평동6가","양화동","여의도동","영등포동","영등포동1가","영등포동2가","영등포동3가","영등포동4가","영등포동5가","영등포동6가","영등포동7가","영등포동8가"];
    var yongsan = ["선택", "갈월동", "남영동", "도원동", "동빙고동", "동자동","문배동","보광동","산천동","서계동", "서빙고동","신계동","신창동","용문동","용산동1가","용산동2가","용산동3가","용산동4가","용산동5가","용산동6가", "원효로1가","원효로2가","원효로3가","원효로4가","이촌동","이태원동,","주성동","청암동", "청파동1가","청파동2가","청파동3가","한강로1가","한강로2가","한강로3가","한남동","효창동", "후암동"];
    var eunpyeong = ["선택", "갈현동", "구산동", "녹번동", "대조동", "불광동", "수색동", "신사동", "역촌동", "응암동", "증산동", "진관동"];
    var jongno = ["선택", "가회동", "견지동", "경운동", "계동", "공평동", "관수동", "관철동", "관훈동", "교남동", "교북동", "구기동", "궁정동", "권농동", "낙원동", "내수동", "내자동",  "누상동", "누하동", "당주동", "도렴동", "돈의동", "동숭동", "명륜1가", "명륜2가","명륜3가","명륜4가", "묘동", "무악동", "봉익동", "부암동", "사간동", "사직동", "삼청동", "서린동", "세종로", "소격동", "송월동", "송현동", "수송동", "숭인동", "신교동", "신문로1가", "신문로2가", "신영동", "연건동", "안국동", "연건동", "연지동", "예지동", "옥인동", "와룡동", "운니동", "원남동", "원서동", "이화동", "익선동", "인의동", "장사동", "제동", "적선동", "종로1가", "종로2가", "종로3가", "종로4가", "종로5가", "종로6가", "중할동", "창성동", "창신동", "청운동", "청진동", "체부동", "충신동", "통의동", "통인동", "팔판동", "평동", "평창동", "필운동", "행촌동", "혜화동", "홍지동", "홍파동", "화동", "효제동"];
    var jungnang = ["선택", "망우동", "면목동","목동", "상봉동", "신내동", "중화동"];
    var jung = ["선택", "광희동1가","광희동2가","남대문로1가","남대문로2가","남대문로3가","남대문로4가","남대문로5가","남산동1가","남산동2가","남산동3가","남창동","남학동","다동","만리동1가","만리동2가","명동1가","명동2가","무교동","무학동","묵정동","방산동","봉래동1가","봉래동2가","북창동","산림동","삼각동","서소문동","소공동","수표동","수하동","순화동","신당동","쌍림동","예관동","예장동","오장동","을지로1가","을지로2가","을지로3가","을지로4가","을지로5가","을지로6가","을지로7가","의주로1가","의주로2가","인현동1가","인현동2가","입정동","장교동","장충동1가","장충동2가","저동1가","저동2가","정동","주교동","주자동","중림동","초동","충무로1가","충무로2가","충무로3가","충무로4가","충무로5가","충정로1가","태평로1가","태평로2가","필동1가","필동2가","필동3가","황학동","회현동1가","회현동2가","회현동3가","흥인동"];
	var target = document.getElementById("dong");

    switch(e.value){
       case "강남구": d = kangnam; break;
       case "강동구": d = kangdong; break;
       case "강북구": d = kangbuk; break;
       case "강서구": d = kangseo; break;
       case "관악구": d = gwanak; break;

       case "광진구": d = gwangjin; break;
       case "구로구": d = guro; break;
       case "금천구": d = geumcheon; break;
       case "노원구": d = nowon; break;
       case "도봉구": d = dobong; break;

       case "동대문구": d = dongdaemun; break;
       case "동작구": d = dongjak; break;
       case "마포구": d = mapo; break;
       case "서대문구": d = seodaemun; break;
       case "서초구": d = seocho; break;

       case "성동구": d = seongdong; break;
       case "성북구": d = seongbukgu; break;
       case "송파구": d = songpagu; break;
       case "양천구": d = yangcheon; break;
       case "영등포구": d = yeongdeungpo; break;

       case "용산구": d = yongsan; break;
       case "은평구": d = eunpyeong; break;
       case "종로구":  d = jongno; break;
       case "중구": d = jung; break;
       case "중랑구": d = jungnang; break;
    }

	target.options.length = 0;

	for (x in d) {
		var opt = document.createElement("option");
		opt.value = d[x];
		opt.innerHTML = d[x];
		target.appendChild(opt);
	}    
}

function chk() {
    if (document.getElementById("gu").value == "지역 선택") {
            alert("지역은 필수 선택값입니다.");
            return false;
    } else document.입력.submit();
}
window.onpageshow = function(event){ // 뒤로가기 이벤트 발생시 새로고침
    if (event.persisted || (window.performance && window.performance.navigation.type == 2)){
        window.location.reload();
    }
};
</script>

</html>
</body>
</html>