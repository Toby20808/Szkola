import random
class Team:
    def __init__(self, a, b):
        self.points = a
        self.nazwa = b


class LigaPodworkowa:
    def __init__(self, a):
        self.teams = a
    def TopTeam(self, n):
        for i in range(n):
            print("Top " + str(i+1) + ": " + self.teams[i].nazwa)

Team1 = Team(42, "Pogoń Książ")
Team2 = Team(6.7, "Spartanie Jerka")
Team3 = Team(41, "Amicus Mórka")
Team4 = Team(4.1, "Warta Śrem")
Team5 = Team(36, "Kostnica Kurnik")

teams = [Team1, Team2, Team3, Team4, Team5] 
liga = LigaPodworkowa(teams)
liga.TopTeam(5)