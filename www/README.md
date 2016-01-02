## Pi Player: The Raspberry Pi Network Audio Player

Pi Player is a network-based audio player system for the Raspberry Pi. It consists of 3 components. Each has its own README.

1. [Desktop app](desktop/) - Used for discovering the Raspberry Pi and as an example of basic network TCP and UDP commands.
1. [Web app](www/) - A responsive web UI to control the Raspberry Pi audio through phones and web browsers. This is an easier alternative than building a from-scratch mobile app.
1. [Pi service](pi/) - A TCP and UDP listener service that actually launches the audio files when requested.

## installation

Pi service is fundamentally required. Depending on how you want to control it, the web app or desktop could be considered optional. The most useful feature of the desktop app is probably just discovering the Pi. If you have a static IP, then that portion is not really needed.

## Static Live Demo

Site can be [viewed here](http://piplayer.naglecode.com/). Commands have been disabled.


## License
All components are GPL v2 or Later. The "www" portion is based heavily on the [Packet Sender web project](https://github.com/dannagle/PacketSender-Website) if you'd prefer an MIT license.


## Copyright

This project is wholly owned and copyright &copy;  -  [@NagleCode](http://twitter.com/NagleCode) - [DanNagle.com](http://DanNagle.com)  
