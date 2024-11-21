<?php
include('../conexion/conexion.php');
session_start();

// Verificar si el usuario está autenticado
if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
} else {
    header("Location: ../vistas/login.php");
    exit;
}

// Verificar si se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Obtener los datos del formulario
    $vendedor_id = $usuario_id; // El vendedor es el usuario logueado
    $comprador = mysqli_real_escape_string($conn, $_POST['comprador']);
    $duicomprador = mysqli_real_escape_string($conn, $_POST['duicomprador']);
    $propiedad_id = mysqli_real_escape_string($conn, $_POST['propiedad_id']);
    $fecha_transaccion = mysqli_real_escape_string($conn, $_POST['fecha_transaccion']);
    $monto = mysqli_real_escape_string($conn, $_POST['monto']);
    $metodo_pago = mysqli_real_escape_string($conn, $_POST['metodo_pago']);
    $estado_transaccion = "Verificado"; // Estado predeterminado de la transacción
    $estado_propiedad = "Vendido"; // Nuevo estado de la propiedad

    // Iniciar la transacción para ambas operaciones (insertar y actualizar)
    mysqli_begin_transaction($conn);

    try {
        // Insertar la transacción en la tabla transacciones
        $query_insert_transaccion = "INSERT INTO transacciones (vendedor_id, comprador, propiedad_id, fecha_transaccion, monto, metodo_pago, estado_transaccion, duicomprador)
                                     VALUES ('$vendedor_id', '$comprador', '$propiedad_id', '$fecha_transaccion', '$monto', '$metodo_pago', '$estado_transaccion', '$duicomprador')";
        if (!mysqli_query($conn, $query_insert_transaccion)) {
            throw new Exception("Error al insertar la transacción: " . mysqli_error($conn));
        }

        // Actualizar el estado de la propiedad en persona_propiedades
        $query_update_propiedad = "UPDATE persona_propiedades SET EstadoPropiedad = '$estado_propiedad'
                                   WHERE usuario_id = '$vendedor_id' AND propiedad_id = '$propiedad_id'";
        if (!mysqli_query($conn, $query_update_propiedad)) {
            throw new Exception("Error al actualizar la propiedad: " . mysqli_error($conn));
        }

        // Si todo está bien, confirmar la transacción
        mysqli_commit($conn);
        echo "<script>alert('Transacción registrada y propiedad actualizada correctamente.');</script>";
        header("Location: ../vistas/transacciones.php"); // Redirigir a la página de transacciones
        exit;
    } catch (Exception $e) {
        // Si ocurre un error, deshacer la transacción
        mysqli_rollback($conn); // Corregido a mysqli_rollback
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>