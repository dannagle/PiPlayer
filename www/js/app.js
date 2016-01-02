

function sendCommand(cmd) {
    var sendObj = {};
    sendObj['command'] = cmd;

    $.post( "/", sendObj, function( data ) {
            console.log(data);
    });
}


jQuery(function($) {'use strict',


    $("#playbutton").click(function(){
        console.log("play");
        sendCommand("play");
    });

    $("#stopbutton").click(function(){
        console.log("stop");
        sendCommand("stop");
    });

    $("#nextbutton").click(function(){
        console.log("next");
        sendCommand("next");
    });

});
