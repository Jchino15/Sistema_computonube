<?php
require('../vendor/setasign/fpdf/fpdf.php');
include("../conexion/conexion.php");
session_start();

// Verify user is logged in
if (!isset($_SESSION['usuario_id'])) { 
    header("Location: ../vistas/login.php"); 
    exit; 
}

$usuario_id = $_SESSION['usuario_id'];

// Fetch user data
$query_user = "SELECT nombre, apellido, dui, telefono FROM usuarios WHERE usuario_id = '$usuario_id'";
$result_user = mysqli_query($conn, $query_user);
$user_data = mysqli_fetch_assoc($result_user);

// Define custom PDF class
class PDF extends FPDF {
    private $user_data;

    function __construct($user_data) {
        parent::__construct();
        $this->user_data = $user_data;
    }

    function Header() {
        // Logo and user data
        $this->Image('../img/logoRegistros2.png', 10, 6, 50);
        $this->SetFont('Arial', '', 10);
        $this->SetXY(70, 10);
        $this->Cell(0, 5, 'Nombre: ' . $this->user_data['nombre'] . ' ' . $this->user_data['apellido']);
        $this->SetXY(70, 15);
        $this->Cell(0, 5, 'DUI: ' . $this->user_data['dui']);
        $this->SetXY(70, 20);
        $this->Cell(0, 5, 'Telefono: ' . $this->user_data['telefono']);
        
        // Title
        $this->SetXY(10, 30);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, 'Transacciones', 0, 1, 'C');
        $this->Ln(10);
    }

    function Footer() {
        // Footer with user data
        $this->SetY(-60);
        $this->Image('../img/sello.png', 125, $this->GetY(), 40);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 80, 'Registros - San Salvador, El Salvador', 0, 1, 'C');
        $this->Cell(0, -60, 'Usuario: ' . $this->user_data['nombre'] . ' ' . $this->user_data['apellido'] . 
                     ' | DUI: ' . $this->user_data['dui'] . 
                     ' | Telefono: ' . $this->user_data['telefono'], 0, 0, 'C');
    }

    function FancyTable($header, $data) {
        $this->SetFillColor(48, 63, 159);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(.3);
        $this->SetFont('', 'B');
        $w = array(30, 35, 35, 30, 30, 25, 35, 35);
        
        // Header
        for ($i = 0; $i < count($header); $i++) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', true);
        }
        $this->Ln();
        
        // Data rows
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        $fill = false;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, $row['propiedad_id'], 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, $row['Vendedor'], 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, $row['comprador'], 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, $row['duicomprador'], 'LR', 0, 'L', $fill);
            $this->Cell($w[4], 6, $row['fecha_transaccion'], 'LR', 0, 'L', $fill);
            $this->Cell($w[5], 6, $row['monto'], 'LR', 0, 'C', $fill);
            $this->Cell($w[6], 6, $row['metodo_pago'], 'LR', 0, 'L', $fill);
            $this->Cell($w[7], 6, $row['estado_transaccion'], 'LR', 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill;
        }
        
        // Closing line
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}

// Query for transaction data
$query = "
    SELECT 
        tra.propiedad_id, CONCAT(usu.nombre, ' ', usu.apellido) AS Vendedor, tra.comprador, 
        tra.duicomprador, tra.fecha_transaccion, tra.monto, tra.metodo_pago, tra.estado_transaccion
    FROM transacciones AS tra 
    INNER JOIN usuarios AS usu ON tra.vendedor_id = usu.usuario_id 
    WHERE tra.vendedor_id = '$usuario_id'
";
$result = mysqli_query($conn, $query);

// Store data in an array
$data = [];
while ($row = mysqli_fetch_array($result)) {
    $data[] = $row;
}

// Create PDF and display transactions table
$pdf = new PDF($user_data);
$pdf->AddPage('L');
$pdf->SetFont('Arial', '', 10);

// Table headers
$header = array(
    'Propiedad',
    'Vendedor',
    'Comprador',
    'DUI Comprador',
    'Fecha',
    'Monto',
    'Método de Pago',
    'Estado'
);


$pdf->FancyTable($header, $data);

$pdf->Output('D', 'Transacciones.pdf');
?>
