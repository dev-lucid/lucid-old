<?php include(__DIR__.'/../config/environment.php'); ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="" />
    <meta name="author" content="" />
    <link rel="icon" href="favicon.ico" />

    <title>Off Canvas Template for Bootstrap</title>

    <!-- Bootstrap core CSS -->
    <?php if (__STAGE__ == 'production'){ ?>
    <link href="media/less/production.css" rel="stylesheet" />
    <?php } else { ?>
    <link href="media/less/compile.php" rel="stylesheet" />
    <?php } ?>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body onload="lucid.init();">
    <nav class="navbar navbar-fixed-top navbar-inverse">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="btn btn-default navbar-toggle collapsed" data-toggle="offcanvas" onclick="offcanvasToggle();"><span class="glyphicon glyphicon-cog"></span></button>


          <a class="navbar-brand visible hidden-xs" href="#!view/index">Project name</a>
          <div class="btn-group btn-breadcrumb navbar-brand hidden visible-xs" style="margin-top: 0px !important; padding:15px !important;" id="path">
              <a href="#" class="btn btn-default"><i class="glyphicon glyphicon-home"></i></a>
              <a href="#" class="btn btn-default">Snippets</a>
              <a href="#" class="btn btn-default">Breadcrumbs</a>
              <a href="#" class="btn btn-default">Default</a>
          </div>
        </div>
        <div id="nav1a" class="collapse navbar-collapse"></div>
      </div>
    </nav>

    <div class="container">
      <div class="row row-offcanvas row-offcanvas-right">

        <div class="col-xs-12 col-sm-9" id="body"></div>

        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar">
            <div id="nav1b"></div>
            <div class="list-group" class="nav2">
                <a href="#" class="list-group-item active">Link</a>
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="list-group-item">Link</a>
                <a href="#" class="list-group-item">Link</a>
            </div>

        </div><!--/.sidebar-offcanvas-->
      </div><!--/row-->
      <hr>

      <footer id="footer">
        <p>&copy; Company 2014</p>
      </footer>

    </div><!--/.container-->


    <?php if (__STAGE__ == 'production'){ ?>
    <script src="media/js/production.js"></script>
    <?php } else { ?>
    <script src="media/js/compile.php"></script>
    <?php } ?>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script>
    (function() {
      if (navigator.userAgent.match(/IEMobile\/10\.0/)) {
        var msViewportStyle = document.createElement("style");
        msViewportStyle.appendChild(
          document.createTextNode("@-ms-viewport{width:auto!important}")
        );
        document.getElementsByTagName("head")[0].appendChild(msViewportStyle);
      }
    })();

    $(document).ready(function () {
      $('[data-toggle="offcanvas"]').click(function () {
        $('.row-offcanvas').toggleClass('active');
      });
    });

    function offcanvasToggle(){
      $('[data-toggle="offcanvas"]').click(function () {$('#sidebar').toggleClass('active');});
    }
    </script>
  </body>
</html>

