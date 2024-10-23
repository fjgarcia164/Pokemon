<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['user'])) {
    header('Location: .');
    exit;
}
$user = $_SESSION['user'];

try {
    // Conexión a la base de datos de Pokémon
    $connection = new \PDO(
      'mysql:host=localhost;dbname=pokemon',
      'pokeuser',
      'pokemonpassword',
      array(
        PDO::ATTR_PERSISTENT => true,
        PDO::MYSQL_ATTR_INIT_COMMAND => 'set names utf8')
    );
} catch(PDOException $e) {
    header('Location: ..');
    exit;
}

if(isset($_POST['id'])) {
    $id = $_POST['id'];
} else {
    $url = '.?op=updatepokemon&result=noid';
    header('Location: ' . $url);
    exit;
}

// Comprobación de permisos
if(($user === 'even' && $id % 2 != 0) || ($user === 'odd' && $id % 2 == 0)) {
    header('Location: .?op=updatepokemon&result=evenodd');
    exit;
}

if(isset($_POST['name'])) {
    $name = trim($_POST['name']);
} else {
    header('Location: .');
    exit;
}

if(isset($_POST['type'])) {
    $type = trim($_POST['type']);
} else {
    header('Location: .');
    exit;
}

if(isset($_POST['evolution'])) {
    $evolution = trim($_POST['evolution']);
} else {
    header('Location: .');
    exit;
}

$ok = true;
if(strlen($name) < 2 || strlen($name) > 100) {
    $ok = false;
}
if(strlen($type) < 2 || strlen($type) > 50) {
    $ok = false;
}
if(strlen($evolution) < 2 || strlen($evolution) > 50) {
    $ok = false;
}

$resultado = 0;

if($ok) {
    // Actualización en la base de datos
    $sql = 'UPDATE pokemon SET name = :name, type = :type, evolution = :evolution WHERE id = :id';
    $sentence = $connection->prepare($sql);
    $parameters = ['name' => $name, 'type' => $type, 'evolution' => $evolution, 'id' => $id];
    foreach($parameters as $nombreParametro => $valorParametro) {
        $sentence->bindValue($nombreParametro, $valorParametro);
    }
    try {
        $sentence->execute();
        $resultado = $sentence->rowCount();
        $url = '.?op=editpokemon&result=' . $resultado;
    } catch(PDOException $e) {
        // Manejo de errores en la actualización
    }
}

if($resultado == 0) {
    $_SESSION['old']['name'] = $name;
    $_SESSION['old']['type'] = $type;
    $_SESSION['old']['evolution'] = $evolution;
    $url = 'edit.php?op=editpokemon&result=' . $resultado . '&id=' . $id;
}

header('Location: ' . $url);
?>