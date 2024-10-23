<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

try {
    // Conectar a la base de datos de Pokémon
    $connection = new \PDO(
      'mysql:host=localhost;dbname=pokemon',
      'pokeuser',
      'pokemonpassword',
      array(
        PDO::ATTR_PERSISTENT => true,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8')
    );
} catch(PDOException $e) {
    header('Location:..');
    exit;
}

// Verificar si se ha proporcionado el ID del Pokémon
if(isset($_GET['id'])) {
    $id = $_GET['id'];
} else {
    $url = '.?op=showpokemon&result=noid';
    header('Location: ' . $url);
    exit;
}

// Consultar los datos del Pokémon según su ID
$sql = 'SELECT * FROM pokemon WHERE id = :id';
$sentence = $connection->prepare($sql);
$parameters = ['id' => $id];
foreach($parameters as $nombreParametro => $valorParametro) {
    $sentence->bindValue($nombreParametro, $valorParametro);
}
try {
    $sentence->execute();
} catch(PDOException $e) {
    $url = '.?op=showpokemon&result=nosql';
    header('Location: ' . $url);
    exit;
}

// Verificar si se ha encontrado el Pokémon
if(!$fila = $sentence->fetch()) {
    $url = '.?op=showpokemon&result=nofetch';
    header('Location: ' . $url);
    exit;
}

// Cerrar la conexión
$connection = null;
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Pokémon Details</title>
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
                        <a class="nav-link" href="./">Pokémon</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main role="main">
            <div class="jumbotron">
                <div class="container">
                    <h4 class="display-4">Pokémon Details</h4>
                </div>
            </div>
            <div class="container">
                <div>
                    <div class="form-group">
                        Pokémon ID #:
                        <?= $fila['id'] ?>
                    </div>
                    <div class="form-group">
                        Name:
                        <?= $fila['name'] ?>
                    </div>
                    <div class="form-group">
                        Type:
                        <?= $fila['type'] ?>
                    </div>
                    <div class="form-group">
                        Evolution:
                        <?= $fila['evolution'] ?>
                    </div>
                    <div class="form-group">
                        <a href="./">Back</a>
                    </div>
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