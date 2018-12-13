<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(!isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}
 
// Include config file
require_once "../config.php";
$userid=$_POST['userid'];
$courseid=$_POST['courseid'];
$desc_c=$_POST['desc_c'];
$max=$_POST['max'];
$iscritti=$_POST["iscritti"];
//inizio la connessione
$pdo=connetti();
// Controllo se sono già iscritto
$sql = "SELECT * FROM ".ISCRIZIONI." WHERE Codice_Corso=:courseid AND Alunno_ID=:userid";
if($stmt = $pdo->prepare($sql)){
    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(":courseid", $courseid, PDO::PARAM_STR);
    $stmt->bindParam(":userid", $userid, PDO::PARAM_STR);  
    // Attempt to execute the prepared statement
    if($stmt->execute()){
        if($stmt->rowCount() >= 1){
            $msg=" Sei già iscritto a questo corso";
            $success=false;
            $todo="Se vuoi puoi iscriverti ad altri corsi";
        }else{
            //mega query
            // Controllo se c'è posto e allora mi iscrivo
            $sql = "SELECT MaxAlunni, Iscritti FROM ".CORSI." WHERE Codice_Corso=:courseid";
            if($stmt = $pdo->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $stmt->bindParam(":courseid", $courseid, PDO::PARAM_STR);  
                // Attempt to execute the prepared statement
                if($stmt->execute()){
                    if($stmt->rowCount() == 1){
                        // Check  results
                        $row = $stmt->fetch();
                        if($row['MaxAlunni']>=$row['Iscritti']+1){
                            //faccio l'iscrizione al corso
                            $sql="INSERT INTO ".ISCRIZIONI."(Codice_Corso,Alunno_ID) VALUES (:cod,:id) "; 
                            $stmt = $pdo->prepare($sql);
                            // Bind variables to the prepared statement as parameters
                             $stmt->bindParam(":cod", $courseid, PDO::PARAM_STR);
                            $stmt->bindParam(":id", $userid, PDO::PARAM_STR);
                                // Attempt to execute the prepared statement
                                if($stmt->execute()){
                                    //faccio l'update del numero di iscritti
                                    $num=$row['Iscritti']+1;
                                    $sql = "UPDATE ".CORSI." SET Iscritti=:num WHERE Codice_Corso=:cod";
                                    $stmt = $pdo->prepare($sql);
                                    // Bind variables to the prepared statement as parameters
                                    $stmt->bindParam(":cod", $courseid, PDO::PARAM_STR);
                                    $stmt->bindParam(":num", $num, PDO::PARAM_STR);
                                    if($stmt->execute()){
                                        $msg=" TI sei iscritto con successo al corso";
                                    $success=true;
                                    $todo="Se vuoi puoi iscriverti ad altri corsi";

                                    }else{
                                        echo "OOOpppsss Qualcosa è andato storto. Prova più tardi";}

                                    
                                        
                                } else{
                                    echo "OOOpppsss Qualcosa è andato storto. Prova più tardi";
                                }
                               

                            
                        }else{
                            $msg=" Il corso è pieno";
                            $success=false;
                            $todo="Prova a cercare un altro corso";
                        }
                        

                        
                    
                        
                    }else{
                        //$msg=" Ti sei iscritto con successo al Corso";
                        //$success=true;
                        //$todo="Ora puoi iscriverti ad altri corsi oppure controllare il tuo orario aggiornato (mettere link)";
                    }
                    
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
}
            $msg=" Ti sei iscritto con successo al Corso";
            $success=true;
            $todo="Ora puoi iscriverti ad altri corsi oppure controllare il tuo orario aggiornato (mettere link)";
            //fine mega query
        }
        
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
}



disconnetti($stmt,$pdo);
    






?>
<!doctype html>
<html lang="it-IT">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content=" login per innovation week 2019 Pascoli">
    <meta name="author" content="Leonardo Martino">
    <link rel="shortcut icon" href="../favicon.ico" type="image/x-icon">
<link rel="icon" href="../favicon.ico" type="image/x-icon">

    <title>Esito Registrazione ai corsi</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">


    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
  </head>

  <body>
  <div class="container">
        <div class="card text-white <?php echo (isset($success)&&($success==TRUE)) ? 'bg-success' : 'bg-danger'; ?> col-sm-6 offset-md-3">
        <div class="card-header">
            Iscrizione a <?php echo $desc_c?>
        </div>
        <div class="card-body">
            <h5 class="card-title"><?php echo $msg; ?></h5>
            <p class="card-text"><?php echo $todo ?></p>
            <a href="dashboard.php" class="btn btn-primary">Home</a>
        </div>
        </div>
    </div>
  
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>