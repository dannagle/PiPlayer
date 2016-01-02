<?php

require_once 'common.php';
require_once 'Parsedown.php';

if(!empty($_POST['command'])) {
    if($_POST['command'] == "next"
        || $_POST['command'] == "play"
        || $_POST['command'] == "stop"

    ) {
        tcpCommand($_POST['command']);
    }

    exit;
}



$markdown = "";

$links = ["download", "documentation"];

if(!empty($_GET['documentation'])) {

    $Parsedown = new Parsedown();
    $markdown =  $Parsedown->text(file_get_contents("PIREADME.md"));
}

if(!empty($_GET['download'])) {
    $Parsedown = new Parsedown();
    $markdown =  $Parsedown->text(file_get_contents("PIDOWNLOAD.md"));
}


if(!empty($markdown)) {
    $markdown = str_ireplace("<img ", "<img class='img-responsive' ", $markdown);
}


?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Dan Nagle">

    <?php

    $title = "Network Audio Player for the Raspberry Pi";
    foreach($links as $link) {
      if(!empty($_GET[$link])) {
        $title = ucwords($link);
      }
    }
    ?>


    <title>Pi Player - <?=$title;?></title>
    <meta name="description" content="Pi Player is an open source network-based audio player for the Raspberry Pi."/>

      <meta property="og:title" content="Pi Player - <?=$title;?>"/>
      <meta property="og:url" content="http://piplayer.naglecode.com/"/>
      <meta property="og:image" content="https://piplayer.naglecode.com/pilogoshare.png"/>
      <meta property="og:description" content="Pi Player is an open source network-based audio player for the Raspberry Pi."/>
      <meta name="twitter:card" content="summary" />
      <meta name="twitter:site" content="@naglecode" />
      <meta name="twitter:creator" content="@naglecode" />

	<!-- core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animate.min.css" rel="stylesheet">
    <link href="css/main.css?v=<?=filemtime("css/main.css");?>" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="js/html5shiv.js"></script>
    <script src="js/respond.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="pi.png">
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-57-precomposed.png">

<?php
if(!empty($markdown)) {
?>
<style>
.markdownarea h1, .markdownarea h2, .markdownarea h3 {
  color: black;
}
.markdownarea h3 {
    font-size: 120%;

}
.markdownarea {
    font-size: 120%;
    line-height: 1.5em;
}
.markdownarea li{
    line-height: 1.7em;

}
</style>
<?
}
?>



</head><!--/head-->

<body class="homepage">

    <header id="header">
        <div class="top-bar hidden-xs hidden-sm">
            <div class="container">
                <div class="row">
                    <div class="col-sm-6 col-xs-8">
                       <div class="social">
                            <ul class="social-share">
                                <li><a href="http://www.facebook.com/sharer/sharer.php?u=http://packetsender.com"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="https://plus.google.com/share?url=http://packetsender.com"><i class="fa fa-google-plus"></i></a></li>


                                <li><a href="https://twitter.com/share?url=http://packetsender.com"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="https://www.linkedin.com/cws/share?url=http://packetsender.com"><i class="fa fa-linkedin"></i></a></li>
                                <li><a href="https://github.com/dannagle/PacketSender"><i class="fa fa-github"></i></a></li>
                            </ul>
                            <div class="search">
                                <form role="form">
                                </form>
                           </div>
                       </div>
                    </div>
                </div>
            </div><!--/.container-->
        </div><!--/.top-bar-->

        <nav class="navbar navbar-inverse" role="banner">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="/"><img src="pi64.png" alt="Pi Player"> Pi Player</a>
                </div>

                <div class="collapse navbar-collapse navbar-right">
                    <ul class="nav navbar-nav">
                        <li <? if(empty($markdown)) echo ' class="active" '; ?> ><a href="/">Home</a></li>
<?php
foreach($links as $link) {
  $isactive = "";
  if(!empty($_GET[$link])) {
    $isactive = " active ";
  }
  echo "\n\t\t<li  class= '$isactive' ><a href='/$link'>".ucwords($link)."</a></li>";

}

 ?>
                    </ul>
                </div>
            </div><!--/.container-->
        </nav><!--/nav-->

    </header><!--/header-->


    <section id="feature" >
        <div class="container">
<?php
             if(!empty($markdown)) {
               echo '<div class="col-md-12 markdownarea">';
               echo $markdown;
               echo "</div></div></section>";
               goto FOOTER; //yeah, so what? It works.
             }
?>

            <div class="row">
                <div class="features">
                    <div class="col-md-4 col-sm-6 ">
                        <div id="playbutton" class="feature-wrap controlbutton">
                            <i  class="fa fa-play"></i>
                            <h2>&nbsp;<br>Play</h2>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 ">
                        <div id="stopbutton" class="feature-wrap controlbutton">
                            <i  class="fa fa-stop"></i>
                            <h2>&nbsp;<br>Stop</h2>
                        </div>
                    </div><!--/.col-md-4-->

                    <div class="col-md-4 col-sm-6 ">
                        <div id="nextbutton" class="feature-wrap controlbutton">
                            <i class="fa fa-step-forward"></i>
                            <h2>&nbsp;<br>Next</h2>
                        </div>
                    </div><!--/.col-md-4-->

                </div><!--/.services-->
            </div><!--/.row-->
        </div><!--/.container-->
    </section><!--/#feature-->



<?php

FOOTER:

 ?>

    <section id="bottom">
        <div class="container ">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <h3>Legal</h3>
                        <ul>
                            <li><a target="_blank" href="/LICENSE.txt">Read the License</a></li>
                        </ul>
                    </div>
                </div><!--/.col-md-3-->

                <div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <h3>Support</h3>
                        <ul>
                            <li><a href="/documentation">Documentation</a></li>
                            <li><a href="https://github.com/dannagle/PiPlayer/issues">File a Ticket</a></li>
                        </ul>
                    </div>
                </div><!--/.col-md-3-->

                <div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <h3>Funding</h3>
                        <ul>
                            <li><a href="https://packetsender.com/enhancements">Hire Me</a></li>
                        </ul>
                    </div>
                </div><!--/.col-md-3-->

                <div class="col-md-3 col-sm-6">
                    <div class="widget">
                        <h3>Guides</h3>
                        <ul>
                          <li><a href="https://en.wikibooks.org/wiki/Communication_Networks/TCP_and_UDP_Protocols">TCP vs UDP</a>
                          <li><a href="https://en.wikipedia.org/wiki/OSI_model">The OSI Model</a></li>
                          <li><a href="https://en.wikipedia.org/wiki/Packet_Sender">Wikipedia</a></li>
                        </ul>
                    </div>
                </div><!--/.col-md-3-->
            </div>
        </div>
    </section><!--/#bottom-->

    <footer id="footer" class="midnight-blue">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    Pi Player &copy; 2015-<?=date("Y");?> <a target="_blank" href="http://dannagle.com/" title="Dan Nagle">Dan Nagle</a>.
                </div>
                <div class="col-sm-6">
                    <ul class="pull-right">
                        <li><a href="/">Home</a></li>
                        <li><a href="http://dannagle.com/">About Me</a></li>
                        <li><a href="http://github.com/dannagle">My GitHub</a></li>
                        <li><a href="http://dannagle.com/contact">Contact</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer><!--/#footer-->

    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.isotope.min.js"></script>
    <script src="js/main.js?v=<?=filemtime("js/main.js");?>"></script>
    <script src="js/app.js"></script>
    <script src="js/wow.min.js"></script>

</body>
</html>
