<?php
session_start();
include 'class/login.php';
if (isset($_SESSION["token"]) && isset($_SESSION["ytrfvbnjjhgfgb"]) ) {
  $l = new LoginApi();
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta content="IE=edge" http-equiv="X-UA-Compatible">
        <meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
        <title>Oli :)</title>

        <!-- css -->
        <link href="css/base.min.css" rel="stylesheet">
        <link href="css/project.min.css" rel="stylesheet">
        <link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="css/bootstrap-theme.min.css" rel="stylesheet" media="screen">
        <link href="css/dropzone.css" rel="stylesheet" type="text/css">

        <!-- scripts -->
        <script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
        <script type="text/javascript" src="js/validation.min.js"></script>
        <!-- ... -->
    </head>
    <body class="page-brand">
    <header class="header header-brand ui-header">
        <span class="header-logo">Oli :)</span>
    </header>
    <?php
    if ($l->isFirstTime()) {
      ?>
      <script type="text/javascript">
          $(window).load(function(){
              $('#ui_dialog_example_alert').modal('show');
          });
      </script>
      <div aria-hidden="true" class="modal modal-va-middle fade" id="ui_dialog_example_alert" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-xs">
          <div class="modal-content">
            <div class="modal-heading"> <h3>Cambia tu contraseña</h3> </div>
            <div class="modal-inner">
              <p class="h5 margin-top-sm text-black-hint">Bienvenido, hemos detectado que no has cambiado
              tu contraseña desde la primera vez.<br> <strong>Te recomendamos que la cambies lo antes posible</strong></p>
            </div>
            <div class="modal-footer">
              <label>
                No volver a recordarme
                <input type="checkbox" name="remember" onClick="javascript:dontRemember()" >
              </label>
              <p class="text-right">
                <a class="btn btn-flat btn-brand-accent waves-attach" data-dismiss="modal">Recordarme más tarde</a>
                <a class="btn btn-flat btn-brand-accent waves-attach" type="submit" href="restore.php">Cambiar</a></p>
            </div>
          </div>
        </div>
      </div>
      <?php
      //$l->setSecondTime();
    }
    ?>
    <main class="content">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-lg-push-3 col-sm-6 col-sm-push-3">
                    <section class="content-inner">
                        <div class="card">
                            <div class="card-main">
                                <div class="card-header">
                                    <div class="card-inner">
                                        <h1 class="card-heading">Oli BB's de lulz :)</h1>
                                    </div>
                                </div>
                                <div class="card-inner">
                                    <div class="form-group">
                                        <a type="submit" href="make.php" id="btn-login" name="btn-login" class="btn btn-info waves-attach waves-light">
                                            Crear archivo cifrado
                                        </a>
                                    </div>
                                    <div class="form-group">
                                        <a type="submit" href="restore.php" id="btn-login" name="btn-login" class="btn  btn-info waves-attach waves-light">
                                            Cambiar contraseña
                                        </a>
                                    </div>
                                    <div class="form-group">
                                        <a type="submit" href="logout.php" id="btn-login" name="btn-login" class="btn  btn-danger waves-attach waves-light">
                                            Cerrar Sesión
                                        </a>
                                    </div>
                                    <div id="error">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </main>
    <footer class="ui-footer">
        <div class="container">
            <p>Creative Commons</p>
        </div>
    </footer>
    <!-- js -->
    <script src="assets/js/session.js"></script>
    <script src="js/base.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/project.min.js"></script>
    <script src="js/dropzone.js"></script>
    <script>
    function dontRemember() {
      $.ajax({
        type : 'POST',
        url  : 'class/dontRemember.php'
      });
      $('#ui_dialog_example_alert').modal('hide');
    }
    </script>
    </body>
    </html>
    <?php
} else {
    header("Location: index.php");
}
?>
