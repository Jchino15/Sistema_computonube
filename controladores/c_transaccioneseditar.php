<?php
include('../conexion/conexion.php');
session_start();

// Verificar si el usuario está autenticado
if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $queryUser = "SELECT email FROM usuarios WHERE usuario_id = '$usuario_id'";
    $resultUser = mysqli_query($conn, $queryUser);

    if ($resultUser && mysqli_num_rows($resultUser) > 0) {
        $array = mysqli_fetch_assoc($resultUser);
        $email = $array['email'];
    } else {
        header("Location: ../vistas/login.php");
        exit;
    }
} else {
    header("Location: ../vistas/login.php");
    exit;
}

// Verificar que la acción sea 'editar_registros'
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $transaccion_id = $_POST['transaccion_id'];
    $comprador = $_POST['comprador'];
    $duicomprador = $_POST['duicomprador'];
    $fecha_transaccion = $_POST['fecha_transaccion'];
    $monto = $_POST['monto'];
    $metodo_pago = $_POST['metodo_pago'];

    // Validar que todos los campos requeridos están presentes
    if (!empty($transaccion_id) && !empty($comprador) && !empty($duicomprador) && !empty($fecha_transaccion) && !empty($monto) && !empty($metodo_pago)) {
        // Actualizar la transacción en la base de datos
        $query = "UPDATE transacciones SET 
                    comprador = '$comprador', 
                    duicomprador = '$duicomprador', 
                    fecha_transaccion = '$fecha_transaccion', 
                    monto = '$monto', 
                    metodo_pago = '$metodo_pago'
                  WHERE transaccion_id = '$transaccion_id'";

        if (mysqli_query($conn, $query)) {
            // Redirigir según el tipo de usuario
            if ($email === 'admin@gmail.com') {
                header("Location: ../vistas/admin_transacciones.php");
            } else {
                header("Location: ../vistas/transacciones.php");
            }
            exit();
        } else {
            // Imprimir el error que está ocurriendo
            echo "Error al actualizar la transacción: " . mysqli_error($conn);
            exit();
        }
    } else {
        // Mostrar un mensaje de error si falta algún dato
        echo "Todos los campos son obligatorios.";
        exit();
    }
}
?>
