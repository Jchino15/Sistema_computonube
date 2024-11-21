<?php
include('../conexion/conexion.php');
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../vistas/login.php");
    exit;
}

// Obtener el ID del usuario actual
$usuario_id = $_SESSION['usuario_id'];

// Verificar el correo del usuario para determinar si es admin
$queryUsuario = "SELECT email FROM usuarios WHERE usuario_id = '$usuario_id'";
$resultUsuario = mysqli_query($conn, $queryUsuario);

if ($resultUsuario && mysqli_num_rows($resultUsuario) > 0) {
    $usuarioData = mysqli_fetch_assoc($resultUsuario);
    $emailUsuario = $usuarioData['email'];
} else {
    // Si no se encuentra el usuario, redirigir al login
    header("Location: ../vistas/login.php");
    exit;
}

// Verificar si se ha enviado el formulario con los datos requeridos
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['propiedad_id'])) {
    // Recoger los datos del formulario
    $propiedad_id = $_POST['propiedad_id'];
    $direccion = mysqli_real_escape_string($conn, $_POST['direccion']);
    $area_m2 = mysqli_real_escape_string($conn, $_POST['area']);
    $tipo = mysqli_real_escape_string($conn, $_POST['tipo']);
    $valor = mysqli_real_escape_string($conn, $_POST['valor']);
    $estado = mysqli_real_escape_string($conn, $_POST['estado']);
    $porcentaje_propiedad = mysqli_real_escape_string($conn, $_POST['porcentaje_propiedad']);
    $fecha_adquisicion = mysqli_real_escape_string($conn, $_POST['fecha_adquisicion']);
    $nombre_municipio = mysqli_real_escape_string($conn, $_POST['nombre_municipio']);

    // Verificar que el propiedad_id existe en la base de datos
    $queryCheck = "SELECT 1 FROM propiedades WHERE propiedad_id = '$propiedad_id'";
    $resultCheck = mysqli_query($conn, $queryCheck);
    if (mysqli_num_rows($resultCheck) == 0) {
        header("Location: ../vistas/tablas_propiedades.php?mensaje=" . urlencode("La propiedad no existe."));
        exit;
    }

    // Actualizar los datos en las tablas propiedades y persona_propiedades
    $queryPropiedades = "UPDATE propiedades 
                         SET direccion = '$direccion', area_m2 = '$area_m2', tipo = '$tipo', valor = '$valor', estado = '$estado'
                         WHERE propiedad_id = '$propiedad_id'";

    $queryPersonaPropiedades = "UPDATE persona_propiedades 
                                SET porcentaje_propiedad = '$porcentaje_propiedad', fecha_adquisicion = '$fecha_adquisicion'
                                WHERE propiedad_id = '$propiedad_id'";

    $queryMunicipio = "UPDATE municipios m
                       JOIN propiedades p ON m.municipio_id = p.municipio_id
                       SET nombre_municipio = '$nombre_municipio'
                       WHERE p.propiedad_id = '$propiedad_id'";

    // Ejecutar las consultas y verificar errores
    if (mysqli_query($conn, $queryPropiedades) && mysqli_query($conn, $queryPersonaPropiedades) && mysqli_query($conn, $queryMunicipio)) {
        // Redirigir dependiendo del usuario
        if ($emailUsuario == 'admin@gmail.com') {
            header("Location: ../vistas/admin_propiedades_tabla.php?mensaje=" . urlencode("Propiedad actualizada con éxito."));
        } else {
            header("Location: ../vistas/tablas_propiedades.php?mensaje=" . urlencode("Propiedad actualizada con éxito."));
        }
        exit();
    } else {
        // Capturar el error de las consultas
        $error = mysqli_error($conn); // Obtener el error
        header("Location: ../vistas/editar_propiedad.php?mensaje=" . urlencode("Error al actualizar la propiedad. Error: $error"));
    }
} else {
    // Si los datos no se enviaron correctamente
    header("Location: ../vistas/tablas_propiedades.php?mensaje=" . urlencode("Datos incompletos para actualizar la propiedad."));
}
?>
