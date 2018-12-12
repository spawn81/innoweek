<?php

//funzione per selezionare i corsi
function seleziona_corsi($classe, $tipo){
    
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

//$querycorsi="SELECT * FROM $corsi WHERE Tipo LIKE '%R%' AND (Classe LIKE '%$c%' OR Classe LIKE '%$a%' OR Classe LIKE '%$d%' OR Classe LIKE '%$b%') ";
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
                    <table class="table">
                        <thead>
                        <tr>
                        
                            <th scope="col">Nome</th>
                            <th scope="col" class="d-none d-sm-table-cell">Classi</th>
                            <th scope="col">Docente</th>
                            <th scope="col">Prenota</th>
                        </tr>
                        </thead>
                        <tbody>';
        // Check  results
           while($row = $stmt->fetch()){
               stampa ($row);

                
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
function stampa($row){
    //echo "<tr><td>".$row["Nome"]."</td>  <td>".$row["Descrizione"]."</td><td>".$row["Tipo"]."</td><td>".$row["Classe"]."</td><td>".$row["Docente"]."</td><td>";
    echo '<tr><td>'.$row["Nome"].'<span data-toggle="tooltip" title="'.$row["Descrizione"].'"> <i class="fas fa-question-circle" style="color:green;"></i><span>
    </td><td class="d-none d-sm-table-cell">'.$row["Classe"].'</td><td>'.$row["Docente"].'</td><td><i class="fas fa-file-signature fa-2x" style="color:red;"></i></td></tr>';

}

?>