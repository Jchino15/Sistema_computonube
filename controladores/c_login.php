<?php 
require '../conexion/conexion.php';
session_start();

// Verificar que los campos de email y contraseña estén en $_POST
if (!empty($_POST['email']) && !empty($_POST['contraseña'])) {
    $email = $_POST['email'];
    $contraseña = $_POST['contraseña'];

    // Consultar el ID y nombre del usuario
    $query = "SELECT COUNT(*) as contar, usuario_id, nombre, email FROM usuarios WHERE email='$email' AND contraseña='$contraseña'";
    $consulta = mysqli_query($conn, $query);
    $array = mysqli_fetch_array($consulta);

    if ($array['contar'] > 0) {
        $_SESSION['usuario_id'] = $array['usuario_id'];
        $_SESSION['nombre'] = $array['nombre'];

        // Verificar si es el administrador
        if ($array['email'] == 'admin@gmail.com') {
            $_SESSION['rol'] = 'admin'; 
            header("Location: ../vistas/admin_index.php");
            exit;
        } else {
            $_SESSION['rol'] = 'usuario';
            header("Location: ../vistas/index.php");
            exit;
        }
    } else {
        // Mensaje de error cuando los datos son incorrectos
        $error = "Datos incorrectos.";
        header("Location: ../vistas/login.php?error=" . urlencode($error));
        exit;
    }
} else{
    // Mensaje de error cuando no se completa el formulario de inicio de sesión
    $formError = "Por favor, complete el formulario de inicio de sesión.";
    header("Location: ../vistas/login.php?error=" . urlencode($formError));
    exit;
}
?>
