<?php
session_start();
include 'class/login.php';

if (isset($_SESSION["token"]) && isset($_SESSION["ytrfvbnjjhgfgb"]) ) {
	header("Location: panel.php");
}

$l = new LoginApi();
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta content="IE=edge" http-equiv="X-UA-Compatible">
	<meta content="initial-scale=1.0, maximum-scale=1.0, user-scalable=no, width=device-width" name="viewport">
	<title>Modelos y pruebas de software</title>

	<!-- css -->
	<link href="css/base.min.css" rel="stylesheet">
	<link href="css/project.min.css" rel="stylesheet">
	<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link href="css/bootstrap-theme.min.css" rel="stylesheet" media="screen">

	<!-- scripts -->
	<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
	<script type="text/javascript" src="js/validation.min.js"></script>
	<script src='https://www.google.com/recaptcha/api.js'></script>
	<!-- ... -->
</head>
<body class="page-brand">
	<header class="header header-brand ui-header">
		<span class="header-logo">Modelos y pruebas de software</span>
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
										<h1 class="card-heading">Iniciar Sesión</h1>
									</div>
								</div>
								<div class="card-inner">
									<p class="text-center">
										<span class="avatar avatar-inline avatar-lg">
											<img alt="Login" src="images/users/avatar-001.jpg">
										</span>
									</p>
									<form class="form" id="login-form" method="POST">
										<div class="form-group form-group-label">
											<div class="row">
												<div class="col-md-10 col-md-push-1">
													<label class="floating-label" for="ui_login_username">Usuario</label>
													<input class="form-control" id="ui_login_username" name="ui_login_username" type="text" required="required" placeholder="Usuario">
												</div>
											</div>
										</div>
										<div class="form-group form-group-label">
											<div class="row">
												<div class="col-md-10 col-md-push-1">
													<label class="floating-label" for="ui_login_password">Contraseña</label>
													<input class="form-control" id="ui_login_password" name="ui_login_password" type="password" required="required" placeholder="Contraseña">
												</div>
											</div>
										</div>
										<?php
										if ($l->showCaptcha()) {
										?>
										<div class="form-group form-group-label">
											<div class="row">
												<div class="col-md-10 col-md-push-1">
													<div class="g-recaptcha" id="html_element" data-sitekey="6LeJJgoUAAAAABylX1ykCGCc6xKYlKwxfz2UP-Of"></div>
												</div>
											</div>
										</div>
										<?php
										}
										?>
										<div class="form-group">
											<div class="row">
												<div class="col-md-10 col-md-push-1">
													<button type="submit" id="btn-login" name="btn-login" class="btn btn-block btn-default waves-attach waves-light"> &nbsp; Iniciar Sesión</button>
												</div>
											</div>
										</div>
										<div id="error">
										</div>
									</form>
								</div>
							</div>
						</div>
						<div class="clearfix">
							<p class="margin-no-top pull-left"><a class="btn btn-flat btn-brand waves-attach" href="recover.php">¿No puedes acceder?</a></p>
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
	<script type="text/javascript">
		$('document').ready(function() {
			/* validation */
			$("#login-form").validate({
				rules: {
					ui_login_password: {
						required: true,
					},
					ui_login_username: {
						required: true
					},
				},
				messages:
				{
					ui_login_password:{
						required: "Introduce tu contraseña"
					},
					ui_login_username: "Introduce tu nombre de Usuario",
				},
				submitHandler: submitForm
			});

			function submitForm() {
				var data = $("#login-form").serialize();
				$.ajax({
					type : 'POST',
					url  : 'class/login.php',
					data : data,
					beforeSend: function() {
						$("#error").fadeOut();
						$("#btn-login").html('&nbsp; Conectando...');
					},
					success :  function(response) {
						if (response == "ok") {
							$("#error").fadeIn(1000, function() {
								$("#error").html('<div class="alert alert-success"> <img src="images/btn-ajax-loader.gif" /> &nbsp; Bienvenid@</div>');
								$("#btn-login").html('&nbsp; Entrando...');
							});
							setTimeout(' window.location.href = "panel.php"; ',4000);
						}
						else {
							$("#error").fadeIn(1000, function() {
								$("#error").html('<div class="alert alert-danger"> &nbsp; '+response+'</div>');
								$("#btn-login").html('&nbsp; Iniciar Sesión');
							});
							setTimeout(' window.location.reload(); ', 3000);
						}
					}
				});
				return false;
			}
		});
	</script>
</body>
</html>
