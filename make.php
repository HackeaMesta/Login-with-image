<?php
session_start();
if (isset($_SESSION["token"]) && isset($_SESSION["ytrfvbnjjhgfgb"]) ) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
	<title>Crear archivo cifrado</title>

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
		<span class="header-logo">Crear archivo cifrado</span>
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
										<h1 class="card-heading">Sube una imagen/foto</h1>
									</div>
								</div>
								<div class="card-inner">
									<p>
										Sube una foto o imagen para crear tu archivo cifrado. En caso de olvidar su contraseña puede usar este archivo
									</p>
									<div class="tile-wrap">
										<div class="tile tile-collapse tile-red">
											<div data-target="#ui_tile_example_red" data-toggle="tile">
												<div class="tile-inner">
													<div class="text-overflow">¡Aviso importante!</div>
												</div>
											</div>
											<div class="tile-active-show collapse" id="ui_tile_example_red">
												<div class="tile-sub">
													<p>¡Atención! si crea este nuevo archivo, todos los archivos anteriores dejaran de funcionar.</p>
												</div>
												<div class="tile-footer">
													<div class="tile-footer-btn pull-left">
														<a class="btn btn-flat waves-attach" data-toggle="tile" href="#ui_tile_example_red"><span class="icon">check</span>&nbsp;Enterado</a>
													</div>
												</div>
											</div>
										</div>
									</div>
									<form action="class/recover.php" method="POST" class="dropzone needsclick" id="demo-upload">
										<p class="text-center">
											<div id="dropzone">
												<div class="dz-message needsclick" id="dZUpload">
													Arrastra y suelta tus archivos en esta sección para comenzar a cargarlos..<br />
													<span class="note needsclick">(o da click y selecciona tu archivo)</span>
												</div>
											</div>
										</p>
										<div class="form-group">
											<div class="row">
												<div class="col-md-10 col-md-push-1">
												</div>
											</div>
										</div>
									</form>
									<div id="error">
									</div>
								</div>
							</div>
						</div>
						<div class="clearfix">
							<p class="margin-no-top pull-left"><a class="btn btn-flat btn-brand waves-attach" href="panel.php">Volver al panel</a></p>
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
	<script src="assets/js/session.js"></script>
	<script src="js/base.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/project.min.js"></script>
	<script src="js/dropzone.js"></script>
	<script type="text/javascript">
		$(document).ready(function () {
    Dropzone.autoDiscover = false;
    $("#dZUpload").dropzone({
        url: "class/make.php",
        addRemoveLinks: true,
				paramName: "fileKey",
        success: function (file, response) {
			var imgName = response;
			file.previewElement.classList.add("dz-success");
			if (response == "ok") {
				$("#error").fadeIn(1000, function () {
					$("#error").html('<div class="alert alert-success"> <img src="images/btn-ajax-loader.gif" /> &nbsp; Llave creada. Descargando archivo.</div>');
				});
				setTimeout(' window.location.href = "recover.php"; ', 4000);
			}
			else if (response.indexOf('Descargar archivo') !== -1) {
				$("#error").fadeIn(1000, function () {
					$("#error").html('<div class="alert alert-success"> <img src="images/btn-ajax-loader.gif" /> &nbsp;  ' + response + '</div>');
				});
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
<?php
} else {
	header("Location: index.php");
}
?>
