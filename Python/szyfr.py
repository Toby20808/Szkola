def szyfr_cezara(tekst, klucz):
    tekst = tekst.replace(" ", "")
    zaszyfrowany = ""
    for znak in tekst:
        if znak.isalpha():
            przesuniecie = klucz % 26
            if znak.islower():
                zaszyfrowany += chr((ord(znak) - ord('a') + przesuniecie) % 26 + ord('a'))
            elif znak.isupper():
                zaszyfrowany += chr((ord(znak) - ord('A') + przesuniecie) % 26 + ord('A'))
        else:
            zaszyfrowany += znak
    return zaszyfrowany

tekst = str("tobiasz")
klucz = 17
zaszyfrowany = szyfr_cezara(tekst, klucz)
print("Zaszyfrowany tekst:", zaszyfrowany)
