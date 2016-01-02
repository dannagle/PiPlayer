<?php

date_default_timezone_set("America/Chicago");

$user_agent = getBrowserOS();
global $port, $address;
$port = 15000;
$address = '127.0.0.1';;

function print_r_html($array) {
    echo "<pre>";
    print_r($array);
    echo "</pre>";
}

function isListening() {
    global $port, $address;
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
        exit;
    }
    $result = socket_connect($socket, $address, $port);
    if($result) {
        socket_close($socket);
    }
    return $result;

}


function tcpCommand($cmd) {
    global $port, $address;
    $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    if ($socket === false) {
        echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
        exit;
    }
    $result = socket_connect($socket, $address, $port);
    if($result) {
        echo "socket_connect() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
    }
    socket_write($socket, $cmd,strlen($cmd));
    usleep(5);
    while ($out = socket_read($socket, 2048)) {
        echo $out;
    }
    socket_close($socket);

}


function getBrowserOS() {

    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    $browser = "Unknown Browser";
    $os_platform = "Unknown OS Platform";

    // Get the Operating System Platform

    if (preg_match('/windows|win32/i', $user_agent)) {

        $os_platform = 'Windows';

        if (preg_match('/windows nt 6.2/i', $user_agent)) {

            $os_platform .= " 8";
        } else if (preg_match('/windows nt 6.1/i', $user_agent)) {

            $os_platform .= " 7";
        } else if (preg_match('/windows nt 6.0/i', $user_agent)) {

            $os_platform .= " Vista";
        } else if (preg_match('/windows nt 5.2/i', $user_agent)) {

            $os_platform .= " Server 2003/XP x64";
        } else if (preg_match('/windows nt 5.1/i', $user_agent) || preg_match('/windows xp/i', $user_agent)) {

            $os_platform .= " XP";
        } else if (preg_match('/windows nt 5.0/i', $user_agent)) {

            $os_platform .= " 2000";
        } else if (preg_match('/windows me/i', $user_agent)) {

            $os_platform .= " ME";
        } else if (preg_match('/win98/i', $user_agent)) {

            $os_platform .= " 98";
        } else if (preg_match('/win95/i', $user_agent)) {

            $os_platform .= " 95";
        } else if (preg_match('/win16/i', $user_agent)) {

            $os_platform .= " 3.11";
        }
    } else if (preg_match('/macintosh|mac os x/i', $user_agent)) {

        $os_platform = 'Mac';

        if (preg_match('/macintosh/i', $user_agent)) {

            $os_platform .= " OS X";
        } else if (preg_match('/mac_powerpc/i', $user_agent)) {

            $os_platform .= " OS 9";
        }
    } else if (preg_match('/linux/i', $user_agent)) {

        $os_platform = "Linux";
    }

    // Override if matched

    if (preg_match('/iphone/i', $user_agent)) {

        $os_platform = "iPhone";
    } else if (preg_match('/android/i', $user_agent)) {

        $os_platform = "Android";
    } else if (preg_match('/blackberry/i', $user_agent)) {

        $os_platform = "BlackBerry";
    } else if (preg_match('/webos/i', $user_agent)) {

        $os_platform = "Mobile";
    } else if (preg_match('/ipod/i', $user_agent)) {

        $os_platform = "iPod";
    } else if (preg_match('/ipad/i', $user_agent)) {

        $os_platform = "iPad";
    }

    // Get the Browser

    if (preg_match('/msie/i', $user_agent) && !preg_match('/opera/i', $user_agent)) {

        $browser = "Internet Explorer";
    } else if (preg_match('/firefox/i', $user_agent)) {

        $browser = "Firefox";
    } else if (preg_match('/chrome/i', $user_agent)) {

        $browser = "Chrome";
    } else if (preg_match('/safari/i', $user_agent)) {

        $browser = "Safari";
    } else if (preg_match('/opera/i', $user_agent)) {

        $browser = "Opera";
    } else if (preg_match('/netscape/i', $user_agent)) {

        $browser = "Netscape";
    }

    $ismobile = false;
    if ($os_platform == "iPhone" || $os_platform == "Android" || $os_platform == "BlackBerry" || $os_platform == "Mobile" || $os_platform == "iPod" || $os_platform == "iPad") {

        if (preg_match('/mobile/i', $user_agent)) {

            $browser = "Mobile";
            $ismobile = true;
        }
    }

    return array(
        'browser' => $browser,
        'os' => $os_platform,
        'mobile' => $ismobile
    );
}


?>
