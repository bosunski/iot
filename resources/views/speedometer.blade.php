<!DOCTYPE html>
<html>
    <!-- Check out the source code on http://github.com/vjt/canvas-speedometer -->
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
        <link href="/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- Custom fonts for this template -->
        <link rel="stylesheet" href="/vendor/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="/vendor/simple-line-icons/css/simple-line-icons.css">
        <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">

        <!-- Plugin CSS -->
        <link rel="stylesheet" href="/device-mockups/device-mockups.min.css">

        <!-- Custom styles for this template -->
        <link href="/css/new-age.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/css/speedometer.css" media="screen" />

        <script type="text/javascript" src="/js/xcanvas.js"></script>
        <script type="text/javascript" src="/js/tbe.js"></script>

        <script type="text/javascript" src="/js/digitaldisplay.js"></script>
        <script type="text/javascript" src="/js/speedometer.js"></script>
        <script type="text/javascript" src="/js/themes/default.js"></script>

        <script type="text/javascript" src="/js/example.js"></script>
        <script type="text/javascript" src="/js/controls.js"></script>
        <script type="text/javascript">
          var volts, amperes;

          // Explicit onready listener for modern browsers. If you use a JS framework,
          // you should replace this code with your toolkit's "onReady" helpers (e.g.
          // $(document).ready() on jQuery, Event.observe('dom:loaded') on prototype,
          // and so on.
          document.addEventListener ("DOMContentLoaded", function() {
            document.removeEventListener ("DOMContentLoaded", arguments.callee, false);

            // Check out configuration and API on GitHub Wiki:
            // http://wiki.github.com/vjt/canvas-speedometer
            volts = new Speedometer ('volts', {theme: 'default', min:0, max:50, value:25});
            volts.draw ();

            // To set New value of Speedometer i.e update it
            volts.animatedUpdate({{ $speedometer->value }}, 300);

            // Starts the control immediately animation ends
            //volts.controls = new Controls ('volts-controls', {speedometer: volts});
            //volts.addEventListener ('speedometer:animateend', function () {
              //volts.controls.start ();
            //});
            //volts.controls.start ();

            /*amperes = new Speedometer ('amperes', {theme: 'default'});
            amperes.draw ()
            amperes.controls = new Controls ('amperes-controls', {speedometer: amperes});
            amperes.addEventListener ('speedometer:animateend', function () {
              amperes.controls.start ();
            });
            amperes.controls.start ();*/

          }, false);
        </script>
    </head>
    <body id="page-top">

        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNav">
          <div class="container">
            <!--<a class="navbar-brand js-scroll-trigger" href="#page-top">Start Hub Tech</a>-->
            <img src="img/start-logo.png" class="img-fluid" alt="">
            <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
              Menu
              <i class="fa fa-bars"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
              <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                  <a class="nav-link js-scroll-trigger" href="#download">About</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link js-scroll-trigger" href="#features">Projects</a>
                </li>
                <li class="nav-item">
                  <a class="nav-link js-scroll-trigger" href="#contact">Contact Us</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>

        <div class="demo">
            <div id="volts" class="speedometer"></div>
            <!--<form id="volts-controls" class="controls">
                Animate update:  <input type="button" name="update" value="start" /><br/>
                Animate rescale: <input type="text" name="maxvalue" value="200" />
                <input type="button" name="rescale" value="rescale" /><br />
                Operating mode:
                <label>
                    <input type="radio" name="mode" value="incremental" checked="checked" /> incremental
                </label>
                <label>
                    <input type="radio" name="mode" value="random" /> random
                </label>
            </form>-->
        </div>


        <footer>
          <div class="container">
            <p>&copy; Hardware 2018. All Rights Reserved.</p>
            <ul class="list-inline">
              <li class="list-inline-item">
                <a href="#">Privacy</a>
              </li>
              <li class="list-inline-item">
                <a href="#">Terms</a>
              </li>
              <li class="list-inline-item">
                <a href="#">FAQ</a>
              </li>
            </ul>
          </div>
        </footer>


        <!--<div class="demo">
            <div id="amperes" class="speedometer"></div>
            <form id="amperes-controls" class="controls">
                Animate update:  <input type="button" name="update" value="start" /><br/>
                Animate rescale: <input type="text" name="maxvalue" value="200" />
                <input type="button" name="rescale" value="rescale" /><br />
                Operating mode:
                <label>
                    <input type="radio" name="mode" value="incremental"  /> incremental
                </label>
                <label>
                    <input type="radio" name="mode" value="random" checked="checked"/> random
                </label>
            </form>
        </div>-->

        <!-- Bootstrap core JavaScript -->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Plugin JavaScript -->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Custom scripts for this template -->
        <script src="js/new-age.min.js"></script>
        <script type="text/javascript">
            $(function() {
                window.setInterval(function() {
                    $.get('/sensor/value', function(response) {
                        volts.animatedUpdate(response.value, 300);
                    });
                }, 1000)
            });

        </script>
    </body>
</html>
