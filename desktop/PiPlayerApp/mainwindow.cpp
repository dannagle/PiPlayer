/*
 * This file is part of Pi Player
 *
 * Licensed GPL v2
 * http://github.com/dannagle/PiPlayer
 *
 * Copyright Dan Nagle
 *
 */

#include <QDesktopServices>

#include "mainwindow.h"
#include "ui_mainwindow.h"

MainWindow::MainWindow(QWidget *parent) :
    QMainWindow(parent),
    ui(new Ui::MainWindow)
{
    ui->setupUi(this);
    ui->piIPLabel->hide();
    ip.clear();

    ui->menuBar->hide();
    ui->mainToolBar->hide();

    setWindowTitle("Pi Player");

    setWindowIcon(QIcon("://pi_icon.png"));

    udpSocket = new QUdpSocket(this);
    udpSocket->bind(QHostAddress::Any, 25000);


    connect(udpSocket, SIGNAL(readyRead()),
            this, SLOT(readPendingDatagrams()));

    ui->playButton->setEnabled(false);
    ui->nextButton->setEnabled(false);
    ui->stopButton->setEnabled(false);

    talkPort = 15000;

    twitterButton = new QPushButton("@NagleCode");

    twitterButton->setStyleSheet("QPushButton { color: white; } QPushButton::hover { color: #BC810C; } ");
    twitterButton->setFlat(true);
    twitterButton->setCursor(Qt::PointingHandCursor);
    connect(twitterButton, SIGNAL(clicked()),
            this, SLOT(gotoNagleCode()));

    statusBar()->insertPermanentWidget(0, twitterButton);


//    udpSocket->writeDatagram(QString("Find Units").toLatin1(),QHostAddress::Broadcast,15000);


}

void MainWindow::gotoNagleCode()
{
    //Open URL in browser
    QDesktopServices::openUrl(QUrl("https://twitter.com/NagleCode"));


}


QByteArray MainWindow::tcpSend(QString ip, int port, QString tcpSend)
{
    QTcpSocket * socket = new QTcpSocket();
    QByteArray returnData;

    socket->connectToHost(ip, port);
    socket->waitForConnected(5000);


    if(socket->state() == QAbstractSocket::ConnectedState)
    {
        socket->write(tcpSend.toLatin1());
        socket->waitForBytesWritten();
        socket->waitForReadyRead();
        returnData = socket->readAll();
    }

    socket->disconnectFromHost();
    socket->deleteLater();

    return returnData;

}


QString removeIPv6Mapping(QHostAddress ipv6)
{
    quint32 ipv4 = ipv6.toIPv4Address();

    //valid address will have a result greater than 0
    if(ipv4 > 0) {
        QHostAddress new_ipv4(ipv4);
        return new_ipv4.toString();
    } else {
        return ipv6.toString();
    }

}


void MainWindow::readPendingDatagrams()
{
    //QDEBUG() << " got a datagram";
    while (udpSocket->hasPendingDatagrams()) {
        QByteArray datagram;
        datagram.resize(udpSocket->pendingDatagramSize());
        QHostAddress sender;
        quint16 senderPort;
        udpSocket->readDatagram(datagram.data(), datagram.size(),
                                &sender, &senderPort);

        QDEBUG() << "UDP: " << QDateTime::currentDateTime().toString(DATETIMEFORMAT) << " "<<datagram.size() <<" bytes from " << sender.toString() <<":" << senderPort;

        ip = removeIPv6Mapping(sender);
        ui->piIPLabel->setText("Connected!");
        ui->piIPLabel->setStyleSheet("QLabel { color: #6dff18; }");

        ui->playButton->setEnabled(true);
        ui->nextButton->setEnabled(true);
        ui->stopButton->setEnabled(true);
        ui->findPiButton->setEnabled(false);
        ui->piIPLabel->show();
        ui->piIPLabel->setText(ip);

        statusBar()->showMessage("Found Raspberry Pi!", 5000);





    }
}


MainWindow::~MainWindow()
{
    delete ui;
}

void MainWindow::on_findPiButton_clicked()
{
    udpSocket->writeDatagram(QString("Find Units").toLatin1(),QHostAddress::Broadcast,talkPort);

}

void MainWindow::on_playButton_clicked()
{
    QDEBUG() << tcpSend(ip, talkPort, "play");
    statusBar()->showMessage("Play 1st song", 5000);

}

void MainWindow::on_nextButton_clicked()
{
    QDEBUG() << tcpSend(ip, talkPort, "next");
    statusBar()->showMessage("Play 2nd song", 5000);

}

void MainWindow::on_stopButton_clicked()
{
    QDEBUG() << tcpSend(ip, talkPort, "stop");
    statusBar()->showMessage("Stop playback", 5000);

}
