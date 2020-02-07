<?php
require_once 'vendor/autoload.php';
if ($_GET["userType"]){
    session_start();
    $_SESSION["user_type"]=$_GET["userType"];
}
try {
    $dbh = new PDO('mysql:host=localhost;dbname=test', 'root', 'qazwsx');
    $owners = [];
    $uT = $_SESSION["user_type"];
    $id=1;
    foreach ($dbh->query('SELECT id,name FROM owners') as $row) {
        $owners[$row["id"]] = $row["name"];
        if ($row["name"]==$uT){
            $id=$row["id"];
        }
    }
    if ($uT==null){
        $uT=$owners[$id];
    }
    $menu=[];
    $st = $dbh->prepare('SELECT * FROM menu WHERE owner_id=:id');
    $st->bindValue(":id",$id);
    $st->execute();
    foreach ($st->fetchAll() as $row) {
        $menu[$row["id"]] = $row;
    }
    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);
    echo $twig->render('slidedemo.html', ["owners" => $owners, 'menu' => $menu, "ut" => $uT]);
} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}