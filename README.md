# **DBP_t02**
2020 2학기 SSWU DBP 기말 프로젝트
#
## **목차**
- [프로젝트 소개](#프로젝트-소개)
- [프로젝트 목적](#프로젝트-목적)
- [구축 환경](#구축-환경)
- [데이터 출처](#데이터-출처)
- [사전 작업](#사전-작업)
- [동작 과정](#동작-과정)
- [참고 문서](#참고-문서)
#
## **프로젝트 소개**
아파트 매매 실거래가 조회 시스템으로 지역과 예산, 평수의 범위를 설정해서 맞춤형으로 아파트 실거래가 변동추이와 도면, 상세 위치 등 서울시 아파트 정보를 제공하는 웹사이트이다.

![동작영상](https://user-images.githubusercontent.com/48701368/101871043-2da0ac00-3bc6-11eb-92be-9f52c4d447f4.gif)
#
## **프로젝트 목적**
최근 치솟는 아파트값 상승 뉴스와 각종 부동산 대책이 끊이지 않고 있다. 이에 따라 사용자 맞춤형 아파트 정보와 아파트 실거래가 변동 추이 그래프를 제공하여 합리적인 가격대에 아파트를 매매 및 해당 부동산에 대해 용이한 투자 가치분석을 실현하고자 한다. 뿐만 아니라 아파트 도면과, 위치와 같은 시각 정보를 제공하여 이사를 원하는 젊은 층부터 투자 목적으로 아파트 실거래가 정보에 대한 관심이 많은 중년층까지 모두에게 편리하고 직관적인 인터페이스를 제공하고자 한다.
#

## **구축 환경**
- 해당 프로젝트는 웹의 형태로 제작했다. 아파트 정보는 거래, 투자 등 필요한 그 순간에만 보는 것이기 때문에 설치가 필요 없이 모든 기기와 브라우저에서 접근이 가능한 웹이 더 적합하다고 판단했다.
- 다음으로 웹서버이다. 직접 웹서버를 구축하기에는 24시간 가동하면서 들어가는 유지비용을 지불하기에는 어려운 상황이기 때문에
 외부 호스팅이 최적의 선택이라고 생각했다. 
 - 외부 호스팅 중에서도 웹호스팅을 선택했다. 트래픽이 많이 발생하는 대규모의 사이트가 아니라 소규모의 간단한 웹페이지를 만들 목적이기 때문이다. 
 - 호스팅 업체는 가격과 안정성, 신뢰도 중 가격을 최우선이었고 위에서 언급했듯이 간단한 웹페이지 제작으로 최소한으로 구동만 되면 되기 때문에  무료 호스팅을 지원하는 닷홈 웹호스팅을 이용하고 있다. 
 - 닷홈의 무료 웹호스팅 사양은 다음과 같다.

    |웹서버|PHP|DB|
    |---|---|---|
    |Apache 2.2|PHP 7.3|MySQL 5.7|

#
## **데이터 출처**
### 사용 데이터
- [카카오 Maps API web](https://apis.map.kakao.com/web/)
- [네이버 부동산 제공 아파트 도면 이미지 및 전용면적](https://search.naver.com/search.naver?sm=tab_hty.top&where=nexearch&query=%EC%97%AD%EC%82%BC%EB%9E%98%EB%AF%B8%EC%95%88&oquery=%EC%95%84%ED%8C%8C%ED%8A%B8+%EA%B2%80%EC%83%89&tqi=U9KZplp0Yidss6Djpm8ssssssxw-266004)
- [국토교통부 제공 아파트매매 실거래 데이터 API](https://www.data.go.kr/data/15057511/openapi.do)
    
    |항목명|항목설명|항목크기|샘플데이터|
    |---|---|---|---|
    |거래금액|거래금액(만원)|40|82,500|
    |건축년도|건축년도|4|2015|
    |년|계약년도|4|2015|
    |법정동|법정동|40|사직동|
    |아파트|아파트명|40|광화문풍림스페이스본(9-0)|
    |월|계약월|2|12|
    |일|일|6|1|
    |전용면적|전용면적(㎡)|20|94.51|
    |지번|지번|10|9|
    |지역코드|지역코드|5| 11110|
    |층|층|4|11|

국토교통부에서 제공하는 아파트 매매 실거래 데이터 OpenAPI를 활용해서 아파트 정보를 가져오고, 카카오 Maps API를 통해 지도를 보여준다. 아파트 도면 이미지와 전용면적 데이터는 네이버 아파트 검색 결과를 크롤링했다. 국토 교통부 제공 아파트 매매 실거래 데이터 항목은 위와 같다.
#
## **사전 작업**
### DB
- [xml파싱 파이썬 코드](https://github.com/yunyezl/DBP_t02/blob/main/python/apiParser.py)로 지역구별로 csv파일을 생성했다.

    ![csv](https://user-images.githubusercontent.com/48701368/101868432-af8dd680-3bc0-11eb-98f3-02f52bba7faa.PNG)

- [Convert CSV to SQL](https://www.convertcsv.com/csv-to-sql.htm) 사이트에서 csv파일을 sql파일로 변환한 후, create table 구문에서 id 컬럼을 추가해 25개의 지역구 테이블을 생성했다. insert문으로 해당 지역구 테이블에 아파트데이터를 삽입했다.

    ![sql 변환](https://user-images.githubusercontent.com/48701368/101867711-2c1fb580-3bbf-11eb-8fe4-f14270c14686.PNG)
    ![테이블 생성](https://user-images.githubusercontent.com/48701368/101867695-2629d480-3bbf-11eb-9eb7-9cbda79fccdc.PNG)
    ![insert](https://user-images.githubusercontent.com/48701368/101867960-bbc56400-3bbf-11eb-8437-b11537c679b8.PNG)

### 도면 이미지
 - [gfp.py](https://github.com/yunyezl/DBP_t02/blob/main/python/gfp.py)를 작성하여 아파트 도면 이미지를 크롤링했다. 자세한 과정은 다음과 같다. 로컬 저장소에 서울시 25개의 지역구에 대해서 지역구\법정동\아파트명\전용면적.jpg 경로로 도면이미지가 저장되도록 코드를 작성했다. 
 - 해당 루트 폴더를 ftp서버로 업로드했으나 무료 호스팅 이용에 따른 계정용량 초과로 25개 지역구 중 14개(강남, 강북, 서대문구, 서초구, 성동구, 성북구, 송파구, 양천구, 영등포구, 용산구, 은평구, 종로구, 중구, 중랑구)의 지역에 대해서만 업로드 했다. 아래 사진은 도면이미지가 업로드된 경로이다.

    ![이미지파일경로2](https://user-images.githubusercontent.com/48701368/102011796-f4189e00-3d89-11eb-882c-14139ceb430a.PNG)
![이미지파일경로1](https://user-images.githubusercontent.com/48701368/102011797-f549cb00-3d89-11eb-9a5c-58a09e1c8b2f.PNG)
#
## **동작 과정**

- [아파트 매매 실거래가 조회 시스템](http://aptinfo.dothome.co.kr)으로 이동한다.

- **[index.php](https://github.com/yunyezl/DBP_t02/blob/main/index.php)** 페이지에서 원하는 예산과 평수의 범위를 조절한 다음 조회하고 싶은 지역구를 선택한다.
추가로 법정동을 선택할 수도 있다. 선택 완료 버튼을 누르면 다음과 같은 5개의 옵션 값이 [search.php](https://github.com/yunyezl/DBP_t02/blob/main/search.php)페이지로 전달된다. 
    - 최소, 최대 평수 - $minA, $maxA
    - 최소, 최대 예산 - $minB, $maxB
    - 지역구 - $gu
    - 동(선택사항) - $dong

    ![인덱스](https://user-images.githubusercontent.com/48701368/101850292-da663380-3b9c-11eb-83d4-d61d6e19a549.png)


- **[search.php](https://github.com/yunyezl/DBP_t02/blob/main/search.php)** 에서 아래와 같은 쿼리가 동작한다. 우선 사용자가 검색 조건으로 법정동까지 선택했는지 여부에 따라 where절에서 법정동 = '{$dong}' 을 추가하여 쿼리를 구분했다. 우선 사용자가 입력한 예산과 평수의 범위, 지역 값을 받아온다. 여기서 평수에 주목할 부분은 평수이다. DB에 저장된 데이터는 평수가 아닌 전용면적이다. 따라서 사용자가 입력한 최소, 최대 평수 값($minA, $maxA)에 3.305785(1평은 약 3.305785㎡)을 곱해서 전용면적으로 변환한 후 조건에 맞는지 검사한다. 이외에도 최소, 최대 거래금액 값($minB, $maxB)사이에 포함되는지 등을 검사한 결과 조건에 맞는 아파트 거래 목록과 함께 실거래가 평균 및 지도 위치정보를 한 눈에 볼 수 있다. 지도의 태그(또는 실거래가 평균)나 목록에서 도면보기를 클릭해서 상세 정보를 조회할 수 있다.

    ![지도](https://user-images.githubusercontent.com/48701368/101861830-5b7cf500-3bb4-11eb-8cd8-cc9d88a044de.PNG)

    ```php

    if($dong == "선택") { # 법정동을 선택하지 않은 경우
            $query = "SELECT 아파트, CONCAT(ROUND(AVG(거래금액)/10000, 1), '억') AS 거래금액, CONCAT(법정동, ' ', 지번) AS 주소, ROUND(전용면적/3.305785) AS 평수, 전용면적, TRUNCATE(전용면적, 2) AS 면적, 법정동, 지번, '{$gu}' AS 지역구
            FROM {$gu}
            WHERE ROUND(전용면적) > ROUND({$minA}*3.305785) AND ROUND(전용면적) < ROUND({$maxA}*3.305785) 
                AND 년 = YEAR(sysdate()) 
                AND 월 IN (MONTH(sysdate()) - 3, MONTH(sysdate()) - 2, MONTH(sysdate()) - 1, MONTH(sysdate())) # 3개월치
                AND 거래금액 > {$minB}*10000 AND 거래금액 < {$maxB}*10000
            GROUP BY 아파트, 거래금액, 주소, 평수, 전용면적, 법정동, 지번
            ORDER BY ROUND(AVG(거래금액)/10000, 1) DESC
            "; 
        } else { # 법정동까지 선택한 경우
            $query = "SELECT 아파트, CONCAT(ROUND(AVG(거래금액)/10000, 1), '억') AS 거래금액, CONCAT(법정동, ' ', 지번) AS 주소, ROUND(전용면적/3.305785) AS 평수, 전용면적, TRUNCATE(전용면적, 2) AS 면적, 법정동, 지번, '{$gu}' AS 지역구
            FROM {$gu}
            WHERE ROUND(전용면적) > ROUND({$minA}*3.305785) AND ROUND(전용면적) < ROUND({$maxA}*3.305785) 
                AND 년 = YEAR(sysdate()) 
                AND 월 IN (MONTH(sysdate()) - 3, MONTH(sysdate()) - 2, MONTH(sysdate()) - 1, MONTH(sysdate())) # 3개월치
                AND 거래금액 > {$minB}*10000 AND 거래금액 < {$maxB}*10000
                AND 법정동 = '{$dong}'
            GROUP BY 아파트, 거래금액, 주소, 평수, 전용면적, 법정동, 지번
            ORDER BY ROUND(AVG(거래금액)/10000, 1) DESC
            "; 
        }
    ```

- 도면보기를 클릭한 경우

    해당 아파트 평수에 맞는 도면을 확인할 수 있다. 관련 코드는 아래와 같다. *images/지역구/법정동/아파트* 라는 경로에 해당 아파트의 전용면적.jpg 파일이 존재하면 도면보기 태그가 출력된다. 만약 도면이미지를 제공하지 않는 아파트의 경우나 무료호스팅에 따른 계정용량 제한으로 도면이 업로드 되지 못한 경우는 도면 보기 태그를 출력하지 않는다.

    ![도면](https://user-images.githubusercontent.com/48701368/101850306-df2ae780-3b9c-11eb-88e7-246bbfb37daf.PNG)
   
   ```php

    while($row = mysqli_fetch_array($result)){
            $list .= '<tr><td>';
            $list .= '<h3><p onclick="movePos(\''.$row['주소'].'\')" style="cursor:pointer;">'.$row['아파트'].'</p></h3>';
            $list .= '</td><td rowspan="3"><h2><a href="detail.php?name='.$row['아파트'].'&ac='.$row['전용면적'].'&gu='.$gu.'&지번='.$row['지번'].'&법정동='.$row['법정동'].'">'.$row['거래금액'].'</h2></td></tr>';
            $path = "images/{$gu}/{$row['법정동']}/{$row['아파트']}/{$row['면적']}.jpg";
            if(file_exists($path)){
                $list .= '<tr><td><a id="apt" href="images/'.$gu.'/'.$row['법정동'].'/'.$row['아파트'].'/'.$row['면적'].'.jpg">'.$row['면적'].'㎡ 도면보기</td></tr>';
            }else{
                $list .= '<tr></tr>';
            }
            $list .= '<tr><td style="font-size:15px;">'.$row['주소'].'</td></tr>'; 
        }
    ```

- 지도의 태그 또는 실거래가 평균을 클릭한 경우

    **[detail.php](https://github.com/yunyezl/DBP_t02/blob/main/detail.php)** 페이지로 이동한다. 앞서 search페이지에서 선택한 아파트의 법정동, 지번, 아파트명, 전용면적 값을 전달받아서 4가지의 요소를 출력한다.
    - 최근 3년간 실거래가 변동추이를 그래프 
    
        해당 아파트명과 전용면적에 해당하는 거래금액과 거래일을 가져와서 x축에는 거래년월, y축에는 거래금액이 출력된다.
    - 최근 3개월 기준 해당 법정동 아파트와 조회 아파트의 평균 평당 가격을 비교 분석한 결과

        앞서 언급했듯이 DB에는 전용면적이 저장되어있으므로 평균 평당 가격을 계산하기 위해서는 전용면적을 평수로 변환해야 한다. 따라서 전용면적을 3.3((1평은 약 3.305785㎡))을 나눈 다음 거래금액 평균을 평수로 나누어 평당 가격을 구한다. 쿼리는 지역별, 해당지역 내 아파트 쿼리 2개로 구분했다.
        
            "최근 3개월 기준 역삼동의 평균 평당 가격은 5680만원이며, 
            역삼래미안의 평당 가격은 9125만원입니다. 
            평균 평당 가격에 비해 3445만원 높은 가격입니다."
        
    - 최신순으로 거래의 상세 정보를 보여주는 리스트 
    - 자세한 위치 정보를 보여주는 지도
         
    ![그래프](https://user-images.githubusercontent.com/48701368/101864744-de537f00-3bb7-11eb-8261-353a12424943.PNG)

    ```php
    // 테이블 표기용 쿼리
    $query = "SELECT 거래금액 AS price, CONCAT(TRUNCATE(거래금액/10000, 1), '억') AS 거래금액, 
    CONCAT(년, '년 ', 월, '월') AS 거래년월, CONCAT(년, '년 ', 월, '월 ', 일, '일') AS 거래일자, 
    CONCAT(법정동, ' ', 지번) AS 주소, CONCAT(전용면적, '㎡') AS 전용면적, 층, CONCAT(TRUNCATE(전용면적,2), '㎡') AS 면적
    FROM {$_GET['gu']} 
    WHERE 아파트 = '{$name2}' AND 전용면적 = {$_GET['ac']} ORDER BY CAST(CONCAT(년,'-',월,'-',일) AS DATE) DESC";   

    //차트용 쿼리
    $chartQuery = "SELECT 거래금액, CONCAT(년, '년 ', 월, '월') AS 거래년월
    FROM {$_GET['gu']} 
    WHERE 아파트 = '{$name2}' AND 전용면적 = {$_GET['ac']}"; 

    // 지역별 평균 평당 가격을 구하는 쿼리
    $avgAcreageQuery = "SELECT ROUND(AVG(a.평당가)) as 평당
    FROM (SELECT 아파트, AVG(거래금액) AS 거래금액, TRUNCATE(거래금액/(전용면적/3.3), 0) AS 평당가
    FROM {$_GET['gu']} WHERE 월 IN (MONTH(sysdate()) - 3, MONTH(sysdate()) - 2, MONTH(sysdate()) - 1) AND 법정동 = '{$_GET['법정동']}' GROUP BY 아파트, 평당가) a";

    // 해당 아파트의 평당 가격을 구하는 쿼리
    $acreageQuery = "SELECT ROUND(AVG(a.평당가)) as 평당
    FROM (SELECT 아파트, AVG(거래금액) AS 거래금액, TRUNCATE(거래금액/(전용면적/3.3), 0) AS 평당가
    FROM {$_GET['gu']} WHERE 월 IN (MONTH(sysdate()) - 3, MONTH(sysdate()) - 2, MONTH(sysdate()) - 1) AND 아파트 = '{$name}' GROUP BY 아파트, 평당가) a";
    ```

#
## **역할 소개**

- 김혜민
    - search.php 지도 구현
    - detail.php 지도 인포윈도우 삽입

- 서채원 
    - gfp.py 도면이미지, 전용면적 크롤링 구현
    - 크롤링 이미지 ftp 서버에 업로드

- 윤예지
    - index.php 구현 
    - search.php 아파트 3개월 평균 실거래가 리스트(도면 제외), 지도 커스텀
    - detail.php 아파트 실거래 상세 테이블, 평당 가격 비교
    - 화면 구성, api -> csv파일화

- 전민지 
    - detail.php 지도 구현

- 최영진
    - DB 구축, csv -> sql 파일화
    - detail.php 아파트 실거래가 그래프 
    - index.php 지역 목록, search.php 도면이미지 화면 구성
    - README.md 작성

#
## **참고 문서**
[OpenAPI 제공 데이터 수집을 위한 파이썬 코드](https://ai-creator.tistory.com/24)

[csv파일을 sql파일로 변환](https://www.convertcsv.com/csv-to-sql.htm)

[전용면적을 평수로 변환](https://search.naver.com/search.naver?sm=tab_hty.top&where=nexearch&query=%EC%A0%84%EC%9A%A9%EB%A9%B4%EC%A0%81+%ED%8F%89%EC%88%98+%EB%B3%80%ED%99%98&oquery=%EC%A0%84%EC%9A%A9%EB%A9%B4%EC%A0%81%EC%9D%84+%ED%8F%89%EC%88%98%EB%A1%9C&tqi=U9hl6lprvN8ssh79NsKssssssTN-125522)

[파이썬 이미지 크롤링](https://geundung.dev/36)

[Chart.js CDN](https://www.chartjs.org/)

[Chart.js 사용법](https://yeahvely.tistory.com/6)

[KaKao 지도 Web API Documentation](https://apis.map.kakao.com/web/documentation/)
