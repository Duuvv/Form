<?php 
require 'functions.php';

if (!is_logged_in()) {
    redirect('login.php');
}

$rows = db_query("SELECT * FROM users");

// Obtener el ID del usuario actual
$current_user_id = $_SESSION['PROFILE']['id'];

/**
 * Función para generar archivo de descarga para un solo usuario.
 * Se incluye el campo "phone" en el JSON y en el ticket.
 */
function generate_download_file($user_data, $format = 'json') {
    $filename = "user_profile_" . $user_data['id'] . "." . $format;
    $filepath = "downloads/" . $filename;

    // Crear la carpeta "downloads" si no existe
    if (!file_exists('downloads')) {
        mkdir('downloads', 0777, true);
    }

    // Generar el archivo en el formato especificado
    if ($format == 'json') {
        // json_encode incluirá todos los campos, incluyendo "phone"
        file_put_contents($filepath, json_encode($user_data, JSON_PRETTY_PRINT));
    } elseif ($format == 'ticket') {
        // Generar un ticket en formato de texto con el campo "phone"
        $ticket_content  = "================================\n";
        $ticket_content .= "   TICKET DE PERFIL DE USUARIO    \n";
        $ticket_content .= "================================\n";
        $ticket_content .= "ID: " . $user_data['id'] . "\n";
        $ticket_content .= "Nombre: " . $user_data['firstname'] . " " . $user_data['lastname'] . "\n";
        $ticket_content .= "Correo: " . $user_data['email'] . "\n";
        $ticket_content .= "Teléfono: " . $user_data['phone'] . "\n";
        $ticket_content .= "Género: " . $user_data['gender'] . "\n";
        $ticket_content .= "Imagen: " . $user_data['image'] . "\n";
        $ticket_content .= "================================\n";
        file_put_contents($filepath, $ticket_content);
    }

    return $filename;
}

/**
 * Función para generar archivo de descarga con todos los usuarios.
 * Se incluye el campo "phone" para cada usuario.
 */
function generate_download_file_all($users, $format = 'json') {
    $filename = "users_all." . $format;
    $filepath = "downloads/" . $filename;

    // Crear la carpeta "downloads" si no existe
    if (!file_exists('downloads')) {
        mkdir('downloads', 0777, true);
    }

    if ($format == 'json') {
        file_put_contents($filepath, json_encode($users, JSON_PRETTY_PRINT));
    } elseif ($format == 'ticket') {
        $ticket_content  = "================================\n";
        $ticket_content .= "   TICKET DE TODOS LOS USUARIOS   \n";
        $ticket_content .= "================================\n";
        foreach ($users as $user_data) {
            $ticket_content .= "ID: " . $user_data['id'] . "\n";
            $ticket_content .= "Nombre: " . $user_data['firstname'] . " " . $user_data['lastname'] . "\n";
            $ticket_content .= "Correo: " . $user_data['email'] . "\n";
            $ticket_content .= "Teléfono: " . $user_data['phone'] . "\n";
            $ticket_content .= "Género: " . $user_data['gender'] . "\n";
            $ticket_content .= "Imagen: " . $user_data['image'] . "\n";
            $ticket_content .= "--------------------------------\n";
        }
        $ticket_content .= "================================\n";
        file_put_contents($filepath, $ticket_content);
    }

    return $filename;
}

// Manejar la descarga de datos
if (isset($_GET['download']) && isset($_GET['id']) && isset($_GET['format'])) {
    $format = $_GET['format'];
    
    // Si se solicita "all" en el parámetro id, se descargan todos los usuarios
    if ($_GET['id'] == 'all') {
        $users = db_query("SELECT * FROM users");
        if (!empty($users)) {
            $filename = generate_download_file_all($users, $format);
        }
    } else {
        // Descarga de un único usuario
        $user_id = $_GET['id'];
        $user = db_query("SELECT * FROM users WHERE id = :id LIMIT 1", ['id' => $user_id]);
        if (!empty($user)) {
            $user = $user[0];
            $filename = generate_download_file($user, $format);
        }
    }

    if (isset($filename)) {
        // Forzar la descarga del archivo
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($filename) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize("downloads/" . $filename));
        readfile("downloads/" . $filename);
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perfiles de Usuario</title>
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
                <a class="nav-link text-white" href="index.php?id=<?= esc($current_user_id) ?>">Perfil</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="logout.php">Cerrar sesión</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" style="margin-left: 250px; padding: 20px;">
        <div class="container-fluid">
            <h1>Perfiles de Usuario</h1>
            <div class="row">
                <?php if (!empty($rows)): ?>
                    <?php foreach ($rows as $row): ?>
                        <div class="col-md-3 mb-4">
                            <div class="card shadow-sm">
                                <a href="index.php?id=<?= esc($row['id']) ?>" class="text-decoration-none text-dark">
                                    <img src="<?= get_image($row['image']) ?>" class="card-img-top" style="width: 100%; height: 200px; object-fit: cover;">
                                    <div class="card-body text-center">
                                        <h5 class="card-title"><?= esc($row['firstname']) ?> <?= esc($row['lastname']) ?></h5>
                                        <p class="card-text text-muted"><?= esc($row['email']) ?></p>
                                    </div>
                                </a>
                                <div class="card-footer text-center">
                                    <!-- Botones de descarga para cada perfil -->
                                    <a href="users.php?download=true&id=<?= esc($row['id']) ?>&format=ticket" class="btn btn-sm btn-warning me-2">Descargar Ticket</a>
                                    <a href="users.php?download=true&id=<?= esc($row['id']) ?>&format=json" class="btn btn-sm btn-secondary">Descargar JSON</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <!-- Botón para descargar todos los perfiles -->
                    <div class="col-12 text-center mt-4">
                        <a href="users.php?download=true&id=all&format=json" class="btn btn-primary me-2">Descargar Todos (JSON)</a>
                        <a href="users.php?download=true&id=all&format=ticket" class="btn btn-secondary">Descargar Todos (Ticket)</a>
                    </div>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-danger text-center">No se encontraron perfiles</div>
                        <div class="text-center">
                            <a href="index.php" class="btn btn-primary">Inicio</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Scripts de Bootstrap -->
    <script src="./js/bootstrap.bundle.min.js"></script>
</body>
</html>
