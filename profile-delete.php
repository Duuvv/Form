<?php 

	require 'functions.php';

	if(!is_logged_in())
	{
		redirect('login.php');
	}

	$id = $_GET['id'] ?? $_SESSION['PROFILE']['id'];

	$row = db_query("select * from users where id = :id limit 1",['id'=>$id]);

	if($row)
	{
		$row = $row[0];
	}

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Eliminar Perfil</title>
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-icons.css">
</head>
<body>

	<?php if(!empty($row)):?>
	
		<div class="row col-lg-8 border rounded mx-auto mt-5 p-2 shadow-lg">
			<div class="col-md-4 text-center">
				<img src="<?=get_image($row['image'])?>" class="js-image img-fluid rounded" style="width: 180px;height:180px;object-fit: cover;">
			</div>
			<div class="col-md-8">
				
				<div class="h2">Eliminar Perfil</div>

				<div class="alert-danger alert text-center my-2">¿Estás seguro de que deseas eliminar este perfil?</div>

				<form method="post" onsubmit="myaction.collect_data(event, 'profile-delete')">
					<table class="table table-striped">
						<tr><th colspan="2">Detalles del Usuario:</th></tr>
						<tr><th><i class="bi bi-envelope"></i> Correo Electrónico</th>
							<td>
								<div class="form-control" ><?=$row['email']?></div>
								<div><small class="js-error js-error-email text-danger"></small></div>
							</td>
						</tr>
						<tr><th><i class="bi bi-person-circle"></i> Nombre</th>
							<td>
								<div class="form-control" name="firstname" ><?=$row['firstname']?></div>
								<div><small class="js-error js-error-firstname text-danger"></small></div>
							</td>
						</tr>
						<tr><th><i class="bi bi-person-square"></i> Apellido</th>
							<td>
								<div class="form-control" ><?=$row['lastname']?></div>
								<div><small class="js-error js-error-lastname text-danger"></small></div>
							</td>
						</tr>
 
					</table>

					<div class="progress my-3 d-none">
					  <div class="progress-bar" role="progressbar" style="width: 50%;" >Trabajando... 25%</div>
					</div>

					<div class="p-2">
						
						<button class="btn btn-danger float-end">Eliminar</button>
						
						<a href="index.php">
							<label class="btn btn-secondary">Volver</label>
						</a>

					</div>
				</form>

			</div>
		</div>

	<?php else:?>
		<div class="text-center alert alert-danger">Este perfil no fue encontrado</div>
		<a href="index.php">
			<button class="btn btn-primary m-4">Inicio</button>
		</a>
	<?php endif;?>

</body>
</html>

<script>

	var myaction  = 
	{
		collect_data: function(e, data_type)
		{
			e.preventDefault();
			e.stopPropagation();

 			let myform = new FormData();
			myform.append('data_type',data_type);
			myform.append('id',<?=$row['id'] ?? 0 ?>);

 
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
				alert("¡Perfil eliminado con éxito!");
				window.location.href = 'logout.php';
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
