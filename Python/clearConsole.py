import random
import time
import os

class Szymon:
    def __init__(self, symbol, szer, wys):
        self.symbol = symbol
        self.szer = szer
        self.wys = wys
        self.x = random.randint(1, self.szer)
        self.y = random.randint(1, self.wys)

    def get(self):
        return Przedmiot(self.x, self.y, self.symbol)

class Przedmiot:
    def __init__(self, x, y, znak):
        self.x = x
        self.y = y
        self.znak = znak

def genObj():
    return Przedmiot(random.randint(0,9), random.randint(0,9), ">")

def genObj1():
    return Przedmiot(6, 7, "Ψ")

def showMap():
    for i in range(10):
        print(mapTab[i])

def Obj1():
    return Przedmiot(4, 3, "𝇚")

def generateObject():
    return Przedmiot(8, 4, "ᾂ")

def obdzekt1():
    return Przedmiot(random.randint(0,9), random.randint(0,9), "(⌐■_■)")

def campagnola():
    return Przedmiot(random.randint(0,9), random.randint(0,9), "‰")

def obj():
    return Przedmiot(6, 7, "c")

def clearConsole():
    os.system("cls")

mapTab = [[" " for _ in range(10)] for _ in range(10)]

figures = [
    Przedmiot(5,5,"F"),
    genObj(),
    genObj1(),
    Obj1(),
    Szymon("S",8,8).get(),
    generateObject(),
    obdzekt1(),
    campagnola(),
    obj()
]

win_x = random.randint(0,9)
win_y = random.randint(0,9)
mapTab[win_y][win_x] = "X"

def checkForWin():
    for f in figures:
        if f.x == win_x and f.y == win_y:
            return f.znak
    return None

while True:
    clearConsole()
    mapTab = [[" " for _ in range(10)] for _ in range(10)]
    mapTab[win_y][win_x] = "X"

    for f in figures:
        los = random.randint(0,3)
        nx, ny = f.x, f.y

        if los == 0: ny += 1
        elif los == 1: nx += 1
        elif los == 2: ny -= 1
        elif los == 3: nx -= 1

        if 0 <= nx < 10 and 0 <= ny < 10:
            f.x, f.y = nx, ny

        mapTab[f.y][f.x] = f.znak

    winner = checkForWin()
    if winner:
        print(f"{winner} wygrał grę!")
        break

    showMap()
    time.sleep(1)
