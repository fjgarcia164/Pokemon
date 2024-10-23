<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!isset($_SESSION['user'])) {
    header('Location: .');
    exit;
}
$user = $_SESSION['user'];

try {
    // Cambiar la conexión para reflejar la base de datos y el usuario correctos
    $connection = new \PDO(
        'mysql:host=localhost;dbname=pokemon',
        'pokeuser',
        'pokemonpassword',
        array(
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8'
        )
    );
} catch (PDOException $e) {
    header('Location: ..');
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $url = '.?op=editpokemon&result=noid';
    header('Location: ' . $url);
    exit;
}

// Validación de usuario "even" y "odd"
if (($user === 'even' && $id % 2 != 0) || 
    ($user === 'odd' && $id % 2 == 0)) {
    header('Location: .?op=editpokemon&result=evenodd');
    exit;
}

// Obtener el Pokémon que se va a editar
$sql = 'SELECT * FROM pokemon WHERE id = :id';
$sentence = $connection->prepare($sql);
$parameters = ['id' => $id];
foreach ($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}

try {
    $sentence->execute();
    $row = $sentence->fetch();
} catch (PDOException $e) {
    header('Location: .');
    exit;
}

if ($row == null) {
    header('Location: .');
    exit;
}

// Preparar los valores para mostrar en el formulario
$name = '';
$type = '';
$evolution = '';

if (isset($_SESSION['old']['name'])) {
    $name = $_SESSION['old']['name'];
    unset($_SESSION['old']['name']);
}
if (isset($_SESSION['old']['type'])) {
    $type = $_SESSION['old']['type'];
    unset($_SESSION['old']['type']);
}
if (isset($_SESSION['old']['evolution'])) {
    $evolution = $_SESSION['old']['evolution'];
    unset($_SESSION['old']['evolution']);
}

// Si no hay datos en la sesión, usar los valores de la base de datos
$id = $row['id'];
if ($name == '') {
    $name = $row['name'];
}
if ($type == '') {
    $type = $row['type'];
}
if ($evolution == '') {
    $evolution = $row['evolution'];
}

$connection = null;
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Editar Pokémon</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="..">dwes</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="..">home</a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="./">pokemon</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main role="main">
            <div class="jumbotron">
                <div class="container">
                    <h4 class="display-4">Editar Pokémon</h4>
                </div>
            </div>
            <div class="container">
                <?php
                if (isset($_GET['op']) && isset($_GET['result'])) {
                    if ($_GET['result'] > 0) {
                        ?>
                        <div class="alert alert-primary" role="alert">
                            Resultado: <?= $_GET['op'] . ' ' . $_GET['result'] ?>
                        </div>
                        <?php 
                    } else {
                        ?>
                        <div class="alert alert-danger" role="alert">
                            Resultado: <?= $_GET['op'] . ' ' . $_GET['result'] ?>
                        </div>
                        <?php
                    }
                }
                ?>
                <div>
                    <!-- Formulario para editar los datos del Pokémon -->
                    <form action="update.php" method="post">
                        <div class="form-group">
                            <label for="name">Nombre del Pokémon</label>
                            <input value="<?= $name ?>" required type="text" class="form-control" id="name" name="name" placeholder="Nombre del Pokémon">
                        </div>
                        <div class="form-group">
                            <label for="type">Tipo de Pokémon</label>
                            <input value="<?= $type ?>" required type="text" class="form-control" id="type" name="type" placeholder="Tipo de Pokémon">
                        </div>
                        <div class="form-group">
                            <label for="evolution">Evolución (1, 2 o 3)</label>
                            <input value="<?= $evolution ?>" required type="number" min="1" max="3" class="form-control" id="evolution" name="evolution" placeholder="Evolución">
                        </div>
                        <input type="hidden" name="id" value="<?= $id ?>" />
                        <button type="submit" class="btn btn-primary">Editar</button>
                    </form>
                </div>
                <hr>
            </div>
        </main>
        <footer class="container">
            <p>&copy; IZV 2024</p>
        </footer>
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
</html>