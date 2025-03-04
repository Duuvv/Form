<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Iniciar sesión</title>
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-icons.css">
</head>
<body class="bg-light">

	<div class="container d-flex justify-content-center align-items-center vh-100">
		<div class="row w-100">
			<!-- Sección izquierda: Mensaje de bienvenida -->
			<div class="col-md-6 bg-primary text-white rounded-start p-5 d-flex flex-column justify-content-center align-items-center">
				<h3 class="mb-4">¡Hola, Bienvenido!</h3>
				<p class="mb-4">Inicia sesión para acceder a todas las funciones del sitio.</p>
				<a href="signup.php" class="btn btn-light">Regístrate aquí</a>
			</div>

			<!-- Sección derecha: Formulario de Login -->
			<div class="col-md-6 bg-white rounded-end p-5 shadow-lg">
				<form method="post" onsubmit="myaction.collect_data(event, 'login')">
					<h2 class="mb-4">Iniciar Sesión</h2>

					<!-- Error display -->
					<div><small class="my-1 js-error js-error-email text-danger"></small></div>

					<!-- Campo Email -->
					<div class="input-group mb-3">
					  <span class="input-group-text" id="basic-addon1"><i class="bi bi-envelope"></i></span>
					  <input name="email" type="text" class="form-control p-3" placeholder="Correo electrónico" required>
					</div>

					<!-- Campo Contraseña -->
					<div class="input-group mb-3">
					  <span class="input-group-text" id="basic-addon1"><i class="bi bi-key"></i></span>
					  <input name="password" type="password" class="form-control p-3" placeholder="Contraseña" required>
					</div>

					<!-- Enlace para olvidar contraseña -->
					<div class="mb-3">
					  <a href="#" class="text-primary">¿Olvidaste tu contraseña?</a>
					</div>

					<!-- Barra de progreso -->
					<div class="progress my-3 d-none">
					  <div class="progress-bar" role="progressbar" style="width: 50%;">Trabajando... 25%</div>
					</div>

					<!-- Botón de Login -->
					<button type="submit" class="btn btn-primary w-100 mb-3">Iniciar sesión</button>

					<!-- Redes Sociales -->
					<p class="text-center">O inicia sesión con plataformas sociales</p>
					<div class="d-flex justify-content-center">
					  <a href="#" class="btn btn-outline-dark mx-2"><i class="bi bi-google"></i></a>
					  <a href="#" class="btn btn-outline-dark mx-2"><i class="bi bi-facebook"></i></a>
					  <a href="#" class="btn btn-outline-dark mx-2"><i class="bi bi-github"></i></a>
					  <a href="#" class="btn btn-outline-dark mx-2"><i class="bi bi-linkedin"></i></a>
					</div>

					<!-- Enlace a la página de registro -->
					<p class="text-center mt-3">¿No tienes una cuenta? <a href="signup.php" class="text-primary">Regístrate aquí</a></p>
				</form>
			</div>
		</div>
	</div>

	<script>
		var myaction  = 
		{
			collect_data: function(e, data_type)
			{
				e.preventDefault();
				e.stopPropagation();

				var inputs = document.querySelectorAll("form input, form select");
				let myform = new FormData();
				myform.append('data_type',data_type);

				for (var i = 0; i < inputs.length; i++) {

					myform.append(inputs[i].name, inputs[i].value);
				}

				myaction.send_data(myform);
			},

			send_data: function (form)
			{

				var ajax = new XMLHttpRequest();

				document.querySelector(".progress").classList.remove("d-none");

				//resetear la barra de progreso
				document.querySelector(".progress-bar").style.width = "0%";
				document.querySelector(".progress-bar").innerHTML = "Trabajando... 0%";

				ajax.addEventListener('readystatechange', function(){

					if(ajax.readyState == 4)
					{
						if(ajax.status == 200)
						{
							// todo bien
							myaction.handle_result(ajax.responseText);
						}else{
							console.log(ajax);
							alert("Ocurrió un error");
						}
					}
				});

				ajax.upload.addEventListener('progress', function(e){

					let percent = Math.round((e.loaded / e.total) * 100);
					document.querySelector(".progress-bar").style.width = percent + "%";
					document.querySelector(".progress-bar").innerHTML = "Trabajando..." + percent + "%";
				});

				ajax.open('post','ajax.php', true);
				ajax.send(form);
			},

			handle_result: function (result)
			{
				console.log(result);
				var obj = JSON.parse(result);
				if(obj.success)
				{
					alert("¡Inicio de sesión exitoso!");
					window.location.href = 'index.php';
				}else{

					//mostrar errores
					let error_inputs = document.querySelectorAll(".js-error");

					//vaciar todos los errores
					for (var i = 0; i < error_inputs.length; i++) {
						error_inputs[i].innerHTML = "";
					}

					//mostrar errores
					for(key in obj.errors)
					{
						document.querySelector(".js-error-"+key).innerHTML = obj.errors[key];
					}
				}
			}
		};
	</script>

</body>
</html>
