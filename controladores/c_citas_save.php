<?php
// Asegúrate de conectar a la base de datos antes de intentar insertar datos
include('../conexion/conexion.php'); // Este archivo debería contener tu conexión a la base de datos

require('../vendor/setasign/fpdf/fpdf.php');

// Asegúrate de que el formulario haya sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recibe y sanitiza los datos del formulario
    $nombres = mysqli_real_escape_string($conn, $_POST['nombres']);
    $apellidos = mysqli_real_escape_string($conn, $_POST['apellidos']);
    $fecha_id = mysqli_real_escape_string($conn, $_POST['id']);

    // Obtener la fecha y la hora de la tabla `fechas_disponibles` utilizando el `id` seleccionado
    $query_fecha = "SELECT fecha, hora FROM cnr.fechas_disponibles WHERE id = $fecha_id AND disponible = 1";
    $resultado_fecha = mysqli_query($conn, $query_fecha);

    if (mysqli_num_rows($resultado_fecha) > 0) {
        $fecha_data = mysqli_fetch_assoc($resultado_fecha);
        $fecha_cita = $fecha_data['fecha'];
        $hora_cita = $fecha_data['hora'];
        
        // Concatenar nombres y apellidos
        $nombre_apellido = $nombres . ' ' . $apellidos;

        // Estado inicial de la cita
        $estado_cita = 'Pendiente';

        // Preparar la consulta de inserción
        $query_insert = "INSERT INTO cnr.citas (nombre_apellido, fecha_cita, hora_cita, estado_cita) 
                         VALUES ('$nombre_apellido', '$fecha_cita', '$hora_cita', '$estado_cita')";

        // Ejecutar la consulta de inserción
        if (mysqli_query($conn, $query_insert)) {
            // Actualizar la disponibilidad de la fecha a `disponible = 0`
            $query_update_disponibilidad = "UPDATE cnr.fechas_disponibles SET disponible = 0 WHERE id = $fecha_id";
            mysqli_query($conn, $query_update_disponibilidad);

            // Crear el PDF del ticket
            $ticket_data = [
                'nombre' => $nombre_apellido,
                'fecha' => date("d/m/Y", strtotime($fecha_cita)),
                'hora' => date("H:i", strtotime($hora_cita))
            ];

            // Crear el objeto PDF y pasar los datos
            class PDF extends FPDF {
                private $user_data;

                // Constructor para recibir los datos del usuario
                function __construct($user_data) {
                    parent::__construct();
                    $this->user_data = $user_data;
                }

                function Header() {
                    
                    $this->Image('../img/logoRegistros2.png', 10, 6, 50);
                    // Datos del usuario a la derecha del logo
                    $this->SetFont('Arial', '', 10);
                    $this->SetXY(70, 10);
                    $this->Cell(0, 5, 'Nombre: ' . $this->user_data['nombre']);
                    $this->SetXY(70, 15);
                    $this->Cell(0, 5, 'Fecha: ' . $this->user_data['fecha']);
                    $this->SetXY(70, 20);
                    $this->Cell(0, 5, 'Hora: ' . $this->user_data['hora']);
                    
                    // Título del ticket
                    $this->SetXY(10, 30);
                    $this->SetFont('Arial', 'B', 12);
                    $this->Cell(0, 10, 'Ticket de Cita', 0, 1, 'C');
                    $this->Ln(10);
                }

                function Footer() {
                    $this->SetY(-15);
                    $this->SetFont('Arial', 'I', 8);
                    $this->Cell(0, 10, 'Registro - Registro', 0, 0, 'C');
                }

                function Body() {
                    // Información de la cita
                    $this->SetXY(10, 60);
                    $this->Cell(0, 10, 'Cita Programada:', 0, 1);
                    $this->Cell(0, 10, 'Nombre: ' . $this->user_data['nombre'], 0, 1);
                    $this->Cell(0, 10, 'Fecha de Cita: ' . $this->user_data['fecha'], 0, 1);
                    $this->Cell(0, 10, 'Hora de Cita: ' . $this->user_data['hora'], 0, 1);
                }
            }

            // Crear una instancia de la clase PDF y generar el ticket
            $pdf = new PDF($ticket_data);
            $pdf->AddPage();
            $pdf->Body();
            $pdf->Output('D', 'ticket_cita.pdf'); // Esto fuerza la descarga del PDF
 // Muestra el PDF en el navegador

            
            exit();
        } else {
            header("Location: ../vistas/citas.php?mensaje=" . urlencode("La fecha no es valida"));
        }
    } else {
        header("Location: ../vistas/citas.php?mensaje=" . urlencode("La fecha no es valida"));
    }
}
?>
