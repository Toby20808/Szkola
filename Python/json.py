import json

class Griegorij:
    def __init__(self, date, place, room, exam_type):
        self.date = date
        self.place = place
        self.room = room
        self.exam_type = exam_type

    def description(self):
        print(f"Egzamin typu '{self.exam_type}' odbędzie się {self.date} " f"w miejscowości {self.place} w sali {self.room}.")

with open("jacek.json", "r") as f:
    data = json.load(f)

exams = [Griegorij(**exam) for exam in data]

print("=== Lista Egzaminów ===")
for exam in exams:
    exam.description()
