def ocena_koncowa_wazona():
    oceny = []
    wagi = []

    for i in range(3):
        ocena = float(input(f"Podaj ocenę {i+1}: "))
        waga = float(input(f"Podaj wagę oceny {i+1}: "))
        oceny.append(ocena)
        wagi.append(waga)

    suma_wazona = 0
    suma_wag = 0

    for i in range(3):
        suma_wazona += oceny[i] * wagi[i]
        suma_wag += wagi[i]

    srednia = suma_wazona / suma_wag

    print(f"\nŚrednia ważona: {round(srednia, 2)}")

ocena_koncowa_wazona()
