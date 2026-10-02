<?php
$_SESSION['identfiant'] = '';
function parse_env () : void
{
    putenv(parse_ini_file('../.env'));
}
try{
    $dsn = "mysql:host=localhost;dbname=users";
    $pdo = new \PDO($dsn, 'root','root');
    $pdo-> exec("SET CHARACTER SET utf8");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    die('Erreur : '.$e->getMessage());
}
$sql = "SELECT count(*) as nb FROM `users` WHERE  email = :email and password = :password";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":email", $email,PDO::PARAM_STR);
$stmt->bindValue(":password", $password,PDO::PARAM_STR);
try{
    $stmt->execute();
    $stmt->rowCount() or die('Pas de résultat' . PHP_EOL);
    $stmt->setFetchMode(PDO::FETCH_OBJ);
    $result = $stmt->fetch()->nb;
}
catch(PDOException $e){
    echo 'Erreur : '.$e->getMessage() . PHP_EOL;
    echo 'Requête : '. $sql . PHP_EOL;
    exit();
}
if($result > 0){

}