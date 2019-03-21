/*
 * This file is part of Pi Player
 *
 * Licensed GPL v2
 * http://github.com/dannagle/PiPlayer
 *
 * Copyright Dan Nagle
 *
 */


#ifndef MAINWINDOW_H
#define MAINWINDOW_H

#include <QMainWindow>

#include <QObject>
#include <QUdpSocket>
#include <QTcpSocket>
#include <QTcpServer>
#include <QStringList>
#include <QTime>
#include <QThread>
#include <QNetworkAccessManager>
#include <QNetworkReply>
#include <QFile>
#include <QDebug>
#include <QDateTime>
#include <QProcess>
#include <QPushButton>

#define QDEBUG() qDebug() << __FILE__ << "/" <<__LINE__  <<"(" << __FUNCTION__ << "):"
#define QDEBUGVAR(var)  QDEBUG() << # var <<  var;

#define DATETIMEFORMAT "h:mm:ss.zzz ap"


namespace Ui {
class MainWindow;
}

class MainWindow : public QMainWindow
{
    Q_OBJECT

public:
    explicit MainWindow(QWidget *parent = 0);
    ~MainWindow();
    QString ip;

    static QByteArray tcpSend(QString ip, int port, QString tcpSend);

public slots:
    void readPendingDatagrams();


    void gotoNagleCode();
private slots:
    void on_findPiButton_clicked();

    void on_playButton_clicked();

    void on_nextButton_clicked();

    void on_stopButton_clicked();

private:
    Ui::MainWindow *ui;
    QUdpSocket * udpSocket;
    int talkPort;
    QPushButton * twitterButton;


};

#endif // MAINWINDOW_H
