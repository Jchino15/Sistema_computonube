<?php
include('../conexion/conexion.php');
session_start();

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../vistas/login.php");
    exit;
}

// Obtener la oficina seleccionada (por defecto, 'ahuachapan')
$office_key = $_GET['office'] ?? 'ahuachapan';

$office_names = [
    'ahuachapan' => 'Oficina de Ahuachapán',
    'sonsonate' => 'Oficina de Sonsonate',
    'san_miguel' => 'Oficina de San Miguel',
    'santa_ana' => 'Oficina de Santa Ana'
];
$office_name = $office_names[$office_key] ?? 'Oficina no encontrada';

// Consulta para obtener la información de la oficina
$query = "SELECT * FROM oficinas WHERE office_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $office_key);
$stmt->execute();
$result = $stmt->get_result();

// Verificar si se encontró la oficina
if ($result && $result->num_rows > 0) {
    $office = $result->fetch_assoc();
} else {    
    // Si no se encuentra la oficina, mostrar un mensaje de error
    $office = [
        'phone' => 'N/A',
        'location' => 'N/A',
        'emails' => 'N/A',
        'map' => '<p>Mapa no disponible.</p>'
    ];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactanos</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8fbff;
            color: #1d3557;
        }
        .header {
            background-color: #2f3640;
            padding: 15px;
            color: #ffffff;
            text-align: center;
        }
        .navbar-custom {
            background-color: #3C4557;
        }
        .navbar-custom .navbar-nav .nav-link {
            color: #ffffff;
            font-size: 18px;
        }

        .containe{
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .content {
            display: flex;
            margin-top: 20px;
        }
        .sidebar {
            width: 30%;
            background: #e9f1ff;
            padding: 10px;
            border-radius: 8px;
            margin-right: 20px;
        }
        .sidebar button {
            width: 100%;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 10px;
            font-size: 1rem;
            cursor: pointer;
        }
        .sidebar button.active {
            background: #0056b3;
        }
        .details {
            flex: 1;
            padding: 10px;
        }
        .details h2 {
            font-size: 1.5rem;
            margin-bottom: 10px;
        }
        .details p {
            margin: 5px 0;
        }
        .map {
            margin-top: 20px;
        }
        iframe {
            width: 100%;
            height: 300px;
            border: none;
            border-radius: 8px;
        }
        .footer {
            background-color: #2f3640;
            padding: 20px;
            color: #ffffff;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="header">
        <img src="../img/Logo-CNR.png" width="350px" height="150px" alt="Logo CNR">
    </div>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="containe">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="index.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="tablas_propiedades.php">Propiedades</a></li>
                    <li class="nav-item"><a class="nav-link" href="transacciones.php">Transacciones</a></li>
                    <li class="nav-item"><a class="nav-link" href="citas.php">Citas</a></li>
                    <li class="nav-item"><a class="nav-link" href="contacto.php">Contáctenos</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="content">
            <div class="sidebar">
                <form method="get">
                    <button type="submit" name="office" value="ahuachapan" 
                            class="<?php echo ($office_key === 'ahuachapan') ? 'active' : ''; ?>">Ahuachapán</button>
                    <button type="submit" name="office" value="sonsonate" 
                            class="<?php echo ($office_key === 'sonsonate') ? 'active' : ''; ?>">Sonsonate</button>
                    <button type="submit" name="office" value="san_miguel" 
                            class="<?php echo ($office_key === 'san_miguel') ? 'active' : ''; ?>">San Miguel</button>
                    <button type="submit" name="office" value="santa_ana"   
                            class="<?php echo ($office_key === 'santa_ana') ? 'active' : ''; ?>">Santa Ana</button>
                </form>
            </div>

            <div class="details">
                <h2><?php echo htmlspecialchars($office_name); ?></h2>

                <p><strong>Información de contacto:</strong></p>
                <p><?php echo htmlspecialchars($office['phone']); ?></p>
                <p><strong>Ubicación:</strong></p>
                <p><?php echo htmlspecialchars($office['location']); ?></p>
                <p><strong>Correos electrónicos:</strong></p>
                <ul>
                    <?php
                    if (is_string($office['emails'])) {
                        // Descomponer los correos si están separados por comas
                        $emails = explode(',', $office['emails']);
                    } else {
                        // Si no es un string, inicializamos $emails como un arreglo vacío
                        $emails = [];
                    }
                    foreach ($emails as $email): ?>
                        <li><?php echo htmlspecialchars(trim($email)); ?></li>
                    <?php endforeach; ?>
                </ul>
                <div class="map">
                    <?php echo $office['map']; ?>
                </div>
            </div>

        </div>
    </div>

    <div class="footer">
    <p>Centro Nacional de Registros<br>
    1ra Calle Poniente y 43 Av. Norte, San Salvador, El Salvador C.A.</p>
    <p>Teléfono: 2593-5000</p>
    <img src="../img/Logo_Gobierno.png" width="120px" height="100px" alt="Escudo Gobierno de El Salvador">
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2D/9Kk7rfHEkYElk9PbjWI8JFSZZhxTd8fDFr5x09xM+mhhLDg7G5eiV0yJ" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>       
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
