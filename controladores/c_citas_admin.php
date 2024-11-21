<?php
include('../conexion/conexion.php'); // Verifica que este archivo contiene la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    // Función para validar fecha y hora
    function esFechaValida($fecha, $hora) {
        $diaSemana = date('N', strtotime($fecha)); // Obtiene el día de la semana (1 para lunes, 7 para domingo)
        $horaFormato24 = date('H:i', strtotime($hora)); // Convierte la hora al formato HH:mm
        return $diaSemana >= 1 && $diaSemana <= 5 && $horaFormato24 >= '07:30' && $horaFormato24 <= '16:00';
    }

    // Acción para agregar una nueva cita
    if ($action === 'add') {
        $fecha = date("Y-m-d", strtotime($_POST['fecha']));
        $hora = $_POST['hora'];
        $disponible = $_POST['disponible'];

        // Validar fecha y hora
        if (!esFechaValida($fecha, $hora)) {
            header("Location: ../vistas/citas_admin.php?mensaje=" . urlencode("Fecha u hora inválida. Solo de lunes a viernes, 07:30-16:00"));
            exit;
        }

        $query = "INSERT INTO fechas_disponibles (fecha, hora, disponible) VALUES ('$fecha', '$hora', '$disponible')";

        if (mysqli_query($conn, $query)) {
            header("Location: ../vistas/citas_admin.php?mensaje=" . urlencode("Cita agregada exitosamente"));
            exit;
        } else {
            header("Location: ../vistas/citas_admin.php?mensaje=" . urlencode("No se pudo agregar"));
        }

    // Acción para editar una cita
    } elseif ($action === 'edit' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $fecha = date("Y-m-d", strtotime($_POST['fecha']));
        $hora = $_POST['hora'];
        $disponible = $_POST['disponible'];

        // Validar fecha y hora
        if (!esFechaValida($fecha, $hora)) {
            header("Location: ../vistas/citas_edit.php?mensaje=" . urlencode("Fecha u hora inválida. Solo de lunes a viernes, 07:30-16:00"));
            exit;
        }

        $query = "UPDATE fechas_disponibles SET fecha='$fecha', hora='$hora', disponible='$disponible' WHERE id='$id'";

        if (mysqli_query($conn, $query)) {
            header("Location: ../vistas/citas_admin.php?mensaje=" . urlencode("Cita actualizada exitosamente"));
            exit;
        } else {
            header("Location: ../vistas/citas_edit.php?mensaje=" . urlencode("No se pudo actualizar"));
        }

    // Acción para eliminar una cita (cambia 'disponible' a 0)
    } elseif ($action === 'delete' && isset($_POST['id'])) {
        $id = $_POST['id'];

        $query = "UPDATE fechas_disponibles SET disponible='0' WHERE id='$id'";

        if (mysqli_query($conn, $query)) {
            header("Location: ../vistas/citas_admin.php?mensaje=" . urlencode("Cita eliminada exitosamente"));
            exit;
        } else {
            header("Location: ../vistas/citas_admin.php?mensaje=" . urlencode("No se pudo eliminar"));
            exit;
        }

    } else {
        echo "Error: Solicitud inválida";
    }
} else {
    echo "Error: Solicitud inválida";
}
?>
