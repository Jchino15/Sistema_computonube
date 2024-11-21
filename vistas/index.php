<?php
include('../conexion/conexion.php'); ?>
<?php
session_start();

// Verificar que el usuario esté logueado y que el ID esté en la sesión
if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    // Ahora puedes utilizar $usuario_id en tus consultas o lógica
} else {
    // Redirigir a la página de inicio de sesión si no está logueado
    header("Location: ../vistas/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - CNR</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
            margin-top: 0 !important;
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
        .logout-button {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #2f3640;
            color: #ffffff;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        .navbar-custom {
            background-color: #3C4557;
        }
        .navbar-custom .navbar-nav .nav-link {
            color: #ffffff;
            font-size: 18px;
            padding: 15px 20px;
        }
        .navbar-custom .navbar-nav .nav-link:hover {
            color: #e1e1e1;
        }
        .carousel-inner {
            width: 100%;
            height: 100vh; /* Ajusta la altura a la pantalla completa o un tamaño específico */
        }

        .slider-image {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Hace que la imagen cubra el contenedor */
            display: block; /* Mantiene la proporción y recorta los bordes si es necesario */
        }

        .carousel {
            margin-bottom: 0;
        }
        .carousel-inner, .carousel-item {
        padding: 0;
        margin: 0;
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
        .card-container {
            display: flex;
            justify-content: center;
            gap: 75px;
        }

        .card {
            flex: 1;
            max-width: 600px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .service-image {
            width: 100%;
            height: 500px; /* Ajusta la altura según tus necesidades */
            object-fit: cover; /* Mantiene la proporción de la imagen dentro del contenedor */
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .card-body {
            text-align: center;
            flex-grow: 1; /* Hace que el contenido de la tarjeta ocupe el mismo espacio en todas las tarjetas */
        }

        .icon-button-container {
            display: flex;
            justify-content: center;
            gap: 40px;
            background-color: #2f3640;
            padding: 20px 0;
        }
        .icon-button {
            text-decoration: none;
            color: #ffffff;
            text-align: center;
            font-size: 16px;
        }
        .icon-button:hover {
            color: #e1e1e1;
        }
    </style>
</head>
<body>


<div class="header">
    <a href="../controladores/c_cerrar_sesion.php" class="logout-button">Cerrar sesión</a>
    <img src="../img/logoRegistros2.png" width="250px" height="150px" alt="Logo">
</div>


<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
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

<div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
    </ol>
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="../img/Slider1.jpg" class="d-block w-100 slider-image" alt="Slide 1">
        </div>
        <div class="carousel-item">
            <img src="../img/Slider2.webp" class="d-block w-100 slider-image" alt="Slide 2">
        </div>
        <div class="carousel-item">
            <img src="../img/slider3.webp" class="d-block w-100 slider-image" alt="Slide 3">
        </div>
        <div class="carousel-item">
            <img src="../img/Slider4.jpg" class="d-block w-100 slider-image" alt="Slide 4">
        </div>
    </div>
    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Anterior</span>
    </a>
    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Siguiente</span>
    </a>
</div>

<div class="content" style="background-color: #2f3640;">
    <h2 class="text-center text-white pt-3">SERVICIOS</h2>
        <div class="card-container">
            <div class="card">
                <img src="../img/Propiedad.png" class="card-img-top service-image" alt="Aviso 1">
                <div class="card-body">
                    <h5 class="card-title">Propiedades</h5>
                    <p class="card-text">Trámite de propiedades.</p>
                </div>
            </div>
            <div class="card">
                <img src="../img/Transaccion.png" class="card-img-top service-image" alt="Aviso 2">
                <div class="card-body">
                    <h5 class="card-title">Transacciones</h5>
                    <p class="card-text">Trámite de transacciones.</p>
                </div>
            </div>
            <div class="card">
                <img src="../img/citas.png" class="card-img-top service-image" alt="Aviso 3" >
                <div class="card-body">
                    <h5 class="card-title">Citas</h5>
                    <p class="card-text">Trámite de citas.</p>
                </div>
            </div>
        </div>
        
    </div>
</div>

<div class="footer">
    <p>Registros<br>
    5a Avenida Sur y 22 Calle Oriente, San Salvador, El Salvador C.A.</p>
    <p>Teléfono: 259X-5XXX</p>
    <img src="../img/logoRegistros2.png" width="100px" height="100px" alt="Escudo Gobierno de El Salvador">
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2D/9Kk7rfHEkYElk9PbjWI8JFSZZhxTd8fDFr5x09xM+mhhLDg7G5eiV0yJ" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>       
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>