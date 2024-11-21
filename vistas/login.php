<?php
include('../conexion/conexion.php'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - CNR</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        html, body {
            height: 100%;
            margin: 0;
            background-color: #f5f7fb;
            display: flex;
            flex-direction: column;
        }
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            width: 500px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .header {
            background-color: #2f3640;
            padding: 15px;
            color: #ffffff;
            text-align: center;
            width: 100%;
        }
        .header img {
            margin-bottom: 10px;
        }
        .footer {
            background-color: #2f3640;
            padding: 20px;
            color: #ffffff;
            text-align: center;
            font-size: 14px;
            width: 100%;
        }
        .footer img {
            margin-top: 10px;
        }
        .btn-primary {
            background-color: #4d9ffb;
            border: none;
            margin-top: 15px;
        }
        .btn-secondary {
            background-color: #333;
            border: none;
            margin-top: 15px;
        }
        .texto {
            font-size: 125%;
        }
        .crear-cuenta {
            color: green;
            font-size: 100%;
            margin-top: 15px;
            margin-left: 300px;
            

        }
    </style>
</head>
<body>

<div class="header">
    <!-- Aquí puedes agregar el logo o imagen que desees en el encabezado -->
    <img src="../img/logoRegistros2.png" width="250px" height="150px" alt="Logo">
</div>

<div class="content">
    <div class="login-container">
        <h3>Iniciar Sesión</h3>
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong><?php echo htmlspecialchars($_GET['error']); ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <?php if (isset($_GET['formError'])): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong><?php echo htmlspecialchars($_GET['formError']); ?></strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
        <form action="../controladores/c_login.php" method="post">
            <div class="form-group">
                <label class="texto" for="username">Usa tu cuenta de Registro</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Correo" 
                       value="<?php echo isset($_GET['correo']) ? htmlspecialchars($_GET['correo']) : ''; ?>">
            </div>
            <div class="form-group">
                <label class="texto" for="password">Contraseña</label>
                <input type="password" class="form-control" id="password" name="contraseña" placeholder="Contraseña" 
                       value="<?php echo isset($_GET['contraseña']) ? htmlspecialchars($_GET['contraseña']) : ''; ?>">
            </div>
            <div class="crear-cuenta">
                <a href="crearcuenta.php">CREAR CUENTA</a>
            </div>
            <button type="submit" class="btn btn-primary btn-block">ENTRAR CON CUENTA</button>
        </form>
        <a href="citassinlogin.php" class="btn btn-secondary btn-block" target="_blank">AGENDAR CITA</a>


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

</body>
</html>
