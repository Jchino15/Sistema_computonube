<!-- navbar.php -->
 <style>
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
 </style>
<div class="header">
    <button class="logout-button">Cerrar sesión</button>
    <img src="../img/logoRegistros2.png" width="250px" height="150px" alt="Logo">
</div>
<nav class="navbar navbar-expand-lg navbar-custom">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Servicios</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Nuestro Trabajo</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Institución</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Contáctenos</a></li>
            </ul>
        </div>
    </div>
</nav>
