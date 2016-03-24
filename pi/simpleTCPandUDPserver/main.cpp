#include <QCoreApplication>
#include <QDebug>
#include <QTextStream>
#include <QTcpSocket>
#include <QUdpSocket>
#include <QTcpServer>
#include <QTimer>

#include "dualserver.h"

int main(int argc, char *argv[])
{
    QCoreApplication a(argc, argv);

    QDEBUG() << "Starting TCP and UDP service...";
    DualServer servers; // = new DualServer(&a);

    return a.exec();

}
