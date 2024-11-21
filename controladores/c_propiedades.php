<?php
session_start();
include('../conexion/conexion.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $direccion = $_POST['direccion'];
    $area_m2 = $_POST['area'];
    $tipo = $_POST['tipo'];
    $valor = str_replace('$', '', $_POST['valor']); // Eliminar símbolo de $
    $estado = $_POST['estado'];
    $municipio = $_POST['municipio']; // Nombre del municipio
    $fecha_adquisicion = $_POST['fecha_construccion'];
    $porcentaje_propiedad = str_replace('%', '', $_POST['porcentaje_propiedad']); // Eliminar símbolo de %

    // Verificar que el usuario esté logueado y que el ID esté en la sesión
    if (isset($_SESSION['usuario_id'])) {
        $usuario_id = $_SESSION['usuario_id'];

        try {
            // Iniciar la transacción
            $conn->begin_transaction();

            // Obtener el ID del municipio usando LIKE para coincidencia parcial
            $query = "SELECT municipio_id FROM municipios WHERE nombre_municipio LIKE ?";
            $stmt = $conn->prepare($query);
            $municipio_like = "%" . $municipio . "%"; // Añadir comodines para LIKE
            $stmt->bind_param("s", $municipio_like);
            $stmt->execute();
            $stmt->bind_result($municipio_id);
            $stmt->fetch();
            $stmt->close();

            if (!$municipio_id) {
                // Si no se encuentra el municipio, cancelar la operación y redirigir con mensaje de error
                $mensaje = "Municipio no encontrado. No se ha insertado ninguna propiedad.";
                header("Location: ../vistas/tablas_propiedades.php?mensaje=" . urlencode($mensaje));
                exit();
            }

            // Insertar en la tabla propiedades
            $stmt = $conn->prepare("INSERT INTO propiedades (direccion, area_m2, tipo, valor, estado, municipio_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sdsssi", $direccion, $area_m2, $tipo, $valor, $estado, $municipio_id);

            if (!$stmt->execute()) {
                throw new Exception("Error al insertar en propiedades: " . $stmt->error);
            }
            $stmt->close();

            // Obtener el `propiedad_id` generado en el trigger
            $result = $conn->query("SELECT @ultimo_propiedad_id AS propiedad_id");
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $propiedad_id = $row['propiedad_id'];
            } else {
                throw new Exception("No se pudo obtener el ID de la propiedad recién insertada.");
            }
            $estadoPropiedad = "En propiedad";

            // Insertar en la tabla persona_propiedades
            $stmt = $conn->prepare("INSERT INTO persona_propiedades (usuario_id, propiedad_id, fecha_adquisicion, porcentaje_propiedad, EstadoPropiedad) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssds", $usuario_id, $propiedad_id, $fecha_adquisicion, $porcentaje_propiedad, $estadoPropiedad);

            if (!$stmt->execute()) {
                throw new Exception("Error al insertar en persona_propiedades: " . $stmt->error);
            }

            // Confirmar la transacción
            $conn->commit();

            // Redirigir con mensaje de éxito
            $mensaje = "Se ha ingresado con éxito la propiedad.";
            header("Location: ../vistas/tablas_propiedades.php?mensaje=" . urlencode($mensaje));
            exit();

        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $conn->rollback();
            // Redirigir con mensaje de error
            header("Location: ../vistas/tablas_propiedades.php?mensaje=" . urlencode($e->getMessage()));
            exit();
        }

    } else {
        // Redirigir a la página de inicio de sesión si no está logueado
        header("Location: ../vistas/login.php");
        exit();
    }
} else {
    $mensaje = "Llenar todo el formulario.";
    header("Location: ../vistas/tablas_propiedades.php?mensaje=" . urlencode($mensaje));
    exit();
}
?>
