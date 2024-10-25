import sys
import re
import os
import requests
import matplotlib.pyplot as plt
from math import log10

header = {
    "Accept": "text/html, */*; q=0.01",
    "Accept-Encoding": "gzip, deflate, br, zstd",
    "Accept-Language": "ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7,de;q=0.6",
    "Content-Length": "47",
    "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
    "Cookie": "_ga=GA1.1.1228581035.1710429464; PHPSESSID=85f0e110c71f30ab564fba78d2245b9b; theme_mode=dark; _ga_4S406GDFPW=GS1.1.1710429463.1.1.1710430259.0.0.0",
    "Dnt": "1",
    "Origin": "https://ibox.tools",
    "Referer": "https://ibox.tools/keyword-forms",
    "Sec-Ch-Ua": '"Not_A Brand";v="8", "Chromium";v="120", "Google Chrome";v="120"',
    "Sec-Ch-Ua-Mobile": "?0",
    'Sec-Ch-Ua-Platform': '"Windows"',
    "Sec-Fetch-Dest": "empty",
    "Sec-Fetch-Mode": "cors",
    "Sec-Fetch-Site": "same-origin",
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
    "X-Requested-With": "XMLHttpRequest"
}

blacklist2 = ['для', 'и', 'мы', 'он', 'вы', 'ты', 'она', 'или', 'о', 'я', 'кто-то', 'что-то', 'что-либо', 'кое-что', 'любой', 'иной', 'какой-то', 'меня', 'себя', 'мой', 'твой', 'ваш', 'свой', 'наш',
             'сколько', 'какой', 'никто', 'ничто', 'некого', 'нечего', 'никакой', 'нечей', 'сам', 'каждый', 'целый', 'всякий', 'весь', 'другой', 'этот', 'тот', 'хорошо', 'открытый', 'в', 'тоже',
             'также', 'а', 'но', 'это', 'зато', 'али', 'кабы', 'то', 'есть', 'с', 'от', 'до', 'вау', 'к', 'ах', 'ух', 'увы', 'ого', 'фу', 'ало', 'эй', 'ау', 'на', 'который']

def process_definition_file(file_path):
    with open(file_path, 'r', encoding='utf8') as f:
        text = f.readlines()

    text = list(map(lambda a: re.sub(r'[-—–―]|понимается|представляет собой|определяется как|называют|обычно толкуется как', '-', a).split('-'), text))

    spisok = list(set(re.sub(r'[.,!?()«»]', '', ' '.join([i[0] for i in text]).lower()).split()))

    text2 = list(map(lambda a: ' '.join(re.sub(r'[.,!?()«»]', '', a[-1].lower()).split()), text))

    slovar = dict()

    for i in text2:
        split = i.split()
        for j in i.split():
            if j in slovar:
                slovar[j] += split.count(j)
                continue
            slovar[j] = split.count(j)

    slovar = [[i[0], [i[1], False]]
              for i in sorted(slovar.items(), key=lambda a: a[1], reverse=True)]
    slovar = dict(slovar)

    keys = list(slovar.keys())

    for i in blacklist2:
        a = requests.post('https://ibox.tools/services/keyforms/handler.php',
                          data={'word': i}, headers=header)
        sclonenia = re.sub(r'(\<(/?[^>]+)>)', ' ', a.text).split()

        for j in keys:
            if j in sclonenia:
                try:
                    slovar.pop(j)
                except:
                    pass

    for i in spisok:
        a = requests.post('https://ibox.tools/services/keyforms/handler.php',
                          data={'word': i}, headers=header)
        sclonenia = re.sub(r'(\<(/?[^>]+)>)', ' ', a.text).split()
        for j in sclonenia:
            try:
                slovar.pop(j)
            except:
                pass

    clearslovar = dict()

    keys = list(slovar.keys())
    # Проверка на склонение по падежам
    for i in slovar.keys():
        if slovar[i][1]:
            continue

        a = requests.post('https://ibox.tools/services/keyforms/handler.php',
                          data={'word': i}, headers=header)
        sclonenia = re.sub(r'(\<(/?[^>]+)>)', ' ', a.text).split()

        if ' '.join(sclonenia) == "Склонения не найдены!" or len(sclonenia) < 2:
            slovar[i][1] = True
            continue

        clearslovar[sclonenia[0]] = slovar[i][0]
        slovar[i][1] = True

        for j in range(keys.index(i), len(keys)):
            if slovar[keys[j]][1]:
                continue

            if keys[j] in sclonenia:
                clearslovar[sclonenia[0]] += slovar[keys[j]][0]
                slovar[keys[j]][1] = True

    # Проверка по cинонимам

    slovar = clearslovar

    slovar = [[i[0], [i[1], False]]
              for i in sorted(slovar.items(), key=lambda a: a[1], reverse=True)]
    slovar = dict(slovar)

    clearslovar = dict()

    keys = list(slovar.keys())

    for i in slovar.keys():
        if slovar[i][1]:
            continue

        a = requests.post('https://ibox.tools/services/synonyms/handler.php',
                          data={'syn': i}, headers=header)
        syn = re.sub(r'(\<(/?[^>]+)>)', ' ', a.text).split()

        if ' '.join(syn) == "Синонимы не найдены!":
            slovar[i][1] = True
            continue

        clearslovar[i] = slovar[i][0]
        slovar[i][1] = True

        for j in range(keys.index(i), len(keys)):
            if slovar[keys[j]][1]:
                continue

            if keys[j] in syn:
                clearslovar[i] += slovar[keys[j]][0]
                slovar[keys[j]][1] = True

    clearslovar = dict(
        sorted(clearslovar.items(), key=lambda a: a[1], reverse=True))

    name = file_path.split('/')[-1].split('.')[0]

    # Создание графика
    y = [log10(i[1]) for i in clearslovar.items() if i[1] != 1]
    x = [log10(i) for i in range(1, len(y) + 1)]

    plt.figure()
    plt.plot(x, y, marker="o")

    # Убираем числовые значения на осях
    plt.gca().axes.xaxis.set_ticks([])
    plt.gca().axes.yaxis.set_ticks([])

    # Убираем метки осей
    plt.gca().axes.xaxis.set_ticklabels([])
    plt.gca().axes.yaxis.set_ticklabels([])

    # Убираем названия осей
    plt.xlabel('')
    plt.ylabel('')

    # Устанавливаем размер полей для обрезки числовых значений
    plt.subplots_adjust(left=0, right=1, top=1, bottom=0)

    plt.title(f"Термин {name}", pad=20)
    plt.savefig(f'../thesaurus/{name}.png', bbox_inches='tight', pad_inches=0)

    with open(f'../thesaurus/{name} повторения.txt', 'w') as f:
        f.writelines(
            [f'{i[0]} - {i[1]}\n' for i in clearslovar.items() if i[1] != 1])

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: /usr/bin/python3.10 semantic_thesaurus.py <thesaurus_dir> <term>")
        sys.exit(1)

    thesaurus_dir = sys.argv[1]
    term = sys.argv[2]
    file_path = os.path.join(thesaurus_dir, f'{term}.txt')

    if os.path.exists(file_path):
        process_definition_file(file_path)
    else:
        print(f"File for term {term} does not exist")

