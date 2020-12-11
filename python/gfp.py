#gfp == ground floor plan(평면도)

from bs4 import BeautifulSoup
from selenium import webdriver
from time import sleep
import urllib.request


def getImage():
    driver = webdriver.Chrome('chromedriver.exe')
    driver.implicitly_wait(1)

    # #사용자에게 받은 키워드 값 서버로부터 받음
    # search = input("검색할 아파트 이름 입력: ")
    # url = "https://search.naver.com/search.naver?sm=tab_hty.top&where=nexearch&query="
    # newUrl = url+parse.quote_plus(search)
    url = "https://search.naver.com/search.naver?sm=tab_hty.top&where=nexearch&query=역삼+대우디오빌&oquery=대우디오빌"

    driver.get(url)
    driver.implicitly_wait(2)
    driver.find_element_by_xpath(
        """/html/body/div[3]/div[2]/div/div[1]/section[1]/div[1]/div[2]/div/div[4]/div/div[2]/div/a""").click()
    driver.implicitly_wait(2)

    # 크롬창에서 새롭게 열린 탭으로 url 지정
    last_tab = driver.window_handles[-1]
    driver.switch_to.window(window_name=last_tab)
    page = driver.page_source  # 웹 페이지 긁어옴
    soup = BeautifulSoup(page, "html.parser")  # 페이지를 soup 객체로 만듦


    # 아파트의 전용면적 개수 몇개인지 count 변수로 알아옴
    count = 0
    location = soup.select('#view_type1 > div._js_grnd_plan_view.ly_add.ly_add_unfd > div.ly_cont > div > div.floor_info._js_grnd_plan_tag_view')
    for tag in location:
        all_a = tag.select('div a')
    for a in all_a:
        count += 1
    print(count)

    driver.find_element_by_xpath(
        """/html/body/div/div[2]/div/div/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[1]/a[2]""").click()

    result = {}
    #  전용면적 em, 이미지 src 받아와 result 딕셔너리에 담음
    for i in range(0, count):
        driver.find_element_by_xpath(
            """/html/body/div/div[2]/div/div/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[1]/a[""" + str(
                (i + 1)) + """]""").click()
        page = driver.page_source
        soup = BeautifulSoup(page, "html.parser")
        location = soup.select("#imageDIV")
        for imgs in location:
            img = imgs.select('div img[src]')
        for srctag in img:
            src = srctag.attrs['src']
        emtag = soup.select_one(
            '#content > div > div.map_section > div.map_snb > div.bx_plan._js_grdplan_info_view.title_center > p > em:nth-child(3)')
        em = emtag.text  # 태그 값이 제외된 텍스트 값만 추출해 em변수에 넣음
        result[em] = src  # result 딕셔너리에 넣음

        urllib.request.urlretrieve(src, em+'.jpg')

    print(result)


    sleep(10) # 크롬 창 자동 꺼짐 10초간 멈춤

    # # 창 닫기
    # driver.close()
    # first_tab = driver.window_handles[0]
    # driver.switch_to.window(window_name=first_tab)

getImage()





