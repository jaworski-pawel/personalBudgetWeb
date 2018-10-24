<?php

	session_start();
	
	if ((isset($_POST['login'])) && (isset($_POST['password']))) {
    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);
      
    try 
    {
      $db_connection = new mysqli($host, $db_user, $db_password, $db_name);
      
      if ($db_connection->connect_errno!=0)
      {
        throw new Exception(mysqli_connect_errno());
      }
      else
      {
        $login = $_POST['login'];
        $password = $_POST['password'];
        
        $login = htmlentities($login, ENT_QUOTES, "UTF-8");
      
        if ($query_result = $db_connection->query(sprintf("SELECT * FROM users WHERE username='%s'", mysqli_real_escape_string($db_connection, $login)))) {
          $number_of_users = $query_result->num_rows;
          if($number_of_users > 0) {
            $user_data = $query_result->fetch_assoc();
            
            if (password_verify($password, $user_data['password'])) {
              $_SESSION['user_logged_in'] = true;
              $_SESSION['id'] = $user_data['id'];
              
              unset($_SESSION['e_login']);
              $query_result->free_result();
              header('Location: mainmenu.php');
            }
            else {
              $_SESSION['e_login'] = "Nieprawidłowy login lub hasło!";
            }
            
          } else {
            $_SESSION['e_login'] = "Nieprawidłowy login lub hasło!";
          }
          
        }
        else
        {
          throw new Exception($db_connection->error);
        }
        
        $db_connection->close();
      }
    }
    catch(Exception $e)
    {
      echo '<div class="error text-center">Błąd serwera! Przepraszamy za niedogodności i prosimy o wizytę w innym terminie!</div>';
      //echo '<br />Informacja developerska: '.$e;
    }
  }
?>

<!DOCTYPE html>
<html lang="pl_PL">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Aplikcja internetowa do zarządzania domowym budżetem.">
    <meta name="keywords" content="budżet, finanse, pieniądze, zarządzanie, ekonomia">
    <meta name="author" content="Paweł Jaworski">
    <title>Budżet domowy</title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet"> 
    <link rel="stylesheet" type="text/css" media="screen" href="css/main.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
  </head>
  <body>
    <div class="container">
      <header>
        <div class="page-header text-center">
          <h1>Budżet domowy</h1>
          <p>Zaplanuj swoje finanse!</p>
        </div>
      </header>
      <div class="content">
          <div class="register text-center col-sm-offset-4 col-sm-4">
            <?php
			  	    if (isset($_SESSION['e_login'])) {
				    	  echo '<div class="error">'.$_SESSION['e_login'].'</div>';
					      unset($_SESSION['e_login']);
				      }
			      ?>
            <form method="post">
              <input type="text" class="form-control input-lg" id="logininput" placeholder="login" name="login">
              <input type="password" class="form-control input-lg" id="passwordinput" placeholder="hasło" name="password">
              <button type="submit" class="btn btn-default btn-lg">Zaloguj się</button>
            </form>
          </div>
      </div>
    </div>
    <footer>
        <div class="footer text-center">
            <p>&copy; Paweł Jaworski 2018</p>
        </div>
    </footer>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
  </body>
</html>