<?php
require('../vendor/setasign/fpdf/fpdf.php');
include("../conexion/conexion.php");
session_start();

// Verifica que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) { 
    header("Location: ../vistas/login.php"); 
    exit; 
}

$usuario_id = $_SESSION['usuario_id']; 

// Obtener los datos del usuario
$query_user = "SELECT nombre, apellido, dui, telefono FROM usuarios WHERE usuario_id = '$usuario_id'";
$result_user = mysqli_query($conn, $query_user);
$user_data = mysqli_fetch_assoc($result_user);

// Crear una clase PDF personalizada
class PDF extends FPDF {
    private $user_data;

    // Constructor para recibir los datos del usuario
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
        $this->Cell(0, 5, 'Nombre: ' . $this->user_data['nombre'] . ' ' . $this->user_data['apellido']);
        $this->SetXY(70, 15);
        $this->Cell(0, 5, 'DUI: ' . $this->user_data['dui']);
        $this->SetXY(70, 20);
        $this->Cell(0, 5, 'Telefono: ' . $this->user_data['telefono']);
        
        // Título de la tabla
        $this->SetXY(10, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Propiedades', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        // Agregar una imagen (sello) en el pie de página
        $this->SetY(-60);
        $this->Image('../img/sello.png', 125, $this->GetY(), 40);
        
        // Texto en el pie de página
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 80, 'Registros - San Salvador, El Salvador', 0, 1, 'C');
        
        // Datos del usuario en el pie de página
        $this->Cell(0, -60, 'Usuario: ' . $this->user_data['nombre'] . ' ' . $this->user_data['apellido'] . 
                     ' | DUI: ' . $this->user_data['dui'] . 
                     ' | Telefono: ' . $this->user_data['telefono'], 0, 0, 'C');
    }

    function FancyTable($header, $data) {
        // Set colors, font, and column widths
        $this->SetFillColor(48, 63, 159);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        $w = array(32, 40, 25, 25, 25, 25, 30, 30, 35);
    
        // Header
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();
    
        // Reset colors and font for table content
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
    
        // Data rows
        $fill = false;
        foreach ($data as $row) {
            $direccionAltura = $this->GetMultiCellHeight($w[1], 6, $row['direccion']);
            $cellHeight = max($direccionAltura, 6);
    
            // Print cells
            $this->Cell($w[0], $cellHeight, $row['propiedad_id'], 'LR', 0, 'L', $fill);
            
            // MultiCell for 'Direccion' and manual alignment
            $x = $this->GetX();
            $y = $this->GetY();
            $this->MultiCell($w[1], 6, $row['direccion'], 'LR', 'L', $fill);
            $this->SetXY($x + $w[1], $y);  // Reset position for next cells in the row
    
            // Other cells in the row
            $this->Cell($w[2], $cellHeight, $row['area_m2'], 'LR', 0, 'C', $fill);
            $this->Cell($w[3], $cellHeight, $row['tipo'], 'LR', 0, 'C', $fill);
            $this->Cell($w[4], $cellHeight, $row['valor'], 'LR', 0, 'C', $fill);
            $this->Cell($w[5], $cellHeight, $row['EstadoPropiedad'], 'LR', 0, 'C', $fill);
            $this->Cell($w[6], $cellHeight, $row['nombre_municipio'], 'LR', 0, 'L', $fill);
            $this->Cell($w[7], $cellHeight, $row['propietario'], 'LR', 0, 'L', $fill);
            $this->Cell($w[8], $cellHeight, $row['fecha_adquisicion'], 'LR', 0, 'L', $fill);
    
            $this->Ln();
            $fill = !$fill;
        }
    
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
    
    // Function to calculate MultiCell height
    function GetMultiCellHeight($w, $h, $text) {
        $clone = clone $this;
        $clone->SetXY($this->GetX(), $this->GetY());
        $clone->MultiCell($w, $h, $text, 0, 'L');
        return $clone->GetY() - $this->GetY();
    }
    
}

// Consulta para obtener los datos de la tabla de propiedades
$query = "
    SELECT 
    p.propiedad_id, p.direccion, p.area_m2, p.tipo, p.valor, pp.EstadoPropiedad, 
    m.nombre_municipio, u.nombre AS propietario, pp.fecha_adquisicion
    FROM propiedades p
    INNER JOIN persona_propiedades pp ON p.propiedad_id = pp.propiedad_id
    INNER JOIN municipios m ON m.municipio_id = p.municipio_id
    INNER JOIN usuarios u ON u.usuario_id = pp.usuario_id
    WHERE pp.EstadoPropiedad='En propiedad' AND u.usuario_id = '$usuario_id'
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

// Encabezado de la tabla
$header = array('Cod Propiedad', 'Direccion', 'Area m2', 'Tipo', 'Valor', 'Estado', 'Municipio', 'Propietario', 'Fecha Adquisicion');
$pdf->FancyTable($header, $data);

$pdf->Output('D', 'Tabla_Propiedades.pdf'); // Muestra el PDF en el navegador
?>
