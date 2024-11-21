<?php
require('../vendor/setasign/fpdf/fpdf.php');
include("../conexion/conexion.php");
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../vistas/login.php");
    exit;
}

// Establece un filtro SQL por defecto para obtener todas las propiedades
$where = "";

// Procesa los criterios de filtro del formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['mostrar_todos'])) {
        $where = ""; // Sin filtro adicional
    } else {
        $criterio = $_POST['criterio'];
        $valor = $_POST['valor_busqueda'];

        if ($criterio == 'propiedad_id') {
            $where = "AND p.propiedad_id = '$valor'";
        } elseif ($criterio == 'dui') {
            $where = "AND u.dui = '$valor'";
        }
    }
}
$usuario_id = $_SESSION['usuario_id'];

// Obtener los datos del usuario
$query_user = "SELECT nombre,email,telefono,direccion FROM usuarios WHERE usuario_id = '$usuario_id'";
$result_user = mysqli_query($conn, $query_user);
$user_data = mysqli_fetch_assoc($result_user);

// Crear una clase PDF personalizada
class PDF extends FPDF {
    private $user_data;

    function __construct($user_data) {
        parent::__construct();
        $this->user_data = $user_data;
    }

    function Header() {
        // Logo en la parte superior izquierda
        $this->Image('../img/logoRegistros2.png', 10, 6, 50);

        // Datos del usuario a la derecha del logo
        $this->SetFont('Arial', '', 10);
        $this->SetXY(70, 10);
        $this->Cell(0, 5, 'Correo: ' . $this->user_data['email']);
        $this->SetXY(70, 15);
        $this->Cell(0, 5, 'Telefono: ' . $this->user_data['telefono']);
        $this->SetXY(70, 20);
        $this->Cell(0, 5, 'Direccion: ' . $this->user_data['direccion']);

        // Título de la tabla
        $this->SetXY(10, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Propiedades', 0, 1, 'C');
        $this->Ln(10);

        // Encabezado de la tabla
        $this->AddTableHeader();
    }

    function Footer() {
        // Agregar una imagen (sello) en el pie de página
        $this->SetY(-60);
        $this->Image('../img/sello.png', 125, $this->GetY(), 40);

        // Texto en el pie de página
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 80, 'Registros - San Salvador, El Salvador', 0, 1, 'C');

        // Datos del usuario en el pie de página
        $this->Cell(0, -60, 'Correo: ' . $this->user_data['email'] .
                     ' | Telefono: ' . $this->user_data['telefono'] .
                     ' | Direccion: ' . $this->user_data['direccion'], 0, 0, 'C');
    }

    function AddTableHeader() {
        $this->SetFillColor(48, 63, 159);
        $this->SetTextColor(255);
        $this->SetFont('', 'B');
        $w = array(32, 40, 25, 25, 25, 25, 30, 30, 40);
        $header = array('Cod Propiedad', 'Direccion', 'Area m2', 'Tipo', 'Valor', 'Estado', 'Municipio', 'Propietario', 'Fecha Adquisicion');

        foreach ($header as $i => $col) {
            $this->Cell($w[$i], 7, $col, 1, 0, 'C', true);
        }
        $this->Ln();
        $this->SetFillColor(224, 235, 255); // Color de fondo para las filas alternadas
        $this->SetTextColor(0);
        $this->SetFont('');
    }

    function FancyTable($data) {
        $w = array(32, 40, 25, 25, 25, 25, 30, 30, 40);
        $fill = false;
        $rowsPerPage = 6;
        $rowCount = 0;

        foreach ($data as $row) {
            // Verificar si se necesita una nueva página
            if ($rowCount > 0 && $rowCount % $rowsPerPage == 0) {
                $this->AddPage('L');
            }

            // Calcular la altura de la celda para ajustar la altura de la fila
            $direccionAltura = $this->GetMultiCellHeight($w[1], 6, $row['direccion']);
            $cellHeight = max($direccionAltura, 6);

            // Ajuste de color de fondo para filas alternadas
            $this->SetFillColor(224, 235, 255); // Color claro
            if (!$fill) {
                $this->SetFillColor(255, 255, 255); // Blanco
            }

            $this->Cell($w[0], $cellHeight, $row['propiedad_id'], 'LR', 0, 'L', $fill);

            $x = $this->GetX();
            $y = $this->GetY();
            $this->MultiCell($w[1], 6, $row['direccion'], 'LR', 'L', $fill);
            $this->SetXY($x + $w[1], $y);

            $this->Cell($w[2], $cellHeight, $row['area_m2'], 'LR', 0, 'C', $fill);
            $this->Cell($w[3], $cellHeight, $row['tipo'], 'LR', 0, 'C', $fill);
            $this->Cell($w[4], $cellHeight, $row['valor'], 'LR', 0, 'C', $fill);
            $this->Cell($w[5], $cellHeight, $row['EstadoPropiedad'], 'LR', 0, 'C', $fill);
            $this->Cell($w[6], $cellHeight, $row['nombre_municipio'], 'LR', 0, 'L', $fill);
            $this->Cell($w[7], $cellHeight, $row['propietario'], 'LR', 0, 'L', $fill);
            $this->Cell($w[8], $cellHeight, $row['fecha_adquisicion'], 'LR', 0, 'L', $fill);

            $this->Ln();
            $fill = !$fill; // Cambiar el color de relleno en cada fila
            $rowCount++;
        }

        // Línea de cierre de la tabla
        $this->Cell(array_sum($w), 0, '', 'T');
    }

    function GetMultiCellHeight($w, $h, $text) {
        $clone = clone $this;
        $clone->SetXY($this->GetX(), $this->GetY());
        $clone->MultiCell($w, $h, $text, 0, 'L');
        return $clone->GetY() - $this->GetY();
    }
}

// Consulta para obtener los datos de la tabla de propiedades
$query = "
    SELECT p.propiedad_id, p.direccion, p.area_m2, p.tipo, p.valor, pp.EstadoPropiedad, 
    m.nombre_municipio, u.nombre AS propietario, pp.fecha_adquisicion
    FROM propiedades p
    INNER JOIN persona_propiedades pp ON p.propiedad_id = pp.propiedad_id
    INNER JOIN municipios m ON m.municipio_id = p.municipio_id
    INNER JOIN usuarios u ON u.usuario_id = pp.usuario_id
    WHERE 1=1 $where
";
$result = mysqli_query($conn, $query);

// Almacenar los datos en un array
$data = [];
while ($row = mysqli_fetch_array($result)) {
    $data[] = $row;
}

// Crear PDF con orientación horizontal y pasar los datos del usuario
$pdf = new PDF($user_data);
$pdf->AddPage('L');
$pdf->SetFont('Arial', '', 10);

// Generar la tabla en el PDF
$pdf->FancyTable($data);

$pdf->Output('D', 'Tabla_Propiedades.pdf'); // Muestra el PDF en el navegador
?>
