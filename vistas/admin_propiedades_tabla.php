<?php 
include("../conexion/conexion.php");
session_start();
// Verificar que el usuario esté logueado y que el ID esté en la sesión
if (!isset($_SESSION['usuario_id'])) {
    // Redirigir al login si no está logueado
    header("Location: ../vistas/login.php?error=" . urlencode("Debes iniciar sesión."));
    exit;
}

// Verificar si el usuario tiene el rol de administrador
if ($_SESSION['rol'] !== 'admin') {
    // Redirigir si no es administrador
    header("Location: ../vistas/index.php?error=" . urlencode("No tienes permisos para acceder a esta página."));
    exit;
}
$usuario_id = $_SESSION['usuario_id']; 

// Lógica de búsqueda
$where = "";
$criterio_seleccionado = ""; // Variable para recordar la opción seleccionada
$valor_busqueda = "";        // Variable para recordar el valor ingresado

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['mostrar_todos'])) {
        // No agregar ningún filtro
        $where = "";
    } else {
        $criterio_seleccionado = $_POST['criterio'];
        $valor_busqueda = $_POST['valor_busqueda'];
        
        if ($criterio_seleccionado == 'propiedad_id') {
            $where = "AND p.propiedad_id = '$valor_busqueda'";
        } elseif ($criterio_seleccionado == 'dui') {
            $where = "AND u.dui = '$valor_busqueda'";
        }
    }
}

$query = "
    SELECT 
    p.propiedad_id, p.direccion, p.area_m2, p.tipo, p.valor, pp.EstadoPropiedad, 
    m.nombre_municipio, u.nombre AS propietario, pp.fecha_adquisicion
    FROM propiedades p
    INNER JOIN persona_propiedades pp ON p.propiedad_id = pp.propiedad_id
    INNER JOIN municipios m ON m.municipio_id = p.municipio_id
    INNER JOIN usuarios u ON u.usuario_id = pp.usuario_id
    WHERE 1=1 $where
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla Propiedad</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('../img/fondo_casa.png');
            background-size: contain;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            color: #333;
        }
        .edit-btn {
            padding: 5px 10px;
            cursor: pointer;
            background-color: #4a90e2;
            color: white;
            margin: 15px;
        }
        .header {
            position: relative;
            background-color: #2f3640;
            padding: 15px;
            color: #ffffff;
            text-align: center;
            width: 100%;
        }
        .header img {
            margin-bottom: 10px;
        }
        .navbar-custom {
            background-color: #3C4557;
        }
        .navbar-custom .navbar-nav .nav-link {
            color: #ffffff;
            font-size: 18px;
        }

        .table-title {
            font-size: 24px;
            font-weight: bold;
        }
        .table-subtitle {
            font-size: 16px;
            color: #666;
        }
        .price-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .price-table th {
            color: white;
            font-size: 18px;
            padding: 15px;
            text-align: center;
        }
        .price-table .basic-header { background-color: #21213b; } /* Verde */
        .price-table .standard-header { background-color: #32314e; } /* Azul */
        .price-table .advanced-header { background-color: #4e4c6b; } /* Rojo */
        .price-table td {
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 30px;
        }
        .form-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 20px 40px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.3);
            width: auto;
            text-align: center;
        }
        .form-container h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input, .form-group select, .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .submit-btn {
            background-color: #ff8c00;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .footer {
            background-color: #2f3640;
            padding: 20px;
            color: #ffffff;
            text-align: center;
        }
        .price-table th, .price-table td {
            width: 10%; /* Cada columna ocupa el 10% para 10 columnas */
        }
        .logout-text {
            color: #ffffff;
            text-decoration: none;
            position: absolute;
            right: 15px;
            top:10px;
            font-size: 16px;
        }
        .logout-text:hover {
            color: #cccccc;
        }
    </style>
</head>
<body>
<div class="header">
    <div class="header-content">
    <img src="../img/logoRegistros2.png" width="250px" height="150px" alt="Logo">
    </div>
</div>

<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- Centro de la navbar -->
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link" href="admin_index.php">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_propiedades_tabla.php">Propiedades</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_transacciones.php">Transacciones</a></li>
                <li class="nav-item"><a class="nav-link" href="citas_admin.php">Citas</a></li>
                <li class="nav-item"><a class="nav-link" href="admin_contacto.php">Contáctenos</a></li>
            </ul>
            <!-- Alineación derecha para "Cerrar sesión" -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link logout-text" href="../controladores/c_cerrar_sesion.php">Cerrar sesión</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="content">


    <div class="form-container">
        <h2 class="table-title">Tabla de propiedades</h2>
        
        <!-- Formulario de búsqueda -->
        <form action="" method="POST" class="form-inline mb-3">
            <label for="criterio" class="mr-2">Buscar por:</label>
            <select name="criterio" id="criterio" class="form-control mr-2">
                <option value="propiedad_id" <?php echo ($criterio_seleccionado == 'propiedad_id') ? 'selected' : ''; ?>>Codigo Propiedad</option>
                <option value="dui" <?php echo ($criterio_seleccionado == 'dui') ? 'selected' : ''; ?>>DUI</option>
            </select>
            <input type="text" name="valor_busqueda" class="form-control mr-2" placeholder="Ingrese el valor..." value="<?php echo htmlspecialchars($valor_busqueda); ?>">
            <button type="submit" class="btn btn-primary">Buscar</button>
            <button type="submit" name="mostrar_todos" class="btn btn-secondary ml-2">Mostrar Todos</button>
        </form>

        <!-- Tabla de resultados -->
        <table class="table price-table mt-4">
            <thead>
                <tr>
                    <th class="basic-header">Codigo Propiedad</th>
                    <th class="standard-header">Dirección</th>
                    <th class="advanced-header">Area m2</th>
                    <th class="basic-header">Tipo</th>
                    <th class="standard-header">Valor</th>
                    <th class="advanced-header">Estado</th>
                    <th class="basic-header">Municipio</th>
                    <th class="standard-header">Propietario</th>
                    <th class="advanced-header">Fecha de adquisición</th>
                    <th class="basic-header">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_array($result)) { ?>
                    <tr>
                        <td><?php echo $row['propiedad_id']; ?></td>
                        <td><?php echo $row['direccion']; ?></td>
                        <td><?php echo $row['area_m2']; ?></td>
                        <td><?php echo $row['tipo']; ?></td>
                        <td><?php echo $row['valor']; ?></td>
                        <td><?php echo $row['EstadoPropiedad']; ?></td>
                        <td><?php echo $row['nombre_municipio']; ?></td>
                        <td><?php echo $row['propietario']; ?></td>
                        <td><?php echo $row['fecha_adquisicion']; ?></td>
                        <td class='action-buttons'>
                            <button class='edit-btn' onclick="location.href='editar_propiedad.php?id=<?php echo $row['propiedad_id']; ?>'">Editar</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        
        <!-- Botón para generar PDF -->
        <form action="../controladores/c_exportarpdfpro_Admin.php" method="POST">
            <input type="hidden" name="criterio" value="<?php echo htmlspecialchars($criterio_seleccionado); ?>">
            <input type="hidden" name="valor_busqueda" value="<?php echo htmlspecialchars($valor_busqueda); ?>">
            <button type="submit" class="btn btn-success">Generar PDF</button>
        </form>
    </div>
</div>
<div class="footer">
    <p>Registros<br>
    5a Avenida Sur y 22 Calle Oriente, San Salvador, El Salvador C.A.</p>
    <p>Teléfono: 259X-5XXX</p>
    <img src="../img/logoRegistros2.png" width="100px" height="100px" alt="Escudo Gobierno de El Salvador">
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2D/9Kk7rfHEkYElk9PbjWI8JFSZZhxTd8fDFr5x09xM+mhhLDg7G5eiV0yJ" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>       
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
