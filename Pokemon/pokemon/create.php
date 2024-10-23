<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['user'])) {
    header('Location: .');
    exit;
}

$name = '';
$type = '';
$evolution = '';
if(isset($_SESSION['old']['name'])) {
    $name = $_SESSION['old']['name'];
    unset($_SESSION['old']['name']);
}
if(isset($_SESSION['old']['type'])) {
    $type = $_SESSION['old']['type'];
    unset($_SESSION['old']['type']);
}
if(isset($_SESSION['old']['evolution'])) {
    $evolution = $_SESSION['old']['evolution'];
    unset($_SESSION['old']['evolution']);
}
?>
<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Pokemon Database</title>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    </head>
    <body>
        <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
            <a class="navbar-brand" href="..">Pokemon DB</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="..">Home</a>
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
                    <h4 class="display-4">Pokémon</h4>
                </div>
            </div>
            <div class="container">
                <?php
                if(isset($_GET['op']) && isset($_GET['result'])) {
                    if($_GET['result'] > 0) {
                        ?>
                        <div class="alert alert-primary" role="alert">
                            Result: <?= $_GET['op'] . ' ' . $_GET['result'] ?>
                        </div>
                        <?php 
                    } else {
                        ?>
                        <div class="alert alert-danger" role="alert">
                            Result: <?= $_GET['op'] . ' ' . $_GET['result'] ?>
                        </div>
                        <?php
                    }
                }
                ?>
                <div>
                    <form action="store.php" method="post">
                        <div class="form-group">
                            <label for="name">Pokémon Name</label>
                            <input value="<?= $name ?>" required type="text" class="form-control" id="name" name="name" placeholder="Pokémon name">
                        </div>
                        <div class="form-group">
                            <label for="type">Pokémon Type</label>
                            <input value="<?= $type ?>" required type="text" class="form-control" id="type" name="type" placeholder="Pokémon type">
                        </div>
                        <div class="form-group">
                            <label for="evolution">Pokémon Evolution Stage</label>
                            <input value="<?= $evolution ?>" required type="number" min="1" max="3" class="form-control" id="evolution" name="evolution" placeholder="Enter 1, 2, or 3">
                        </div>
                        <button type="submit" class="btn btn-primary">Add Pokémon</button>
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