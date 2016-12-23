<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
    <title>Recuperar Contraseña</title>

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
<body class="page-brand" onunload="javascript:logout()">
<header class="header header-brand ui-header">
    <span class="header-logo">Recuperar Contraseña</span>
</header>
<main class="content">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-lg-push-3 col-sm-6 col-sm-push-3">
                <section class="content-inner">
                    <div class="card">
                        <div class="card-main">
                            <div class="card-header">
                                <div class="card-inner">
                                    <h1 class="card-heading">Ingresa tu archivo cifrado</h1>
                                </div>
                            </div>
                            <div class="card-inner">
                                <p>
                                    Sube tu archivo cifrado para validar y recuperar tu cuenta.
                                </p>
                                <form action="class/recover.php" method="POST" class="dropzone needsclick"
                                      id="demo-upload">
                                    <p class="text-center">
                                    <div id="dropzone">
                                        <div class="dz-message needsclick" id="dZUpload">
                                            Arrastra y suelta tus archivos en esta sección para comenzar a
                                            cargarlos..<br/>
                                            <span class="note needsclick">(o da click y selecciona tu archivo)</span>
                                        </div>
                                    </div>
                                    </p>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-10 col-md-push-1">
                                                <!--<button type="submit" id="btn-upload" name="btn-upload" class="btn btn-block btn-default waves-attach waves-light"> &nbsp; Validar Archivo!</button>-->
                                            </div>
                                        </div>
                                    </div>
                                    <div id="error">
                                    </div>
                                </form>
                                <div id="error">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix">
                        <p class="margin-no-top pull-left"><a class="btn btn-flat btn-brand waves-attach"
                                                              href="index.php">Iniciar Sesión</a></p>
                        <p class="margin-no-top pull-right"><a class="btn btn-flat btn-brand waves-attach" href="manual.pdf">¿Necesitas ayuda?</a></p>
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
<script src="js/base.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/project.min.js"></script>
<script src="js/dropzone.js"></script>
<script type="text/javascript">
function logout() {
  alert('Saliendo');
}
</script>
<script type="text/javascript">
    $(document).ready(function () {
        Dropzone.autoDiscover = false;
        $("#dZUpload").dropzone({
            url: "class/recover.php",
            addRemoveLinks: true,
            paramName: "fileKey",
            success: function (file, response) {
                var imgName = response;
                file.previewElement.classList.add("dz-success");
                console.log("Successfully uploaded :" + imgName);
                if (response == "ok") {
                    $("#error").fadeIn(1000, function () {
                        $("#error").html('<div class="alert alert-success"> <img src="images/btn-ajax-loader.gif" /> &nbsp; Llave encontrada, iniciando restauracion</div>');
                    });
                    setTimeout(' window.location.href = "restore.php"; ', 4000);
                }
                else {
                    $("#error").fadeIn(1000, function () {
                        $("#error").html('<div class="alert alert-danger"> &nbsp; ' + response + '</div>');
                    });
                }
            },
            error: function (file, response) {
                file.previewElement.classList.add("dz-error");
            }
        });
    });
</script>
</body>
</html>
