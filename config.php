<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'pascoli');
//definisco le tabelle
define('CORSI','inno2018_corsi');
define('ALUNNI','inno2018_alunni');
define('BLOCCHI','inno2018_blocchi');
define('ISCRIZIONI','inno2018_iscrizioni');
 
//funzione per connettersi al db
function connetti(){
    try{
        $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD,array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        // Set the PDO error mode to exception
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e){
        die("ERROR: Could not connect. " . $e->getMessage());
    }
    if ($pdo) {
        return($pdo);
    }

}
//funzione per chiudere la connessione al db
function disconnetti($s,$p){
    unset($s);
    unset($p);

}
?>