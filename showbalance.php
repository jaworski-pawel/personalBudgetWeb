<?php
    session_start();

    if (!isset($_SESSION['user_logged_in'])) {
		header('Location: index.php');
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
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Przychód</th>
                            <th>Kategoria</th>
                            <th>Data</th>
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

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Wydatek</th>
                            <th>Sposób płatności</th>
                            <th>Kategoria</th>
                            <th>Data</th>
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
                <div class="text-center col-sm-offset-4 col-sm-4">
                    <a href="mainmenu.php"><button type="button" class="btn btn-default btn-lg btn-block">Menu główne</button></a>
                </div>
            </div>
        </div>
    </div> 
    <footer>
        <div class="footer text-center">
            <p>&copy; Paweł Jaworski 2018</p>
        </div>
    </footer>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script> 
    <script type="text/javascript" src="js/piechartexpenses.js"></script> 
    <?php    
       require_once "showpiechartincomes.php";
    ?>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script type="text/javascript" src="js/balance.js"></script>
</body>
</html>