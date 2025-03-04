<?php 

	require 'functions.php';

	if(!is_logged_in())
	{
		redirect('login.php');
	}

	$id = $_GET['id'] ?? $_SESSION['PROFILE']['id'];

	$row = db_query("select * from users where id = :id limit 1", ['id'=>$id]);

	if($row)
	{
		$row = $row[0];
	}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Editar Perfil</title>
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-icons.css">
</head>
<body>

	<?php if(!empty($row)):?>
	
		<div class="row col-lg-8 border rounded mx-auto mt-5 p-2 shadow-lg">
			<div class="col-md-4 text-center">
				<img src="<?=get_image($row['image'])?>" class="js-image img-fluid rounded" style="width: 180px;height:180px;object-fit: cover;">
				<div>
					<div class="mb-3">
					  <label for="formFile" class="form-label">Haga clic a continuación para seleccionar una imagen</label>
					  <input onchange="display_image(this.files[0])" class="js-image-input form-control" type="file" id="formFile">
					</div>
					<div><small class="js-error js-error-image text-danger"></small></div>
				</div>
			</div>
			<div class="col-md-8">
				
				<div class="h2">Editar Perfil</div>

				<form method="post" onsubmit="myaction.collect_data(event, 'profile-edit')">
					<table class="table table-striped">
						<tr><th colspan="2">Detalles del usuario:</th></tr>
						<tr><th><i class="bi bi-envelope"></i> Correo</th>
							<td>
								<input value="<?=$row['email']?>" type="text" class="form-control" name="email" placeholder="Correo electrónico">
								<div><small class="js-error js-error-email text-danger"></small></div>
							</td>
						</tr>
						<tr><th><i class="bi bi-telephone"></i> Teléfono</th>
							<td>
								<input value="<?=$row['phone']?>" type="text" class="form-control" name="phone" placeholder="Teléfono">
								<div><small class="js-error js-error-phone text-danger"></small></div>
							</td>
						</tr>
						<tr><th><i class="bi bi-person-circle"></i> Nombre</th>
							<td>
								<input value="<?=$row['firstname']?>" type="text" class="form-control" name="firstname" placeholder="Nombre">
								<div><small class="js-error js-error-firstname text-danger"></small></div>
							</td>
						</tr>
						<tr><th><i class="bi bi-person-square"></i> Apellido</th>
							<td>
								<input value="<?=$row['lastname']?>" type="text" class="form-control" name="lastname" placeholder="Apellido">
								<div><small class="js-error js-error-lastname text-danger"></small></div>
							</td>
						</tr>
						<tr><th><i class="bi bi-gender-ambiguous"></i> Género</th>
							<td>
								<select name="gender" class="form-select form-select mb-3" aria-label=".form-select-lg example">
								  <option value="">--Seleccione Género--</option>
								  <option selected value="<?=$row['gender']?>">
									<?php echo ($row['gender'] == 'Male' ? 'Masculino' : ($row['gender'] == 'Female' ? 'Femenino' : $row['gender'])); ?>
								  </option>
								  <option value="Male">Masculino</option>
								  <option value="Female">Femenino</option>
								</select>
								<div><small class="js-error js-error-gender text-danger"></small></div>
							</td>
						</tr>
						
						<tr><th><i class="bi bi-key"></i> Contraseña</th>
							<td>
								<input type="password" class="form-control" name="password" placeholder="Contraseña (dejar en blanco para conservar la contraseña actual)">
								<div><small class="js-error js-error-password text-danger"></small></div>
							</td>
						</tr>
						<tr><th><i class="bi bi-key-fill"></i> Confirmar Contraseña</th>
							<td>
								<input type="password" class="form-control" name="retype_password" placeholder="Confirmar Contraseña">
							</td>
						</tr>

					</table>

					<div class="progress my-3 d-none">
					  <div class="progress-bar" role="progressbar" style="width: 50%;">Trabajando... 25%</div>
					</div>

					<div class="p-2">
						
						<button class="btn btn-primary float-end">Guardar</button>
						
						<a href="index.php">
							<label class="btn btn-secondary">Volver</label>
						</a>

					</div>
				</form>

			</div>
		</div>

	<?php else:?>
		<div class="text-center alert alert-danger">Ese perfil no fue encontrado</div>
		<a href="index.php">
			<button class="btn btn-primary m-4">Inicio</button>
		</a>
	<?php endif;?>

</body>
</html>

<script>

	var image_added = false;

	function display_image(file)
	{
		var img = document.querySelector(".js-image");
		img.src = URL.createObjectURL(file);

		image_added = true;
	}
 
	var myaction  = 
	{
		collect_data: function(e, data_type)
		{
			e.preventDefault();
			e.stopPropagation();

			var inputs = document.querySelectorAll("form input, form select");
			let myform = new FormData();
			myform.append('data_type', data_type);

			for (var i = 0; i < inputs.length; i++) {
				myform.append(inputs[i].name, inputs[i].value);
			}

			if(image_added)
			{
				myform.append('image', document.querySelector('.js-image-input').files[0]);
			}

			myaction.send_data(myform);
		},

		send_data: function (form)
		{
			var ajax = new XMLHttpRequest();

			document.querySelector(".progress").classList.remove("d-none");

			// resetear la barra de progreso
			document.querySelector(".progress-bar").style.width = "0%";
			document.querySelector(".progress-bar").innerHTML = "Trabajando... 0%";

			ajax.addEventListener('readystatechange', function(){
				if(ajax.readyState == 4)
				{
					if(ajax.status == 200)
					{
						// Todo bien
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
				alert("Perfil editado con éxito");
				window.location.reload();
			}else{
				// mostrar errores
				let error_inputs = document.querySelectorAll(".js-error");

				// vaciar todos los errores
				for (var i = 0; i < error_inputs.length; i++) {
					error_inputs[i].innerHTML = "";
				}

				// mostrar errores
				for(key in obj.errors)
				{
					document.querySelector(".js-error-" + key).innerHTML = obj.errors[key];
				}
			}
		}
	};

</script>
