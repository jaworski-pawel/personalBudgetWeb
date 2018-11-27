<?php
    session_start();

    if (!isset($_SESSION['user_logged_in'])) {
		header('Location: index.php');
		exit();
    }
    
    if (isset($_POST['start_date'])) {

      $good_start_date = true;
      $good_end_date = true;

      // Start date validation
      
      $start_date = $_POST['start_date'];

      if(!(preg_match('/^[0-9]{4}+\-+[0-9]{1,2}+\-+[0-9]{1,2}$/', $start_date))) {
        $good_start_date = false;
        $_SESSION['e_start_date'] = "Data musi być w formacie: RRRR-MM-DD";
      }
      else {
        $year = substr($start_date, 0, 4);
        $month = substr($start_date, 5, 2);
        $day = substr($start_date, 8, 2);
        
        if(!checkdate($month, $day, $year)) {
          $good_start_date = false;
          $_SESSION['e_start_date'] = "Niepoprawna data!";
        }
        else {
          $currentdate = date('Y-m-d');
          $currentyear = substr($currentdate, 0, 4);
          $currentmonth = substr($currentdate, 5, 2);
          $currentday = substr($currentdate, 8, 2);
        
          if($year > $currentyear) {
            $good_start_date = false;
            $_SESSION['e_start_date'] = "Data nie może być z przyszłości!";
          }
          elseif($year == $currentyear) {
            if($month > $currentmonth) {
              $good_start_date = false;
              $_SESSION['e_start_date'] = "Data nie może być z przyszłości!";
            }
            elseif($month = $currentmonth) {
              if($day > $currentday) {
                $good_start_date = false;
                $_SESSION['e_start_date'] = "Data nie może być z przyszłości!";
              }
            }
          }
        }
      }

      // End date validation
      
      $end_date = $_POST['end_date'];

      if(!(preg_match('/^[0-9]{4}+\-+[0-9]{1,2}+\-+[0-9]{1,2}$/', $end_date))) {
        $good_end_date = false;
        $_SESSION['e_end_date'] = "Data musi być w formacie: RRRR-MM-DD";
      }
      else {
        $year = substr($end_date, 0, 4);
        $month = substr($end_date, 5, 2);
        $day = substr($end_date, 8, 2);
        
        if(!checkdate($month, $day, $year)) {
          $good_end_date = false;
          $_SESSION['e_end_date'] = "Niepoprawna data!";
        }
        else {
          $currentdate = date('Y-m-d');
          $currentyear = substr($currentdate, 0, 4);
          $currentmonth = substr($currentdate, 5, 2);
          $currentday = substr($currentdate, 8, 2);
        
          if($year > $currentyear) {
            $good_end_date = false;
            $_SESSION['e_end_date'] = "Data nie może być z przyszłości!";
          }
          elseif($year == $currentyear) {
            if($month > $currentmonth) {
              $good_end_date = false;
              $_SESSION['e_end_date'] = "Data nie może być z przyszłości!";
            }
            elseif($month = $currentmonth) {
              if($day > $currentday) {
                $good_end_date = false;
                $_SESSION['e_end_date'] = "Data nie może być z przyszłości!";
              }
            }
          }
        }
      }
      if ($good_start_date && $good_end_date) {
        $_SESSION['start_date'] = $start_date;
        $_SESSION['end_date'] = $end_date;
        header('Location: showbalance.php');
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
          <div class="col-sm-offset-3 col-sm-6">
            <form method="post">
              <fieldset class="operation">
                  <legend>Podaj okres bilansu:</legend>
                  <label for="date">Data początkowa: </label>
                  <input type="date" id="start_date" name="start_date" class="form-control">
                  <?php
			            	if (isset($_SESSION['e_start_date'])) {
				            	echo '<div class="error">'.$_SESSION['e_start_date'].'</div>';
					            unset($_SESSION['e_start_date']);
				            }
			            ?>
                  <label for="date">Data końcowa: </label>
                  <input type="date" id="end_date" name="end_date" class="form-control">
                  <?php
			            	if (isset($_SESSION['e_end_date'])) {
				            	echo '<div class="error">'.$_SESSION['e_end_date'].'</div>';
					            unset($_SESSION['e_end_date']);
				            }
			            ?>
                  <div class="buttons text-center">
                      <button type="submit" class="btn btn-default btn-lg">Wyświetl</button>
                      <a href="showbalance.php"><button type="button" class="btn btn-default btn-lg">Anuluj</button></a>
                  </div>
              </fieldset>
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