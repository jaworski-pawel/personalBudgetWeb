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

    if ((!(preg_match('/^[0-9\+]{1,20}$/', $amount))) && (!(preg_match('/^[0-9\+]{1,20}+\.+[0-9\+]{1,2}$/', $amount))) && (!(preg_match('/^[0-9\+]{1,20}+\,+[0-9\+]{1,2}$/', $amount)))) {
      $succesful_validation = false;
      $_SESSION['e_amount'] = "Niepoprawny format kwoty!";
    }
    else {
      if (preg_match('/^[0-9\+]{1,20}+\,+[0-9\+]{1,2}$/', $amount)) {
        $amount = str_replace(",",".",$amount);
      }
    }

    // Date validation



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
            <form method="post">
              <fieldset class="operation">
                  <legend>Dodaj wydatek:</legend>
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
                  <label for="pay">Sposób płatności: </label>
                  <select id="pay" name="pay" class="form-control">
                      <option value="cash">Gotówka</option>
                      <option value="debetcard">Karta debetowa</option>
                      <option value="creditcard">Karta kredytowa</option>
                  </select>
                  <label for="category">Kategoria: </label>
                  <select id="category" name="category" class="form-control">
                      <option value="food">Jedzenie</option>
                      <option value="apartament">Mieszkanie</option>
                      <option value="transport">Transport</option>
                      <option value="communication">Telekomunikacja</option>
                      <option value="healthcare">Opieka zdrowotna</option>
                      <option value="clothes">Ubrania</option>
                      <option value="hygiene">Higiena</option>
                      <option value="children">Dzieci</option>
                      <option value="entertaiment">Rozrywka</option>
                      <option value="tour">Wycieczko</option>
                      <option value="training">Szkolenia</option>
                      <option value="books">Książki</option>
                      <option value="savings">Oszczędności</option>
                      <option value="pension">Na złotą jesień, czyli emeryturę</option>
                      <option value="debtrepayment">Spłata długów</option>
                      <option value="donation">Darowizna</option>
                      <option value="others">Inne wydatki</option>
                  </select>
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
                      <button type="submit" class="btn btn-default btn-lg">Anuluj</button>
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