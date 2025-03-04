<?php
// Incluir el archivo de funciones
require 'functions.php';

// Verificar si se ha enviado un tipo de dato (data_type) en la solicitud POST
if (!empty($_POST['data_type'])) {
    // Inicializar el array de información que se devolverá como respuesta
    $info['data_type'] = $_POST['data_type'];
    $info['errors'] = []; // Array para almacenar errores
    $info['success'] = false; // Indicador de éxito

    try {
        // Manejar diferentes tipos de solicitudes según el valor de data_type
        if ($_POST['data_type'] == "signup") {
            // Incluir la lógica de registro
            require 'includes/signup.php';
        } else if ($_POST['data_type'] == "profile-edit") {
            // Obtener el ID del usuario actual
            $id = user('id');

            // Buscar el perfil del usuario en la base de datos
            $row = db_query("select * from users where id = :id limit 1", ['id' => $id]);
            if ($row) {
                $row = $row[0];
            }

            // Incluir la lógica de edición de perfil
            require 'includes/profile-edit.php';
        } else if ($_POST['data_type'] == "profile-delete") {
            // Obtener el ID del usuario actual
            $id = user('id');

            // Buscar el perfil del usuario en la base de datos
            $row = db_query("select * from users where id = :id limit 1", ['id' => $id]);
            if ($row) {
                $row = $row[0];
            }

            // Incluir la lógica de eliminación de perfil
            require 'includes/profile-delete.php';
        } else if ($_POST['data_type'] == "login") {
            // Incluir la lógica de inicio de sesión
            require 'includes/login.php';
        }
        
        // Si todo sale bien, se marca el éxito
        $info['success'] = true;
    } catch (Exception $e) {
        // Si ocurre algún error, lo capturamos y lo agregamos a los errores
        $info['errors'][] = $e->getMessage();
    }

    // Devolver la respuesta en formato JSON
    echo json_encode($info);
}
?>
