import random

class Player:
    def __init__(self, HP, ATK, DEF):
        self.HP = HP
        self.ATK = ATK
        self.DEF = DEF

    def attack(self, enemy):
        damage = max(0, self.ATK - enemy.DEF)
        enemy.HP -= damage
        print(f"Atak! Zadajesz {damage} obrażeń!")

    def heal(self, amount):
        chance = random.randint(1, 10)
        if chance == 1:
            self.HP += amount
            print("Leczenie się powiodło! Odzyskałeś HP.")
        else:
            print("Nie udało się uleczyć.")

def play():
    print("Stwórz swojego bohatera!")
    hp = int(input("Podaj zdrowie swojego bohatera: "))
    atk = int(input("Podaj atak swojego bohatera: "))
    defense = int(input("Podaj obronę swojego bohatera: "))

    print("\n==============================================\n")

    print("Stwórz swojego przeciwnika!")
    enemy_hp = int(input("Podaj zdrowie przeciwnika: "))
    enemy_atk = int(input("Podaj atak przeciwnika: "))
    enemy_def = int(input("Podaj obronę przeciwnika: "))

    player = Player(hp, atk, defense)
    enemy = Player(enemy_hp, enemy_atk, enemy_def)

    print(f"\nStatystyki gracza: HP: {player.HP}, ATK: {player.ATK}, DEF: {player.DEF}")
    print(f"Statystyki przeciwnika: HP: {enemy.HP}, ATK: {enemy.ATK}, DEF: {enemy.DEF}")

    turn = 1

    print("\n=== Rozpoczyna się walka! ===")

    while player.HP > 0 and enemy.HP > 0:
        print(f"\n--- RUNDA {turn} ---")
        print(f"Twoje HP: {player.HP} | HP przeciwnika: {enemy.HP}")

        print("Wybierz akcję:")
        print("1. Atak")
        print("2. Leczenie")
        choice = input("Twój wybór: ")

        if choice == "1":
            player.attack(enemy)
        elif choice == "2":
            player.heal(10)
        else:
            print("Nieprawidłowy wybór! Tracisz turę.")

        if enemy.HP <= 0:
            print("\nWygrałeś walkę!")
            break

        print("\nPrzeciwnik atakuje!")
        enemy.attack(player)

        if player.HP <= 0:
            print("\nZostałeś pokonany!")
            break

        turn += 1

play()
