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
if (isset($_GET['id'])) {
    $transaccion_id = $_GET['id'];  // Obtienes el valor de 'id' pasado por la URL
    // Aquí puedes usar $transaccion_id para consultar la base de datos o realizar alguna acción
} else {
}
    $querytrasaccion = "SELECT comprador, propiedad_id, fecha_transaccion, monto, metodo_pago, duicomprador 
    FROM transacciones 
    WHERE transaccion_id = '$transaccion_id'"; 
    $resultadotransaccion = mysqli_query($conn, $querytrasaccion); 
    $resultadot= mysqli_fetch_assoc($resultadotransaccion);

    $query = "SELECT CONCAT(nombre, ' ', apellido) AS nombre_apellido FROM usuarios WHERE usuario_id = '$usuario_id'"; 
    $resultado = mysqli_query($conn, $query); 
    $nombre_apellido = mysqli_fetch_assoc($resultado);

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
            width: 100%;
            margin-top: 10px;
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
        .edit-btn, .delete-btn {
            padding: 5px 10px;
            border-radius: 4px;
            cursor: pointer;
        }
        .edit-btn {
            background-color: #ff9800;
            color: white;
        }
        .delete-btn {
            background-color: #f44336;
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

        .btn-editar {
        margin-right: 10px; /* Espaciado entre los botones */
    }

    .btn-regresar {
        background-color: #1a3e70; 
        color: white;
        margin-right: 10px;
        margin-top: 10px;
        font-size: 16px;
        border-radius: 4px;
        cursor: pointer;
    }

    .btn-regresar:hover {
        background-color: #0f2b50; /* Azul aún más oscuro al pasar el mouse */
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


<div class="contenedor">
    <h1 class="p-3">Formulario de Transacción</h1>
        
    <form id="transactionForm" action="../controladores/c_transaccioneseditar.php" method="POST">
        <div class="form-group">
            <label for="vendedor">Vendedor:</label>
            <input type="text" class="form-control" id="vendedor" name="vendedor" value="<?php echo htmlspecialchars($nombre_apellido['nombre_apellido']); ?>" readonly>
        </div>
        <input type="hidden" name="usuario_id" value="<?php echo htmlspecialchars($usuario_id); ?>">
        
        <div class="form-group">
            <label for="comprador">Comprador:</label>
            <input type="text" id="comprador" name="comprador" value="<?php echo htmlspecialchars($resultadot['comprador']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="duicomprador">DUI Comprador:</label>
            <input type="text" id="duicomprador" name="duicomprador" value="<?php echo htmlspecialchars($resultadot['duicomprador']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="propiedad">Propiedad:</label>
            <input type="text" id="duicomprador" name="" value="<?php echo htmlspecialchars($resultadot['propiedad_id']); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="fecha">Fecha de Transacción:</label>
            <input type="date" id="fecha" name="fecha_transaccion" value="<?php echo htmlspecialchars($resultadot['fecha_transaccion']); ?>" required>
        </div>
        <input type="hidden" name="transaccion_id" value="<?php echo htmlspecialchars($transaccion_id); ?>">
        
        <div class="form-group">
            <label for="monto">Monto:</label>
            <input type="number" id="monto" name="monto" value="<?php echo htmlspecialchars($resultadot['monto']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="metodoPago">Método de Pago:</label>
            <select id="tipo" name="metodo_pago" required>
                <option value="">Seleccione el tipo</option>
                <option value="Efectivo" <?php echo ($resultadot['metodo_pago'] == 'Efectivo') ? 'selected' : ''; ?>>Efectivo</option>
                <option value="Tarjeta de crédito" <?php echo ($resultadot['metodo_pago'] == 'Tarjeta de crédito') ? 'selected' : ''; ?>>Tarjeta de crédito</option>
                <option value="Tarjeta de débito" <?php echo ($resultadot['metodo_pago'] == 'Tarjeta de débito') ? 'selected' : ''; ?>>Tarjeta de débito</option>
            </select>
        </div>
        
        <button type="submit" class="btn-editar">Editar Transacción</button>
       
    </form>
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


</body>
</html>
