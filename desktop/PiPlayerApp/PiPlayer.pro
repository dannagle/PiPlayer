#-------------------------------------------------
#
# Project created by QtCreator 2014-12-31T21:28:58
#
#-------------------------------------------------

QT       += core gui
QT += network

greaterThan(QT_MAJOR_VERSION, 4): QT += widgets

TARGET = PiPlayer
TEMPLATE = app


SOURCES += main.cpp\
        mainwindow.cpp

HEADERS  += mainwindow.h

FORMS    += mainwindow.ui


macx:ICON = pi_icon.icns

win:ICON = pi_icon.png

win32:RC_FILE = piplayericon.rc



RESOURCES += \
    qdarkstyle/style.qrc \
    pires.qrc
