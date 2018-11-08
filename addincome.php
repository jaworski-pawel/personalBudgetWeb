<?php

	session_start();
	
	if (!isset($_SESSION['user_logged_in'])) {
		header('Location: index.php');
		exit();
  }
  

  if (isset($_POST['amount'])) {
    $succesful_validation = true;

    // Amount validation

    $amount = $_POST['amount'];

    if ((!(preg_match('/^[0-9]{1,20}$/', $amount))) && (!(preg_match('/^[0-9]{1,20}+\.+[0-9]{1,2}$/', $amount))) && (!(preg_match('/^[0-9\+]{1,20}+\,+[0-9\+]{1,2}$/', $amount)))) {
      $succesful_validation = false;
      $_SESSION['e_amount'] = "Niepoprawny format kwoty!";
    }
    else {
      if (preg_match('/^[0-9]{1,20}+\,+[0-9]{1,2}$/', $amount)) {
        $amount = str_replace(",",".",$amount);
      }
    }

    // Date validation
    
    $date = $_POST['date'];

    if(!(preg_match('/^[0-9]{4}+\-+[0-9]{1,2}+\-+[0-9]{1,2}$/', $date))) {
      $succesful_validation = false;
      $_SESSION['e_date'] = "Data musi być w formacie: RRRR-MM-DD";
    }
    else {
      $year = substr($date, 0, 4);
      $month = substr($date, 5, 2);
      $day = substr($date, 8, 2);
      
      if(!checkdate($month, $day, $year)) {
        $succesful_validation = false;
        $_SESSION['e_date'] = "Niepoprawna data!";
      }
      else {
        $currentdate = date('Y-m-d');
        $currentyear = substr($currentdate, 0, 4);
        $currentmonth = substr($currentdate, 5, 2);
        $currentday = substr($currentdate, 8, 2);
      
        if($year > $currentyear) {
          $succesful_validation = false;
          $_SESSION['e_date'] = "Data nie może być z przyszłości!";
        }
        elseif($year == $currentyear) {
          if($month > $currentmonth) {
            $succesful_validation = false;
            $_SESSION['e_date'] = "Data nie może być z przyszłości!";
          }
          elseif($month = $currentmonth) {
            if($day > $currentday) {
              $succesful_validation = false;
              $_SESSION['e_date'] = "Data nie może być z przyszłości!";
            }
          }
        }
      }
    }

    // Comment validation
    $comment = $_POST['comment'];

    if ((strlen($comment) < 2) || (strlen($comment) >100)) {
      $succesful_validation = false;
      $_SESSION['e_comment'] = "Komentarz powienien zawierać od 2 do 100 znaków!";
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
          <div class="addoperation col-sm-offset-3 col-sm-6">
            <form action="mainmenu.html">
                <fieldset class="operation">
                  <legend>Dodaj przychód:</legend>
                  <label for="amount">Kwota:</label>
                  <input type="text" id="amount" name="amount" class="form-control">
                  <?php
                    if (isset($_SESSION['e_amount'])) {
                      echo '<div class="error">'.$_SESSION['e_amount'].'</div>';
                      unset($_SESSION['e_amount']);
                    }
                  ?>
                  <label for="date">Data: </label>
                  <input type="date" id="date" name="date" class="form-control">
                  <?php
                    if (isset($_SESSION['e_date'])) {
                      echo '<div class="error">'.$_SESSION['e_date'].'</div>';
                      unset($_SESSION['e_date']);
                    }
                  ?>
                  <label for="category">Kategoria: </label>
                  <select id="category" name="category" class="form-control">
                    <?php
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
                          if ($query_result = $db_connection->query(sprintf("SELECT * FROM incomes_category_assigned_to_users WHERE user_id='%s'", mysqli_real_escape_string($db_connection, $_SESSION['id'])))) {
                            $number_of_categories = $query_result->num_rows;
                            if($number_of_categories > 0) {
                              while($category = $query_result->fetch_assoc()) {
                                echo '<option value="'.$category["id"].'">'.$category["name"].'</option>';
                              }
                            }
                            else {
                              $succesful_validation = false;
                              $_SESSION['e_category'] = "Nie udało się pobrać kategorii z bazy danych";
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
                    ?>
                  </select>
                  <?php
                    if (isset($_SESSION['e_category'])) {
                      echo '<div class="error">'.$_SESSION['e_category'].'</div>';
                      unset($_SESSION['e_']);
                    }
                  ?>
                  <label for="comment">Komentarz: </label>
                  <textarea id="comment" class="form-control" name="comment"></textarea>
                  <?php
                    if (isset($_SESSION['e_comment'])) {
                      echo '<div class="error">'.$_SESSION['e_comment'].'</div>';
                      unset($_SESSION['e_comment']);
                    }
                  ?>
                  <div class="buttons text-center">
                    <button type="submit" class="btn btn-default btn-lg">Dodaj</button>
                    <a href="mainmenu.php"><button type="button" class="btn btn-default btn-lg">Anuluj</button></a>
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