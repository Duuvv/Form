<?php 
	require 'functions.php';

	if(!is_logged_in())
	{
		redirect('login.php');
	}

	$id = $_GET['id'] ?? $_SESSION['PROFILE']['id'];

	$row = db_query("select * from users where id = :id limit 1", ['id' => $id]);

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
	<title>Panel de Control</title>
	<link rel="stylesheet" type="text/css" href="./css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="./css/bootstrap-icons.css">
</head>
<body>

	<!-- Sidebar -->
	<div class="sidebar bg-dark text-white" style="width: 250px; height: 100vh; position: fixed; top: 0; left: 0; padding-top: 20px;">
		<h3 class="px-3">Panel de Control</h3>
		<ul class="nav flex-column">
			<li class="nav-item">
				<a class="nav-link text-white" href="users.php">Todos los Usuarios</a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-white" href="index.php?id=<?=$_SESSION['PROFILE']['id']?>">Perfil</a>
			</li>
			<li class="nav-item">
				<a class="nav-link text-white" href="logout.php">Cerrar sesión</a>
			</li>
		</ul>
	</div>

	<!-- Main Content -->
	<div class="main-content" style="margin-left: 250px; padding: 20px;">
		<div class="container-fluid">
			<h1>Hola, Administrador</h1>
			<div class="row">
				<div class="col-md-3">
					<div class="card bg-primary text-white">
						<div class="card-body">
							<h5 class="card-title">Usuarios Totales</h5>
							<p class="card-text">4</p>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card bg-success text-white">
						<div class="card-body">
							<h5 class="card-title">Usuarios Activos</h5>
							<p class="card-text">4</p>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card bg-warning text-white">
						<div class="card-body">
							<h5 class="card-title">Solicitudes Pendientes</h5>
							<p class="card-text">5</p>
						</div>
					</div>
				</div>
				<div class="col-md-3">
					<div class="card bg-danger text-white">
						<div class="card-body">
							<h5 class="card-title">Problemas</h5>
							<p class="card-text">0</p>
						</div>
					</div>
				</div>
			</div>

			<?php if(!empty($row)):?>
				<div class="card mt-4">
					<div class="card-header">
						<h2>Perfil de Usuario</h2>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-4 text-center">
								<img src="<?=get_image($row['image'])?>" class="img-fluid rounded" style="width: 180px;height:180px;object-fit: cover;">
								<div class="mt-3">
									<?php if(user('id') == $row['id']):?>
										<a href="profile-edit.php">
											<button class="btn btn-primary btn-sm">Editar</button>
										</a>
										<a href="profile-delete.php">
											<button class="btn btn-warning btn-sm text-white">Eliminar</button>
										</a>
										<a href="logout.php">
											<button class="btn btn-info btn-sm text-white">Cerrar sesión</button>
										</a>
									<?php endif;?>
								</div>
							</div>
							<div class="col-md-8">
								<table class="table table-striped">
									<tr><th colspan="2">Detalles del Usuario:</th></tr>
									<tr><th><i class="bi bi-envelope"></i> Correo</th><td><?=esc($row['email'])?></td></tr>
									<tr><th><i class="bi bi-person-circle"></i> Nombre</th><td><?=esc($row['firstname'])?></td></tr>
									<tr><th><i class="bi bi-person-square"></i> Apellido</th><td><?=esc($row['lastname'])?></td></tr>
									<tr><th><i class="bi bi-gender-ambiguous"></i> Género</th><td><?=esc($row['gender'])?></td></tr>
								</table>
							</div>
						</div>
					</div>
				</div>
			<?php else:?>
				<div class="alert alert-danger text-center">No se encontró ese perfil</div>
				<a href="index.php">
					<button class="btn btn-primary m-4">Inicio</button>
				</a>
			<?php endif;?>
		</div>
	</div>

	<!-- Scripts de Bootstrap -->
	<script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>
