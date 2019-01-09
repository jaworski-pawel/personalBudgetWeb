<?php

	session_start();
	
	if (!isset($_SESSION['user_logged_in']))
	{
		header('Location: index.php');
		exit();
  }
  
  $_SESSION['start_date'] = "1970-01-01";
  $_SESSION['end_date'] = $currentdate = date('Y-m-d');
	
?>

<!DOCTYPE html>
<html lang="pl">

<head>
  <title>Personal Budget</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat&amp;subset=latin-ext" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

  <nav class="navbar navbar-inverse visible-xs">
    <div class="container-fluid">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#"><span class="glyphicon glyphicon-piggy-bank"></span>PERSONAL BUDGET</a>
      </div>
      <div class="collapse navbar-collapse" id="myNavbar">
        <ul class="nav navbar-nav">
          <li class="active"><a href="#section1"><span class="glyphicon glyphicon-home"></span>Menu główne</a></li>
          <li><a href="addincome.php"><span class="glyphicon glyphicon-export"></span>Dodaj przychód</a></li>
          <li><a href="addexpense.php"><span class="glyphicon glyphicon-import"></span>Dodaj wydatek</a></li>
          <li><a href="showbalance.php"><span class="glyphicon glyphicon-list-alt"></span>Przeglądaj bilans</a></li>
          <li><a href="settings.php"><span class="glyphicon glyphicon-cog"></span>Ustawienia</a></li>
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Wyloguj się</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row content">
      <div class="col-sm-2 sidenav hidden-xs">
        <div class="logo">
          <a href="mainmenu.php">
            <span class="glyphicon glyphicon-piggy-bank"></span>PERSONAL BUDGET
          </a>
        </div>
        <hr />
        <ul class="nav nav-pills nav-stacked">
          <li class="active"><a href="mainmenu.php"><span class="glyphicon glyphicon-home"></span>Menu główne</a></li>
          <li><a href="addincome.php"><span class="glyphicon glyphicon-export"></span>Dodaj przychód</a></li>
          <li><a href="addexpense.php"><span class="glyphicon glyphicon-import"></span>Dodaj wydatek</a></li>
          <li><a href="showbalance.php"><span class="glyphicon glyphicon-list-alt"></span>Przeglądaj bilans</a></li>
          <li><a href="settings.php"><span class="glyphicon glyphicon-cog"></span>Ustawienia</a></li>
          <li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span>Wyloguj się</a></li>
        </ul>
      </div>

      <div class="col-sm-10 main-content">
        <div class="text-right user-profile">
          <div class="dropdown">
            <div class="dropdown-toggle" data-toggle="dropdown">
              <span class="glyphicon glyphicon-user"></span>
              <?php
              echo $_SESSION['login_of_user'];
              ?>
            </div>
            <ul class="dropdown-menu dropdown-menu-right">
              <li><a href="settings.php">Ustawienia</a></li>
              <li><a href="logout.php">Wyloguj</a></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-sm-10 main-content">
        <div class="well text-center">
          <h4>
            Witaj
            <?php
            echo $_SESSION['login_of_user'];
            ?>
            !
          </h4>
          <p>Wybierz opcje z menu aby zarządzać swoim osobistym budżetem ;-)</p>
          <img src="img/piggy-bank.jpg" class="img-responsive text-center img-center img-piggy-bank" alt="piggy-bank">
        </div>
      </div>
    </div>
  </div>
  <footer class="col-sm-12 footer text-center">
    <p>&copy; Paweł Jaworski 2018</p>
  </footer>

</body>

</html>