
<?php
    $db_host = "localhost"; 
    $db_user = "aptinfo"; 
    $db_passwd = "epqpvmf#2";
    $db_name = "aptinfo";
    header("Content-Type:text/html;charset=utf-8");
    // MySQL - DB 접속.
    $link = mysqli_connect($db_host, $db_user, $db_passwd, $db_name);
   
    $filtered_id = array(
        '법정동' => mysqli_real_escape_string($link, $_GET['법정동']),
        '지번' => mysqli_real_escape_string($link, $_GET['지번']),
        '아파트' => mysqli_real_escape_string($link, $_GET['아파트'])
    );

    $name = $_GET['name']; 
    $name2 = str_replace("'", "''", $name); # 아파트 이름에 홑따옴표가 들어가는 경우 오류가 나서 '' 로 바꿈

    // 차트용 쿼리, 테이블 표기용 쿼리 (정렬 문제로 분리함, 차트는 오름차순이나 테이블은 내림차순으로 표기해야함)
    $query = "SELECT 거래금액 AS price, CONCAT(TRUNCATE(거래금액/10000, 1), '억') AS 거래금액, 
    CONCAT(년, '년 ', 월, '월') AS 거래년월, CONCAT(년, '년 ', 월, '월 ', 일, '일') AS 거래일자, 
    CONCAT(법정동, ' ', 지번) AS 주소, CONCAT(전용면적, '㎡') AS 전용면적, 층, CONCAT(TRUNCATE(전용면적,2), '㎡') AS 면적
    FROM {$_GET['gu']} 
    WHERE 아파트 = '{$name2}' AND 전용면적 = {$_GET['ac']} ORDER BY 1 DESC";    

    $chartQuery = "SELECT 거래금액, CONCAT(년, '년 ', 월, '월') AS 거래년월
    FROM {$_GET['gu']} 
    WHERE 아파트 = '{$name2}' AND 전용면적 = {$_GET['ac']}"; 

    // 지역별 평균 평당 가격을 구하는 쿼리
    $avgAcreageQuery = "SELECT ROUND(AVG(a.평당가)) as 평당
    FROM (SELECT 아파트, AVG(거래금액) AS 거래금액, TRUNCATE(거래금액/(전용면적/3.3), 0) AS 평당가
            FROM {$_GET['gu']} WHERE 월 IN (MONTH(sysdate()) - 1, MONTH(sysdate()), MONTH(sysdate()) + 1) AND 법정동 = '{$_GET['법정동']}' GROUP BY 아파트, 평당가) a";

    // 해당 아파트의 평당 가격을 구하는 쿼리
    $acreageQuery = "SELECT ROUND(AVG(a.평당가)) as 평당
    FROM (SELECT 아파트, AVG(거래금액) AS 거래금액, TRUNCATE(거래금액/(전용면적/3.3), 0) AS 평당가
        FROM {$_GET['gu']} WHERE 월 IN (MONTH(sysdate()) - 1, MONTH(sysdate()), MONTH(sysdate()) + 1) AND 아파트 = '{$name}' AND 전용면적 = {$_GET['ac']} GROUP BY 아파트, 평당가) a";

    $result = mysqli_query($link, $query);
    $chartResult = mysqli_query($link, $chartQuery);
    $acreageResult = mysqli_query($link, $avgAcreageQuery);
    $acreageResult2 = mysqli_query($link, $acreageQuery);

    $list = '';
    while($row = mysqli_fetch_array($result)){
        $adress = $row['주소'];
        $list .= '<tr class="row100 body"><td class="cell100 column1">'.$row['거래일자'].'</td>';
        $list .= '<td class="cell100 column2">'.$row['거래금액'].'</td>';
        $list .= '<td class="cell100 column3">'.$row['층'].'</td>';
        $list .= '<td class="cell100 column4">'.$row['면적'].'</td></tr>';
    }

    while($row = mysqli_fetch_array($chartResult)){
        //$money[] = $row['거래금액'];
        $money[] = ROUND($row['거래금액']/10000, 1);
        $date[] = $row['거래년월'];
    }

    while($row = mysqli_fetch_array($acreageResult)){
        $지역평당가격 = $row['평당'];
    }

    while($row = mysqli_fetch_array($acreageResult2)){
        $평당가격 = $row['평당'];
    }

    $gap = abs($지역평당가격 - $평당가격);
    $지역평당가격 > $평당가격 ? $msg = "낮은 가격입니다." : $msg = "높은 가격입니다.";

    $m = json_encode($money, JSON_NUMERIC_CHECK);
    $d = json_encode($date);
    
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title> DBP final </title>
    <link rel="stylesheet" href="./css/style.css?after">
    	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/perfect-scrollbar/perfect-scrollbar.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<style>
html, body {
    width: 100%;
    height: 100%;
}
.center {
    height: 80vh; 
}
p {
    display:inline;
}
#bold {
    font-weight:bold;
    color:#5882FA;
}

</style>
<body>
        <div class="container-table100">
			<div class="wrap-table100">
                <div style="text-align:center;">
                <h1><?=$_GET['법정동']?> <?= $name ?></h1><br>
                서울특별시 <?= $_GET['gu'] ?> <?= $adress ?><br><br>
                <!-- 실거래가 그래프 -->
                <div style='width:100%;'>
                <canvas id="myChart" width="10" height="4"></canvas>
                </div>
            <br>
            <p> 최근 3개월 기준 <?=$_GET['법정동']?>의 평균 평당 가격은 <p id="bold"><?=$지역평당가격?>만원</p>이며, <?=$name?>의 평당 가격은 <p id="bold"><?=$평당가격?>만원</p>입니다. </p>
            평균 평당 가격에 비해 <p id="bold"><?= $gap ?>만원</p> <?= $msg ?><br><br> 
            </div>
            <!-- 실거래가 테이블 -->
				<div class="table100 ver1 m-b-110" style="width:48%; float:left;">
					<div class="table100-head">
						<table style="vertical-align: middle;">
							<thead>
								<tr class="row100 head">
                                <th class="cell100 column1">계약일자</th>
								<th class="cell100 column2">금액</th>
                                <th class="cell100 column3">층수</th>
								<th class="cell100 column4">전용면적</th>
								</tr>
							</thead>
						</table>
					</div>
					<div class="table100-body js-pscroll">
						<table>
							<tbody>
                            <?= $list ?>
							</tbody>
						</table>
                    </div>
                </div>
                <div id="map" style="width:48%; height:650px; float:right;"></div>
            </div>
        </div>
    
    <!-- 상세 지도 -->
    <script type="text/javascript"
        src="//dapi.kakao.com/v2/maps/sdk.js?appkey=2ee0eb182138378558cb4db1be2a8a3b&libraries=services">
    </script>
    <script type="text/javascript">
        var myAddress = ["<?php echo $filtered_id['법정동']." ".$filtered_id['지번']; ?>"]; // 주소
        var aptName= "<br><?php echo $filtered_id['아파트']?>"
       // 맵을 넣을 div 
        var container = document.getElementById('map');
        var options = {
            center: new kakao.maps.LatLng(35.95, 128.25),
            level: 3
        };

         // 맵 표시 
        var map = new kakao.maps.Map(container, options);

        // 일반 지도와 스카이뷰로 지도 타입을 전환할 수 있는 지도타입 컨트롤을 생성합니다 
        var mapTypeControl = new kakao.maps.MapTypeControl();
        map.addControl(mapTypeControl, kakao.maps.ControlPosition.TOPRIGHT);

        // 지도 확대 축소를 제어할 수 있는 줌 컨트롤을 생성합니다 
        var zoomControl = new kakao.maps.ZoomControl();
        map.addControl(zoomControl, kakao.maps.ControlPosition.RIGHT);

        // 주소 -> 좌표 변환 라이브러리 
        var geocoder = new kakao.maps.services.Geocoder();
        

        //주소로 좌표 검색
        function myMarker(number, address) {
            geocoder.addressSearch(address, function(result, status) {
                if (status === kakao.maps.services.Status.OK) {
                    var coords = new kakao.maps.LatLng(result[0].y, result[0].x);

                    var marker = new kakao.maps.Marker({
                        position: coords,
                        clickable: true
                    });

                    // 마커를 지도에 표시합니다. 
                    marker.setMap(map);
                    
                    // 인포윈도우로 장소에 대한 설명을 표시합니다
                    var infowindow = new kakao.maps.InfoWindow({
                        content: '<div style="width:150px;text-align:center;padding:6px 0;">' + myAddress + aptName + '</div>'
                    });
                    infowindow.open(map, marker);

                    map.setCenter(coords); //지도 중심 좌표 검색한 위치로 이동
                }
            });
        }

        //위치 이동
        for (i=0; i<myAddress.length; i++) {
            myMarker(i+1, myAddress[i]);
        }

    window.onpageshow = function(event){ // 뒤로가기 이벤트 발생시 새로고침
    if (event.persisted || (window.performance && window.performance.navigation.type == 2)){
        window.location.reload();
    }
    };
    </script>
    <!-- 차트 관련 스크립트 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
            <script>
                var ctx = document.getElementById('myChart').getContext('2d');
                var data = {
                type: 'line', //차트 종류
                    data: {
                        labels: <?php echo $d; ?>, //x축 (계약년도, 계약월)
                        scaleLabel: "<%=new Intl.NumberFormat().format(value) %> 억",
                        datasets: [{
                            label: "<?= $name ?>", //상단 중앙 데이터 라벨 (아파트명)
                            backgroundColor: 'rgb(88, 130, 250)', //상단 중앙 라벨 컬러
                            fill:false, // line의 아래쪽 색칠 여부
                            borderColor: 'rgb(88, 130, 250)', //라인 컬러
                            lineTension:0.3, // 값을 높이면, line의 장력 커짐
                            data: <?php echo print_r($m, true); ?>, //거래금액, labels 수와 일치해야함
                        }]
                    }, 
                    options: {
                        title: {
                            text: 'Chart.js Time Scale'
                        },
                        scales: {
                            yAxes: [{
                                    scaleLabel: {
                                    display: true,
                                    labelString: '거래금액(억)'
                                }
                            }]
                        },
		            }
                }
                var chart = new Chart(ctx, data);
            </script>
</body>
</html>