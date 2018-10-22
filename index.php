<?php

	session_start();
	
	if ((isset($_SESSION['is_loged_in'])) && ($_SESSION['is_loged_in']==true))
	{
		header('Location: mainmenu.php');
		exit();
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
          <div class="loginmenu text-center col-sm-offset-4 col-sm-4">
              <a href="register.html"><button type="button" class="btn btn-default btn-lg btn-block">Rejestracja</button></a>
              <a href="login.html"><button type="button" class="btn btn-default btn-lg btn-block">Logowanie</button></a>
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