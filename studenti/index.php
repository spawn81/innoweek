<?php
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: dashboard.php");
    exit;
}
 
// Include config file
require_once "../config.php";
 
// Define variables and initialize with empty values
$password = "";
$password_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Inserisci la tua password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($password_err)){
        $pdo=connetti();
        // Prepare a select statement
        $sql = "SELECT * FROM ".ALUNNI." WHERE Password = :password";
        
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
         
            
            // Set parameters
            $param_password = trim($_POST["password"]);
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Check if password exists, if yes then verify password
                if($stmt->rowCount() == 1){
                    if($row = $stmt->fetch()){
                        $id = $row["ID"];
                        $nome = $row["Nome"];
                        $cognome = $row["Cognome"];
                        $classe= $row["Classe"]." ".$row["Sezione"];
                        
                        
                            // Password is correct, so start a new session
                            session_start();
                            
                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $nome." ".$cognome;
                            $_SESSION["classe"]= $classe;                            
                            // Redirect user to welcome page
                            header("location: dashboard.php");
                        
                    }
                } else{
                    // Display an error message if username doesn't exist
                    $password_err = "La password che hai inserito è sbagliata.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        
        // Close statement &connection
        disconnetti($stmt, $pdo);
    }
    
    // Close connection
    //unset($pdo);
}
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

    <title>Login alla Innovation week del Pascoli</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">


    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">
  </head>

  <body class="text-center">
    <form class="form-signin" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <img class="mb-4" src="../logo.png" alt="" width="72" height="72">
      <h1 class="h3 mb-3 font-weight-normal">Esegui il Login</h1>
      <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
             <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" id="inputPassword" name="password" class="form-control" placeholder="Password" required>
            <span class="help-block"><?php echo $password_err; ?></span>
        
     
      <button class="btn btn-lg btn-primary btn-block" type="submit">Entra</button>
      <p class="mt-5 mb-3 text-muted">&copy; 2019</p>
    </form>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>