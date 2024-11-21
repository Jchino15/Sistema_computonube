<?php
include('../conexion/conexion.php');
session_start();

// Verificar si el usuario está autenticado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../vistas/login.php");
    exit();
}

// Verificar si se envió el formulario mediante POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $office_id = $_POST['office_id']; // ID de la oficina en formato texto
    $phone = $_POST['phone'];
    $location = $_POST['location'];
    $emails = $_POST['emails'];
    $map = $_POST['map'];

    // Validar que todos los campos estén completos
    if (!empty($office_id) && !empty($phone) && !empty($location) && !empty($emails) && !empty($map)) {
        // Construir la consulta de actualización
        $query = "UPDATE oficinas SET 
                    phone = '$phone', 
                    location = '$location', 
                    emails = '$emails',
                    map = '$map'
                  WHERE office_id = '$office_id'";

        if (mysqli_query($conn, $query)) {
            // Redirigir a la página de éxito
            header("Location: ../vistas/admin_contacto.php?success=1");
            exit();
        } else {
            // Mostrar error si ocurre algún problema con la consulta
            echo "Error al actualizar los datos: " . mysqli_error($conn);
            exit();
        }
    } else {
        // Mostrar un mensaje de error si falta algún campo
        echo "Todos los campos son obligatorios.";
        exit();
    }
} else {
    // Mostrar error si no se usó el método POST
    echo "Método de solicitud no permitido.";
    exit();
}
?>
