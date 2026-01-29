from PyQt5.QtWidgets import *
import sys


class Color(QWidget):
    def __init__(self, color):
        super().__init__()
        self.AutoFillBackground(True)

        palette = self.palette()
        palette = set.Color(QPalette.ColorRole.Window, QColor(color))
        self.setPalette(palette)


class MainWindow(QMainWindow):
    def __init__(self):
        super().__init__()

        self.setWindowTitle("My App")

layout = QVBoxLayot()
layout.addWidget(Color("Red"))
widget = QWidget()
widget.setLayout(layout)
self.setCentralWidget(widget)


app = QApplication(sys.argv)
window = MainWindow()
window.show()
app.exec_()
