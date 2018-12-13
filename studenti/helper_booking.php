<?php

//funzione per selezionare i corsi
function seleziona_corsi($classe, $tipo,$id,$t){
    
$c= $classe[0];
switch($c)
{case "1" :
    $a="%prime%";
    $b="%biennio%";
    break;
    case "2": 
    $a="%seconde%";
    $b="%biennio%";
    break;
    case "3" :
    $a="%terze%";
    $b="%triennio%";
    break;
    case "4" :
    $a="%quarte%";
    $b="%triennio%";
    break;
    case "5" :
    $a="%quinte%";
    $b="%triennio%";
    break;
}
$d="%tutte%";
$cl="%".$c."%";
//inizio la connessione
$pdo=connetti();
// Prepare a select statement
$sql = "SELECT * FROM ".CORSI." WHERE Tipo LIKE :tipo AND (Classe LIKE :cl OR Classe LIKE :a OR Classe LIKE :b OR Classe LIKE :d)";


if($stmt = $pdo->prepare($sql)){
    // Bind variables to the prepared statement as parameters
    $stmt->bindParam(":tipo", $tipo, PDO::PARAM_STR);
    $stmt->bindParam(":cl", $cl, PDO::PARAM_STR);
    $stmt->bindParam(":a", $a, PDO::PARAM_STR);
    $stmt->bindParam(":b", $b, PDO::PARAM_STR);
    $stmt->bindParam(":d", $d, PDO::PARAM_STR);    
    // Attempt to execute the prepared statement
    if($stmt->execute()){
            echo '<div class="table-responsive">
                    <table class="table table-sm" id="'.$t.'">
                        <thead>
                        <tr>
                        
                            <th scope="col">Nome</th>
                            <th scope="col" class="d-none d-sm-table-cell">Classi</th>
                            <th scope="col">Docente</th>
                            <th scope="col">Orario</th>
                            <th scope="col" class="d-none d-sm-table-cell">Prenota</th>
                        </tr>
                        </thead>
                        <tbody>';
        // Check  results
           while($row = $stmt->fetch()){
               stampa ($row, $id,$pdo);

                
            }
            echo '      </tbody>
                     </table>
                    </div>';
        
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
}
disconnetti($stmt,$pdo);
    
}
//stampo le tabelle
function stampa($row, $id,$pdo){
    if($row["Iscritti"]<$row["MaxAlunni"]){
        $ora=orario($row["Codice_Corso"],$pdo);
    echo '<tr><td>'.$row["Nome"].'<span data-toggle="tooltip" title="'.$row["Descrizione"].'"> <i class="fas fa-question-circle" style="color:green;"></i><span>
    </td><td class="d-none d-sm-table-cell">'.$row["Classe"].'</td><td>'.$row["Docente"].'</td><td>'.$ora.'</td><td class="d-none d-sm-table-cell">
    <form method="post" action="register.php">
    <input type="hidden" name="userid" value="'.$id.'">
    <input type="hidden" name="courseid" value="'.$row["Codice_Corso"].'">
    <input type="hidden" name="desc_c" value="'.$row["Nome"].'">
    <input type="hidden" name="max" value="'.$row["MaxAlunni"].'">
    <input type="hidden" name="iscritti" value="'.$row["Iscritti"].'">

    <button  class="btn btn-danger btn-circle" name="vai" type="submit"><i class="fas fa-file-signature fa-2x" "></i></button>
    </form>
    </td></tr>';
    //faccio il bottone di prenotazione visualizzabile solo in mobile mode
    echo '<tr>
    <td class="d-lg-none" colspan="3" >
    <div class="d-none">'.$row["Nome"].'</div>
    <form method="post" action="register.php">
    <input type="hidden" name="userid" value="'.$id.'">
    <input type="hidden" name="courseid" value="'.$row["Codice_Corso"].'">
    <input type="hidden" name="desc_c" value="'.$row["Nome"].'">
    <input type="hidden" name="max" value="'.$row["MaxAlunni"].'">
    <input type="hidden" name="iscritti" value="'.$row["Iscritti"].'">
    

    <button  class="btn btn-danger btn-block  " name="vai" type="submit"><i class="fas fa-file-signature fa-2x" "></i>Prenota</button>
    </form>
    </td>
    </tr>';
    }

}
//recupero l'orario dei corsi
function orario($id,$p){
    $ora='';
   $sql_orario="SELECT Giorno, Orario FROM ".BLOCCHI." WHERE Codice_Corso=:id";	
   if($stmt1 = $p->prepare($sql_orario)){
    // Bind variables to the prepared statement as parameters
    $stmt1->bindParam(":id", $id, PDO::PARAM_STR);
   
    // Attempt to execute the prepared statement
    if($stmt1->execute()){
           
        // Check  results
           while($row = $stmt1->fetch()){
               $ora.=$row["Giorno"]." ".$row["Orario"]."<br>";

                
            }
         
        
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }

}
unset($stmt1);
return($ora);


}

?>