import requests
import datetime
import pandas as pd
import os

def get_data(gu_code, base_date):
    url = "http://openapi.molit.go.kr:8081/OpenAPI_ToolInstallPackage/service/rest/RTMSOBJSvc/getRTMSDataSvcAptTrade?" #일반
    service_key = "YdWjhoMNGiDs8SBhrIb%2FOT4HW2l5GhE9i4yx%2B8bF4dAOtyn7Lram801uVely4JAOZCUfrYvgWF24MRaOhYsouw%3D%3D"
    payload = "LAWD_CD=" + gu_code + "&" + \
              "DEAL_YMD=" + base_date + "&" + \
              "serviceKey=" + service_key + "&"

    print(url+payload)
    res = requests.get(url + payload)
    return res

import xml.etree.ElementTree as ET
def get_items(response):
    root = ET.fromstring(response.content)
    item_list = []
    for child in root.find('body').find('items'):
        elements = child.findall('*')
        data = {}
        for element in elements:
            tag = element.tag.strip()
            text = element.text.strip()
            # print tag, text
            data[tag] = text
        item_list.append(data)  
    return item_list
        
         
code_file = "법정동코드.txt"
code = pd.read_csv(code_file, sep='\t')
code.columns = ['code', 'name', 'is_exist']
code = code [code['is_exist'] == '존재']
print(code['code'][1])
print(type(code['code'][0])) 
code['code'] = code['code'].apply(str) 

year = [str("%02d" %(y)) for y in range(2018, 2021)]
month = [str("%02d" %(m)) for m in range(1, 13)]
base_date_list = ["%s%s" %(y, m) for y in year for m in month ]

gu_list = ['강북구']
for gu in gu_list:
    gu_code = code[ (code['name'].str.contains(gu))]
    gu_code = gu_code['code'].reset_index(drop=True)
    gu_code = str(gu_code[0])[0:5]
    print(gu_code)

    items_list = []
    for base_date in base_date_list:
        print(base_date)
        res = get_data(gu_code, base_date)
        items_list += get_items(res)

    items = pd.DataFrame(items_list) 
    items.head()
    items.to_csv(os.path.join("new/%s_%s~%s.csv" %(gu, year[0], year[-1])), index=False, encoding="euc-kr")
