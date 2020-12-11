# gfp == ground floor plan(평면도)
from bs4 import BeautifulSoup
from selenium import webdriver
from time import sleep
import urllib.request
import os
import sys
import pandas as pd

def getImage(dong, apartment):
    # 크롬 드라이버가 백그라운드로 크롤링하기 위한 옵션
    options = webdriver.ChromeOptions()
    options.add_argument('headless')
    options.add_argument('window-size=1920x1080')
    options.add_argument("disable-gpu")

    driver = webdriver.Chrome('chromedriver', chrome_options=options)
    driver.implicitly_wait(1)

    # #사용자에게 받은 키워드 값 서버로부터 받음
    url = "https://search.naver.com/search.naver?sm=tab_hty.top&where=nexearch&query="+ dong + apartment

    driver.get(url)
    driver.implicitly_wait(2)
    alert = "도면 없음!"
    try:
        if driver.find_element_by_xpath(
        """/html/body/div[3]/div[2]/div/div[1]/section[1]/div[1]/div[2]/div/div[4]/div/div[2]/div/a""").click():
            print('found')
    except:
        driver.quit()
        return alert
    driver.implicitly_wait(2)

    # 크롬창에서 새롭게 열린 탭으로 url 지정
    last_tab = driver.window_handles[-1]
    driver.switch_to.window(window_name=last_tab)
    page = driver.page_source  # 웹 페이지 긁어옴
    soup = BeautifulSoup(page, "html.parser")  # 페이지를 soup 객체로 만듦

    # 아파트의 전용면적 개수 몇개인지 count 변수로 알아옴
    count = 0
    location = soup.select(
        '#view_type1 > div._js_grnd_plan_view.ly_add.ly_add_unfd > div.ly_cont > div > div.floor_info._js_grnd_plan_tag_view')
    for tag in location:
        all_a = tag.select('div a')
    try:
        for a in all_a:
            count += 1
    except:
        driver.quit()
        return alert
    print(count)

    try:
        if driver.find_element_by_xpath("""/html/body/div/div[2]/div/div/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[1]/a[2]""").click():
            print('found')
    except:
        driver.quit()
        return alert


    result = {}
    path = saveImage(dong, apartment)
    #  전용면적 em, 이미지 src 받아와 result 딕셔너리에 담음
    for i in range(0, count):
        try:
            if driver.find_element_by_xpath(
                    """/html/body/div/div[2]/div/div/div[2]/div[2]/div[1]/div[2]/div[2]/div/div[1]/a[""" + str((i + 1)) + """]""").click():
                print('found')
        except:
            driver.quit()
            return alert
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
        tmp = str(em)
        area = tmp[:-1]  # 단위 ㎡ 제거
        print(area)
        result[em] = src  # result 딕셔너리에 넣음

        save = str(path) + '/' + str(round(float(area), 2)) + '.jpg'
        urllib.request.urlretrieve(src, save)

    print(result)
    driver.quit()


def saveImage(dong, apartment):
    gu = '강남구'


    base_dir = 'C:\domyun'
    os.chdir(base_dir)

    path = os.path.join(base_dir, gu)
    if not os.path.isdir(path):
        os.mkdir(path)  # 사용자에게 입력받은 구 정보를 폴더이름으로 만듦
    next1_dir = os.path.join(path, dong)
    if not os.path.isdir(next1_dir):
        os.mkdir(next1_dir)  # 구 이름 하위폴더에 새 폴더 동이름으로 만듦
    next2_dir = os.path.join(next1_dir, apartment)
    if not os.path.isdir(next2_dir):
        os.mkdir(next2_dir)  # 동 폴더 하위에 아파트이름 폴더 만듦
    os.chdir(next2_dir)
    print(os.getcwd())
    return os.getcwd()


gu = '강남구'
code_file = "강남구.csv"
code = pd.read_csv(code_file, encoding='euc-kr', sep="\t")
code.columns = ['address']
aptList = []

for i in range(len(code)):
    address = code['address'][i].split(',')
    dong = address[0]
    apartment = address[1]
    base_dir = 'C:\domyun'

    print(os.path.join(base_dir, gu, dong, apartment))
    if os.path.isdir(os.path.join(base_dir, gu, dong, apartment)): # 로컬에 해당 구+동+아파트이름 파일이 있으면 건너뜀
        aptList.append(gu+dong+apartment) # 도면 못담은 아파트를 aptList에 담음
        continue
    getImage(dong, apartment)

with open('aptList.txt', 'w') as f:
    f.writelines((aptList))
