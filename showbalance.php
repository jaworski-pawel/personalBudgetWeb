<?php

	session_start();
	
	if (!isset($_SESSION['user_logged_in']))
	{
		header('Location: index.php');
		exit();
  }
  
  require_once "selectedperiodvalid.php";
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
          <li><a href="#section1"><span class="glyphicon glyphicon-home"></span>Menu główne</a></li>
          <li><a href="addincome.php"><span class="glyphicon glyphicon-export"></span>Dodaj przychód</a></li>
          <li><a href="addexpense.php"><span class="glyphicon glyphicon-import"></span>Dodaj wydatek</a></li>
          <li class="active"><a href="showbalance.php"><span class="glyphicon glyphicon-list-alt"></span>Przeglądaj bilans</a></li>
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
          <li><a href="mainmenu.php"><span class="glyphicon glyphicon-home"></span>Menu główne</a></li>
          <li><a href="addincome.php"><span class="glyphicon glyphicon-export"></span>Dodaj przychód</a></li>
          <li><a href="addexpense.php"><span class="glyphicon glyphicon-import"></span>Dodaj wydatek</a></li>
          <li class="active"><a href="showbalance.php"><span class="glyphicon glyphicon-list-alt"></span>Przeglądaj bilans</a></li>
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
        <div class="well">
          <div class="balance">
            <div class="dropdown text-right">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                  Okres
                  <span class="caret"></span>
                </button>
                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                  <li><a href="showbalancefromcurrentmonth.php">Bieżący miesiąc</a></li>
                  <li><a href="showbalancefrompreviousmonth.php">Poprzedni miesiąc</a></li>
                  <li><a href="showbalancefromcurrentyear.php">Bieżący rok</a></li>
                  <li role="separator" class="divider"></li>
                  <li><a href="showbalancefromselectedperiod.php">Niestandardowe</a></li>
                </ul>
            </div>
                <h3 class="text-center">
                  Bilans przychodów i wydatków z okresu od: 
                  <?php
                  echo $_SESSION['start_date'];
                  ?>
                   do: 
                  <?php
                  echo $_SESSION['end_date'];
                  ?>
                </h3>
                <hr />
                <table class="table table-hover table-responsive" id="table-incomes">
                  <caption class="text-center"><span class="glyphicon glyphicon-export"></span>Przychody</caption>
                    <thead>
                        <tr>
                            <th>Kategoria</th>
                            <th>Data</th>
                            <th>Komentarz</th>
                            <th>Kwota</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                        require_once "getincomes.php";
                    ?>
                    </tbody>
                </table>

                <div id="piechartincomes"></div>

                <table class="table table-hover table-responsive" id="table-expenses">
                  <caption class="text-center"><span class="glyphicon glyphicon-import"></span>Wydatki</caption>
                    <thead>
                        <tr>
                            <th>Kategoria</th>
                            <th>Sposób płatności</th>
                            <th>Data</th>
                            <th>Komentarz</th>
                            <th>Kwota</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            require_once "getexpenses.php";
                        ?>
                    </tbody>
                </table>

                <div id="piechartexpenses"></div>
                <div class="text-center" id="summary"></div>
                <div class="text-center" id="comment"></div>

                <!-- Modal select period -->
                <div class="modal fade" id="modal-select-period" role="dialog">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title"><span class="glyphicon glyphicon-calendar"></span>Podaj okres bilansu</h4>
                      </div>
                      <div class="modal-body">
                        <?php 
                          if (isset($_SESSION['show_modal_select_period'])) {
                            echo '<script type="text/javascript">$(\'#modal-select-period\').modal(\'show\')</script>';
                            unset($_SESSION['show_modal_select_period']);
                          }
                        ?>
                        <form method="post">
                          <fieldset class="operation">
                              <label for="date">Data początkowa: </label>
                              <input type="date" id="start_date" name="start_date" class="form-control">
                              <?php
                                if (isset($_SESSION['e_start_date'])) {
                                  echo '<script type="text/javascript">$(\'#modal-select-period\').modal(\'show\')</script>';
                                  echo '<div  class="alert alert-danger" role="alert">'.$_SESSION['e_start_date'].'</div>';
                                  unset($_SESSION['e_start_date']);
                                }
                              ?>
                              <label for="date">Data końcowa: </label>
                              <input type="date" id="end_date" name="end_date" class="form-control">
                              <?php
                                if (isset($_SESSION['e_end_date'])) {
                                  echo '<script type="text/javascript">$(\'#modal-select-period\').modal(\'show\')</script>';
                                  echo '<div class="alert alert-danger" role="alert">'.$_SESSION['e_end_date'].'</div>';
                                  unset($_SESSION['e_end_date']);
                                }
                              ?>
                              <div class="buttons text-center">
                                  <button type="submit" class="btn btn-info">Przeglądaj bilans</button>
                                  <a href="showbalance.php"><button type="button" class="btn btn-danger">Anuluj</button></a>
                              </div>
                          </fieldset>
                        </form>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
      </div>
    </div>
  </div>
  <footer class="col-sm-12 footer text-center">
    <p>&copy; Paweł Jaworski 2018</p>
  </footer>

  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
    <?php    
       require_once "showpiechartincomes.php";
    ?>
    <?php    
       require_once "showpiechartexpenses.php";
    ?>
  <script type="text/javascript" src="js/balance.js"></script>
</body>

</html>