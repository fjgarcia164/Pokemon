<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['user'])) {
    header('Location: .');
    exit;
}

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
    header('Location: create.php?op=errorconnection&result=0');
    exit;
}
 
$resultado = 0;
$url = 'create.php?op=insertpokemon&result=' . $resultado;

if(isset($_POST['name']) && isset($_POST['type']) && isset($_POST['evolution'])) {
    $name = $_POST['name'];
    $type = $_POST['type'];
    $evolution = $_POST['evolution'];
    $ok = true;
    $name = trim($name);

    if(strlen($name) < 2 || strlen($name) > 100) {
        $ok = false;
    }
    // Validación del tipo y la evolución (opcional, puedes ajustarlo según tu lógica)
    if(strlen($type) < 2 || strlen($type) > 50) {
        $ok = false;
    }
    if(strlen($evolution) < 2 || strlen($evolution) > 50) {
        $ok = false;
    }

    if($ok) {
        $sql = 'INSERT INTO pokemon (name, type, evolution) VALUES (:name, :type, :evolution)';
        $sentence = $connection->prepare($sql);
        $parameters = ['name' => $name, 'type' => $type, 'evolution' => $evolution];
        foreach($parameters as $nombreParametro => $valorParametro) {
            $sentence->bindValue($nombreParametro, $valorParametro);
        }

        try {
            $sentence->execute();
            $resultado = $connection->lastInsertId();
            $url = 'index.php?op=insertpokemon&result=' . $resultado;
        } catch(PDOException $e) {
            // Manejo de errores en la inserción, podrías registrar el error o redirigir a una página de error.
        }
    }
}

if($resultado == 0) {
    $_SESSION['old']['name'] = $name;
    $_SESSION['old']['type'] = $type;
    $_SESSION['old']['evolution'] = $evolution;
}

header('Location: ' . $url);
?>