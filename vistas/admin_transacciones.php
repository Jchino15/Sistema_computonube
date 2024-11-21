<?php 
include('../conexion/conexion.php'); 
session_start(); 

// Verificar si el usuario ha iniciado sesión
if (isset($_SESSION['usuario_id'])) { 
    $usuario_id = $_SESSION['usuario_id']; 
} else { 
    header("Location: ../vistas/login.php"); 
    exit; 
} 
if (isset($_GET['transaccion_id'])) {
    $transaccion_id = $_GET['transaccion_id'];  // Obtienes el valor de 'id' pasado por la URL
    // Aquí puedes usar $transaccion_id para consultar la base de datos o realizar alguna acción
} 


// Obtener nombre y apellido del vendedor
$query = "SELECT CONCAT(nombre, ' ', apellido) AS nombre_apellido FROM usuarios WHERE usuario_id = '$usuario_id'"; 
$resultado = mysqli_query($conn, $query); 
$nombre_apellido = mysqli_fetch_assoc($resultado);

// Obtener las propiedades para el combo box
$querypropiedades = "SELECT propiedad_id FROM persona_propiedades WHERE EstadoPropiedad='En propiedad' and usuario_id = '$usuario_id'";
$resultadopropiedades = mysqli_query($conn, $querypropiedades);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Transacción</title>
    <style>
        /* Estilos CSS aquí */
            body {
        font-family: Arial, sans-serif;
        background-image: url('../img/fondo_casa.png');
        background-size: contain;
        display: flex;
        flex-direction: column;
        min-height: 100vh;
        margin: 0;
        color: #333;
        align-items: center; /* Centrar contenido horizontalmente */
    }

    .contenedor {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    form {
        width: 600px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 8px;
        background-color: #ffffff;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
    }

        h1 {
            color: #4a90e2;
            margin-top: 20px;
        }


        .form-group {
            flex: 1 1 calc(50% - 20px);
        }
        label {
            font-weight: bold;
            color: #555;
        }
        input[type="text"], input[type="date"], input[type="number"], select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #4a90e2;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
            margin-right: 25px;
        }
        button:hover {
            background-color: #357ab7;
        }
        table {
            width: 80%;
            border-collapse: collapse;
            font-size: 1em;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #4a90e2;
            color: white;
        }
        tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .edit-btn {
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .edit-btn {
            background-color: #4a90e2;
            color: white;
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
        .footer {
            background-color: #2f3640;
            padding: 20px;
            color: #ffffff;
            text-align: center;
            width: 100%;
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
            width: 100%;
        }
        .navbar-custom .navbar-nav .nav-link {
            color: #ffffff;
            font-size: 18px;
        }
        .table-container h2 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
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
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
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
<div class="contenedor">
    <h1>Formulario de Transacción</h1>
        
        <form id="transactionForm" action="../controladores/c_transacciones.php" method="POST">
            <div class="form-group">
                <label for="vendedor">Vendedor:</label>
                <input type="text" class="form-control" id="vendedor" name="vendedor" value="<?php echo htmlspecialchars($nombre_apellido['nombre_apellido']); ?>" readonly>
            </div>
            <input type="hidden" name="usuario_id" value="<?php echo htmlspecialchars($usuario_id); ?>">
            
            <div class="form-group">
                <label for="comprador">Comprador:</label>
                <input type="text" id="comprador" name="comprador" required>
            </div>
            
            <div class="form-group">
                <label for="duicomprador">DUI Comprador:</label>
                <input type="text" id="duicomprador" name="duicomprador" required>
            </div>
            
            <div class="form-group">
                <label for="propiedad">Propiedad:</label>
                <select id="propiedad" name="propiedad_id" required>
                    <option value="">Seleccione una propiedad</option>
                    <?php while ($propiedad = mysqli_fetch_assoc($resultadopropiedades)) { 
                        echo "<option value='{$propiedad['propiedad_id']}'>{$propiedad['propiedad_id']}</option>"; 
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <label for="fecha">Fecha de Transacción:</label>
                <input type="date" id="fecha" name="fecha_transaccion" required>
            </div>
            
            <div class="form-group">
                <label for="monto">Monto:</label>
                <input type="number" id="monto" name="monto" required>
            </div>
            
            <div class="form-group">
                <label for="metodoPago">Método de Pago:</label>
                <select id="tipo" name="metodo_pago" required>
                    <option value="">Seleccione el tipo</option>
                    <option value="Efectivo">Efectivo</option>
                    <option value="Tarjeta de crédito">Tarjeta de crédito</option>
                    <option value="Tarjeta de débito">Tarjeta de débito</option>
                </select>
            </div>
            
            <center><button type="submit">Guardar Transacción</button></center>
        </form>

        <div class="table-container text-center">
    <h2>Transacciones</h2>
    <table class="table price-table mt-4">
        <thead>
            <tr>
                <th class="basic-header">Codigo Propiedad</th>
                <th class="standard-header">Vendedor</th>
                <th class="advanced-header">Comprador</th>
                <th class="basic-header">DUI Comprador</th>
                <th class="standard-header">Fecha</th>
                <th class="advanced-header">Monto</th>
                <th class="basic-header">Método de Pago</th>
                <th class="standard-header">Estado</th>
                <th class="advanced-header">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query_transacciones = "SELECT tra.propiedad_id, CONCAT(usu.nombre, ' ', usu.apellido) AS Vendedor, tra.comprador, 
                                    tra.duicomprador, tra.fecha_transaccion, tra.monto, tra.metodo_pago, tra.estado_transaccion, 
                                    tra.transaccion_id 
                                    FROM transacciones AS tra 
                                    INNER JOIN usuarios AS usu ON tra.vendedor_id = usu.usuario_id";
            $result = mysqli_query($conn, $query_transacciones);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['propiedad_id']}</td>
                            <td>{$row['Vendedor']}</td>
                            <td>{$row['comprador']}</td>
                            <td>{$row['duicomprador']}</td>
                            <td>{$row['fecha_transaccion']}</td>
                            <td>{$row['monto']}</td>
                            <td>{$row['metodo_pago']}</td>
                            <td>{$row['estado_transaccion']}</td>
                            <td class='action-buttons'>
                                <button class='edit-btn' onclick=\"location.href='transaccioneedit.php?id={$row['transaccion_id']}'\">Editar</button>
                            </td>
                        </tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No hay transacciones registradas.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</div>
            <div class="pb-4">
                <center><a href="../controladores/c_exportarpdftran_Admin.php" class="btn btn-success">Generar PDF</a></center>
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
    </div>

</body>
</html>
