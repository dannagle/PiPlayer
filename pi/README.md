## Pi Player: Server Component

This creates 2 simple servers: UDP and TCP on port 15000 called "simpleTCPandUDPserver" . It listens for commands to start the via the desktop and/or web app.  

## Installation

The Pi Player runs on [Raspbian](https://www.raspbian.org/) and uses [mpg123](http://www.mpg123.de/) to play the audio.

```
sudo apt-get install qt5-default build-essential mpg123
qmake && make
sudo daemon ./simpleTCPandUDPserver
```

## Audio files

Upon launch, Pi Player server scans the home directory for \*.mp3 files. Those are the files that get played when commanded. 


## License
All components are GPL v2 or Later.


## Copyright

This project is wholly owned and copyright &copy;  -  [@NagleCode](http://twitter.com/NagleCode) - [DanNagle.com](http://DanNagle.com)  
